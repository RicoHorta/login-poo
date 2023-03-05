<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'lib/vendor/autoload.php';

require_once 'Crud.php';

class Recuperar_senha extends Crud{
    protected string $tabela = 'usuarios'; // Define tabela Usuários

    //Constroi a Classe com os atributos email e erro
    function __construct(private string $email,public array $erro=[]){}

    // Validação do email
    public function valida_email(){
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) { 
            $this->erro["erro_email"]="Digite seu e-mail cadastrado";
        }
    }
    public function insert(){
        //Verificar se email já consta no Banco de Dados
        $sql = "SELECT * FROM $this->tabela WHERE email=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $usuario = $sql->fetch();

        if (!$usuario) {
            $this->erro["erro_geral"]="Email não cadastrado!";
        }else{
            $recupera_senha = password_hash($this->email . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);
            $sql = "UPDATE $this->tabela SET recupera_senha=? WHERE id=?";
            $sql = DB::prepare($sql);
            if($sql->execute(array($recupera_senha,$id))){
                $this->erro["erro_geral"]="Falha na comunicação com o Servidor!";
            }else{
           
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
                $mail->Subject = 'Recupere seu acesso - RicoCred';
                $mail->Body    = "Prezado(a) " . $this->nome . ".<br><br>Atendendo a sua solicita&ccedil;&atilde;o de recuperação da senha em nosso site, solicitamos que clique no link abaixo e volte a ser feliz.<br><br><a href='http://localhost/login-poo/confirmar-email.php?recupera_senha=?recupera_senha'>Clique aqui e Volte a ser Feliz</a><br><br>Esta mensagem foi enviada a pela RicoCred.<br>Nenhum e-mail enviado pela RicoCred tem arquivos anexados ou solicita o preenchimento de senhas e informações cadastrais.<br><br>" ;
                $mail->AltBody = 'Por favor, reserve um tempo para verificar seu e-mail agora. \n\nEsteja atento as suas notifica&ccedil;&otilde;es importantes e evite problemas ao fazer seu login. \n\n';

                $mail->send();
                
            }catch (Exception $e) {
                $this->erro["erro_geral"]="Email de Confirmação não pode ser enviado {$mail->ErrorInfo}";
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
           
            }
        }
    }
    public function update($id){
        $sql = "UPDATE $this->tabela SET recupera_senha=? WHERE id=?";
        $sql = DB::prepare($sql);
        return $sql->execute(array($recupera_senha,$id));
    }
}
