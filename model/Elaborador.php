<?php
    include_once('model/Usuario.php');

    class Elaborador extends Usuario{
        private $instituicao;
        private $is_admin;

        public function __construct( $id, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin) {
            parent::__construct($id, $login, $senha, $nome, $email, $cpf);
            $this->instituicao = $instituicao;
            $this->is_admin = $is_admin;
        }

        public function getInstituicao() { return $this->instituicao; }
        public function setInstituicao($instituicao) { $this->instituicao = $instituicao; }

        public function getIsAdmin() { return $this->is_admin; }
        public function setIsAdmin($is_admin) { $this->is_admin = $is_admin; }

        public function getDadosParaJSON() {
            $data = ['id' => Usuario::getId(), 
                     'login' => Usuario::getLogin(),
                     'senha' => Usuario::getSenha(),
                     'nome' => Usuario::getNome(),
                     'email' => Usuario::getEmail(),
                     'cpf' => Usuario::getcpf(),
                     'instituicao' => $this->instituicao,
                     'is_admin' => $this->is_admin,
                    ];
            return $data;
        }

    }
?>