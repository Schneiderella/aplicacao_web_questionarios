<?php

include_once('DaoFactory.php');
include_once('PostgresOfertaDao.php');
include_once('PostgresSubmissaoRespostaDao.php');
include_once('PostgresQuestionarioDao.php');
include_once('PostgresQuestionarioQuestaoDao.php');
include_once('PostgresQuestaoDao.php');
include_once('PostgresAlternativaDao.php');
include_once('PostgresElaboradorDao.php');
include_once('PostgresRespondenteDao.php');
include_once('PostgresEstatisticaDao.php');


class PostgresDaofactory extends DaoFactory {

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "questionarios";
    private $port = "5432";
    private $username;
    private $password;
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
        $this->username =  $this->getUserName();
        $this->password =  $this->getPassword();
  
        try{
            //$this->conn = new PDO("pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn = new PDO("pgsql:host=localhost;port=5432;dbname=questionarios", $this->username, $this->password);
    
      }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function getSubmissaoRespostaDao() {

        return new PostgresSubmissaoRespostaDao($this->getConnection());

    }

    public function getOfertaDao() {

        return new PostgresOfertaDao($this->getConnection());

    }

    public function getQuestionarioDao() {

        return new PostgresQuestionarioDao($this->getConnection());

    }

    public function getQuestionarioQuestaoDao() {

        return new PostgresQuestionarioQuestaoDao($this->getConnection());

    }

    public function getQuestaoDao() {

        return new PostgresQuestaoDao($this->getConnection());

    }

    public function getAlternativaDao() {

        return new PostgresAlternativaDao($this->getConnection());

    }

    public function getElaboradorDao() {

        return new PostgresElaboradorDao($this->getConnection());

    }

    public function getRespondenteDao() {

        return new PostgresRespondenteDao($this->getConnection());

    }

    public function getEstatisticaDao() {

        return new PostgresEstatisticaDao($this->getConnection());

    }

    private function getUserName() {
        $path = __DIR__ . "/config.json";
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString);
        return $jsonData->USERNAME;
    }

    private function getPassword() {
        $path = __DIR__ . "/config.json";
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString);
        return $jsonData->PASSWORD;
    }
}
?>