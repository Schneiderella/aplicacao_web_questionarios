<?php
    class Oferta{
        private $id;
        private $data;
        private $questionario;
        private $respondente;

        public function __construct( $id, $data, $questionario, $respondente) {
            $this->id = $id;
            $this->data = $data;
            $this->questionario = $questionario;
            $this->respondente = $respondente;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getData() { return $this->data; }
        public function setData($data) { $this->data = $data; }

        public function getQuestionario() { return $this->questionario; }
        public function setQuestionario($questionario) { $this->questionario = $questionario; }

        public function getRespondente() { return $this->respondente; }
        public function setRespondente($respondente) { $this->respondente = $respondente; }
    }
?>