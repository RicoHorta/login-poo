<?php

//function __autoload($nomeClasse){

    // $arquivo = __DIR__.'/class/'.$nomeClasse.'.php';
    // if(is_file($arquivo)){
    // require_once($arquivo);
    // Cria um arquivo
    // }else{
    // throw new Exception('Arquivo '.$nomeClasse.' não existe!');
    // }

    spl_autoload_register(function($nomeClasse) {
        if(file_exists(DIR_FS_CLASSES . "class." . $nomeClasse . ".php"))
            include DIR_FS_CLASSES . "class." . $nomeClasse . ".php";
    });
?>