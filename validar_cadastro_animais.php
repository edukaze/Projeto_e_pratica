<?php
session_start();

include('banco.php');

$nome = $_POST['a-nome'];
$especie = $_POST['a-especie'];
$raca = $_POST['a-raca'];
$porte = $_POST['a-porte'];
$genero = $_POST['a-genero'];
$descricao = $_POST['a-descricao'];
$nome_imagem = $_FILES['imagem']['name'];

$query =  $pdo->prepare("
	SELECT ESP_ID FROM IPET_ESPECIE
	");
$query ->execute();
$chaveEspecie = $query->fetchAll();
var_dump($especie);
var_dump($chaveEspecie);

	if ($especie = "cachorro") {
		$insert =  $pdo->prepare("
			INSERT INTO IPET_ESPECIE(ESP_CACHORRO)
			VALUES (?);
			");
		$insert ->execute([$especie]);
		$select = $pdo->prepare("
			SELECT ESP_ID, ESP_CACHORRO FROM IPET_ESPECIE
			where esp_cachorro= ;
			")
	}


include 'funcoes.php';

if (empty($nome) ||  empty($especie) || empty($raca) || empty($porte) || empty($genero) || empty($descricao)){
	$_SESSION['erro-campo'] = true;
	header("location:doacao.php");
	exit(); 
}


$pdo = dbConnect();

if (isset($_SESSION['nome'])) {
$chaveUsu = $_SESSION['id_usuario'];
$ultimo_id = $chaveUsu;
	
$stmt = $pdo->prepare("
    INSERT  INTO IPET_ANIMAIS (ANI_NOME, ANI_ESPECIE, ANI_RAÇA, ANI_PORTE, ANI_GENERO, ANI_DESCRICAO, ANI_NOR_CODIGO, ANI_IMAGEM, ANI_ESP_ID)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?,?);
");

$stmt->execute([$nome, $especie, $raca, $porte, $genero, $descricao, $chaveUsu,$nome_imagem, $chaveEspecie]);

include 'validar_imagem.php';
print_r($stmt->errorInfo());
header("location:adocao.php");
	}
	elseif (isset($_SESSION['nome_ong'])) {
$chaveOng = $_SESSION['id_ong'];
$ultimo_id = $chaveOng;	
$stmt = $pdo->prepare("
    INSERT  INTO IPET_ANIMAIS (ANI_NOME, ANI_ESPECIE, ANI_RAÇA, ANI_PORTE, ANI_GENERO, ANI_DESCRICAO, ANI_ONG_ID, ANI_IMAGEM, ANI_ESP_ID)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
");

$stmt->execute([$nome, $especie, $raca, $porte, $genero, $descricao, $chaveOng, $nome_imagem, $chaveEspecie]);
include 'validar_imagem.php';
print_r($stmt->errorInfo());
header("location:adocao.php");
	}

?>
