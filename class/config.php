<?php
// CONFIGURAÇÕES DO BANCO MYSQL LOGIN
define('SERVIDOR', 'localhost');
define('USUARIO', 'root');
define('SENHA','');
define('BANCO', 'login');

// ANTI HTML INJECTION
function limpaPost ($dados){
    $dados = trim($dados);
    $dados = stripslashes ($dados);
    $dados = htmlspecialchars ($dados);
    return $dados;
}

?>