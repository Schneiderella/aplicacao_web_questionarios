<?php

include_once('SubmissaoRespostaDao.php');
include_once('PostgresDao.php');

class PostgresSubmissaoRespostaDao extends PostgresDao implements SubmissaoRespostaDao {

    private $table_name_first = 'submissao';
    private $table_name_second = 'resposta';
    
    public function insere($submissao) {

        $this->conn->beginTransaction(); //pausa o auto-commit

        $query = "INSERT INTO $this->table_name_first 
                ( nome_ocasiao, descricao, respondente_id, oferta_id) VALUES
                (:nome_ocasiao, :descricao, :respondente_id, :oferta_id)";

        $stmt = $this->conn->prepare($query);
        // bind values 
        $stmt->bindValue(":nome_ocasiao", $submissao->getNomeOcasiao());
        $stmt->bindValue(":descricao", $submissao->getDescricao());
        $stmt->bindValue(":respondente_id", $submissao->getRespondente());
        $stmt->bindValue(":oferta_id", $submissao->getOferta()->getId());
        
        if($stmt->execute()){
            $submissao_id = $this->conn->lastInsertId();

        } else {
            $submissao_id = -1;
        }

        if ($submissao_id >= 0) {

            $sucesso = true;
            $respostas = $submissao->getRespostas();

            foreach($respostas as $resposta){
                $query = "INSERT INTO $this->table_name_second 
                ( texto, alternativa_id, questao_id, submissao_id, nota) VALUES
                (:texto, :alternativa_id, :questao_id, :submissao_id, :nota)";

                $stmt = $this->conn->prepare($query);

                $alternativa_id = null;

                if ($resposta->getAlternativa()){
                    $alternativa_id = $resposta->getAlternativa()->getId();
                }
     
                // bind values 
                $stmt->bindValue(":texto", $resposta->getTexto());
                $stmt->bindValue(":alternativa_id", $alternativa_id);
                $stmt->bindValue(":questao_id", $resposta->getQuestao()->getId());
                $stmt->bindValue(":submissao_id", $submissao_id);
                $stmt->bindValue(":nota", $resposta->getNota());

                if ($stmt->execute()) {
                    $respostaId =  $this->conn->lastInsertId();
                } else {
                    $respostaId = -1;
                }

                if ($respostaId < 0 ) {
                    $sucesso = false;
                }
            }

            if ($sucesso) {
                $this->conn->commit(); //Finaliza transacao, salva todos os dados
                return true;
            } else {
                $this->conn->rollback(); //Reverte dados salvos desde o inicio da transacao
                return false;
            }

        } else { //Ocorreu erro ao salvar submissao
            $this->conn->rollback(); //Reverte dados salvos desde o inicio da transacao
            return false;
        }

    }

    public function corrigeResposta($resposta) {

        $query = "UPDATE  $this->table_name_second 
        SET nota = :nota, observacao = :observacao
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindValue(":nota", $resposta->getNota());
        $stmt->bindValue(":observacao", $resposta->getObservacao());
        $stmt->bindValue(':id', $resposta->getId());

        // execute the query
        if($stmt->execute()){
        return true;
        }    

        return false;
    }

    public function buscaRespostaPorId($respostaId){

        $resposta= null;

        $query = "SELECT res.id as res_id, res.questao_id as res_questao_id, res.texto as res_texto, 
                res.alternativa_id as res_alternativa_id, res.nota as res_nota, res.observacao as res_observacao
                  FROM $this->table_name_second as res
                  WHERE res.id = :id ";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":id", $respostaId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            extract($row);
            $resposta = new Resposta($res_id, $res_questao_id, $res_texto, $res_alternativa_id, $res_nota);
            $resposta->setObservacao($res_observacao);
        }

        return $resposta;
    }

    public function buscaPorId($submissaoId){

        $submissao= null;

        $query = "SELECT sub.id as sub_id, sub.nome_ocasiao, sub.descricao as sub_desc, sub.data as sub_data,
                         ofer.id as ofer_id, ofer.data as ofer_data,
                         quest.id as quest_id, quest.nome as quest_nome, quest.descricao as quest_desc, quest.data_criacao as quest_data, quest.nota_aprovacao as quest_nota,
                         elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                         resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name_first as sub
                  INNER JOIN oferta as ofer on ofer.id = sub.oferta_id
                  INNER JOIN questionario as quest on quest.id = ofer.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = ofer.respondente_id
                  WHERE sub.id = :id
                  LIMIT 1 OFFSET 0";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":id", $submissaoId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            extract($row);
            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);
    
            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);
    
            $questionario = new Questionario($quest_id, $quest_nome, $quest_desc, $quest_data, $quest_nota, $elaborador);
    
            $oferta = new Oferta($ofer_id, $ofer_data, $questionario, $respondente);
    
            $submissao = new Submissao($sub_id, $nome_ocasiao, $sub_desc, $sub_data, $respondente, $oferta);
        }

        return $submissao;
    }

    public function buscaPorRespondente($respondenteId){

        $submissoes = array();

        $query = "SELECT sub.id as sub_id, sub.nome_ocasiao, sub.descricao as sub_desc, sub.data as sub_data,
                         ofer.id as ofer_id, ofer.data as ofer_data,
                         quest.id as quest_id, quest.nome as quest_nome, quest.descricao as quest_desc, quest.data_criacao as quest_data, quest.nota_aprovacao as quest_nota,
                         elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                         resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name_first as sub
                  INNER JOIN oferta as ofer on ofer.id = sub.oferta_id
                  INNER JOIN questionario as quest on quest.id = ofer.questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = ofer.respondente_id
                  WHERE sub.respondente_id = :resp
                  ORDER BY sub.id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":resp", $respondenteId);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);

            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);

            $questionario = new Questionario($quest_id, $quest_nome, $quest_desc, $quest_data, $quest_nota, $elaborador);

            $oferta = new Oferta($ofer_id, $ofer_data, $questionario, $respondente);

            $submissoes[] = new Submissao($sub_id, $nome_ocasiao, $sub_desc, $sub_data, $respondente, $oferta);
        }
        
        return $submissoes;
    }

    public function totalPorRespondente($respondenteId){

        $submissoes = 0;

        $query = "SELECT count(*)
                  FROM $this->table_name_first
                  WHERE respondente_id = :resp";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":resp", $respondenteId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row){
            extract($row);
            $submissoes = $count;
        }
        
        return $submissoes;
    }

    public function buscaPorResultados($respondenteId, $questionarioId){

        $submissoes = array();

        $query = "SELECT sub.id as sub_id, sub.nome_ocasiao, sub.descricao as sub_desc, sub.data as sub_data,
                         ofer.id as ofer_id, ofer.data as ofer_data,
                         quest.id as quest_id, quest.nome as quest_nome, quest.descricao as quest_desc, quest.data_criacao as quest_data, quest.nota_aprovacao as quest_nota,
                         elab.id as elab_id, elab.login as elab_login, elab.senha as elab_senha, elab.nome as elab_nome, elab.email as elab_email, elab.cpf as elab_cpf, elab.instituicao, elab.is_admin,
                         resp.id as resp_id, resp.login as resp_login, resp.senha as resp_senha, resp.nome as resp_nome, resp.email as resp_email, resp.cpf as resp_cpf, resp.telefone
                  FROM $this->table_name_first as sub
                  INNER JOIN oferta as ofer on ofer.id = sub.oferta_id
                  INNER JOIN questionario as quest on quest.id = ofer.questionario_id and quest.id = :questionario_id
                  INNER JOIN elaborador as elab on elab.id = quest.elaborador_id
                  INNER JOIN respondente as resp on resp.id = ofer.respondente_id
                  WHERE sub.respondente_id = :resp 
                  ORDER BY sub.id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(":resp", $respondenteId);
        $stmt->bindValue(":questionario_id", $questionarioId);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            $elaborador = new Elaborador($elab_id, $elab_login, $elab_senha, $elab_nome, $elab_email, $elab_cpf, $instituicao, $is_admin);

            $respondente = new Respondente($resp_id, $resp_login, $resp_senha, $resp_nome, $resp_email, $resp_cpf, $telefone);

            $questionario = new Questionario($quest_id, $quest_nome, $quest_desc, $quest_data, $quest_nota, $elaborador);

            $oferta = new Oferta($ofer_id, $ofer_data, $questionario, $respondente);

            $submissoes[] = new Submissao($sub_id, $nome_ocasiao, $sub_desc, $sub_data, $respondente, $oferta);
        }
        
        return $submissoes;
    }

    public function buscaRespostas($submissao){
        return $this->buscaRespostasPorId($submissao->getId());
    }

    public function buscaRespostasPorId($submissaoId){

        $respostas = array();

        $query = "SELECT resposta.id as resposta_id, resposta.texto as resp_text, resposta.nota, resposta.observacao,
                    quest.id as quest_id, quest.descricao as quest_desc, quest.is_discursiva as quest_discursiva, 
                    quest.is_objetiva as quest_objetiva, quest.is_multipla_escolha as quest_multiescolha,
                    alternativa.id as alter_id, alternativa.descricao as alter_desc, alternativa.is_correta as alter_correta,
                    submissao.nome_ocasiao, submissao.descricao, submissao.data
                  FROM $this->table_name_second
                  INNER JOIN submissao on submissao.id = resposta.submissao_id
                  INNER JOIN questao as quest on quest.id = resposta.questao_id
                  LEFT JOIN alternativa on alternativa.id = resposta.alternativa_id
                  WHERE submissao.id = :submissao_id
                  ORDER BY resposta.id ASC";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindValue(':submissao_id', $submissaoId);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $questao = new Questao($quest_id, $quest_desc, $quest_discursiva, $quest_objetiva, $quest_multiescolha);
            $alternativa = new Alternativa($alter_id, $alter_desc, $alter_correta);
            $resposta = new Resposta($resposta_id, $questao, $resp_text, $alternativa, $nota);
            $resposta->setObservacao($observacao);
            $respostas[] = $resposta;
        }
        
        return $respostas;
    }
}
?>