<?php
// Arquivo: cadastro.php
session_start();
include 'config/db.php';

$erro = ""; 
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $senha_digitada = $_POST['senha'];

    // 1. Verifica se o e-mail já está cadastrado
    $sql_verifica = "SELECT id FROM usuarios WHERE email = '$email'";
    $resultado_verifica = $conexao->query($sql_verifica);

    if ($resultado_verifica->num_rows > 0) {
        $erro = "Este e-mail já está em uso. Tente fazer login.";
    } else {
        // 2. CRIPTOGRAFIA DE SENHA (A Mágica acontece aqui!)
        // O PASSWORD_DEFAULT diz ao PHP para usar o algoritmo mais forte e atual disponível (hoje é o BCRYPT)
        $senha_criptografada = password_hash($senha_digitada, PASSWORD_DEFAULT);

        // 3. Insere o novo usuário no banco, salvando o hash ao invés da senha normal
        $sql_inserir = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_criptografada')";
        
        if ($conexao->query($sql_inserir) === TRUE) {
            $sucesso = "Conta criada com segurança! Você já pode fazer login.";
        } else {
            $erro = "Erro ao criar conta: " . $conexao->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Aspas</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Reutilizando as classes visuais do login */
        body { display: flex; height: 100vh; overflow: hidden; align-items: stretch; }
        .lado-esquerdo { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; position: relative; }
        .lado-direito { flex: 1; background-color: var(--cor-fundo-menu); color: var(--cor-texto-menu); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; text-align: center; }
        .box-login { width: 100%; max-width: 400px; }
        .box-login h1 { font-size: 2.2em; margin-bottom: 10px; text-align: center; }
        .box-login p.subtitulo { text-align: center; color: var(--cor-texto-secundario); margin-bottom: 30px; }
        .grupo-input { margin-bottom: 20px; }
        .grupo-input label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9em; }
        .erro { background-color: #fdecea; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #ef9a9a; }
        .sucesso { background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #a5d6a7; }
        .logo-img { max-width: 300px; margin-bottom: 20px; }
        .lado-direito img { filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.3)); }
    </style>
</head>
<body>

    <div class="lado-esquerdo">
        <div class="container-tema" style="position: absolute; top: 20px; left: 20px;">
            <label class="switch">
                <input type="checkbox" id="toggle-tema">
                <span class="slider"></span>
            </label>
            <span>Modo Escuro</span>
        </div>

        <div class="box-login">
            <h1>Junte-se à equipe</h1>
            <p class="subtitulo">Crie sua conta para começar a organizar projetos.</p>
            
            <?php 
            if($erro != "") { echo "<div class='erro'>$erro</div>"; } 
            if($sucesso != "") { echo "<div class='sucesso'>$sucesso</div>"; } 
            ?>
            
            <form action="cadastro.php" method="POST">
                <div class="grupo-input">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" placeholder="Seu nome" required>
                </div>
                <div class="grupo-input">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="exemplo@aspas.com" required>
                </div>
                <div class="grupo-input">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="Crie uma senha forte" required>
                </div>

                <button type="submit" style="width: 100%;">Criar Conta</button>
            </form>
            
            <p style="text-align: center; margin-top: 30px; font-size: 0.9em; color: var(--cor-texto-secundario);">
                Já possui uma conta? <a href="login.php" style="color: var(--cor-botao); text-decoration: none; font-weight: bold;">Faça login aqui</a>
            </p>
        </div>
    </div>

    <div class="lado-direito">
        <img src="img/logo.png" alt="Logo Aspas" class="logo-img">
        <p>A plataforma ágil que destaca o que importa.</p>
    </div>

    <script>
        const toggleTema = document.getElementById('toggle-tema');
        const body = document.body;
        if(localStorage.getItem('tema_aspas') === 'escuro') { body.classList.add('dark-mode'); toggleTema.checked = true; }
        toggleTema.addEventListener('change', () => {
            if (toggleTema.checked) { body.classList.add('dark-mode'); localStorage.setItem('tema_aspas', 'escuro'); } 
            else { body.classList.remove('dark-mode'); localStorage.setItem('tema_aspas', 'claro'); }
        });
    </script>
</body>
</html>