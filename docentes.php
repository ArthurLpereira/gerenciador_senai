<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Docentes - SGA SENAI</title>
    <link rel="stylesheet" href="./css/gerenciar_cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* CSS necessário para o layout de duas colunas do modal */
        .modal-content .form-grid-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 25px 0;
            margin-bottom: 30px;
        }

        .modal-content .input-wrapper {
            flex-basis: 48%;
        }

        .modal-content .input-wrapper input,
        .modal-content .input-wrapper select {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-bottom: 2px solid #ccc;
            font-size: 16px;
            background-color: transparent;
        }

        .modal-content .input-wrapper input:focus,
        .modal-content .input-wrapper select:focus {
            outline: none;
            border-bottom-color: #C8000C;
        }

        .modal-content .label-cor {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 12px 0;
            border-bottom: 2px solid #ccc;
            font-size: 16px;
            color: #666;
            cursor: pointer;
        }

        .modal-content .cor-quadrado {
            width: 25px;
            height: 25px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-content .input-cor-oculto {
            visibility: hidden;
            width: 0;
            height: 0;
            padding: 0;
            border: none;
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
            <li>
                <a href="./home.php">
                    <img src="./images/calendar.png" alt="Ícone de perfil">
                    <span class="menu-texto">Calendário<br></span>
                </a>
            </li>
            <li>
                <a href="./gerenciar.php">
                    <img src="./images/profile.png" alt="Ícone de perfil">
                    <span class="menu-texto">Gerenciar<br>Cadastros</span>
                </a>
            </li>
            <li>
                <a href="./validacao.php">
                    <img src="./images/checked.png" alt="Ícone de check">
                    <span class="menu-texto">Validação<br>de Turmas</span>
                </a>
            </li>
            <li>
                <a href="./ferramentas.php">
                    <img src="./images/gear.png" alt="Ícone de engrenagem">
                    <span class="menu-texto">Ferramentas<br>de Gestão</span>
                </a>
            </li>
            <li>
                <a href="./locacoes.php">
                    <img src="./images/alocacao.png" alt="Ícone de alocação">
                    <span class="menu-texto">Alterar<br>Alocações</span>
                </a>
            </li>
            <li>
                <a href="./relatorios.php">
                    <img src="./images/report (1).png" alt="Ícone de relatório">
                    <span class="menu-texto">Gerar<br>Relatórios</span>
                </a>
            </li>
            <li>
                <a href="./perfil.php">
                    <img src="./images/account.png" alt="Ícone de conta">
                    <span class="menu-texto">Meu<br>Perfil</span>
                </a>
            </li>
        </ul>
    </nav>
    <main id="conteudo-cadastro">
        <div class="titulos">
            <div class="gerenciar-titulo"><img src="./images/profile2.png" alt="icone_person">
                <h1>Gerenciador de Cadastro</h1>
            </div>
            <div class="docentes">
                <h2>Docentes</h2>
                <hr>
            </div>
            <div class="sub_titulos">
                <div class="adiconar"><button id="abrirModal" class="adicionar_button"><i class="bi bi-plus-circle-fill"></i> Criar</button></div>
                <div class="pesquisa"><input type="text" id="searchInput" placeholder="Pesquisar Docentes..."><span class="icon_pesquisa"><i class="bi bi-search"></i></span></div>
            </div>
        </div>
        <div class="conteudo_docente" id="lista-colaboradores">
        </div>
    </main>

    <div id="modalCriar" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" data-modal-id="modalCriar"><i class="bi bi-x-circle"></i></span>
            <h2>Criar Docente</h2>
            <hr>
            <form id="formCriar">
                <div class="form-grid-container">
                    <div class="input-wrapper"><input type="text" id="nome_colaborador" placeholder="Nome do Docente" required></div>
                    <div class="input-wrapper"><input type="email" id="email_colaborador" placeholder="Email" required></div>
                    <div class="input-wrapper"><input type="password" id="senha_colaborador" placeholder="Senha" required></div>
                    <div class="input-wrapper"><input type="text" id="especialidade_colaborador" placeholder="Especialidades"></div>
                    <div class="input-wrapper"><select id="tipo_colaborador_id" required>
                            <option value="" disabled selected>Selecione um Tipo</option>
                        </select></div>
                    <div class="input-wrapper">
                        <label class="label-cor"><span>Escolha uma cor</span>
                            <div id="cor-quadrado" class="cor-quadrado"></div><input type="color" id="cor_colaborador" class="input-cor-oculto" value="#CE000F">
                        </label>
                    </div>
                </div>
                <div class="button-container"><button type="submit" class="btn-criar">Criar</button></div>
            </form>
        </div>
    </div>

    <div id="modalEditar" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" data-modal-id="modalEditar"><i class="bi bi-x-circle"></i></span>
            <h2>Editar Docente</h2>
            <hr>
            <form id="formEditar">
                <input type="hidden" id="edit_colaborador_id">
                <div class="form-grid-container">
                    <div class="input-wrapper"><input type="text" id="edit_nome_colaborador" placeholder="Nome do Docente" required></div>
                    <div class="input-wrapper"><input type="email" id="edit_email_colaborador" placeholder="Email" required></div>
                    <div class="input-wrapper"><input type="text" id="edit_especialidade_colaborador" placeholder="Especialidades"></div>
                    <div class="input-wrapper"><select id="edit_tipo_colaborador_id" required>
                            <option value="" disabled selected>Selecione um Tipo</option>
                        </select></div>
                    <div class="input-wrapper">
                        <label class="label-cor"><span>Escolha uma cor</span>
                            <div id="edit_cor_quadrado" class="cor-quadrado"></div><input type="color" id="edit_cor_colaborador" class="input-cor-oculto">
                        </label>
                    </div>
                </div>
                <div class="button-container"><button type="submit" class="btn-salvar">Salvar Alterações</button></div>
            </form>
        </div>
    </div>

    <script src="./js/docente.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>