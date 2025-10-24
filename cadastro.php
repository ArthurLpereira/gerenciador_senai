<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/cadastro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <main class="main">
        <section class="part1">
            <h1>SGA-SENAI</h1>
            <p>(Sistema de Gerenciamento de Alocação)</p>
        </section>
        <section class="part2">
            <div class="titulo-cadastro">
                <h1>Cadastre-se</h1>
                <p>Crie sua conta para acessar o SGA-SENAI</p>
            </div>

            <form id="formCadastro">
                <input type="text" name="nome" id="nome" placeholder="Nome" required>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="text" name="especialidade" id="especialidade" placeholder="Especialidade" required>

                <div class="color-input-wrapper">
                    <input type="color" name="cor" id="color-input" value="#d62828">
                    <span class="color-placeholder">Cor:</span>
                    <div class="color-swatch" id="color-swatch"></div>
                </div>

                <input type="password" name="senha" id="senha" placeholder="Senha" required>

                <!-- <input type="password" name="senha_confirmar" id="senha_confirmar" placeholder="Confirme sua Senha" required> -->
                <button type="submit" id="submit-button">Cadastrar</button>
            </form>
            <div class="comunicacoes">
                <button><img src="./images/search.png" alt=""> Entrar com o Google</button>
                <button><img src="./images/microsoft.png" alt=""> Entrar com a Microsoft</button>
            </div>
        </section>
    </main>

    <script>
        const colorInput = document.getElementById('color-input');
        const colorSwatch = document.getElementById('color-swatch');
        colorSwatch.style.backgroundColor = colorInput.value;
        colorInput.addEventListener('input', (event) => {
            colorSwatch.style.backgroundColor = event.target.value;
        });
        colorSwatch.addEventListener('click', () => {
            colorInput.click();
        });
    </script>

    <script src="./js/cadastro.js"></script>
</body>

</html>