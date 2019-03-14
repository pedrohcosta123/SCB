<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de Comissão";
carrega_topo($pempresa,$puser,'Cadastro',$titulo,'glyphicon glyphicon-plus');


  $formulario = new form();
  $formulario-> openform('GET'); 
  $formulario->divid('teste','linhas col-sm-12 col-md-12'); 
  $formulario->label('teste',3);
  $formulario->input('generico','teste[]','');
  $formulario->opendiv('col-sm-3 col-md-3',1);
  echo "<a href='#' class='removerCampo' title='Remover linha'><img src='../login/teste.png' border='0' /></a>";
  $formulario->closediv();
  $formulario->closediv();
  $formulario->opendiv('col-sm-3 col-md-3',1);
  echo "<a href='#' class='adicionarCampo glyphicon glyphicon-minus' title='Adicionar item'><img  border='0' /></a>";
  $formulario->btn('vermelho','Remover','12','glyphicon glyphicon-plus','salvar');
  $formulario->closediv();
  $formulario->opendiv('col-sm-12 col-md-12',1);
  $formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
  $formulario->closediv();
  $formulario->closeform();

rodape();