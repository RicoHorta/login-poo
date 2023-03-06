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
    <title>Atualizar Senha</title>
</head>
<body>
    <!-- <h1>Recuperar Senha</h1> -->
<?php
$codigo_confirmacao = filter_input(INPUT_GET, "chave", FILTER_SANITIZE_STRING);
//var_dump($chave);
if(!empty($codigo_confirmacao)){
    //recebe dados do formulario abaixo POST
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if (!empty($dados['SendNovaSenha'])) {
            //Captura a senha(str) dos dados(array)
        $senha=$dados['senha'];
            //instancia classe Recupsenha
        $recupsenha = new Recupsenha($codigo_confirmacao,$senha);
             // Validar a chave do usuário
        if(empty($recupsenha->validachave($codigo_confirmacao,$senha))){
            
            if(empty($recupsenha->erro)){
             //inserir cadastro no banco 
                if($recupsenha->insert()){
                 header('location: index.php');
                }else{
                 $erro_geral = $recupsenha->erro["erro_geral"];
                }
            }else{
            $erro_geral = $recupsenha->erro["erro_geral"];
            }
        }
    }  
}   
?>
<form method="POST" action="">
    <h1>Atualizar Senha</h1>

    <?php
        if(isset($recupsenha->erro["erro_geral"])){?>
            <div class="erro-geral animate__animated animate__rubberBand">
            <?php echo $recupsenha->erro["erro_geral"];?>
            </div>
    <?php }?>
   
    <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if(isset($recupsenha->erro["erro_senha"]) || isset($erro_geral)){ echo'class="erro-input"';}?>type="password" name="senha" <?php if(isset($_POST['senha'])){echo 'value"'.$_POST['senha'].'"';}?> placeholder="Digite a nova Senha (mínimo 6 Dígitos)" required>
            <div class="erro"><?php if(isset($recupsenha->erro["erro_senha"])){echo $recupsenha->erro["erro_senha"];}?></div>
    </div>
    <button class="btn-blue" value="Atualiza" name="SendNovaSenha" type="submit">Clique para Recuperar Senha</button>
    <div class="input-group">
    <a href="index.php">Lembrei minha senha!</a>
        <br>
    <a href="cadastrar.php">Quero me cadastrar agora</a>
    </div>
</form>
</body>
</html>