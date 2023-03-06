<?php
require_once('class/config.php');
require_once('autoload.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet">
    <title>Recuperar Senha</title>
</head>
<body>
    <!-- <h1>Recuperar Senha</h1> -->
<?php

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($dados['SendRecupSenha'])) {
    //var_dump($dados);    
    $email=$dados['email'];
        //Criar um objeto Usuário
     $recupsenha = new Recupsenha($email);
             // Validar o email do usuário
             $recupsenha->valida_email();
             // Verifica se houve algum erro
         if(empty($recupsenha->erro)){
             //inserir cadastro no banco 
             if($recupsenha->insert()){
                 header('location: index.php');
             }else{
                 $erro_geral = $recupsenha->erro["erro_geral"];
             }; 
         }
}
?>
    <form method="POST" action="">
        <h1>Recuperar Senha</h1>

        <?php
            if(isset($recupsenha->erro["erro_geral"])){?>
                <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo $recupsenha->erro["erro_geral"];?>
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
            <input type="email" value="<?php if(isset($dados['email'])){echo $dados['email'];}?>" name="email" placeholder="Digite seu email" required>
        </div>
       
        <button class="btn-blue" value="erro" name="SendRecupSenha" type="submit">Clique para Recuperar Senha</button>
        <div class="input-group">
        <a href="cadastrar.php">Quero me cadastrar agora</a>
    
        </div>
    </form>
</body>
</html>



