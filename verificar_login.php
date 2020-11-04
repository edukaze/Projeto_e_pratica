<?php 
session_start();

if (!isset($_SESSION['anonimo'])) {
	session_destroy();
	header('location:index.php');

}

include 'banco.php';

$pdo = dbConnect();

$usuario = $_POST['t-usuario'];
$senha = $_POST['t-senha'];


	$query = $pdo->prepare("
		SELECT NOR_usuario, NOR_SENHA FROM IPET_USUARIO_NORMAL
		WHERE NOR_USUARIO = ?;
		");
	$query->execute([$usuario]);
	$verifiUsu = $query->fetchAll();
	$row1 = $query->rowCount();
	if ($row1 == 1) {

	$senhadb = password_verify($senha, $verifiUsu[0]['NOR_SENHA']);
	$stmt = $pdo->prepare("
	SELECT NOR_USUARIO, NOR_SENHA, NOR_CODIGO FROM IPET_USUARIO_NORMAL 
	WHERE (NOR_USUARIO = ?) 
	");
if (isset($senhadb)) {
	$stmt -> execute([$usuario]);
	$row = $stmt->rowCount();

	if ($row == 1) {
		$usuario_normal = $stmt->fetchAll();
		$_SESSION['nome'] = $usuario_normal[0]['NOR_USUARIO'];
		$_SESSION['id_usuario'] = $usuario_normal[0]['NOR_CODIGO'];

		header('location:index.php');
		exit();
	
		}
	}

	}


$queryOng = $pdo->prepare("
	SELECT ONG_ID, ONG_SENHA FROM IPET_USUARIOS_ONG
	WHERE ONG_ID = ?;
	");
$queryOng->execute([$usuario]);
$verifiOng = $queryOng->fetchAll();
$rowOng1 = $queryOng->rowCount();
var_dump($rowOng1);
if ($rowOng1 == 1) {
	
$senhadbOng = password_verify($senha,$verifiOng[0]['ONG_ID']);

$stmtOng = $pdo->prepare("
	SELECT ONG_USUARIO, ONG_SENHA, ONG_NOME, ONG_ID FROM IPET_USUARIOS_ONG 
	WHERE (ONG_USUARIO = ?) 
	");
if (isset($senhadbOng)) {
		$stmtOng -> execute([$usuario]);
		$rowOng = $stmtOng->rowCount();
		if ($rowOng == 1) {
		$usuario_ong = $stmtOng->fetchAll();
		unset($_SESSION['anonimo']);
		$_SESSION['nome_ong'] = $usuario_ong[0]['ONG_NOME'];
		$_SESSION['id_ong'] = $usuario_ong[0]['ONG_ID'];
		header('location:index.php');
		exit();
		}
	}

}

 else{
   	$_SESSION['nao-autenticado'] = true;
   	header("location:login.php");
   	exit();
 }

 ?>