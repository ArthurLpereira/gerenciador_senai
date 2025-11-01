<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Turmas - SGA SENAI</title>
    <link rel="stylesheet" href="./css/turmas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .sair a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            gap: 8px;
        }

        .titulo {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sair {
            margin-right: 20px;
        }

        .sair img {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body>
    <header>
        <div class="menu-box" id="menu-btn"><span></span><span></span><span></span></div>
        <img class="logo_senai" src="./images/logo_senai.png" alt="Logo SENAI">
    </header>
    <nav class="sidebar" id="sidebar">
        <!-- Seu menu lateral aqui -->
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
    <main id="conteudo-cadastro">
        <section class="titulo">
            <div class="gerenciar-titulo">
                <img src="./images/validacao.png" alt="icone_person">
                <h1>Validação de Turmas</h1>
            </div>
            <div class="sair">
                <a href="#" id="logout-button">
                    <img src="./images/logout (2).png" alt="Ícone de Sair">
                    <span>Sair</span>
                </a>
            </div>
        </section>
        <div class="docentes">
            <h2>Turmas</h2>
        </div>
        <div class="sub_titulos">
            <div class="adiconar">
                <button id="abrirModal" class="adicionar_button"><i class="bi bi-plus-circle-fill"></i> Criar</button>
            </div>
            <div class="pesquisa">
                <input type="text" id="searchInput" placeholder="Pesquisar Turmas...">
                <span class="icon_pesquisa"><i class="bi bi-search"></i></span>
            </div>
        </div>
        <div class="conteudo_docente" id="lista-turmas">
            <!-- Os cards das turmas serão inseridos aqui -->
        </div>
    </main>
    <!-- Modal Criar Turma -->
    <div id="modalCriar" class="modal">
        <div class="modal-content">
            <span class="close" data-modal-id="modalCriar"><i class="bi bi-x-circle"></i></span>
            <h2>Criar Turma</h2>
            <form id="formCriar">
                <div class="form-body">
                    <div class="form-column">
                        <div class="form-group"><label for="curso_id">Curso</label><select id="curso_id" required></select></div>
                        <div class="form-group"><label for="ambiente_id">Ambiente</label><select id="ambiente_id" required></select></div>
                        <div class="form-group"><label for="data_inicio_turma">Data de Início</label><input type="date" id="data_inicio_turma" required></div>
                        <div class="form-group"><label for="turno_id">Turno</label><select id="turno_id" required></select></div>
                        <div class="form-group"><label for="minuto_aula_id">Duração da Aula</label><select id="minuto_aula_id" required></select></div>
                        <!-- CAMPO DE STATUS ADICIONADO -->
                        <div class="form-group"><label for="status_turma_id">Status</label><select id="status_turma_id" required></select></div>
                    </div>
                    <div class="form-column">
                        <div class="form-group"><label for="nome_turma">Nome da Turma</label><input type="text" id="nome_turma" placeholder="Ex: DEV-2025-1" required></div>
                        <div class="form-group"><label for="colaborador_id">Docente</label><select id="colaborador_id" required></select></div>
                        <div class="form-group"><label for="data_termino_turma">Data de Término</label><input type="date" id="data_termino_turma"></div>
                        <div class="form-group"><label for="capacidade_turma">Capacidade</label><input type="number" id="capacidade_turma" placeholder="Nº de alunos" required></div>
                        <div class="form-group">
                            <label>Dias da Semana</label>
                            <div class="radio-group" id="dias_semana_criar">
                                <div class="radio-item"><input type="checkbox" id="criar_dia_2" value="2"><label for="criar_dia_2">Seg</label></div>
                                <div class="radio-item"><input type="checkbox" id="criar_dia_3" value="3"><label for="criar_dia_3">Ter</label></div>
                                <div class="radio-item"><input type="checkbox" id="criar_dia_4" value="4"><label for="criar_dia_4">Qua</label></div>
                                <div class="radio-item"><input type="checkbox" id="criar_dia_5" value="5"><label for="criar_dia_5">Qui</label></div>
                                <div class="radio-item"><input type="checkbox" id="criar_dia_6" value="6"><label for="criar_dia_6">Sex</label></div>
                                <div class="radio-item"><input type="checkbox" id="criar_dia_7" value="7"><label for="criar_dia_7">Sáb</label></div>
                                <div class="radio-item"><input type="checkbox" id="criar_dia_1" value="1"><label for="criar_dia_1">Dom</label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-container"><button type="submit" class="btn-criar">Criar</button></div>
            </form>
        </div>
    </div>
    <!-- Modal Editar Turma -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" data-modal-id="modalEditar"><i class="bi bi-x-circle"></i></span>
            <h2>Editar Turma</h2>
            <form id="formEditar">
                <input type="hidden" id="edit_turma_id">
                <div class="form-body">
                    <div class="form-column">
                        <div class="form-group"><label for="edit_curso_id">Curso</label><select id="edit_curso_id" required></select></div>
                        <div class="form-group"><label for="edit_ambiente_id">Ambiente</label><select id="edit_ambiente_id" required></select></div>
                        <div class="form-group"><label for="edit_data_inicio_turma">Data de Início</label><input type="date" id="edit_data_inicio_turma" required></div>
                        <div class="form-group"><label for="edit_turno_id">Turno</label><select id="edit_turno_id" required></select></div>
                        <div class="form-group"><label for="edit_minuto_aula_id">Duração da Aula</label><select id="edit_minuto_aula_id" required></select></div>
                        <!-- CAMPO DE STATUS ADICIONADO -->
                        <div class="form-group"><label for="edit_status_turma_id">Status</label><select id="edit_status_turma_id" required></select></div>
                    </div>
                    <div class="form-column">
                        <div class="form-group"><label for="edit_nome_turma">Nome da Turma</label><input type="text" id="edit_nome_turma" required></div>
                        <div class="form-group"><label for="edit_colaborador_id">Docente</label><select id="edit_colaborador_id" required></select></div>
                        <div class="form-group"><label for="edit_data_termino_turma">Data de Término</label><input type="date" id="edit_data_termino_turma"></div>
                        <div class="form-group"><label for="edit_capacidade_turma">Capacidade</label><input type="number" id="edit_capacidade_turma" required></div>
                        <div class="form-group">
                            <label>Dias da Semana</label>
                            <div class="radio-group" id="dias_semana_editar">
                                <div class="radio-item"><input type="checkbox" id="editar_dia_2" value="2"><label for="editar_dia_2">Seg</label></div>
                                <div class="radio-item"><input type="checkbox" id="editar_dia_3" value="3"><label for="editar_dia_3">Ter</label></div>
                                <div class="radio-item"><input type="checkbox" id="editar_dia_4" value="4"><label for="editar_dia_4">Qua</label></div>
                                <div class="radio-item"><input type="checkbox" id="editar_dia_5" value="5"><label for="editar_dia_5">Qui</label></div>
                                <div class="radio-item"><input type="checkbox" id="editar_dia_6" value="6"><label for="editar_dia_6">Sex</label></div>
                                <div class="radio-item"><input type="checkbox" id="editar_dia_7" value="7"><label for="editar_dia_7">Sáb</label></div>
                                <div class="radio-item"><input type="checkbox" id="editar_dia_1" value="1"><label for="editar_dia_1">Dom</label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-container"><button type="submit" class="btn-salvar">Salvar Alterações</button></div>
            </form>
        </div>
    </div>
    <script src="./js/script.js"></script>
    <script src="./js/validacao.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- CONFIGURAÇÕES GLOBAIS ---
            const API_URL = 'http://127.0.0.1:8000/api';
            const TOKEN = localStorage.getItem('authToken');

            // --- PROTEÇÃO DE PÁGINA ---
            if (!TOKEN) {
                window.location.href = './index.php'; // Altere para o nome da sua tela de login
                return;
            }

            // --- LÓGICA DO MENU LATERAL (SIDEBAR) ---
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('conteudo-cadastro');
            if (menuBtn && sidebar && mainContent) {
                menuBtn.addEventListener('click', () => {
                    menuBtn.classList.toggle('active');
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('push'); // Use a classe correta se for diferente
                });
            }

            // --- LÓGICA DO BOTÃO DE LOGOUT ---
            const logoutButton = document.getElementById('logout-button');
            if (logoutButton) {
                logoutButton.addEventListener('click', async (event) => {
                    event.preventDefault();
                    const result = await Swal.fire({
                        title: 'Você tem certeza?',
                        text: "Sua sessão será encerrada com segurança.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, quero sair!',
                        cancelButtonText: 'Cancelar'
                    });

                    if (result.isConfirmed) {
                        try {
                            await fetch(`${API_URL}/logout`, {
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${TOKEN}`,
                                    'Accept': 'application/json',
                                }
                            });
                        } catch (error) {
                            console.error('Falha ao comunicar com a API de logout:', error);
                        } finally {
                            localStorage.removeItem('authToken');
                            localStorage.removeItem('user');
                            window.location.href = './index.php'; // Altere para o nome da sua tela de login
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>