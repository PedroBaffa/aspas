<?php



$servidor = "localhost"; 
$usuario = "root";       
$senha = "";             
$banco = "aspas_db";     


$conexao = new mysqli($servidor, $usuario, $senha, $banco);


if ($conexao->connect_error) {
    
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}
?>