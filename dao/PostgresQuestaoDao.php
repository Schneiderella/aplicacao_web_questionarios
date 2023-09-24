<?php

include_once('QuestaoDao.php');
include_once('PostgresDao.php');

class PostgresQuestaoDao extends PostgresDao implements QuestaoDao {

    private $table_name = 'questao';
    
    public function insere($questao) {
        $id = 0;
        $query = "INSERT INTO " . $this->table_name . 
        " (descricao, is_discursiva, is_objetiva, is_multipla_escolha, imagem) VALUES" .
        " (:descricao, :is_discursiva, :is_objetiva, :is_multipla_escolha, :imagem) RETURNING id";

        $stmt = $this->conn->prepare($query);

        // bind values 
        $stmt->bindValue(":descricao", $questao->getDescricao());
        $stmt->bindValue(":is_discursiva", $questao->getIsDiscursiva(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":is_objetiva", $questao->getIsObjetiva(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":is_multipla_escolha", $questao->getIsMultiplaEscolha(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":imagem", $questao->getImagem());

        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row) {
                extract($row);
            }
            return [true, $id];
        }else{
            return [false, $id];
        }

    }

    public function altera($questao) {

        $query = "UPDATE  $this->table_name 
                    SET descricao = :descricao, is_discursiva = :is_discursiva, is_objetiva = :is_objetiva, is_multipla_escolha = :is_multipla_escolha, imagem = :imagem
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":descricao", $questao->getDescricao());
        $stmt->bindValue(":is_discursiva", $questao->getIsDiscursiva(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":is_objetiva", $questao->getIsObjetiva(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":is_multipla_escolha", $questao->getIsMultiplaEscolha(), \PDO::PARAM_BOOL);
        $stmt->bindValue(":imagem", $questao->getImagem());
        $stmt->bindValue(':id', $questao->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaTodos() {

        $questoes = array();

        $query = "SELECT questao.id as questao_id, questao.descricao as questao_desc, questao.is_discursiva, questao.is_objetiva, questao.is_multipla_escolha, questao.imagem, 
                    alt.id as alt_id, alt.descricao as alt_desc, alt.is_correta
                  FROM $this->table_name 
                  LEFT JOIN alternativa as alt on alt.questao_id = questao.id
                  ORDER BY questao.id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            if (!isset($questoes[$questao_id])) {
                $questao = new Questao($questao_id, $questao_desc, $is_discursiva, $is_objetiva, $is_multipla_escolha);
                $questao->setAlternativas(array());
                $questoes[$questao_id] = $questao;
            }

            if (isset($imagem)){
                $questoes[$questao_id]->setImagem($imagem);
            }

            if (isset($alt_id)){
                $alternativa = new Alternativa($alt_id, $alt_desc, $is_correta);
                $questoes[$questao_id]->addAlternativa($alternativa);
            }
            
        }
        
        return $questoes;
    }

    public function buscaPorID($id) {

        $questao = null;

        $query = "SELECT questao.id as questao_id, questao.descricao as questao_desc, questao.is_discursiva, questao.is_objetiva, questao.is_multipla_escolha, questao.imagem, 
                         alt.id as alt_id, alt.descricao as alt_desc, alt.is_correta
                  FROM $this->table_name
                  LEFT JOIN alternativa as alt on alt.questao_id = questao.id
                  WHERE questao.id IN (
                    SELECT questao.id FROM questao WHERE questao.id = ?
                    LIMIT 10 OFFSET 0)";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
            if (!isset($questao)){
                $questao = new Questao($row['questao_id'], $row['questao_desc'], $row['is_discursiva'], $row['is_objetiva'], $row['is_multipla_escolha']);
            }

            if (isset($row['imagem'])){
                $questao->setImagem($row['imagem']);
            }

            if (isset($row['alt_id'])){
                $alternativa = new Alternativa($row['alt_id'], $row['alt_desc'], $row['is_correta']);
                $questao->addAlternativa($alternativa);
            }
        }

        return $questao;
    }

    public function buscaPorDesc($desc, $limite, $pagina, $id = null) {

        $params = [];
        $questoes = [];

        $query = "SELECT id, descricao, is_discursiva, is_objetiva, is_multipla_escolha, imagem
                  FROM $this->table_name
                  WHERE UPPER(descricao) LIKE :descricao ";

        if (isset($id) && !empty($id)) {
            $query .= " OR id = :id ";
            $params["id"] = $id;
        }

        $query .= "LIMIT :limite OFFSET :pagina";
        $params["descricao"] = "%" . strtoupper($desc) . "%";
        $params["limite"] = $limite;
        $params["pagina"] = $pagina;

        $stmt = $this->conn->prepare( $query );
        $this->bindArrayValue($stmt, $params);
        $stmt->execute();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
            $questao = new Questao($row["id"], $row["descricao"], $row["is_discursiva"], $row["is_objetiva"], $row["is_multipla_escolha"]);

            if (isset($row['imagem'])) {
                $questao->setImagem($row['imagem']);
            }

            $questoes[] = $questao;
        }

        return $questoes;
    }

    public function contaRegistros($desc) {

        $totalQuestoes = 0;

        $query = "SELECT count(id) as count_is
                  FROM $this->table_name
                  WHERE UPPER(descricao) LIKE :descricao";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":descricao", "%" . strtoupper($desc) . "%");
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            extract($row);
            $totalQuestoes = $count_is;
        }

        return $totalQuestoes;
    }

    private function verifica_relacao($questao){
        $relacao = false;

        $query = "SELECT id FROM alternativa WHERE questao_id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(':id', $questao->getId());
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $relacao = true;
        } 
        
        return $relacao;
    }

    public function remove($questao) {

        $relacao = $this->verifica_relacao($questao);

        if($relacao) {
            return false;
        } else {
            $query = "DELETE FROM $this->table_name WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindValue(':id', $questao->getId());

            // execute the query
            if($stmt->execute()){
                return true;
            }
        }
       
        return false;
        
    }

    protected function bindArrayValue($stmt, $array){

        foreach($array as $key => $value){
            $stmt->bindValue( ":$key", $value);
        }

    }

}
?>