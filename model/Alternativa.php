<?php
    class Alternativa{
        private $id;
        private $descricao;
        private $isCorreta;

        public function __construct( $id, $descricao, $isCorreta) {
            $this->id = $id;
            $this->descricao = $descricao;
            $this->isCorreta = $isCorreta;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getDescricao() { return $this->descricao; }
        public function setDescricao($descricao) { $this->descricao = $descricao; }

        public function getIsCorreta() { return $this->isCorreta; }
        public function setIsCorreta($isCorreta) { $this->isCorreta = $isCorreta; }

        public function getDadosParaJSON() {
            $data = ['id' => $this->getId(), 
                     'descricao' => $this->getDescricao(),
                     'isCorreta' => $this->getIsCorreta()];
            return $data;
        }

    }
?>