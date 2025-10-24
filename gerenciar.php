<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/home.css"> <!-- Ou o CSS apropriado -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Adicionado para garantir que o link de sair funcione bem */
        .sair a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
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
                <img src="./images/profile2.png" alt="">
                <h1>Gerenciar Cadastro</h1>
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
                <p style="color: gray;font-size: 23px;">Selecione uma das opções abaixo</p>
                <p style="color: gray;font-size: 23px;">para gerenciar os cadastros</p>
            </div>
        </section>
        <article class="container">
            <div class="cards" id="prim">
                <a href="./cursos.php">
                    <img src="./images/books-stack-of-three.png" alt="">
                    <span></span>
                    <h1>Cursos</h1>
                </a>
            </div>
            <div class="cards" id="seg">
                <a href="./docentes.php">
                    <img src="./images/multiple-users-silhouette.png" alt="">
                    <span></span>
                    <h1>Docentes</h1>
                </a>
            </div>
            <div class="cards" id="ter">
                <a href="./ambientes.php">
                    <img src="./images/ambiente.png" alt="">
                    <span></span>
                    <h1>Ambientes</h1>
                </a>
            </div>
        </article>
    </main>

    <!-- Chame o script universal no final da página -->
    <script src="./js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- CONFIGURAÇÕES GLOBAIS ---
            const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
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