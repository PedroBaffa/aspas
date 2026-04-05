<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include 'config/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $conexao->real_escape_string($_POST['titulo']);
    $descricao = $conexao->real_escape_string($_POST['descricao']);

    
    $id_do_dono = $_SESSION['usuario_id'];

    
    $sql_inserir = "INSERT INTO tarefas (titulo, descricao, status, usuario_id) VALUES ('$titulo', '$descricao', 'a_fazer', $id_do_dono)";
    $conexao->query($sql_inserir);

    header("Location: index.php");
    exit;
}



$sql = "SELECT tarefas.*, usuarios.nome AS responsavel_nome 
        FROM tarefas 
        LEFT JOIN usuarios ON tarefas.usuario_id = usuarios.id";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Painel - Aspas</title>
    <link rel="stylesheet" href="css/style.css">
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
            <a href="logout.php" class="btn-sair">Sair do sistema</a>
        </div>
    </aside>

    <main class="conteudo-principal">
        <div class="cabecalho">
            <form action="index.php" method="POST">
                <input type="text" name="titulo" placeholder="O que precisa ser feito?" required>
                <textarea name="descricao" placeholder="Descreva os detalhes..." rows="2"></textarea>
                <button type="submit">+ Adicionar Tarefa</button>
            </form>
        </div>

        <div class="quadro">
            <div class="coluna">
                <h3>A Fazer</h3>
                <?php
                $resultado->data_seek(0);
                while ($tarefa = $resultado->fetch_assoc()) {
                    if ($tarefa['status'] == 'a_fazer') {
                        echo "<div class='cartao'>";
                        echo "<strong>" . htmlspecialchars($tarefa['titulo']) . "</strong>";

                        
                        $nome_dono = $tarefa['responsavel_nome'] ? htmlspecialchars($tarefa['responsavel_nome']) : 'Sem dono';
                        echo "<div class='dono-tarefa'>👤 " . $nome_dono . "</div>";

                        echo "<small>" . nl2br(htmlspecialchars($tarefa['descricao'])) . "</small>";
                        echo "<div class='acoes-cartao'>";
                        echo "<a href='acoes/mover.php?id=" . $tarefa['id'] . "&status=em_andamento' class='btn-acao btn-mover'>Iniciar ➔</a>";
                        echo "<a href='editar.php?id=" . $tarefa['id'] . "' class='btn-acao btn-editar'>✏️ Editar</a>";
                        echo "<a href='acoes/excluir.php?id=" . $tarefa['id'] . "' class='btn-acao btn-excluir' onclick=\"return confirm('Excluir?');\">🗑️ Excluir</a>";
                        echo "</div></div>";
                    }
                }
                ?>
            </div>

            <div class="coluna">
                <h3>Em Andamento</h3>
                <?php
                $resultado->data_seek(0);
                while ($tarefa = $resultado->fetch_assoc()) {
                    if ($tarefa['status'] == 'em_andamento') {
                        echo "<div class='cartao'>";
                        echo "<strong>" . htmlspecialchars($tarefa['titulo']) . "</strong>";

                        
                        $nome_dono = $tarefa['responsavel_nome'] ? htmlspecialchars($tarefa['responsavel_nome']) : 'Sem dono';
                        echo "<div class='dono-tarefa'>👤 " . $nome_dono . "</div>";

                        echo "<small>" . nl2br(htmlspecialchars($tarefa['descricao'])) . "</small>";
                        echo "<div class='acoes-cartao'>";
                        echo "<a href='acoes/mover.php?id=" . $tarefa['id'] . "&status=a_fazer' class='btn-acao btn-mover'>⬅ Voltar</a>";
                        echo "<a href='acoes/mover.php?id=" . $tarefa['id'] . "&status=concluido' class='btn-acao btn-mover'>Concluir ✔</a>";
                        echo "<a href='editar.php?id=" . $tarefa['id'] . "' class='btn-acao btn-editar'>✏️</a>";
                        echo "<a href='acoes/excluir.php?id=" . $tarefa['id'] . "' class='btn-acao btn-excluir' onclick=\"return confirm('Excluir?');\">🗑️</a>";
                        echo "</div></div>";
                    }
                }
                ?>
            </div>

            <div class="coluna">
                <h3>Concluído</h3>
                <?php
                $resultado->data_seek(0);
                while ($tarefa = $resultado->fetch_assoc()) {
                    if ($tarefa['status'] == 'concluido') {
                        echo "<div class='cartao'>";
                        echo "<strong>" . htmlspecialchars($tarefa['titulo']) . "</strong>";

                        
                        $nome_dono = $tarefa['responsavel_nome'] ? htmlspecialchars($tarefa['responsavel_nome']) : 'Sem dono';
                        echo "<div class='dono-tarefa'>👤 " . $nome_dono . "</div>";

                        echo "<small>" . nl2br(htmlspecialchars($tarefa['descricao'])) . "</small>";
                        echo "<div class='acoes-cartao'>";
                        echo "<a href='acoes/mover.php?id=" . $tarefa['id'] . "&status=em_andamento' class='btn-acao btn-mover'>⬅ Reabrir</a>";
                        echo "<a href='editar.php?id=" . $tarefa['id'] . "' class='btn-acao btn-editar'>✏️ Editar</a>";
                        echo "<a href='acoes/excluir.php?id=" . $tarefa['id'] . "' class='btn-acao btn-excluir' onclick=\"return confirm('Excluir?');\">🗑️ Excluir</a>";
                        echo "</div></div>";
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <script>
        const toggleTema = document.getElementById('toggle-tema');
        const body = document.body;
        if (localStorage.getItem('tema_aspas') === 'escuro') {
            body.classList.add('dark-mode');
            toggleTema.checked = true;
        }
        toggleTema.addEventListener('change', () => {
            if (toggleTema.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('tema_aspas', 'escuro');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('tema_aspas', 'claro');
            }
        });
    </script>
</body>

</html>