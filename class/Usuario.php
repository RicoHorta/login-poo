<?php
require_once dirname(__FILE__) . '/Crud.php';

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

        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/",$nome)) { $erro_nome = "Somente permitido letras e espaços em branco!"; }



        }
}

?>