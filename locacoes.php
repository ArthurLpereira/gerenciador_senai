<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Alocações - SGA SENAI</title>
    <link rel="stylesheet" href="./css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            overflow-y: scroll !important;
        }

        .sair a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            gap: 8px;
            margin-right: 12px;
        }

        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 71px;
            margin-top: 20px;
            justify-content: flex-start;
            width: 100%;
            margin: 20px auto 0 auto;
        }

        .turma-card {
            flex-basis: calc(50% - 10px);
            max-width: 400px;
            min-width: 300px;
            flex-grow: 1;
            /* Permite que ocupe o espaço disponível se necessário */

            background-color: #fff;
            /* Fundo branco para se destacar */
            border: 1px solid #e0e0e0;
            /* Borda sutil como na imagem */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .turma-card h3 {
            margin-top: 0;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .turma-card p {
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
        }

        .turma-card .alterar-btn {
            background-color: #C8000C;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .turma-card .alterar-btn:hover {
            background-color: #9A1915;
        }

        .barra-pesquisa-funcional {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-top: 20px;
        }

        .barra-pesquisa-funcional span {
            margin: 0 15px;
        }

        .input-container {
            position: relative;
            margin-left: auto;
        }

        .input-container input {
            padding-right: 40px;
        }

        .lupa-icone {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    <header>
        <div class="menu-box" id="menu-btn"><span></span><span></span><span></span></div>
        <img class="logo_senai" src="./images/logo_senai.png" alt="Logo SENAI">
    </header>

    <nav class="sidebar" id="sidebar">
        <ul>
            <li><a href="./home.php"><img src="./images/calendar.png" alt="Ícone"> <span class="menu-texto">Calendário</span></a></li>
            <li><a href="./gerenciar.php"><img src="./images/profile.png" alt="Ícone"> <span class="menu-texto">Gerenciar Cadastros</span></a></li>
            <li><a href="./validacao.php"><img src="./images/checked.png" alt="Ícone"> <span class="menu-texto">Validação de Turmas</span></a></li>
            <li><a href="./ferramentas.php"><img src="./images/gear.png" alt="Ícone"> <span class="menu-texto">Ferramentas de Gestão</span></a></li>
            <li><a href="./locacoes.php"><img src="./images/alocacao.png" alt="Ícone"> <span class="menu-texto">Alterar Alocações</span></a></li>
            <li><a href="./relatorios.php"><img src="./images/report (1).png" alt="Ícone"> <span class="menu-texto">Gerar Relatórios</span></a></li>
            <li><a href="./perfil.php"><img src="./images/account.png" alt="Ícone"> <span class="menu-texto">Meu Perfil</span></a></li>
        </ul>
    </nav>

    <main id="conteudo">
        <section class="titulo">
            <div class="calendar">
                <img src="./images/red.png" alt="Ícone de Alocação" style="width: 40px; height: 40px;">
                <h1>Alterar Alocações</h1>
            </div>
            <div class="sair">
                <a href="#" id="logout-button">
                    <img src="./images/logout (2).png" alt="Ícone de Sair">
                    <span>Sair</span>
                </a>
            </div>
        </section>

        <div class="pesquisa" id="container-pesquisa">
            <div class="barra-pesquisa-funcional">
                <img src="./images/multiplos.png" alt="" style="width: 32px; height: 32px;">
                <span>Selecione a turma para alterar a alocação</span>
                <div class="input-container">
                    <input type="text" id="input-pesquisa-turma" placeholder="Pesquise o nome da turma...">
                    <img src="./images/pesquisar.png" alt="Lupa" class="lupa-icone">
                </div>
            </div>
        </div>

        <div id="lista-turmas" class="card-grid">
            <div class="turma-card" data-id="1">
                <h3>Nome: DEV REG 2024</h3>
                <p><i class="bi bi-person"></i> Docente: Marcos Reis Ferreira</p>
                <p><i class="bi bi-geo-alt-fill"></i> Atual Ambiente: Sala 202</p>
                <button class="alterar-btn" data-id="1" data-nome="DEV REG 2024">Alterar</button>
            </div>
            <div class="turma-card" data-id="2">
                <h3>Nome: EDIFICAÇÕES 2024</h3>
                <p><i class="bi bi-person"></i> Docente: Ana Paula SIlva</p>
                <p><i class="bi bi-geo-alt-fill"></i> Atual Ambiente: Laboratório 1</p>
                <button class="alterar-btn" data-id="2" data-nome="EDIFICAÇÕES 2024">Alterar</button>
            </div>
        </div>
    </main>

    <script src="./js/script.js"></script>
    <script src="./js/locacoes.js"></script>
</body>

</html>