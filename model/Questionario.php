<?php
    class Questionario{
        private $id;
        private $nome;
        private $descricao;
        private $dataCriacao;
        private $notaAprovacao;
        private $elaborador;
        private $questionarioQuestoes = array(); // pag 26 pdf

        public function __construct( $id, $nome, $descricao, $dataCriacao, $notaAprovacao, $elaborador) {
            $this->id = $id;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->dataCriacao = $dataCriacao;
            $this->notaAprovacao = $notaAprovacao;
            $this->elaborador = $elaborador;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getNome() { return $this->nome; }
        public function setNome($nome) { $this->nome = $nome; }

        public function getDescricao() { return $this->descricao; }
        public function setDescricao($descricao) { $this->descricao = $descricao; }

        public function getDataCriacao() { return $this->dataCriacao; }

        public function getNotaAprovacao() { return $this->notaAprovacao; }
        public function setNotaAprovacao($notaAprovacao) { $this->notaAprovacao = $notaAprovacao; }
        
        public function getElaborador() { return $this->elaborador; }

        public function getQuestionarioQuestoes() {return $this->questionarioQuestoes;}

        public function addQuestionarioQuestoes($questionarioQuestoes) {
            $this->questionarioQuestoes[] = $questionarioQuestoes;
        }

        public function getDadosParaJSON() {
            $data = ['id' => $this->getId(), 
                     'nome' => $this->getNome(),
                     'descricao' => $this->getDescricao(),
                     'dataCriacao' => $this->getDataCriacao(),
                     'notaAprovacao' => $this->getNotaAprovacao(),
                     'elaborador' => $this->getElaborador()->getDadosParaJSON()];
            return $data;
        }

    }
?>