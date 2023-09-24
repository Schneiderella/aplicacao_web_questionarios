<?php

error_reporting(E_ERROR | E_PARSE);

require_once("libs/util.php");

include_once('model/Respondente.php');
include_once('model/Elaborador.php');

include_once('dao/RespondenteDao.php');
include_once('dao/ElaboradorDao.php');

include_once('dao/DaoFactory.php');
include_once('dao/PostgresDaoFactory.php');

include_once('model/Questao.php');
include_once('model/Alternativa.php');
include_once('model/Oferta.php');
include_once('model/Resposta.php');
include_once('model/Submissao.php');
include_once('model/Questionario.php');
include_once('model/QuestionarioQuestao.php');

include_once('dao/QuestaoDao.php');
include_once('dao/AlternativaDao.php');
include_once('dao/OfertaDao.php');
include_once('dao/SubmissaoRespostaDao.php');
include_once('dao/QuestionarioDao.php');
include_once('dao/QuestionarioQuestaoDao.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$factory = new PostgresDaofactory();

?>
