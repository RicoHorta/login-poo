<?php

require_once('class/config.php');
require_once('autoload.php');


// verificar se usuário digitou e enviou todos os dados
if (isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){
// Recebe e Limpa os dados
    $email = limpaPost($_POST['email']);
    $senha = limpaPost($_POST['senha']);
    $email = strtolower($email);   
// instancia classe Login
    $login = new Login();
// Chama o método auth da Classe Login
    $login->auth($email, $senha);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <h1>Login</h1>

        <?php
            if(isset($login->erro["erro_geral"])){?>
                <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo $login->erro["erro_geral"];?>
                </div>
        <?php }?>
              
        <!-- recebe msg do arquivo Confirmar-email caso o código de confirmação falhe -->
        <?php 
        if (isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
        }?>
        
        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>
        
        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
       
        <button class="btn-blue" type="submit">Fazer Login</button>
        <div class="input-group">
        <a href="cadastrar.php">Quero me cadastrar agora</a>
        <br>
        <a style="text-align:center;" href="recuperar-senha.php">Esqueci a minha senha</a>
        </div>
    </form>
</body>
</html>