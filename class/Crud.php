<?php
require_once('DB.php');

abstract class Crud extends DB{
    protected string $tabela; // DEFINE ATRIBUTO OBRIGATÓRIO PARA FILHOS

    abstract public function insert();
    abstract public function update($id);
   // abstract public function delete($id); //automatico

    public function find($id){
        $sql = "SELECT * FROM $this->tabela WHERE id=?";
        $sql = DB::prepare($sql);
        $sql->execute(array($id));
        $valor = $sql->fetch();
        return $valor;
    }

    public function findAll(){
        $sql = "SELECT * FROM $this->tabela";
        $sql = DB::prepare($sql);
        $sql->execute();
        $valor = $sql->fetchAll();
        return $valor;
    }

    public function delete($id){
        $sql = "DELETE * FROM $this->tabela WHERE id=?";
        $sql = DB::prepare($sql);
        return $sql->execute(array($id));
    }
}
?>