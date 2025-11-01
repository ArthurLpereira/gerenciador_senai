<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/semanal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">

    <style>
        .info-modal-turma-section {
            text-align: left;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-modal-turma-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-modal-turma-section h4 {
            color: #d33;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .info-modal-turma-section p {
            margin: 2px 0;
            font-size: 0.95em;
        }

        .swal2-html-container {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px !important;
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body>
    <header>
        <div class="menu-box" id="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <img class="logo_senai" src="./images/logo_senai.png" alt="Logo SENAI">
    </header>

    <nav class="sidebar" id="sidebar">
        <ul>
            <li><a href="./home.php"><img src="./images/calendar.png" alt="Ícone de perfil"><span class="menu-texto">Calendário<br></span></a></li>
            <li><a href="./gerenciar.php"><img src="./images/profile.png" alt="Ícone de perfil"><span class="menu-texto">Gerenciar<br>Cadastros</span></a></li>
            <li><a href="./validacao.php"><img src="./images/checked.png" alt="Ícone de check"><span class="menu-texto">Validação<br>de Turmas</span></a></li>
            <li><a href="./ferramentas.php"><img src="./images/gear.png" alt="Ícone de engrenagem"><span class="menu-texto">Ferramentas<br>de Gestão</span></a></li>
            <li><a href="./locacoes.php"><img src="./images/alocacao.png" alt="Ícone de alocação"><span class="menu-texto">Alterar<br>Alocações</span></a></li>
            <li><a href="./relatorios.php"><img src="./images/report (1).png" alt="Ícone de relatório"><span class="menu-texto">Gerar<br>Relatórios</span></a></li>
            <li><a href="./perfil.php"><img src="./images/account.png" alt="Ícone de conta"><span class="menu-texto">Meu<br>Perfil</span></a></li>
        </ul>
    </nav>

    <main id="conteudo">
        <section class="titulo">
            <div class="calendar">
                <img src="./images/calendar (2).png" alt="Ícone de Engrenagem">
                <h1>Calendário Semanal</h1>
            </div>
            <div class="sair">
                <a href="logout.php" id="btn-sair"><img src="./images/logout (2).png" alt="Ícone de sair"> Sair</a>
            </div>
        </section>

        <div class="content-body">
            <div class="filter-container">
                <img src="./images/filter.png" alt="Ícone de Filtro" class="filter-icon">
                <label>Filtrar</label>
                <div class="search-box">
                    <input type="text" placeholder="Filtrar...">
                    <button type="submit" class="search-button">
                        <img src="./images/pesquisar.png" alt="Ícone de Lupa">
                    </button>
                </div>
            </div>
            <div class="management-controls">
                <button class="manage-days-btn">
                    <img src="./images/gear.png" alt="Ícone de Engrenagem">
                    Gerenciar Dias Letivos
                </button>
                <label class="toggle-switch" title="Mudar para visualização Mensal">
                    <input type="checkbox" id="view-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h2 id="week-range-title">Carregando...</h2>
                <button class="nav-arrow" id="prev-week-btn">&lt;</button>
                <button class="nav-arrow" id="next-week-btn">&gt;</button>
            </div>
            <table class="calendar-grid">
                <thead>
                    <tr id="calendar-header-days">
                        <th>Ambientes</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    <tr>
                        <td>Carregando dados...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/semanal.js"></script>
</body>

</html>