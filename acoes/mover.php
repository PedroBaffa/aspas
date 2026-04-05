<?php


session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php"); 
    exit;
}


include '../config/db.php'; 

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_tarefa = (int)$_GET['id']; 
    $novo_status = $conexao->real_escape_string($_GET['status']);

    $sql_atualizar = "UPDATE tarefas SET status = '$novo_status' WHERE id = $id_tarefa";
    $conexao->query($sql_atualizar);
}


header("Location: ../index.php"); 
exit;
?>