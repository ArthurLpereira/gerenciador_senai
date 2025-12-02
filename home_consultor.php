<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultor-SENAI</title>
    <link rel="stylesheet" href="./css/home.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script>
        (function() {
            const token = localStorage.getItem('authToken');
            const user = localStorage.getItem('user');
            // Se não tiver token, manda para o login (ajuste 'index.php' se for .html)
            if (!token || !user) {
                window.location.href = 'index.php';
            }
        })();
    </script>
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
                <a href="./home_consultor.php">
                    <img src="./images/multiple-users-silhouette.png" alt="Ícone de perfil">
                    <span class="menu-texto">Painel de<br>Turmas</span>
                </a>
            </li>
            <li>
                <a href="./user.php">
                    <img src="./images/account.png" alt="Ícone de perfil">
                    <span class="menu-texto">Meu Perfil</span>
                </a>
            </li>
        </ul>
    </nav>

    <main id="conteudo">
        <section class="titulo">
            <div class="calendar">
                <img src="./images/consultor.png" alt="">
                <h1>Painel de Turmas</h1>
            </div>
            <div class="sair">
                <a href="#" id="btn-sair">
                    <img src="./images/logout (2).png" alt="Sair" style="vertical-align: middle;">
                    Sair
                </a>
            </div>
        </section>
        <section class="options">
            <div class="alinha">
                <p style="font-size: 22px;">Seja bem vindo (a) ao</p>
                <h1>Sistema de Gerenciamento de Alocação</h1>
            </div>
            <p style="color: gray;font-size: 20px;">Selecione como deseja acompanhar as turmas:</p>
        </section>
        <article class="container">
            <div class="cards" id="prim">
                <a href="gerenciar_consultor.php">
                    <img src="./images/dash.png" alt="">
                    <span></span>
                    <h1>Dashboard</h1>
                </a>
            </div>
            <div class="cards" id="seg">
                <a href="./programacao.php">
                    <img src="./images/calendario.png" alt="">
                    <span></span>
                    <h1>Programação</h1>
                </a>
            </div>

        </article>
    </main>

    <script src="./js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- CONFIGURAÇÃO DA API ---
            const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
            const TOKEN = localStorage.getItem('authToken');

            // --- LÓGICA DO BOTÃO SAIR (LOGOUT) ---
            const btnSair = document.getElementById('btn-sair');

            if (btnSair) {
                btnSair.addEventListener('click', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Você tem certeza?',
                        text: "Você será desconectado do sistema.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sim, quero sair!',
                        cancelButtonText: 'Cancelar'
                    }).then(async (result) => {
                        if (result.isConfirmed) {

                            // Feedback visual
                            Swal.fire({
                                title: 'Saindo...',
                                text: 'Encerrando sessão.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            try {
                                // Tenta fazer o logout na API
                                if (TOKEN) {
                                    await fetch(`${API_URL}/logout`, {
                                        method: 'POST',
                                        headers: {
                                            'Authorization': `Bearer ${TOKEN}`,
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json'
                                        }
                                    });
                                }
                            } catch (error) {
                                console.error("Erro na comunicação com API de logout:", error);
                            } finally {
                                // Limpa o token e redireciona de qualquer forma
                                localStorage.removeItem('authToken');
                                localStorage.removeItem('user');
                                window.location.href = 'index.php'; // Confirma se é .php ou .html
                            }
                        }
                    });
                });
            }

            // --- SCRIPT DO CARD SEMESTRAL (Mantido mas inativo até criares o HTML) ---
            const cardSemestral = document.getElementById('card-semestral');
            if (cardSemestral) {
                // ... lógica do modal ...
                // Ocultada para economizar espaço, já que o elemento não existe no HTML
            }
        });
    </script>
</body>

</html>