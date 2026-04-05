<?php

session_start();
include 'config/db.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $resultado = $conexao->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login - Aspas</title>
    <style>
        /* 1. VARIÁVEIS DE CORES */
        :root {
            --bg-esquerda: #ffffff;
            --texto-esquerda: #333333;
            --borda-input: #cccccc;
            --bg-direita: #0a162e;
            /* O Azul bem escuro! */
            --texto-direita: #ffffff;
            --cor-primaria: #4776E6;
            --cor-primaria-hover: #3b63c2;
        }

        body.dark-mode {
            --bg-esquerda: #121212;
            --texto-esquerda: #e0e0e0;
            --borda-input: #444444;
            --bg-direita: #050b17;
            /* Um azul ainda mais escuro para contrastar no dark mode */
            --texto-direita: #e0e0e0;
            --cor-primaria: #638cf0;
            --cor-primaria-hover: #82a3f5;
        }

        /* 2. ESTILOS GERAIS */
        body {
            margin: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: var(--bg-esquerda);
            color: var(--texto-esquerda);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* 3. LAYOUT DIVIDIDO */
        .lado-esquerdo {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            /* Para posicionar o botão de tema */
        }

        .lado-direito {
            flex: 1;
            background-color: var(--bg-direita);
            color: var(--texto-direita);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        /* 4. FORMULÁRIO DE LOGIN */
        .box-login {
            width: 100%;
            max-width: 400px;
        }

        .box-login h1 {
            font-size: 2.2em;
            margin-bottom: 30px;
            text-align: center;
        }

        .grupo-input {
            margin-bottom: 20px;
        }

        .grupo-input label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .grupo-input input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--borda-input);
            border-radius: 6px;
            box-sizing: border-box;
            background: transparent;
            color: var(--texto-esquerda);
            font-size: 1em;
            transition: 0.3s;
        }

        .grupo-input input:focus {
            outline: none;
            border-color: var(--cor-primaria);
        }

        .opcoes-extras {
            display: flex;
            justify-content: space-between;
            font-size: 0.85em;
            margin-bottom: 30px;
            color: #777;
        }

        .opcoes-extras a {
            color: var(--cor-primaria);
            text-decoration: none;
        }

        .btn-entrar {
            width: 100%;
            background-color: var(--cor-primaria);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-entrar:hover {
            background-color: var(--cor-primaria-hover);
        }

        .erro {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #ef9a9a;
        }

        /* 5. ÁREA DO LOGO (DIREITA) */
        .logo-img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        /* Dica: O filtro 'invert' ajuda a deixar um logo preto ficar branco no fundo escuro, caso necessário */
        .lado-direito img {
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.3));
        }

        .lado-direito p {
            font-size: 1.2em;
            max-width: 80%;
            opacity: 0.9;
        }

        /* 6. SWITCH MODERNO DO MODO ESCURO */
        .container-tema {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--cor-primaria);
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
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
            <h1>Faça seu login</h1>

            <?php if ($erro != "") {
                echo "<div class='erro'>$erro</div>";
            } ?>

            <form action="login.php" method="POST">
                <div class="grupo-input">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="exemplo@aspas.com" required>
                </div>
                <div class="grupo-input">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="Sua senha" required>
                </div>

                <div class="opcoes-extras">
                    <label><input type="checkbox"> Lembrar de mim</label>
                    <a href="#">Esqueci minha senha</a>
                </div>

                <button type="submit" class="btn-entrar">Entrar</button>
            </form>

            <p style="text-align: center; margin-top: 30px; font-size: 0.9em; color: #777;">
                Não tem conta ainda? <a href="cadastro.php" style="color: var(--cor-primaria); text-decoration: none; font-weight: bold;">Crie agora</a>
            </p>
        </div>
    </div>

    <div class="lado-direito">
        <img src="img/logo-branco.png" alt="Logo Aspas" class="logo-img">
        <p>A plataforma ágil que destaca o que importa.</p>
    </div>

    <script>
        const toggleTema = document.getElementById('toggle-tema');
        const body = document.body;

        // 1. Checa o localStorage para ver se já estava no modo escuro
        if (localStorage.getItem('tema_aspas') === 'escuro') {
            body.classList.add('dark-mode');
            toggleTema.checked = true; // Deixa a chavinha ativada
        }

        // 2. Ação de clicar na chavinha
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