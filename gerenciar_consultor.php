<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/gerenciar_cadastro_consultor.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                    <img src="./images/multiple-users-silhouette.png" alt="Ícone de Painel">
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
                <img src="./images/dash2.png" alt="">
                <h1>Dashboard</h1>
            </div>
            <div style="cursor: pointer;" class="opcoes-menu" id="abrir-modal-relatorio">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
            </div>
        </section>

        <article class="container">
            <div class="dashboard-grid">

                <div class="card span-3 card-taxa-ocupacao">
                    <div class="card-grafico-img">
                        <img src="./images/grafic.png" alt="Gráfico de Ocupação">
                    </div>
                    <div class="card-texto-info">
                        <h3>Taxa de Ocupação:</h3>
                        <span class="percentual-grande">70%</span>
                    </div>
                </div>

                <div class="card span-2">
                    <div class="card-header">
                        <h3>Ocupação da Próxima Semana</h3>
                    </div>
                    <div class="bar-chart">
                        <div class="chart-y-axis">
                            <span>100%</span>
                            <span>80%</span>
                            <span>60%</span>
                            <span>40%</span>
                            <span>20%</span>
                            <span>0%</span>
                        </div>

                        <div class="chart-bars">

                            <div class="chart-grid-lines">
                                <div class="grid-line"></div>
                                <div class="grid-line"></div>
                                <div class="grid-line"></div>
                                <div class="grid-line"></div>
                                <div class="grid-line"></div>
                                <div class="grid-line"></div>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 72%;" data-percent="72%"></div>
                                <span class="bar-label">Seg</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 88%;" data-percent="88%"></div> <span class="bar-label">Ter</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 50%;" data-percent="50%"></div> <span class="bar-label">Qua</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 55%;" data-percent="55%"></div>
                                <span class="bar-label">Qui</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 62%;" data-percent="62%"></div>
                                <span class="bar-label">Sex</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 78%;" data-percent="78%"></div>
                                <span class="bar-label">Sáb</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-red span-2">
                    <h3>Turmas Iniciadas:</h3>
                    <p class="numero-grande">12</p>
                </div>

                <div class="card card-icon span-3">
                    <h3>Docentes em Atividade:</h3>
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                            class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path
                                d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                        </svg>
                        <p class="numero-grande">50</p>
                    </div>
                </div>
            </div>
        </article>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* --- MENU SIDEBAR --- */
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            if (menuBtn && sidebar) {
                menuBtn.addEventListener('click', () => {
                    menuBtn.classList.toggle('active');
                    sidebar.classList.toggle('active');
                });
            }

            /* --- MODAL SWEETALERT DE RELATÓRIO PADRONIZADO --- */
            const btnAbrirModal = document.getElementById('abrir-modal-relatorio');
            if (btnAbrirModal) {
                btnAbrirModal.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Gerar Relatórios',
                        html: `
                            <div class="swal-download-options">
                                <a href="#" id="btn-gerar-pdf" class="swal-download-button pdf" aria-label="Gerar PDF">
                                    <img src="./images/pdf.png" alt="PDF">
                                </a>
                                <a href="#" id="btn-gerar-xls" class="swal-download-button xls" aria-label="Gerar XLS">
                                    <img src="./images/file.png" alt="XLS">
                                </a>
                            </div>
                        `,
                        showCloseButton: true,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'swal2-popup modal-relatorio',
                            title: 'swal-title-custom',
                            htmlContainer: 'swal-download-options',
                            closeButton: 'modal-relatorio-close-btn'
                        },
                        didOpen: () => {
                            const btnPdf = document.getElementById('btn-gerar-pdf');
                            const btnXls = document.getElementById('btn-gerar-xls');
                            if (btnPdf) btnPdf.addEventListener('click', e => {
                                e.preventDefault();
                                console.log('Gerar PDF clicado!');
                                Swal.close();
                            });
                            if (btnXls) btnXls.addEventListener('click', e => {
                                e.preventDefault();
                                console.log('Gerar XLS clicado!');
                                Swal.close();
                            });
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>