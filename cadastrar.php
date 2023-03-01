<?php
require_once dirname(__FILE__) . 'class/config.php';
require_once dirname(__FILE__) . 'autoload.php';

// verificar se usuário digitou e enviou todos os dados
if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
   // Recebe e Limpa os dados
    $nome = limpaPost($_POST['nome']);
    $email = limpaPost($_POST['email']);
    $senha = limpaPost($_POST['senha']);
    $repete_senha = limpaPost($_POST['repete_senha']);
    

    // Verifica se campos do formulario de login estão vazios
    if(empty($nome) || empty($email) || empty($senha) || empty($repete_senha) || empty($_POST['termos'])){
       $erro_geral = "Todos os campos são obrigatórios!";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title>Cadastrar</title>
</head>

<body>
    <form method="POST">
        <h1>Cadastrar</h1>

        <?php
            if(isset($erro_geral)){?>
        <div class="erro-geral animate__animated animate__rubberBand">
            <?php echo $erro_geral;?>
        </div>
        <?php }?>

        <div class="input-group">
            <img class="input-icon" src="img/card.png">
            <input class="erro-input" name="nome" type="text" placeholder="Nome Completo" required>
            <div class="erro">Por favor informe um nome válido!</div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" name="email" placeholder="Seu melhor email" required>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input type="password" name="senha" placeholder="Senha mínimo 6 Dígitos" required>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input type="password" name="repete_senha" placeholder="Repita a senha criada" required>
        </div>

        <div class="input-group">
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de
                    Privacidade</a> e os <a class="link" href="#">Termos de uso</a></label>
        </div>


        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.html">Já tenho uma conta</a>
    </form>
</body>

</html>