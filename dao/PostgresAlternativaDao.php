<?php

include_once('AlternativaDao.php');
include_once('PostgresDao.php');

class PostgresAlternativaDao extends PostgresDao implements AlternativaDao {

    private $table_name = 'alternativa';
    
    public function insere($alternativa, $questao_id) {

        $query = "INSERT INTO $this->table_name 
                (descricao, is_correta, questao_id) VALUES
                (:descricao, :is_correta, :questao_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 
        $stmt->bindValue(":descricao", $alternativa->getDescricao());
        $stmt->bindValue(":is_correta", $alternativa->getIsCorreta(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":questao_id", $questao_id);
        
        if($stmt->execute()){
            return true;
        }
        return false;  
    }
    
    public function altera($alternativa) {

        $query = "UPDATE  $this->table_name 
                    SET descricao = :descricao, is_correta = :is_correta
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":descricao", $alternativa->getDescricao());
        $stmt->bindValue(":is_correta", $alternativa->getIsCorreta(), \PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $alternativa->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    public function buscaPorId($id) {

        $alternativa = null;

        $query = "SELECT id, descricao, is_correta
                  FROM $this->table_name
                  WHERE id = :id";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $alternativa = new Alternativa($row['id'], $row['descricao'], $row['is_correta']);
        } 
        
        return $alternativa;
    }

    public function remove($alternativa) {

        $query = "DELETE FROM $this->table_name WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindParam(':id', $alternativa->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;  
    }
}
?>