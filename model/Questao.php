<?php
    // require_once("../libs/util.php");
    
    class Questao{
        private $id;
        private $descricao;
        private $isDiscursiva;
        private $isObjetiva;
        private $isMultiplaEscolha;
        private $imagem;
        private $alternativas; // Alterei baseado na pag. 26 do pdf do khron 

        public function __construct( $id, $descricao, $isDiscursiva, $isObjetiva, $isMultiplaEscolha) {
            $this->id = $id;
            $this->descricao = $descricao;
            $this->isDiscursiva = $isDiscursiva;
            $this->isObjetiva = $isObjetiva;
            $this->isMultiplaEscolha = $isMultiplaEscolha;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getDescricao() { return $this->descricao; }
        public function setDescricao($descricao) { $this->descricao = $descricao; }

        public function getIsDiscursiva() { return $this->isDiscursiva; }
        public function setIsDiscursiva($isDiscursiva) { $this->isDiscursiva = $isDiscursiva; }

        public function getIsObjetiva() { return $this->isObjetiva; }
        public function setIsObjetiva($isObjetiva) { $this->isObjetiva = $isObjetiva; }

        public function getIsMultiplaEscolha() { return $this->isMultiplaEscolha; }
        public function setIsMultiplaEscolha($isMultiplaEscolha) { $this->isMultiplaEscolha = $isMultiplaEscolha; }

        public function getImagem() { return $this->imagem; }
        public function setImagem($imagem) { $this->imagem = $imagem; }

        public function getAlternativas() { return $this->alternativas; }
        public function setAlternativas($alternativas) { $this->alternativas = $alternativas; }
        
        public function addAlternativa($alternativa) {
            $this->alternativas[] = $alternativa;
        }

        public function getTipo(){
            if ($this->isDiscursiva) {
                return "Discursiva";
            } else if ($this->isObjetiva) {
                return "Objetiva";
            } else if ($this->isMultiplaEscolha) {
                return "Multipla escolha";
            } else {
                return "";
            }
        }

        public function getDadosParaJSON() {
            $data = ['id' => $this->getId(), 
                     'descricao' => $this->getDescricao(),
                     'tipo' => $this->getTipo(),
                     'imagem' => $this->getImagem(),
                     'alternativas' => toJSON($this->getAlternativas())];
            return $data;
        }
    }
?>