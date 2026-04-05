<?php



session_start();


if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}


include '../config/db.php';


if (isset($_GET['id'])) {
    
    $id_tarefa = (int)$_GET['id']; 

    
    $sql_excluir = "DELETE FROM tarefas WHERE id = $id_tarefa";
    
    
    $conexao->query($sql_excluir);
}


header("Location: ../index.php");
exit;
?>