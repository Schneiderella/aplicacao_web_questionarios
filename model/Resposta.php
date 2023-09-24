<?php
    class Resposta{
        private $id;
        private $texto;
        private $observacao;
        private $nota;
        private $alternativa;
        private $questao;
        // private $submissao;
        
        public function __construct( $id, $questao, $texto, $alternativa, $nota) {
            $this->id = $id;
            $this->questao = $questao;
            $this->texto = $texto;
            $this->alternativa = $alternativa;
            $this->nota = $nota;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }
        public function getTexto() { return $this->texto; }
        public function setTexto($texto) { $this->texto = $texto; }
        public function getObservacao() { return $this->observacao; }
        public function setObservacao($observacao) { $this->observacao = $observacao; }
        public function getNota() { return $this->nota; }
        public function setNota($nota) { $this->nota = $nota; }
        public function getAlternativa() { return $this->alternativa; }
        public function setAlternativa($alternativa) { $this->alternativa = $alternativa; }
        public function getQuestao() { return $this->questao; }
        public function setQuestao($questao) { $this->questao = $questao; }

        public function getDadosParaJSON() {
            $data = ['id' => $this->getId(), 
                     'texto' => $this->getTexto(),
                     'observacao' => $this->getObservacao(),
                     'nota' => $this->getNota(),
                     'alternativa' => $this->getAlternativa()->getDadosParaJSON(),
                     'questao' => $this->getQuestao()->getDadosParaJSON()];
            return $data;
        }

    }
    
?>