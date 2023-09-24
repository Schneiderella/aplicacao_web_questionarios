<?php

include_once('OfertaDao.php');
include_once('PostgresDao.php');

class PostgresOfertaDao extends PostgresDao implements OfertaDao {

    private $table_name = 'oferta';
    
    public function insere($oferta) {

        $query = "INSERT INTO " . $this->table_name . 
        " (data, respondente_id, questionario_id) VALUES" .
        " (:data, :respondente_id, :questionario_id)";

        $stmt = $this->conn->prepare($query);

        // bind values 
        $stmt->bindValue(":data", $oferta->getData());
        $stmt->bindValue(":respondente_id", $oferta->getRespondente()->getId());
        $stmt->bindValue(":questionario_id", $oferta->getQuestionario()->getId());

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function altera($oferta) {

        $query = "UPDATE $this->table_name 
                  SET data = :data, respondente_id = :respondente_id, questionario_id = :questionario_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":id", $oferta->getId());
        $stmt->bindValue(":data", $oferta->getData());
        $stmt->bindValue(":respondente_id", $oferta->getRespondente()->getId());
        $stmt->bindValue(":questionario_id", $oferta->getQuestionario()->getId());

        // execute the query
        if($stmt->execute()){
            return true;
        }    

        return false;
    }

    public function buscaTodos() {

        $ofertas = array();

        $query = "SELECT oferta.id as oferta_id, oferta.data,
                    quest.id as quest_id, quest.nome as quest_nome, quest.descricao, quest.data_criacao, quest.nota_aprovacao,
                    elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                    resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = oferta.respondente_id
                  ORDER BY oferta.id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);
            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);
            $questionario = new Questionario($quest_id, $quest_nome, $descricao, $data_criacao, $nota_aprovacao, $elaborador);

            $ofertas[] = new Oferta($oferta_id, $data, $questionario, $respondente);
        }
        
        return $ofertas;
    }
    public function buscaPorId($id) {

        $oferta = new Oferta(null, null, null, null);

        $query = "SELECT oferta.id as oferta_id, oferta.data,
                    quest.id as quest_id, quest.nome as quest_nome, quest.descricao, quest.data_criacao, quest.nota_aprovacao,
                    elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                    resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = oferta.respondente_id
                  WHERE oferta.id = :id
                  LIMIT 1 OFFSET 0 ";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);

            $questionario = new Questionario($quest_id,$quest_nome, $descricao, $data_criacao, $nota_aprovacao, $elaborador);

            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);
            
            $oferta = new Oferta($oferta_id, $data, $questionario, $respondente);
        }
        
        return $oferta;
    }

    public function buscaPorQuestionario($questionario) {

        $ofertas = array();

        $query = "SELECT oferta.id as oferta_id, oferta.data,
                    quest.id as quest_id, quest.nome as quest_nome, quest.descricao, quest.data_criacao, quest.nota_aprovacao,
                    elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                    resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = oferta.respondente_id
                  WHERE oferta.questionario_id = :questionario_id
                  ORDER BY oferta.id ASC";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':questionario_id', $questionario);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);
            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);
            $questionario = new Questionario($quest_id, $quest_nome, $descricao, $data_criacao, $nota_aprovacao, $elaborador);

            $ofertas[] = new Oferta($oferta_id, $data, $questionario, $respondente);
        }
        
        return $ofertas;
    }
        
    public function buscaPorRespondente($respondente, $limit, $start, $descricao) {

        $ofertas = array();

        $query = "SELECT oferta.id as oferta_id, oferta.data,
                    quest.id as quest_id, quest.nome as quest_nome, quest.descricao, quest.data_criacao, quest.nota_aprovacao,
                    elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                    resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = oferta.respondente_id
                  WHERE oferta.respondente_id = :respondente_id
                  and (UPPER(quest.descricao) LIKE :descricao or UPPER(quest.nome) LIKE :nome)
                  ORDER BY oferta.id ASC LIMIT :limite OFFSET :pagina";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':respondente_id', $respondente);
        $stmt->bindValue(':descricao', '%' . strtoupper($descricao) . '%');
        $stmt->bindValue(':nome', '%' . strtoupper($descricao) . '%');
        $stmt->bindValue(":limite", $limit);
        $stmt->bindValue(":pagina", $start);
        $stmt->execute();

        $filter_query = $query . "LIMIT " .$limit. " OFFSET " . $start . '';
        error_log("---> DAO Query : " . $filter_query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);
            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);
            $questionario = new Questionario($quest_id, $quest_nome, $descricao, $data_criacao, $nota_aprovacao, $elaborador);

            $ofertas[] = new Oferta($oferta_id, $data, $questionario, $respondente);
        }
        
        return $ofertas;
    }

    public function buscaPorDesc($descricao, $limit, $start) {

        $ofertas = array();

        $query = "SELECT oferta.id as oferta_id, oferta.data,
                    quest.id as quest_id, quest.nome as quest_nome, quest.descricao, quest.data_criacao, quest.nota_aprovacao,
                    elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                    resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = oferta.respondente_id
                  WHERE (UPPER(quest.descricao) LIKE :descricao or UPPER(quest.nome) LIKE :nome)
                  ORDER BY oferta.id ASC LIMIT :limite OFFSET :pagina";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':descricao', '%' . strtoupper($descricao) . '%');
        $stmt->bindValue(':nome', '%' . strtoupper($descricao) . '%');
        $stmt->bindValue(":limite", $limit);
        $stmt->bindValue(":pagina", $start);
        $stmt->execute();

        $filter_query = $query . "LIMIT " .$limit. " OFFSET " . $start . '';
        error_log("---> DAO Query : " . $filter_query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);
            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);
            $questionario = new Questionario($quest_id, $quest_nome, $descricao, $data_criacao, $nota_aprovacao, $elaborador);

            $ofertas[] = new Oferta($oferta_id, $data, $questionario, $respondente);
        }
        
        return $ofertas;
    }
   
    public function contaRegistros($respondente, $descricao) {

        $totalOfertas = 0;

        $query = "SELECT count(oferta.id) as count_is
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  WHERE oferta.respondente_id = :respondente_id
                  and (UPPER(quest.descricao) LIKE :descricao or UPPER(quest.nome) LIKE :nome)";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':respondente_id', $respondente);
        $stmt->bindValue(':descricao', '%' . strtoupper($descricao) . '%');
        $stmt->bindValue(':nome', '%' . strtoupper($descricao) . '%');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            extract($row);
            $totalOfertas = $count_is;
        } 

        return $totalOfertas;
    }

    
    public function contaRegistrosDesc($descricao) {

        $totalOfertas = 0;

        $query = "SELECT count(oferta.id) as count_is
                  FROM $this->table_name
                  INNER JOIN questionario as quest on quest.id = oferta.questionario_id
                  WHERE (UPPER(quest.descricao) LIKE :descricao or UPPER(quest.nome) LIKE :nome)";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':descricao', '%' . strtoupper($descricao) . '%');
        $stmt->bindValue(':nome', '%' . strtoupper($descricao) . '%');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            extract($row);
            $totalOfertas = $count_is;
        } 

        return $totalOfertas;
    }

    private function verifica_relacao($oferta){
        $relacao = false;

        $query = "SELECT id FROM submissao WHERE oferta_id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindParam(':id', $oferta->getId());
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $relacao = true;
        } 
        
        return $relacao;
    }

    public function remove($oferta) {

        $relacao = $this->verifica_relacao($oferta);

        if($relacao){
            return false;
        }
        else{
            $query = "DELETE FROM $this->table_name WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindParam(':id', $oferta->getId());

            // execute the query
            if($stmt->execute()){
                return true;
            }
        }
       
        return false;
    }

}
?>