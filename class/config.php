<?php
// Inicializa a SESSÃO $_SESSION['TOKEN'] do arquivo Login.php
session_start();
// CONFIGURAÇÕES DO BANCO MYSQL LOGIN
define('SERVIDOR', 'localhost');
define('USUARIO', 'root');
define('SENHA','614424Beta');
define('BANCO', 'login');

// ANTI HTML INJECTION
function limpaPost ($dados){
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

?>
