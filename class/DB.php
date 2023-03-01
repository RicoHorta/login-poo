<?php
// CARREGA CONFIG.PHP
require_once dirname(__FILE__) . '/config.php'

// ACESSO AO MYSQL LOGIN VIA PDO
class DB{
    private static $pdo;
    public static function instanciar(){
        if(!isset(self::$pdo)){
          try{    
            self::$pdo = new PDO('mysql:host='SERVIDOR'.;dbname='.BANCO,USUARIO,SENHA);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Automatico
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJECT);
            self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //Automatico
            }catch(PDOException $erro){
                echo "Falha na conecção MYSQL: ".$erro->getMessage();
            }

        }
        return self::$pdo;
    }   

    public static function prepare($sql){
        return self::->instanciar()->prepare($sql);
    }

}