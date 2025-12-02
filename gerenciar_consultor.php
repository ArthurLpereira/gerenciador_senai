<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/gerenciar_cadastro_consultor.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* --- Estilos do Dashboard existentes --- */
        .chart-bars {
            position: relative;
            overflow: visible !important;
        }

        .bar-group {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
        }

        .bar-label {
            position: absolute;
            bottom: -25px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            font-weight: bold;
        }

        .bar-chart {
            margin-bottom: 25px;
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
            <li>
                <a href="./home_consultor.php">
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
                    <div class="card-grafico-img" style="display: flex; justify-content: center; align-items: center;">

                        <svg viewBox="0 0 200 120" style="width: 100%; max-width: 220px;">
                            <path d="M 20 100 A 80 80 0 0 1 180 100"
                                fill="none"
                                stroke="#e0e0e0"
                                stroke-width="27"
                                stroke-linecap="round" />

                            <path id="gauge-progress"
                                d="M 20 100 A 80 80 0 0 1 180 100"
                                fill="none"
                                stroke="#cc0000"
                                stroke-width="20"
                                stroke-linecap="round"
                                stroke-dasharray="251.2"
                                stroke-dashoffset="251.2"
                                style="transition: stroke-dashoffset 1s ease-out;" />

                            <polygon id="gauge-needle"
                                points="35,100 100,95 100,105"
                                fill="#555"
                                style="transform-origin: 100px 100px; transition: transform 1s ease-out;">
                            </polygon>

                            <circle cx="100" cy="100" r="8" fill="#555" />
                        </svg>
                    </div>
                    <div class="card-texto-info">
                        <h3>Taxa de Ocupação:</h3>
                        <span class="percentual-grande" id="valor-taxa-ocupacao">...</span>
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
                                <div class="bar" style="height: 0%;" data-percent="0%"></div>
                                <span class="bar-label">Seg</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 0%;" data-percent="0%"></div>
                                <span class="bar-label">Ter</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 0%;" data-percent="0%"></div>
                                <span class="bar-label">Qua</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 0%;" data-percent="0%"></div>
                                <span class="bar-label">Qui</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 0%;" data-percent="0%"></div>
                                <span class="bar-label">Sex</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 0%;" data-percent="0%"></div>
                                <span class="bar-label">Sáb</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-red span-2">
                    <h3>Turmas Iniciadas:</h3>
                    <p class="numero-grande" id="contador-turmas-iniciadas">...</p>
                </div>

                <div class="card card-icon span-3">
                    <h3>Docentes em Atividade:</h3>
                    <div class="card-content">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                            class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path
                                d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                        </svg>
                        <p class="numero-grande" id="contador-docentes-ativos">...</p>
                    </div>
                </div>
            </div>
        </article>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* --- CONFIGURAÇÃO DA API --- */
            const API_BASE_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api/';
            const TOKEN = localStorage.getItem('authToken');

            /* --- REQUISIÇÃO 1: TURMAS INICIADAS --- */
            async function carregarTurmasIniciadas() {
                const contadorElement = document.getElementById('contador-turmas-iniciadas');
                try {
                    const response = await fetch(`${API_BASE_URL}turmas/turmas-iniciadas`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${TOKEN}`
                        }
                    });
                    if (!response.ok) throw new Error('Erro API Turmas');
                    const data = await response.json();
                    contadorElement.textContent = data.quantidade;
                } catch (error) {
                    console.error('Erro turmas:', error);
                    contadorElement.textContent = '-';
                }
            }

            /* --- REQUISIÇÃO 2: DOCENTES ATIVOS --- */
            async function carregarDocentesAtivos() {
                const contadorElement = document.getElementById('contador-docentes-ativos');
                try {
                    const response = await fetch(`${API_BASE_URL}colaboradores/colaboradores-ativos`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${TOKEN}`
                        }
                    });
                    if (!response.ok) throw new Error('Erro API Colaboradores');
                    const data = await response.json();
                    contadorElement.textContent = data.quantidade;
                } catch (error) {
                    console.error('Erro colaboradores:', error);
                    contadorElement.textContent = '-';
                }
            }

            /* --- REQUISIÇÃO 3: TAXA DE OCUPAÇÃO --- */
            async function carregarTaxaOcupacao() {
                const elementoTaxa = document.getElementById('valor-taxa-ocupacao');
                const gaugePath = document.getElementById('gauge-progress');
                const gaugeNeedle = document.getElementById('gauge-needle');

                try {
                    const response = await fetch(`${API_BASE_URL}ambientes/taxa-ocupacao`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${TOKEN}`
                        }
                    });

                    if (!response.ok) throw new Error('Erro API Taxa Ocupação');

                    const data = await response.json();

                    let valorFinal = 0;
                    if (data.taxa !== undefined) valorFinal = data.taxa;
                    else if (data.valor !== undefined) valorFinal = data.valor;
                    else if (data.taxa_ocupacao !== undefined) valorFinal = data.taxa_ocupacao;

                    if (valorFinal > 100) valorFinal = 100;
                    if (valorFinal < 0) valorFinal = 0;

                    elementoTaxa.textContent = `${valorFinal}%`;

                    const maxDash = 251.2;
                    const offset = maxDash - (maxDash * valorFinal / 100);
                    if (gaugePath) {
                        gaugePath.style.strokeDashoffset = offset;
                    }

                    const rotationAngle = (valorFinal / 100) * 180;
                    if (gaugeNeedle) {
                        gaugeNeedle.style.transform = `rotate(${rotationAngle}deg)`;
                    }

                } catch (error) {
                    console.error('Erro taxa ocupação:', error);
                    elementoTaxa.textContent = '-%';
                    if (gaugePath) gaugePath.style.strokeDashoffset = 251.2;
                    if (gaugeNeedle) gaugeNeedle.style.transform = `rotate(0deg)`;
                }
            }

            /* --- REQUISIÇÃO 4: GRÁFICO SEMANAL --- */
            async function carregarGraficoSemanal() {
                try {
                    const response = await fetch(`${API_BASE_URL}ambientes/semana`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${TOKEN}`
                        }
                    });

                    if (!response.ok) throw new Error('Erro API Gráfico Semanal');

                    const dados = await response.json();
                    const barras = document.querySelectorAll('.chart-bars .bar-group');

                    dados.forEach((item, index) => {
                        if (barras[index]) {
                            const barraFill = barras[index].querySelector('.bar');
                            if (barraFill) {
                                barraFill.style.height = `${item.percentual}%`;
                                barraFill.setAttribute('data-percent', `${item.percentual}%`);
                            }
                        }
                    });

                } catch (error) {
                    console.error('Erro ao carregar gráfico semanal:', error);
                }
            }

            // Executa as funções de carga de dados
            carregarTurmasIniciadas();
            carregarDocentesAtivos();
            carregarTaxaOcupacao();
            carregarGraficoSemanal();

            /* --- FUNÇÃO INTELIGENTE DE DOWNLOAD (PDF EM NOVA ABA) --- */
            async function gerarRelatorio(endpoint, tipo) {
                let janelaNova = null;

                // Se for PDF, abrimos a aba IMEDIATAMENTE para o navegador não bloquear
                if (tipo === 'pdf') {
                    janelaNova = window.open('', '_blank');
                    // Mensagem amigável enquanto carrega
                    if (janelaNova) {
                        janelaNova.document.write('<html><body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;"><h2>Gerando pré-visualização do PDF...</h2></body></html>');
                    }
                } else {
                    // Se for CSV, mostramos loading no SweetAlert
                    Swal.fire({
                        title: 'Baixando CSV...',
                        text: 'Aguarde um momento.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                }

                try {
                    const response = await fetch(endpoint, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${TOKEN}`,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Erro na requisição: ' + response.status);

                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);

                    if (tipo === 'pdf' && janelaNova) {
                        // Redireciona a aba branca para o PDF carregado
                        janelaNova.location.href = url;
                    } else {
                        // Download forçado para CSV
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'relatorio.csv';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        Swal.close();
                    }

                } catch (error) {
                    console.error('Erro no relatório:', error);
                    if (janelaNova) janelaNova.close(); // Fecha a aba se deu erro
                    Swal.fire('Erro', 'Não foi possível gerar o arquivo. Tente novamente.', 'error');
                }
            }

            /* --- OUTROS SCRIPTS --- */
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            if (menuBtn && sidebar) {
                menuBtn.addEventListener('click', () => {
                    menuBtn.classList.toggle('active');
                    sidebar.classList.toggle('active');
                });
            }

            const btnAbrirModal = document.getElementById('abrir-modal-relatorio');
            if (btnAbrirModal) {
                btnAbrirModal.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Gerar Relatórios',
                        html: `
                        <div style="display: flex; justify-content: space-around; padding: 20px;">
                            <a href="#" id="btn-gerar-pdf" style="text-decoration: none; text-align: center;">
                                <img src="./images/pdf.png" alt="PDF" style="width: 60px; cursor: pointer;">
                                <p style="margin-top: 5px; color: #333;">PDF</p>
                            </a>
                            <a href="#" id="btn-gerar-xls" style="text-decoration: none; text-align: center;">
                                <img src="./images/file.png" alt="XLS" style="width: 60px; cursor: pointer;">
                                <p style="margin-top: 5px; color: #333;">XLS</p>
                            </a>
                        </div>
                        `,
                        showConfirmButton: false,
                        showCancelButton: false,
                        showCloseButton: true,
                        didOpen: () => {
                            const btnPdf = document.getElementById('btn-gerar-pdf');
                            const btnXls = document.getElementById('btn-gerar-xls');

                            if (btnPdf) {
                                btnPdf.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    Swal.close(); // Fecha o modal
                                    // Chama a função passando 'pdf'
                                    gerarRelatorio('http://10.141.117.34:8024/arthur-pereira/api_sga/api/gerar-pdf', 'pdf');
                                });
                            }

                            if (btnXls) {
                                btnXls.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    Swal.close(); // Fecha o modal
                                    // Chama a função passando 'csv'
                                    gerarRelatorio('http://10.141.117.34:8024/arthur-pereira/api_sga/api/gerar-csv', 'csv');
                                });
                            }
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>