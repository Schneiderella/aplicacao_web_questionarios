<?php

include_once('RespondenteDao.php');
include_once('PostgresDao.php');

class PostgresRespondenteDao extends PostgresDao implements RespondenteDao {

    private $table_name = 'respondente';
    
    public function insere($respondente) {

        $query = "INSERT INTO " . $this->table_name . 
        " (login, senha, nome, email, cpf, telefone) VALUES" .
        " (:login, :senha, :nome, :email, :cpf, :telefone)";

        $stmt = $this->conn->prepare($query);

        // bind values
        $temp_login = ($respondente->getLogin());
        $temp_senha = md5($respondente->getSenha());
        $temp_nome = ($respondente->getNome());
        $temp_email = ($respondente->getEmail());
        $temp_cpf = ($respondente->getCpf());
        $temp_telefone = ($respondente->getTelefone());

        $stmt->bindValue(":login", $temp_login);
        $stmt->bindValue(":senha", $temp_senha);
        $stmt->bindValue(":nome", $temp_nome);
        $stmt->bindValue(":email", $temp_email);
        $stmt->bindValue(":cpf", $temp_cpf);
        $stmt->bindValue(":telefone", $temp_telefone);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

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

    public function remove($respondente) {
        return $this->removePorId($respondente->getId());
    }

    public function altera($respondente) {

        $query = "UPDATE " . $this->table_name .
        " SET login = :login, senha = :senha, nome = :nome, email = :email, cpf = :cpf, telefone = :telefone" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":login", $respondente->getLogin());
        $stmt->bindValue(":senha", md5($respondente->getSenha()));
        $stmt->bindValue(":nome", $respondente->getNome());
        $stmt->bindValue(":email", $respondente->getEmail());
        $stmt->bindValue(":cpf", $respondente->getCpf());
        $stmt->bindValue(":telefone", $respondente->getTelefone());
        $stmt->bindValue(':id', $respondente->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaTodos() {

        $respondentes = array();

        $query = "SELECT id, login, senha, nome, email, cpf, telefone
                  FROM $this->table_name
                  ORDER BY id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $respondentes[] = new Respondente($id, $login, $senha, $nome, $email, $cpf, $telefone);
        }
        
        return $respondentes;
    }

    public function buscaPorID($id) {

        $respondente = null;

        $query = "SELECT 
                    id, login, senha, nome, email, cpf, telefone
                FROM 
                    " . $this->table_name . "
                WHERE
                    id = ?
                LIMIT 
                    1 OFFSET 0";
       
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        
        if($row) {
            $respondente = new Respondente($row['id'],$row['login'],$row['senha'],$row['nome'],$row['email'],$row['cpf'],$row['telefone']);
        } 

        return $respondente;
    }

    /**
     * Summary of buscaPorLogin
     * @param mixed $login
     * @return Respondente|null
     */
    public function buscaPorLogin($login) {

        $respondente = null;

        $query = "SELECT id, login, senha, nome, email, cpf, telefone
                FROM  $this->table_name
                WHERE  login = ?
                LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $login);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        
        if($row) {
            $respondente = new Respondente($row['id'],$row['login'],$row['senha'],$row['nome'],$row['email'],$row['cpf'],$row['telefone']);
        } 

        return $respondente;
    }

    public function buscaPorCpf($cpf) {

        $respondente = null;

        $query = "SELECT
                    id, login, senha, nome, email, cpf, telefone
                FROM
                    " . $this->table_name . "
                WHERE 
                    cpf = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $cpf);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        
        if($row) {
            $respondente = new Respondente($row['id'],$row['login'],$row['senha'],$row['nome'],$row['email'],$row['cpf'],$row['telefone']);
        } 
        
        return $respondente;
    }

    public function buscaPorNome($nome, $limite, $pagina, $id = null) {

        $params = [];
        $respondentes = [];

        $query = "SELECT id, login, senha, nome, email, cpf, telefone
                FROM $this->table_name
                WHERE UPPER(nome) LIKE :nome ";

        if (isset($id) && !empty($id)) {
            $query .= "OR id = :id ";
            $params["id"] = $id;
        }

        $query .= "LIMIT :limite OFFSET :pagina";
        $params["nome"] = strtoupper($nome) . "%";
        $params["limite"] = $limite;
        $params["pagina"] = $pagina;

        $stmt = $this->conn->prepare( $query );
        $this->bindArrayValue($stmt, $params);
        $stmt->execute();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $respondentes[] = new Respondente($id, $login, $senha, $nome, $email, $cpf, $telefone);
        }
        
        return $respondentes;
    }

    public function contaRegistros($nome) {

        $total = 0;

        $query = "SELECT count(*) as total
                  FROM $this->table_name
                  WHERE UPPER(nome) LIKE :nome";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":nome", strtoupper($nome) . "%");
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $total = $row['total'];
        }

        return $total;
    }

    protected function bindArrayValue($stmt, $array){

        foreach($array as $key => $value){
            $stmt->bindValue( ":$key", $value);
        }

    }

}
?>