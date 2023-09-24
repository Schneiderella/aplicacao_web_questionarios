<?php
    include_once('Usuario.php');

    class Respondente extends Usuario{
        private $telefone;

        public function __construct( $id, $login, $senha, $nome, $email, $cpf, $telefone)
        {
            parent::__construct($id, $login, $senha, $nome, $email, $cpf);
            $this->telefone = $telefone;
        }

        public function getTelefone() { return $this->telefone; }
        public function setTelefone($telefone) { $this->telefone = $telefone; }

        public function getDadosParaJSON() {
            $data = ['id' => Usuario::getId(), 
                     'login' => Usuario::getLogin(),
                     'senha' => Usuario::getSenha(),
                     'nome' => Usuario::getNome(),
                     'email' => Usuario::getEmail(),
                     'cpf' => Usuario::getcpf(),
                     'telefone' => $this->telefone];
            return $data;
        }
    }
?>