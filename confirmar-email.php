<?php

//session_start(); // Inicia Sessão
//ob_start(); // Limpa o buffer de saída 

require_once('class/config.php');
require_once('autoload.php');

$status='1';

$chave = filter_input(INPUT_GET, "chave", FILTER_SANITIZE_STRING);

if(!empty($chave)){
    //Verificar se a chave já consta no Banco de Dados
    $sql = "SELECT id FROM usuarios WHERE chave=? LIMIT 1";
    $sql = DB::prepare($sql);
    $sql->execute(array($chave));
    $usuario = $sql->fetch();
    //Adicionar o novo usuário caso ele ainda não exista no Banco
    if ($usuario) {
        //Altera a situação do usuário para 1(ativo)
        $sql = "UPDATE usuarios SET status=1 WHERE chave=?";
        $sql = DB::prepare($sql); 
            if ($sql->execute(array($chave))){
                $_SESSION["msg"] = "<div class='sucesso animate__animated animate__rubberBand'>Confirmação de Email Realizada com Sucesso</div>";
                header("Location: index.php");
            }else{
                $_SESSION["msg"] = "<div class='erro-geral animate__animated animate__rubberBand'>Confira seu email e valide seu Cadastro</div>";
                header("Location: index.php");
            }

    }else{
        $_SESSION["msg"] = "<div class='erro-geral animate__animated animate__rubberBand'>Confira seu email e valide seu Cadastro</div>";
        header("Location: index.php");
    }
}