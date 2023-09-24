<?php

include_once('EstatisticaDao.php');
include_once('PostgresDao.php');

class PostgresEstatisticaDao extends PostgresDao implements EstatisticaDao {


    public function getTotal($tabela) {

        $total = 0;

        $query = "SELECT COUNT(*) as total
                  FROM $tabela  ";

        $stmt = $this->conn->prepare($query);
    
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $total = $row['total'];
        }

        return $total;
    }

    public function getTotalAprovacao() {

        $total = 0;

        $query = "select sum(resposta.nota) as nota, submissao.id, questionario.nota_aprovacao as nota_aprovacao from resposta
        inner join submissao on(submissao.id = resposta.submissao_id)
        inner join oferta on (oferta.id = submissao.oferta_id)
        inner join questionario on (questionario.id = oferta.questionario_id)
        GROUP BY submissao.id, questionario.nota_aprovacao" ;
        

        $stmt = $this->conn->prepare($query);
    
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if ($nota >= $nota_aprovacao){
                $total += 1;
            }
            
        }

        return $total;
    }


    public function getCalculaPorcentagem($total, $valor){
        if ($total != 0){
            return ((float)$valor / (float)$total) * 100;
        }
        else{
            return 0;
        }

    }

    public function getCalculaPorcentagemPorOferta(array $ofe,array $apr){
        $dadosOfertas = $ofe;
        $dadosQuestionariosAprovados = $apr;

        $submissoes = $dadosOfertas[1]; // Array com a quantidade de submissões
        $aprovacoes = $dadosQuestionariosAprovados[1]; // Array com a quantidade de aprovações

        $percentuais = []; // Array para armazenar os percentuais de aprovação

        for ($i = 0; $i < count($submissoes); $i++) {
            $submissoes_count = $submissoes[$i];
            $aprovacoes_count = $aprovacoes[$i];
            
            if ($submissoes_count === 0) {
                $percentual = 0; // Caso não haja submissões, o percentual é zero
            } else {
                $percentual = ($aprovacoes_count / $submissoes_count) * 100;
            }
            
            $percentuais[] = $percentual;
        }
        return $percentuais;
    }

    
    public function getDadosOfertas() {

        
        $legenda = array();
        $dados = array();

        $query = "select count(oferta.id) as quant_oferta, questionario.nome as quest_nome from oferta
        inner join questionario on (questionario.id = oferta.questionario_id)
        GROUP BY questionario.id
        ORDER BY quest_nome" ;
        

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $legenda[] = $quest_nome;
            $dados[] = $quant_oferta;
            
        }
        $dados_oferta = array($legenda,  $dados);

        return $dados_oferta;
    }

    
    public function getDadosQuestionariosRespondidos() {

        
        $legenda = array();
        $dados = array();

        $query = "select count(submissao.id) as quant_submissao, questionario.nome as quest_nome from submissao
        inner join oferta on (oferta.id = submissao.oferta_id)
        inner join questionario on (questionario.id = oferta.questionario_id)
        GROUP BY questionario.id
        ORDER BY quest_nome" ;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $legenda[] = $quest_nome;
            $dados[] = $quant_submissao;
            
        }
        $dados_submissao = array($legenda,  $dados);

        return $dados_submissao;
    }

    public function getDadosQuestionariosAprovados() {

        $legenda = array();
        $dados = array();

        $query = "SELECT q.nome AS quest_nome, 
        COALESCE(SUM(CASE WHEN subquery.nota >= q.nota_aprovacao THEN 1 ELSE 0 END), 0) AS quantidade
        FROM questionario q
        LEFT JOIN (
            SELECT oferta.questionario_id, submissao.id, SUM(resposta.nota) AS nota
            FROM resposta
            INNER JOIN submissao ON submissao.id = resposta.submissao_id
            INNER JOIN oferta ON oferta.id = submissao.oferta_id
            GROUP BY oferta.questionario_id, submissao.id
        ) AS subquery ON subquery.questionario_id = q.id
        GROUP BY q.nome
        ORDER BY q.nome; " ;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $legenda[] = $quest_nome;
            $dados[] = $quantidade;
            
        }
        $dados_submissao = array($legenda,  $dados);

        return $dados_submissao;
    }

}

?>