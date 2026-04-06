<?php
// Arquivo: login.php
session_start();
include 'config/db.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha_digitada = $_POST['senha'];

    // 1. Busca APENAS o e-mail no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = $conexao->query($sql);

    // 2. Se encontrou o e-mail...
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // 3. O PHP compara a senha digitada com a criptografia salva no banco
        if (password_verify($senha_digitada, $usuario['senha'])) {
            // Senha correta! Coloca a pulseira VIP
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: index.php");
            exit;
        } else {
            // Senha errada
            $erro = "E-mail ou senha incorretos!";
        }
    } else {
        // E-mail não encontrado
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login - Aspas</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Mesmas classes do cadastro para manter o padrão */
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
            align-items: stretch;
        }

        .lado-esquerdo {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
        }

        .lado-direito {
            flex: 1;
            background-color: var(--cor-fundo-menu);
            color: var(--cor-texto-menu);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }

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

        .opcoes-extras {
            display: flex;
            justify-content: space-between;
            font-size: 0.85em;
            margin-bottom: 30px;
            color: var(--cor-texto-secundario);
        }

        .opcoes-extras a {
            color: var(--cor-botao);
            text-decoration: none;
        }

        .erro {
            background-color: #fdecea;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #ef9a9a;
        }

        .logo-img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .lado-direito img {
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.3));
        }
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

                <button type="submit" style="width: 100%;">Entrar</button>
            </form>

            <p style="text-align: center; margin-top: 30px; font-size: 0.9em; color: var(--cor-texto-secundario);">
                Não tem conta ainda? <a href="cadastro.php" style="color: var(--cor-botao); text-decoration: none; font-weight: bold;">Crie agora</a>
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