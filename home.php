<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Adicionado para garantir que o link de sair funcione bem */
        .sair a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            /* Herda a cor do texto do pai */
            gap: 8px;
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
        <!-- Seu menu lateral aqui -->
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
                <img src="./images/calendar (2).png" alt="">
                <h1>Calendário</h1>
            </div>
            <!-- ÁREA DO LOGOUT CORRIGIDA -->
            <div class="sair">
                <!-- A imagem e o texto agora estão dentro de um único link com ID -->
                <a href="#" id="logout-button">
                    <img src="./images/logout (2).png" alt="Ícone de Sair">
                    <span>Sair</span>
                </a>
            </div>
        </section>
        <section class="options">
            <div class="alinha">
                <!-- Adicionado um ID para a mensagem de boas-vindas -->
                <p id="welcome-message" style="font-size: 22px;">Seja bem vindo(a) ao</p>
                <h1>Sistema de Gerenciamento de Alocação</h1>
            </div>
            <p style="color: gray;font-size: 20px;">Selecione qual calendário deseja visualizar:</p>
        </section>
        <article class="container">
            <div class="cards" id="p"><a href="./semestral.php" id="card-semestral"><img src="./images/graduation.png" alt=""><span></span>
                    <h1>Semestral</h1>
                </a></div>
            <div class="cards" id="s"><a href="./mensal.php"><img src="./images/graduation.png" alt=""><span></span>
                    <h1>Mensal</h1>
                </a></div>
            <div class="cards" id="t"><a href="./semanal.php"><img src="./images/graduation.png" alt=""><span></span>
                    <h1>Semanal</h1>
                </a></div>
        </article>
    </main>

    <script src="./js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- CONFIGURAÇÕES GLOBAIS ---
            const API_URL = 'http://127.0.0.1:8000/api';

            const TOKEN = localStorage.getItem('authToken');
            const USER_STRING = localStorage.getItem('user');


            if (!TOKEN || !USER_STRING) {
                window.location.href = './index.php';
                return;
            }

            const user = JSON.parse(USER_STRING);
            const welcomeMessage = document.getElementById('welcome-message');
            if (welcomeMessage) {
                welcomeMessage.textContent = `Seja bem-vindo(a), ${user.nome_colaborador}!`;
            }

            const logoutButton = document.getElementById('logout-button');
            if (logoutButton) {
                logoutButton.addEventListener('click', async (event) => {
                    event.preventDefault(); // Impede que o link mude a URL

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
                            window.location.href = './index.php';
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>