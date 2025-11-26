<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/relatorios.css">

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
                <img src="./images/relat.png" alt="">
                <h1>Gerar Relatórios</h1>
            </div>

            <div class="opcoes-menu" id="abrir-modal-relatorio">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
            </div>
        </section>

        <section class="dashboard">
            <div class="card-grafico">
                <h2>Ocupação por Ambiente</h2>
                <div class="container-grafico">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            </div>

            <div class="coluna-info">
                <div class="card-info">
                    <h3>Ambientes Disponíveis</h3>
                    <p class="numero-grande" id="ambientes-disponiveis-numero">...</p>
                    <div class="barra-progresso">
                        <div class="progresso" id="ambientes-disponiveis-barra" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="card-info card-destaque">
                    <h3>Taxa de Ocupação</h3>
                    <div class="info-com-icone">
                        <p class="numero-grande" id="taxa-ocupacao-valor">...</p>
                        <img src="./images/dashtime.png" alt="Ícone de medidor">
                    </div>
                </div>
                <div class="card-info">
                    <h3>Colaboradores Ativos</h3>
                    <div class="info-com-icone">
                        <p class="numero-grande" id="colaboradores-ativos-valor">...</p>
                        <img src="./images/dashtwo.png" alt="Ícone de calendário">
                    </div>
                </div>
            </div>
        </section>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./js/relatorios.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const abrirModalBtn = document.getElementById('abrir-modal-relatorio');

            abrirModalBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Gerar Relatórios',
                    html: `
                <div class="modal-opcoes-container">
                    <a href="relatorios.php?export=pdf" id="btn-gerar-pdf" class="modal-opcao-btn modal-btn-pdf">
                        <img src="./images/pdf.png" alt="Ícone PDF" style="width: 50px; height: auto; margin-bottom: 5px;">
                        <span>PDF</span>
                    </a>
                    <a href="relatorios.php?export=xls" id="btn-gerar-xls" class="modal-opcao-btn modal-btn-xls">
                        <img src="./images/file.png" alt="Ícone XLS" style="width: 45px; height: auto; margin-bottom: 5px;">
                        <span>XLS</span>
                    </a>
                </div>
            `,
                    showConfirmButton: false,
                    showCancelButton: false,
                    showCloseButton: true,
                    width: '450px',
                    customClass: {
                        popup: 'modal-relatorios-custom'
                    },
                    didOpen: () => {
                        const btnPdf = document.getElementById('btn-gerar-pdf');
                        const btnXls = document.getElementById('btn-gerar-xls');

                        btnPdf.addEventListener('click', function(event) {
                            event.preventDefault();
                            const url = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api/gerar-pdf';

                            // *** MUDANÇA AQUI ***
                            // Abre a URL em uma nova guia (o seu "_blank")
                            window.open(url, '_blank');

                            // Fecha o modal de opções
                            Swal.close();
                        });

                        btnXls.addEventListener('click', function(event) {
                            event.preventDefault();
                            const url = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api/gerar-csv';

                            // Ação direta (na mesma aba, forçando o download)
                            window.location.href = url;
                            // Fecha o modal de opções
                            Swal.close();
                        });
                    }
                });
            });

        });
    </script>
</body>

</html>