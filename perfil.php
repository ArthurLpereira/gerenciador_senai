<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - SGA SENAI</title>
    <link rel="stylesheet" href="./css/gerenciar_cadastro.css">
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
                <img src="./images/perfil.png" alt="icone_person">
                <h1>Meu Perfil</h1>
            </div>
            <div class="sair">
                <a href="#" id="logout-button">
                    <img src="./images/logout (2).png" alt="Ícone de Sair">
                    <span>Sair</span>
                </a>
            </div>
        </section>

        <div class="docentes">
            <h2>Dados Pessoais</h2>
            <hr>
        </div>

        <div class="icone_perfil">
            <div class="picture">
                <div class="foto">
                    <img src="./images/foto_perfil.png" alt="Foto do Perfil">
                </div>
                <h2 id="user-name">Carregando...</h2>
                <p id="user-email-display">...</p> <!-- Alterado ID para evitar conflito -->
            </div>

            <div class="informacoes">
                <div class="des">
                    <!-- ALTERAÇÃO 1: 'Matrícula' virou 'Email' -->
                    <p><strong>Email:</strong> <span id="user-email">...</span></p>
                    <p><strong>Especialidades:</strong> <span id="user-specialty">...</span></p>
                    <!-- ALTERAÇÃO 2: Adicionado campo para a Cor -->
                    <p><strong>Cor:</strong> <span id="user-color">...</span></p>
                    <p><strong>Data de Cadastro:</strong> <span id="user-created">...</span></p>
                </div>
            </div>
        </div>
    </main>

    <script src="./js/script.js"></script>
    <script src="./js/perfil.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- CONFIGURAÇÕES GLOBAIS ---
            const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';

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