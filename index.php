<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="body-login">
    <main class="main-login">
        <div class="titulo-login">
            <h1>Sistema de Gerenciamento</h1>
            <h1>de Alocação (SGA-SENAI)</h1>
        </div>
        <form action="" id="login-form">
            <div class="inps-login">
                <div class="input-box">
                    <i class="bi bi-person-circle"></i>
                    <input type="text" placeholder="Email" name="email_colaborador" id="email_colaborador" required>
                </div>
                <div class="input-box">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" placeholder="Senha" name="senha_colaborador" id="senha_colaborador" required>
                </div>
                <div class="conect">
                    <button> <i class="bi bi-google"></i> Entrar com o Google</button>
                    <button><i class="bi bi-windows"></i> Entrar com a Microsoft</button>
                </div>
                <div style="display: flex; justify-content: space-between; width: 100%;">
                    <a href="#">Esqueceu sua senha?</a>
                    <a href="./cadastro.php" id="abrirModalCadastro" style="cursor: pointer;">Cadastre-se</a>
                </div>
            </div>
            <div class="enviar-login">
                <input type="submit" value="Acessar">
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="./js/login.js"></script>
</body>

</html>