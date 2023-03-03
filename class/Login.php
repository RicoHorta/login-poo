<?php
require_once('DB.php');

class Login{
    protected string $tabela = 'usuarios';
    public string $email;
    private string $senha;
    private string $token;
    public string $nome;
    public array $erro=[];

    public function auth($email, $senha){

        //Criptografa a senha

        $senha_cripto = sha1($senha);

        // Verificar se tem usuário cadastrado

        $sql = "SELECT * FROM usuarios WHERE email=? AND senha=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($email, $senha_cripto));
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        if(!$usuario){
            $this->erro["erro_geral"]="Usuário ou senha incorretos!";
        }else{
            // criar um TOKEN
            $this->token = sha1(uniqid().date('d-m-Y-i-s'));
            // Atualiza esse token no MySQL
            $sql = "UPDATE $this->tabela SET token=? WHERE email=? AND senha=? LIMIT 1";
            $sql = DB::prepare($sql);
            if ($sql->execute(array($this->token, $email, $senha_cripto))){
                //Colocar o TOKEN na SESSÃO
                $_SESSION['TOKEN'] = $this->token;
                //Redirecionamos usuario para área restrita
                header('location: restrita/index.php');
            }else{
                $this->erro["erro_escolar"]="Falha de comunicação com o servidor!";
            }
        }
    }
    
    public function isAuth($token){
        $sql = "SELECT * FROM usuarios WHERE token=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($token));
        $usuario = $sql->fetch(PDO::FETCH_ASSOC); //recebe os dados do Banco como Matriz Associativa
        if($usuario){
            $this->nome = $usuario["nome"];
            $this->email = $usuario["email"];
        }else{
            header('location: ../index.php');
        }
    }
}
?>