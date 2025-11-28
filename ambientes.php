<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Ambientes - SGA SENAI</title>
    <link rel="stylesheet" href="./css/gerenciar_cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div class="gerenciar-titulo">
                <img src="./images/profile2.png" alt="icone_person">
                <h1>Gerenciador de Cadastro</h1>
            </div>
            <div class="docentes">
                <h2>Ambientes</h2>
                <hr>
            </div>
            <div class="sub_titulos">
                <div class="adiconar">
                    <button id="abrirModal" class="adicionar_button"><i class="bi bi-plus-circle-fill"></i> Criar</button>
                </div>
                <div class="pesquisa">
                    <input type="text" id="searchInput" placeholder="Pesquisar Ambientes...">
                    <span class="icon_pesquisa"><i class="bi bi-search"></i></span>
                </div>
            </div>
        </div>
        <div class="conteudo_docente" id="lista-ambientes">
        </div>
    </main>

    <div id="modalCriar" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" data-modal-id="modalCriar"><i class="bi bi-x-circle"></i></span>
            <h2>Criar Ambiente</h2>
            <hr>
            <form id="formCriar">
                <input type="text" id="nome_ambiente" placeholder="Nome do Ambiente" required>
                <input type="number" id="num_ambiente" placeholder="Número do Ambiente">
                <input type="number" id="capacidade_ambiente" placeholder="Capacidade de Alunos" required>
                <select id="tipo_ambiente_id" required>
                    <option value="" disabled selected>Selecione um Tipo</option>
                </select>
                <div class="button-container">
                    <button type="submit" class="btn-criar">Criar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditar" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" data-modal-id="modalEditar"><i class="bi bi-x-circle"></i></span>
            <h2>Editar Ambiente</h2>
            <hr>
            <form id="formEditar">
                <input type="hidden" id="edit_ambiente_id">
                <input type="text" id="edit_nome_ambiente" placeholder="Nome do Ambiente" required>
                <input type="number" id="edit_num_ambiente" placeholder="Número do Ambiente (Opcional)">
                <input type="number" id="edit_capacidade_ambiente" placeholder="Capacidade de Alunos" required>
                <select id="edit_tipo_ambiente_id" required>
                    <option value="" disabled selected>Selecione um Tipo</option>
                </select>
                <div class="button-container">
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script src="./js/ambientes.js"></script>
</body>

</html>