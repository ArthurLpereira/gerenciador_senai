<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/relatorios.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* CSS para o Modal de Download */
        .modal-opcoes-container {
            display: flex;
            justify-content: center;
            gap: 60px;
            padding: 10px;
            height: 170px;
        }

        .modal-opcao-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s;
            font-family: Arial, sans-serif;
            cursor: pointer;
        }

        .modal-opcao-btn:hover {
            transform: scale(1.05);
        }

        .modal-btn-pdf {
            background-color: #e74c3c;
        }

        .modal-btn-xls {
            background-color: #27ae60;
        }

        .modal-opcao-btn img {
            width: 65px;
            height: auto;
            margin-bottom: 5px;
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

            <div class="opcoes-menu" id="abrir-modal-relatorio" style="cursor: pointer;">
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

            // --- 1. CONFIGURAÇÕES (API e TOKEN) ---
            const API_BASE_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
            const TOKEN = localStorage.getItem('authToken');

            if (!TOKEN) {
                window.location.href = './index.php'; // Redireciona se não tiver logado
                return;
            }

            const AUTH_HEADERS = {
                'Authorization': `Bearer ${TOKEN}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };

            // --- 2. MENU LATERAL ---
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            const conteudo = document.getElementById('conteudo');

            if (menuBtn && sidebar && conteudo) {
                menuBtn.addEventListener('click', () => {
                    menuBtn.classList.toggle('active');
                    sidebar.classList.toggle('active');
                    conteudo.classList.toggle('push');
                });
            }

            // --- 3. FUNÇÃO PARA DOWNLOAD SEGURO (COM TOKEN) ---
            async function downloadArquivoSeguro(endpoint, nomeArquivo) {
                let janelaNova = null;

                // Para PDF, tentamos abrir em nova aba para visualização
                if (nomeArquivo.endsWith('.pdf')) {
                    janelaNova = window.open('', '_blank');
                    if (janelaNova) {
                        janelaNova.document.write('<html><body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;"><h2>Gerando relatório...</h2></body></html>');
                    }
                } else {
                    Swal.fire({
                        title: 'Baixando...',
                        text: 'Aguarde um momento.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                }

                try {
                    // Fetch enviando o TOKEN no Header
                    const response = await fetch(endpoint, {
                        method: 'GET',
                        headers: AUTH_HEADERS
                    });

                    if (!response.ok) throw new Error('Erro na requisição: ' + response.status);

                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);

                    if (nomeArquivo.endsWith('.pdf') && janelaNova) {
                        janelaNova.location.href = url; // Exibe PDF na nova aba
                    } else {
                        // Força download (CSV/XLS)
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = nomeArquivo;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        Swal.close();
                    }

                } catch (error) {
                    console.error('Erro no download:', error);
                    if (janelaNova) janelaNova.close();
                    Swal.fire('Erro', 'Não foi possível baixar o arquivo. Verifique sua conexão.', 'error');
                }
            }

            // --- 4. LÓGICA DO MODAL ---
            const abrirModalBtn = document.getElementById('abrir-modal-relatorio');

            if (abrirModalBtn) {
                abrirModalBtn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Gerar Relatórios',
                        html: `
                        <div class="modal-opcoes-container">
                            <div id="btn-gerar-pdf" class="modal-opcao-btn modal-btn-pdf">
                                <img src="./images/pdf.png" alt="PDF">
                                <span>PDF</span>
                            </div>
                            <div id="btn-gerar-xls" class="modal-opcao-btn modal-btn-xls">
                                <img src="./images/file.png" alt="XLS">
                                <span>XLS</span>
                            </div>
                        </div>
                        `,
                        showConfirmButton: false,
                        showCancelButton: false,
                        showCloseButton: true,
                        width: '500px',
                        customClass: {
                            popup: 'modal-relatorios-custom'
                        },
                        didOpen: () => {
                            // Botão PDF
                            document.getElementById('btn-gerar-pdf').addEventListener('click', () => {
                                Swal.close();
                                // Chama função segura passando a URL
                                downloadArquivoSeguro(`${API_BASE_URL}/gerar-pdf`, 'relatorio_geral.pdf');
                            });

                            // Botão XLS/CSV
                            document.getElementById('btn-gerar-xls').addEventListener('click', () => {
                                Swal.close();
                                // Chama função segura passando a URL
                                downloadArquivoSeguro(`${API_BASE_URL}/gerar-csv`, 'relatorio_geral.csv');
                            });
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>