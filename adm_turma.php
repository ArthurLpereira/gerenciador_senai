<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>

    <link rel="stylesheet" href="././css/secretaria.css">
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 25px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        .modal-header {
            display: flex;
            flex-direction: column;
            border: none;
        }

        .modal-header h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-button {
            background-color: #c00;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
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
            <li>
                <a href="adm_turma.php">
                    <img src="./images/turma.png" alt="Ícone de calendário">
                    <span class="menu-texto">Administração de Turmas<br></span>
                </a>
            </li>
            <li>
                <a href="perfil_secretaria.php">
                    <img src="./images/account.png" alt="Ícone de conta">
                    <span class="menu-texto">Meu<br>Perfil</span>
                </a>
            </li>
        </ul>
    </nav>
    <main>
        <div class="titulo">
            <img src="./images/secretaria_red.png" alt="">
            <p>Administração de Turmas</p>
        </div>
        <div class="sub-titulo">
            <h2>Seja bem vindo (a) ao </h2>
            <h1><b>Sistema de Gerenciamento de Alocação</b></h1>
        </div>
        <div class="content-body">
            <div class="filter-container">
                <img src="./images/filtrar.png" alt="Ícone de Filtro" class="filter-icon">
                <label>Filtrar</label>
                <div class="search-box">

                    <input type="text" id="searchInput" placeholder="Pesquisar...">

                    <button class="search-button">
                        <img src="./images/pesquisar.png" alt="Ícone de Lupa">
                    </button>
                </div>
            </div>

            <div id="lista-turmas">
            </div>

        </div>
    </main>

    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" data-modal="modalEditar">&times;</span>
            <div class="modal-header">
                <h2>Editar Turma</h2>
                <p>Altere os dados da turma selecionada.</p>
            </div>
            <div class="modal-body">
                <form id="formEditar">
                    <input type="hidden" id="edit_turma_id" name="id">

                    <div class="form-group">
                        <label for="edit_nome_turma">Nome da Turma:</label>
                        <input type="text" id="edit_nome_turma" name="nome_turma" placeholder="Nome da turma" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_capacidade_atual">Capacidade Atual:</label>
                        <input type="number" id="edit_capacidade_atual" name="capacidade_atual" placeholder="Quantidade atual de alunos">
                    </div>

                    <button type="submit" class="submit-button">Salvar Edição</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('main');
        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                menuBtn.classList.toggle('active');
                mainContent.classList.toggle('push');
            });
        }
    </script>

    <script src="./js/adm_turma.js"></script>
</body>

</html>