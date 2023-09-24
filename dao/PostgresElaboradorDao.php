<?php

include_once('ElaboradorDao.php');
include_once('PostgresDao.php');

class PostgresElaboradorDao extends PostgresDao implements ElaboradorDao {

    private $table_name = 'elaborador';
    
    public function insere($elaborador) {

        $query = "INSERT INTO " . $this->table_name . 
        " (login, senha, nome, email, cpf, instituicao, is_admin) VALUES" .
        " (:login, :senha, :nome, :email, :cpf, :instituicao, :is_admin)";

        $stmt = $this->conn->prepare($query);

        // bind values 
        $stmt->bindValue(":login", $elaborador->getLogin());
        $stmt->bindValue(":senha", md5($elaborador->getSenha()));
        $stmt->bindValue(":nome", $elaborador->getNome());
        $stmt->bindValue(":email", $elaborador->getEmail());
        $stmt->bindValue(":cpf", $elaborador->getCpf());
        $stmt->bindValue(":instituicao", $elaborador->getInstituicao());
        $stmt->bindValue(":is_admin", $elaborador->getIsAdmin(), \PDO::PARAM_BOOL);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function altera($elaborador) {

        $query = "UPDATE  $this->table_name 
                    SET login = :login, senha = :senha, nome = :nome, email = :email, cpf = :cpf, instituicao = :instituicao, is_admin = :is_admin
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":login", $elaborador->getLogin());
        $stmt->bindValue(":senha", md5($elaborador->getSenha()));
        $stmt->bindValue(":nome", $elaborador->getNome());
        $stmt->bindValue(":email", $elaborador->getEmail());
        $stmt->bindValue(":cpf", $elaborador->getCpf());
        $stmt->bindValue(":instituicao", $elaborador->getInstituicao());
        $stmt->bindValue(':id', $elaborador->getId());
        $stmt->bindValue(":is_admin", $elaborador->getIsAdmin(), \PDO::PARAM_BOOL);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . 
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(':id', $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaTodos() {

        $elaboradores = array();

        $query = "SELECT id, login, senha, nome, email, cpf, instituicao, is_admin
                  FROM $this->table_name
                  ORDER BY id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $elaboradores[] = new Elaborador($id, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin);
        }
        
        return $elaboradores;
    }

    public function buscaPorID($id) {

        $elaborador = null;

        $query = "SELECT id, login, senha, nome, email, cpf, instituicao, is_admin
                  FROM $this->table_name
                  WHERE $this->table_name.id = :id
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            extract($row);
            $elaborador = new Elaborador($id, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin);
        } 
     
        return $elaborador;
    }

    public function buscaPorLogin($login) {

        $elaborador = null;

        $query = "SELECT id, login, senha, nome, email, cpf, instituicao, is_admin
                  FROM $this->table_name
                  WHERE $this->table_name.login = :login
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            extract($row);
            $elaborador = new Elaborador($id, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin);
        } 
     
        return $elaborador;
    }

    public function buscaPorCpf($cpf) {

        $elaborador = null;

        $query = "SELECT id, login, senha, nome, email, cpf, instituicao, is_admin
                  FROM $this->table_name
                  WHERE $this->table_name.cpf = :cpf
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':cpf', $cpf);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            extract($row);
            $elaborador = new Elaborador($id, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin);
        } 
     
        return $elaborador;
    }
}
?>