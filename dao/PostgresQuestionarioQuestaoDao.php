<?php

include_once('QuestionarioQuestaoDao.php');
include_once('PostgresDao.php');

class PostgresQuestionarioQuestaoDao extends PostgresDao implements QuestionarioQuestaoDao {

    private $table_name = 'questionario_questao';
    
    public function insere($questionarioQuestao, $questionario_id) {

        $query = "INSERT INTO $this->table_name (ordem, pontos, questionario_id, questao_id)
                VALUES (:ordem, :pontos, :questionario_id, :questao_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 
        $stmt->bindValue(":ordem", $questionarioQuestao->getOrdem());
        $stmt->bindValue(":pontos", $questionarioQuestao->getPontos());
        $stmt->bindValue(":questionario_id", $questionario_id);
        $stmt->bindValue(":questao_id", ($questionarioQuestao->getQuestao())->getId());

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    public function altera($questionarioQuestao, $questionario_id) {

        $query = "UPDATE  $this->table_name 
                    SET ordem = :ordem, pontos = :pontos, questionario_id = :questionario_id,  questao_id = :questao_id
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":ordem", $questionarioQuestao->getOrdem());
        $stmt->bindValue(":pontos", $questionarioQuestao->getPontos());
        $stmt->bindValue(":questionario_id", $questionario_id);
        $stmt->bindValue(":questao_id", ($questionarioQuestao->getQuestao())->getId());
        $stmt->bindValue(':id', $questionarioQuestao->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function remove($questionarioQuestao) {

        $query = "DELETE FROM $this->table_name WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindParam(':id', $questionarioQuestao->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }
        
           
        return false;
        
    }

    public function buscaTodos() {

        $questionarios = array();

        $table_name = $this->table_name;

        $query = "SELECT  $table_name.id, $table_name.nome, descricao, data_criacao, nota_aprovacao, elaborador.nome as nome_elaborador
                  FROM $table_name
                  INNER JOIN elaborador on (elaborador_id = elaborador.id)
                  ORDER BY id ASC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $questionarios[] = new Questionario($id, $nome, $descricao, $data_criacao, $nota_aprovacao, $nome_elaborador);
        }
        
        return $questionarios;
    }

    public function buscaPorID($id) {

        $questionarioQuestao = null;
        $table_name = $this->table_name;

        $query = "SELECT $table_name.id, ordem, pontos, questao_id , questao.descricao as questao_descricao, is_discursiva, is_objetiva, is_multipla_escolha
                  FROM $table_name
                  INNER JOIN questao on (questao_id = questao.id)
                  WHERE $table_name.id = ?
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            if($row['questao_id']){
                $questao = new Questao($row['questao_id'], $row['questao_descricao'], $row['is_discursiva'], $row['is_objetiva'], $row['is_multipla_escolha']);
            }
            $questionarioQuestao = new QuestionarioQuestao($row['id'], $row['ordem'], $row['pontos'], $questao);
        } 

        return $questionarioQuestao;
    }

    public function buscaQuestionarioQuestao($questao, $quationario) {
  
        $questionarioQuestao = null;

        $table_name = $this->table_name;

        $query = "SELECT id, ordem, pontos
                  FROM $table_name
                  WHERE questionario_id = :questionario_id and questao_id = :questao_id
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':questionario_id', $quationario->getId());
        $stmt->bindValue(':questao_id', $questao->getId());
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $questionarioQuestao = new QuestionarioQuestao($row['id'], $row['ordem'], $row['pontos'], $questao);
        } 

        return $questionarioQuestao;
    }

}
?>