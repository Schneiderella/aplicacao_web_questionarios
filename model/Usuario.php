<?php
    class Usuario {
                
        private $id;
        private $login;
        private $senha;
        private $nome;
        private $email;
        private $cpf;

        public function __construct($id, $login, $senha, $nome, $email, $cpf)
        {
            $this->id = $id;
            $this->login = $login;
            $this->senha = $senha;
            $this->nome = $nome;
            $this->email = $email;
            $this->cpf = $cpf;
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }

        public function getLogin() { return $this->login; }
        public function setLogin($login) { $this->login = $login; }

        public function getSenha() { return $this->senha; }
        public function setSenha($senha) { $this->senha = $senha; }

        public function getNome() { return $this->nome; }
        public function setNome($nome) { $this->nome = $nome; }

        public function getEmail() { return $this->email; }
        public function setEmail($email) { $this->email = $email; }

        public function getCpf() { return $this->cpf; }
        public function setCpf($cpf) { $this->cpf = $cpf; }

    }
?>