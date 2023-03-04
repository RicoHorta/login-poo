<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'lib/vendor/autoload.php';

require_once 'Crud.php';

class Usuario extends Crud{
    protected string $tabela = 'usuarios'; // Define tabela Usuários

    function __construct(
        public string $nome,
        private string $email,
        private string $senha,
        private string $repete_senha="",
        private string $recupera_senha="",
        private string $token="",
        private string $codigo_confirmacao="",
        private string $sits_usuario_id="",
        private string $status="",
        public array $erro=[],
    ){}

    public function set_repeticao($repete_senha){
        $this->repete_senha = $repete_senha;
    }

    // Validação dos campos
    public function validar_cadastro(){

        // Validação Campo NOME
        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/",$this->nome)) { 
            $this->erro["erro_nome"]="Digite seu nome completo";
        }

        // Validação Campo EMAIL
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) { 
            $this->erro["erro_email"]="Digite seu e-mail completo";
        }

        // Validação Campo SENHA tem pelo menos 6 dígitos
        if (strlen($this->senha)<6) { 
            $this->erro["erro_senha"]="Senha deve ter pelo menos 6 caracteres";
        }

        // Validação Senhas iguais
        if (($this->senha) !==  ($this->repete_senha)) { 
            $this->erro["erro_repete"]="Senhas não conferem";
        }
    }
    
    public function insert(){
        //Verificar se email já consta no Banco de Dados
        $sql = "SELECT * FROM usuarios WHERE email=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $usuario = $sql->fetch();
        //Adicionar o novo usuário caso ele ainda não exista no Banco
        if ($usuario) {
            $this->erro["erro_geral"]="Usuário já cadastrado!";
        }else{
            $data_cadastro = date('d/m/Y');
            $sits_usuario_id = 3;
            $chave = password_hash($this->email . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);
            $senha_cripto = sha1($this->senha);
            $sql = "INSERT INTO $this->tabela VALUES (null,?,?,?,?,?,?,?,?,?,?)";
            $sql = DB::prepare($sql);
            //return $sql->execute(array($this->nome,$this->email,$senha_cripto,$this->recupera_senha,$this->token,$this->codigo_confirmacao,$this->status,$data_cadastro));

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            try{
                 //Server settings
                $mail->CharSet = "UTF-8";                            //Caracteres especiais do português
                $mail->isSMTP();                                     //Send using SMTP
                $mail->Host       = 'smtp.hostinger.com.br';         //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                            //Enable SMTP authentication
                $mail->Username   = 'cobranca@cdlacemais.com.br';    //SMTP username
                $mail->Password   = '#CDLacemais79#';                //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //Enable implicit TLS encryption
                $mail->Port       = 587;   
                
                //Recipients
                $mail->setFrom('cobranca@cdlacemais.com.br', 'Rico da RicoCred');
                $mail->addAddress($this->email, $this->nome);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Falta pouco pra se juntar a RicoCred';
                $mail->Body    = "Prezado(a) " . $this->nome . ".<br><br>Agradecemos a sua solicita&ccedil;&atilde;o de cadastramento em nosso site!<br><br>Para que possamos liberar o seu cadastro em nosso sistema, solicitamos a confirma&ccedil;&atilde;o do e-mail clicanco no link abaixo: <br><br> <a href='http://localhost/login-poo/confirmar-email.php?chave=$chave'>Clique aqui</a><br><br>Esta mensagem foi enviada a pela RicoCred.<br>Nenhum e-mail enviado pela RicoCred tem arquivos anexados ou solicita o preenchimento de senhas e informações cadastrais.<br><br>" ;
                $mail->AltBody = 'Por favor, reserve um tempo para verificar seu e-mail agora. \n\nEsteja atento as suas notifica&ccedil;&otilde;es importantes e evite problemas ao fazer seu login. \n\n';

                $mail->send();
                
            }catch (Exception $e) {
                $this->erro["erro_geral"]="Email de Confirmação não pode ser enviado {$mail->ErrorInfo}";
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            return $sql->execute(array($this->nome,$this->email,$senha_cripto,$this->recupera_senha,$this->token,$this->codigo_confirmacao,$this->status,$data_cadastro,$chave,$sits_usuario_id));
            }
        }
    public function update($id){
        $sql = "UPDATE $this->tabela SET token=? WHERE id=?";
        $sql = DB::prepare($sql);
        return $sql->execute(array($token,$id));
    }
    }    
?>