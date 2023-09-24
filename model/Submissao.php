<?php
    class Submissao{
        private $id;
        private $nomeOcasiao;
        private $descricao;
        private $data;
        private $respondente;
        private $oferta;
        private $respostas;

        public function __construct($id, $nomeOcasiao, $descricao, $data, $respondente, $oferta) {
            $this->id = $id;
            $this->nomeOcasiao = $nomeOcasiao;
            $this->descricao = $descricao;
            $this->data = $data;
            $this->respondente = $respondente;
            $this->oferta = $oferta;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getNomeOcasiao() { return $this->nomeOcasiao; }
        public function setNomeOcasiao($nomeOcasiao) { $this->nomeOcasiao = $nomeOcasiao; }

        public function getDescricao() { return $this->descricao; }
        public function setDescricao($descricao) { $this->descricao = $descricao; }

        public function getData() { return $this->data; }
        public function setData($data) { $this->data = $data; }

        public function getOferta() { return $this->oferta; }
        public function setOferta($oferta) { $this->oferta = $oferta; }

        public function getRespostas() { return $this->respostas; }
        public function setRespostas($respostas) { $this->respostas = $respostas; }
        public function addResposta($resposta) {
            $this->respostas[] = $resposta;
        }
        public function getRespondente() { return $this->respondente; }
        public function setRespondente($respondente) { $this->respondente = $respondente; }

    }
?>