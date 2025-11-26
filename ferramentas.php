<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferramentas de Gestão - SGA SENAI</title>
    <link rel="stylesheet" href="./css/ferramentas.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Estilos adicionais para garantir o funcionamento do logout e dropdown */
        .sair a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            gap: 8px;
        }

        .action-dropdown .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .action-dropdown.open .dropdown-content {
            display: block;
        }

        .action-dropdown .dropdown-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
        }

        .action-dropdown .dropdown-content button:hover {
            background-color: #f1f1f1;
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
                <img src="./images/ferramenta_gestao.png" alt="Ícone de Engrenagem">
                <h1>Ferramentas de Gestão</h1>
            </div>
            <div class="sair">
                <a href="#" id="logout-button">
                    <img src="./images/logout (2).png" alt="Ícone de sair">
                    <span>Sair</span>
                </a>
            </div>
        </section>

        <div class="content-body">
            <h1 class="subtitle">Usuários</h1>

            <div class="filter-container">
                <img src="./images/filter.png" alt="Ícone de Filtro" class="filter-icon">
                <label>Filtrar</label>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Filtrar por nome ou email...">
                    <button type="button" class="search-button">
                        <img src="./images/pesquisar.png" alt="Ícone de Lupa">
                    </button>
                </div>
            </div>

            <div class="user-table-container">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nome do Usuário</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <!-- As linhas da tabela serão inseridas aqui pelo JavaScript -->
                        <tr>
                            <td colspan="4" style="text-align:center;">Carregando usuários...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="./js/script.js"></script> <!-- Script Geral (menu, logout) -->
    <script src="./js/ferramentas.js"></script> <!-- Script Específico desta página -->
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