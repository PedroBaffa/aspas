<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $titulo = $conexao->real_escape_string($_POST['titulo']);
    $descricao = $conexao->real_escape_string($_POST['descricao']);
    $sql_atualizar = "UPDATE tarefas SET titulo = '$titulo', descricao = '$descricao' WHERE id = $id";
    $conexao->query($sql_atualizar);
    header("Location: index.php"); exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $resultado = $conexao->query("SELECT * FROM tarefas WHERE id = $id");
    $tarefa = $resultado->fetch_assoc();
    if (!$tarefa) { header("Location: index.php"); exit; }
} else {
    header("Location: index.php"); exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa - Aspas</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container-edicao { max-width: 600px; margin: 0 auto; }
        .btn-cancelar { color: var(--cor-texto); background: transparent; text-decoration: none; padding: 10px 15px; margin-left: 10px; font-weight: bold; border-radius: 6px; }
        .btn-cancelar:hover { background-color: var(--cor-borda-input); }
    </style>
</head>
<body>

    <aside class="menu-lateral">
        <div class="logo-menu">aspas"</div>
        <div class="usuario-info">
            Logado como:<br><strong><?php echo $_SESSION['usuario_nome']; ?></strong>
        </div>

        <div class="rodape-menu">
            <div class="container-tema">
                <label class="switch">
                    <input type="checkbox" id="toggle-tema">
                    <span class="slider"></span>
                </label>
                <span>Tema Escuro</span>
            </div>
            <a href="index.php" class="btn-sair">⬅ Voltar ao Painel</a>
        </div>
    </aside>

    <main class="conteudo-principal">
        <div class="container-edicao cabecalho">
            <h2>✏️ Editar Tarefa</h2>
            <form action="editar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                <p><strong>Título:</strong></p>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($tarefa['titulo']); ?>" required>
                <p><strong>Descrição:</strong></p>
                <textarea name="descricao" rows="5"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea>
                
                <div style="margin-top: 20px;">
                    <button type="submit">💾 Salvar Alterações</button>
                    <a href="index.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const toggleTema = document.getElementById('toggle-tema');
        const body = document.body;
        if(localStorage.getItem('tema_aspas') === 'escuro') {
            body.classList.add('dark-mode'); toggleTema.checked = true;
        }
        toggleTema.addEventListener('change', () => {
            if (toggleTema.checked) {
                body.classList.add('dark-mode'); localStorage.setItem('tema_aspas', 'escuro');
            } else {
                body.classList.remove('dark-mode'); localStorage.setItem('tema_aspas', 'claro');
            }
        });
    </script>
</body>
</html>