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
            $senha_cripto = sha1($this->senha);
            $sql = "INSERT INTO $this->tabela VALUES (null,?,?,?,?,?,?,?,?)";
            $sql = DB::prepare($sql);
            //return $sql->execute(array($this->nome,$this->email,$senha_cripto,$this->recupera_senha,$this->token,$this->codigo_confirmacao,$this->status,$data_cadastro));

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            try{
                 //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;               //Enable verbose debug output
                $mail->isSMTP();                                     //Send using SMTP
                $mail->Host       = 'sandbox.smtp.mailtrap.io';         //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                            //Enable SMTP authentication
                $mail->Username   = '77c3e19b074a2c';    //SMTP username
                $mail->Password   = 'ebd351081a45f2';                //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //Enable implicit TLS encryption
                $mail->Port       = 2525;   
                
                //Recipients
                $mail->setFrom('ricardo@peoplemuriae.com.br', 'Ricardo');
                $mail->addAddress($this->email, $this->nome);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Falta pouco pra você acessar nosso site';
                $mail->Body    = "This is the HTML message body <b>in bold!</b> <a 'href=http://localhost/login-poo/confirmar-email.php?chave='>Clique Aqui</a>";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                
            }catch (Exception $e) {
                $this->erro["erro_geral"]="Email de Confirmação não pode ser enviado {$mail->ErrorInfo}";
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            return $sql->execute(array($this->nome,$this->email,$senha_cripto,$this->recupera_senha,$this->token,$this->codigo_confirmacao,$this->status,$data_cadastro));
            }
        }
    public function update($id){
        $sql = "UPDATE $this->tabela SET token=? WHERE id=?";
        $sql = DB::prepare($sql);
        return $sql->execute(array($token,$id));
    }
    }    
?>