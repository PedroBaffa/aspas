<?php

session_start();
include 'config/db.php';

$erro = ""; 
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']); 

    
    $sql_verifica = "SELECT id FROM usuarios WHERE email = '$email'";
    $resultado_verifica = $conexao->query($sql_verifica);

    if ($resultado_verifica->num_rows > 0) {
        $erro = "Este e-mail já está em uso. Tente fazer login.";
    } else {
        
        $sql_inserir = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        
        if ($conexao->query($sql_inserir) === TRUE) {
            $sucesso = "Conta criada com sucesso! Você já pode fazer login.";
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
    <style>
        /* Reutilizando as variáveis e o layout moderno do nosso Login */
        :root {
            --bg-esquerda: #ffffff; --texto-esquerda: #333333; --borda-input: #cccccc;
            --bg-direita: #0a162e; --texto-direita: #ffffff;
            --cor-primaria: #4776E6; --cor-primaria-hover: #3b63c2;
        }
        body.dark-mode {
            --bg-esquerda: #121212; --texto-esquerda: #e0e0e0; --borda-input: #444444;
            --bg-direita: #050b17; --texto-direita: #e0e0e0;
            --cor-primaria: #638cf0; --cor-primaria-hover: #82a3f5;
        }

        body {
            margin: 0; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            display: flex; height: 100vh; overflow: hidden;
            background-color: var(--bg-esquerda); color: var(--texto-esquerda);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .lado-esquerdo { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; position: relative; }
        .lado-direito { flex: 1; background-color: var(--bg-direita); color: var(--texto-direita); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; text-align: center; transition: background-color 0.3s ease; }

        .box-login { width: 100%; max-width: 400px; }
        .box-login h1 { font-size: 2.2em; margin-bottom: 10px; text-align: center; }
        .box-login p.subtitulo { text-align: center; color: #777; margin-bottom: 30px; font-size: 0.95em; }
        
        .grupo-input { margin-bottom: 20px; }
        .grupo-input label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9em; }
        .grupo-input input { width: 100%; padding: 12px; border: 1px solid var(--borda-input); border-radius: 6px; box-sizing: border-box; background: transparent; color: var(--texto-esquerda); font-size: 1em; transition: 0.3s; }
        .grupo-input input:focus { outline: none; border-color: var(--cor-primaria); }

        .btn-entrar { width: 100%; background-color: var(--cor-primaria); color: white; padding: 14px; border: none; border-radius: 6px; font-size: 1em; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-entrar:hover { background-color: var(--cor-primaria-hover); }

        .erro { background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #ef9a9a; }
        .sucesso { background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; border: 1px solid #a5d6a7; }

        .logo-img { max-width: 300px; margin-bottom: 20px; }
        .lado-direito img { filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.3)); }

        .container-tema { position: absolute; top: 20px; left: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.85em; font-weight: bold; }
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--cor-primaria); }
        input:checked + .slider:before { transform: translateX(20px); }
    </style>
</head>
<body>

    <div class="lado-esquerdo">
        <div class="container-tema">
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

                <button type="submit" class="btn-entrar">Criar Conta</button>
            </form>
            
            <p style="text-align: center; margin-top: 30px; font-size: 0.9em; color: #777;">
                Já possui uma conta? <a href="login.php" style="color: var(--cor-primaria); text-decoration: none; font-weight: bold;">Faça login aqui</a>
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