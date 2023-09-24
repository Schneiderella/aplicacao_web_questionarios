<?php

include_once('QuestionarioDao.php');
include_once('PostgresDao.php');

class PostgresQuestionarioDao extends PostgresDao implements QuestionarioDao {

    private $table_name = 'questionario';
    
    public function insere($questionario) {

        $query = "INSERT INTO $this->table_name (nome, descricao, nota_aprovacao, elaborador_id)
                VALUES (:nome, :descricao, :nota_aprovacao, :elaborador_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 
        $stmt->bindValue(":nome", $questionario->getNome());
        $stmt->bindValue(":descricao", $questionario->getDescricao());
        $stmt->bindValue(":nota_aprovacao", $questionario->getNotaAprovacao());
        $stmt->bindValue(":elaborador_id", $questionario->getElaborador()->getId());

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    public function altera($questionario) {

        $query = "UPDATE  $this->table_name 
                    SET nome = :nome, descricao = :descricao, nota_aprovacao = :nota_aprovacao 
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":nome", $questionario->getNome());
        $stmt->bindValue(":descricao", $questionario->getdescricao());
        $stmt->bindValue(":nota_aprovacao", $questionario->getNotaAprovacao());
        $stmt->bindValue(':id', $questionario->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    private function verifica_relacao($questionario){
        $relacao = false;

        $query = "SELECT id FROM questionario_questao  WHERE questionario_id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(':id', $questionario->getId());
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $relacao = true;
        } 
        
        return $relacao;
    }

    public function remove($questionario) {

        $relacao = $this->verifica_relacao($questionario);

        if($relacao){
            return false;
        }

        else{
            $query = "DELETE FROM $this->table_name WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindValue(':id', $questionario->getId());

            // execute the query
            if($stmt->execute()){
                return true;
            }
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

    private function buscaQuestoesQuestionario($questionario){

        $id = $questionario->getId();
 
        $table_name = "questionario_questao";

        $query = "SELECT $table_name.id, ordem, pontos, questao_id , questao.descricao as questao_descricao, is_discursiva, is_objetiva, is_multipla_escolha, imagem
                  FROM $table_name
                  INNER JOIN questionario on (questionario.id = questionario_id)
                  INNER JOIN questao on (questao_id = questao.id)
                  WHERE questionario_id = ?
                  ORDER BY ordem ASC ";

                  $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, $id);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $questao = new Questao($row['questao_id'], $row['questao_descricao'], $row['is_discursiva'], $row['is_objetiva'], $row['is_multipla_escolha']);

            if (isset($row['imagem'])) {
                $questao->setImagem($row['imagem']);
            }

            $questionarioQuestoes = new QuestionarioQuestao($row['id'], $row['ordem'], $row['pontos'], $questao);
            $questionario->addQuestionarioQuestoes($questionarioQuestoes);
        }
    }

    public function buscaPorID($id) {

        $questionario = null;
        $table_name = $this->table_name;

        $query = "SELECT $table_name.id, $table_name.nome, descricao, data_criacao, nota_aprovacao, elaborador.nome as nome_elaborador
                  FROM $table_name
                  INNER JOIN elaborador on (elaborador_id = elaborador.id)
                  WHERE $table_name.id = ?
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $questionario = new Questionario($row['id'],$row['nome'], $row['descricao'], $row['data_criacao'], $row['nota_aprovacao'], $row['nome_elaborador']);
        } 
        if($questionario){
           $this->buscaQuestoesQuestionario($questionario);
        }

        return $questionario;
    }

    public function buscaPorNome($nome, $limite, $pagina, $id = null) {

        $params = array();

        $questionarios = array();
        $table_name = $this->table_name;

        $query = "SELECT $table_name.id, $table_name.nome, descricao, data_criacao, nota_aprovacao, elaborador.nome as nome_elaborador
                  FROM $table_name
                  INNER JOIN elaborador ON (elaborador_id = elaborador.id)
                  WHERE UPPER($table_name.nome) LIKE :nome ";

        if (isset($id) && !empty($id)) {
            $query .= " OR $table_name.id = :id ";
            $params["id"] = $id;
        }
        
        $query .= "LIMIT :limite OFFSET :pagina";
        $params["nome"] = "%" . strtoupper($nome) . "%";
        $params["limite"] = $limite;
        $params["pagina"] = $pagina;

        $stmt = $this->conn->prepare($query);
        $this->bindArrayValue($stmt, $params);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $questionarios[] = new Questionario($row['id'],$row['nome'], $row['descricao'], $row['data_criacao'], $row['nota_aprovacao'], $row['nome_elaborador']);
        }

        return $questionarios;
    }

    public function contaPorNome($nome) {

        $total = 0;
        $table_name = $this->table_name;

        $query = "SELECT COUNT(*) as total
                  FROM $table_name
                  WHERE UPPER($table_name.nome) LIKE :nome ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":nome", "%" . strtoupper($nome) . "%");
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $total = $row['total'];
        }

        return $total;
    }

    public function buscaPorElaborador($elaboradorId, $nome, $limite, $pagina) {

        $questionarios = array();
        $table_name = $this->table_name;

        $query = "SELECT $table_name.id, $table_name.nome, descricao, data_criacao, nota_aprovacao, elaborador.nome as nome_elaborador
                  FROM $table_name
                  INNER JOIN elaborador ON (elaborador_id = elaborador.id)
                  WHERE elaborador_id = :id AND UPPER(elaborador.nome) LIKE :nome
                  LIMIT :limite OFFSET :pagina";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $elaboradorId);
        $stmt->bindValue(":nome", "%" . strtoupper($nome) . "%");
        $stmt->bindValue(":limite", $limite);
        $stmt->bindValue(":pagina", $pagina);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $questionarios[] = new Questionario($row['id'],$row['nome'], $row['descricao'], $row['data_criacao'], $row['nota_aprovacao'], $row['nome_elaborador']);
        }

        return $questionarios;
    }

    public function contaPorElaborador($elaboradorId, $nome) {

        $total = 0;

        $query = "SELECT COUNT(*) as total
                  FROM $this->table_name
                  INNER JOIN elaborador ON elaborador.id = elaborador_id
                  WHERE elaborador.id = :id AND UPPER(elaborador.nome) LIKE :nome ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $elaboradorId);
        $stmt->bindValue(":nome", "%" .  strtoupper($nome) . "%");
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $total = $row['total'];
        }

        return $total;
    }

    public function contaRespostas($elaboradorId, $nome) {

        $total = 0;

        $query = "SELECT COUNT(*) as total
                  FROM $this->table_name
                  INNER JOIN elaborador ON elaborador.id = elaborador_id
                  WHERE elaborador.id = :id AND UPPER(elaborador.nome) LIKE :nome ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $elaboradorId);
        $stmt->bindValue(":nome", "%" . strtoupper($nome) . "%");
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
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