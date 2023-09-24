<?php
    class QuestionarioQuestao{
        private $id;
        private $pontos;
        private $ordem;
        // private $questionario; // pag 26 pdf. Nao se faz sentido
        private $questao;

        public function __construct( $id, $ordem, $pontos, $questao) {
            $this->id = $id;
            $this->ordem = $ordem;
            $this->pontos = $pontos;
            $this->questao = $questao;
        }

        public function getId() { return $this->id; }
        
        public function setId($id) { $this->id = $id; }

        public function getPontos() { return $this->pontos; }
        public function setPontos($pontos) { $this->pontos = $pontos; }

        public function getOrdem() { return $this->ordem; }

        public function setOrdem($ordem) { $this->ordem = $ordem; }

        public function getQuestao() {return $this->questao;}
        public function setQuestao($questao) { $this->questao = $questao; }

    }
?>