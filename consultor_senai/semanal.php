<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/semanal.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
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
                <img src="./images/calendar (2).png" alt="Ícone de Engrenagem">
                <h1>Calendário Semanal</h1>
            </div>
            <div class="sair">
                <a href="logout.php" id="btn-sair"><img src="./images/logout (2).png" alt="Ícone de sair"> Sair</a>
            </div>
        </section>

        <div class="content-body">
            <div class="filter-container">
                <img src="./images/filter.png" alt="Ícone de Filtro" class="filter-icon">
                <label>Filtrar</label>
                <div class="search-box">
                    <input type="text" placeholder="Filtrar...">
                    <button type="submit" class="search-button">
                        <img src="./images/pesquisar.png" alt="Ícone de Lupa">
                    </button>
                </div>
            </div>
            <div class="management-controls">
                <label class="toggle-switch" title="Mudar para visualização Mensal">
                    <input type="checkbox" id="view-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h2 id="week-range-title"></h2> 
                <button class="nav-arrow" id="prev-week-btn">&lt;</button>
                <button class="nav-arrow" id="next-week-btn">&gt;</button>
            </div>
            <table class="calendar-grid">
                <thead>
                    <tr id="calendar-header-days">
                        <th>Ambientes</th>
                        </tr>
                </thead>
                <tbody id="calendar-body">
                    </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- LÓGICA DO BOTÃO DE ALTERNÂNCIA (VIEW TOGGLE) ---
            const viewToggle = document.getElementById('view-toggle');
            if (viewToggle) {
                // Na página semanal, o botão começa MARcado
                viewToggle.checked = true;

                viewToggle.addEventListener('change', function() {
                    // Se for DESmarcado, redireciona para a página mensal
                    if (!this.checked) {
                        window.location.href = 'programacao.php';
                    }
                });
            }

            // --- CÓDIGO DO BOTÃO DE SAIR (LOGOUT) ---
            const btnSair = document.getElementById('btn-sair');
            if (btnSair) {
                btnSair.addEventListener('click', function(event) {
                    event.preventDefault();
                    const logoutUrl = this.href;
                    Swal.fire({
                        title: 'Você tem certeza?',
                        text: "Você será desconectado do sistema.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sim, quero sair!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = logoutUrl;
                        }
                    });
                });
            }

            // --- CÓDIGO PARA O MENU SIDEBAR ---
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('conteudo');
            if (menuBtn && sidebar && mainContent) {
                menuBtn.addEventListener('click', () => {
                    menuBtn.classList.toggle('active');
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('push');
                });
            }

            // --- CÓDIGO PARA O POPUP "GERENCIAR DIAS LETIVOS" ---
            const manageDaysBtn = document.querySelector('.manage-days-btn');
            if (manageDaysBtn) {
                manageDaysBtn.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Gerenciar Dias Letivos',
                        html: `
                        <div class="swal-form-container">
                            <div class="swal-form-group">
                                <label for="swal-data">Data</label>
                                <input type="date" id="swal-data" class="swal2-input">
                            </div>
                            <div class="swal-form-group">
                                <label>Tipo de Dia</label>
                                <div class="swal-radio-group">
                                    <input type="radio" id="nao-letivo" name="tipo_dia" value="nao-letivo">
                                    <label for="nao-letivo">Não Letivo</label>
                                </div>
                            </div>
                            <div class="swal-form-group">
                                <label for="swal-descricao">Descrição</label>
                                <input type="text" id="swal-descricao" class="swal2-input">
                            </div>
                            <div class="swal-form-group">
                                <label for="swal-classificacao">Classificação do Feriado</label>
                                <input type="text" id="swal-classificacao" class="swal2-input">
                            </div>
                        </div>`,
                        showCancelButton: true,
                        confirmButtonText: 'Salvar Alterações',
                        cancelButtonText: 'Cancelar',
                        showCloseButton: true,
                        customClass: {
                            popup: 'custom-swal-popup',
                            title: 'custom-swal-title',
                            closeButton: 'custom-swal-close-button',
                            confirmButton: 'swal-confirm-btn',
                            cancelButton: 'swal-cancel-btn',
                            htmlContainer: 'custom-swal-html-container'
                        },
                        focusConfirm: false
                    });
                });
            }
            
            // ==========================================================
            // --- LÓGICA ADAPTADA PARA O CALENDÁRIO SEMANAL ---
            // ==========================================================
            const titleElement = document.getElementById('week-range-title');
            const headerDaysRow = document.getElementById('calendar-header-days');
            const calendarBody = document.getElementById('calendar-body');
            const prevWeekBtn = document.getElementById('prev-week-btn');
            const nextWeekBtn = document.getElementById('next-week-btn');

            const ambientes = ["Lab. 202", "Lab. 203"];
            let dataAtual = new Date();

            // --- FUNÇÃO ADICIONADA ---
            /**
             * Formata um objeto Date para o formato "DD/MM".
             * @param {Date} data O objeto Date a ser formatado.
             * @returns {string} A data formatada.
             */
            function formatarDataTitulo(data) {
                const dia = String(data.getDate()).padStart(2, '0');
                const mes = String(data.getMonth() + 1).padStart(2, '0'); // getMonth() é 0-indexado
                return `${dia}/${mes}`;
            }
            // --- FIM DA FUNÇÃO ADICIONADA ---

            function criarSlotAgendamento() {
                return `
                <div class="schedule-slot">
                    <p><span class="time-initial">M</span> Framework</p>
                    <p><span class="time-initial">T</span> Eletrônica</p>
                    <p><span class="time-initial">N</span> Google</p>
                    <button class="ver-mais-btn">Ver Mais</button>
                </div>`;
            }

            function gerarCalendarioSemanal(dataBase) {
                if (!titleElement || !headerDaysRow || !calendarBody) return;

                // Limpa o conteúdo anterior
                headerDaysRow.innerHTML = '<th>Ambientes</th>';
                calendarBody.innerHTML = '';

                // Calcula o início da semana (Domingo)
                const inicioSemana = new Date(dataBase);
                inicioSemana.setDate(dataBase.getDate() - dataBase.getDay());

                
                // *** BLOCO DE CÓDIGO DO TÍTULO CORRIGIDO ***
                // Calcula o fim da semana (Sábado)
                const fimSemana = new Date(inicioSemana);
                fimSemana.setDate(inicioSemana.getDate() + 6);

                // Formata as datas de início e fim
                const dataFormatadaInicio = formatarDataTitulo(inicioSemana);
                const dataFormatadaFim = formatarDataTitulo(fimSemana);

                // Define o título no formato "Semana - DD/MM a DD/MM"
                titleElement.textContent = `Semana - ${dataFormatadaInicio} a ${dataFormatadaFim}`;
                // *** FIM DA CORREÇÃO ***


                const diasDaSemanaNomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
                const diasDaSemana = [];

                // Gera os cabeçalhos dos 7 dias da semana
                for (let i = 0; i < 7; i++) {
                    const diaCorrente = new Date(inicioSemana);
                    diaCorrente.setDate(inicioSemana.getDate() + i);
                    diasDaSemana.push(diaCorrente);

                    const th = document.createElement('th');
                    th.innerHTML = `${String(diaCorrente.getDate()).padStart(2, '0')}/${String(diaCorrente.getMonth() + 1).padStart(2, '0')} (${diasDaSemanaNomes[i]})`;
                    headerDaysRow.appendChild(th);
                }
                
                // Gera as linhas para cada ambiente
                ambientes.forEach(ambiente => {
                    const tr = document.createElement('tr');
                    const thAmbiente = document.createElement('th');
                    thAmbiente.className = 'room-name';
                    thAmbiente.textContent = ambiente;
                    tr.appendChild(thAmbiente);

                    diasDaSemana.forEach(dia => {
                        const td = document.createElement('td');
                        const diaDaSemanaNum = dia.getDay();

                        // Se for Domingo (dia 0), marca como não letivo
                        if (diaDaSemanaNum === 0) {
                            td.className = 'dia-nao-letivo';
                            td.textContent = 'Dia Não Letivo';
                        } else {
                            td.innerHTML = criarSlotAgendamento();
                        }
                        tr.appendChild(td);
                    });

                    calendarBody.appendChild(tr);
                });

                adicionarListenersVerMais();
            }

            function adicionarListenersVerMais() {
                const verMaisBotoes = document.querySelectorAll('.ver-mais-btn');
                verMaisBotoes.forEach(botao => {
                    botao.addEventListener('click', (event) => {
                        const cellIndex = event.target.closest('td').cellIndex;
                        const dataTitulo = headerDaysRow.querySelectorAll('th')[cellIndex].textContent;
                        
                        Swal.fire({
                            html: `
                            <div class="info-modal-content">
                                <div class="info-modal-columns">
                                    <div class="info-modal-column">
                                        <div class="info-modal-section">
                                            <h3>Curso</h3>
                                            <p><b>Nome:</b> Desenvolvimento de Sistemas</p>
                                        </div>
                                        <div class="info-modal-section">
                                            <h3>Turma</h3>
                                            <p><b>Hora Início:</b> 08:00</p>
                                            <p><b>Data Início:</b> 26/01/2024</p>
                                        </div>
                                    </div>
                                    <div class="info-modal-column">
                                        <div class="info-modal-section">
                                            <h3>Docente</h3>
                                            <p><b>Nome:</b> Hermilo</p>
                                        </div>
                                        <div class="info-modal-section">
                                            <h3>Ambiente</h3>
                                            <p><b>Nome:</b> Sala de Aula</p>
                                        </div>
                                    </div>
                                </div>
                            </div>`,
                            title: `Data: ${dataTitulo}`,
                            showConfirmButton: false,
                            showCloseButton: true,
                            customClass: {
                                popup: 'custom-swal-popup',
                                title: 'custom-swal-title',
                                closeButton: 'custom-swal-close-button',
                                htmlContainer: 'custom-swal-html-container'
                            }
                        });
                    });
                });
            }

            // --- LISTENERS PARA NAVEGAÇÃO SEMANAL (COMO NO SEU CÓDIGO ORIGINAL) ---
            if (prevWeekBtn) {
                prevWeekBtn.addEventListener('click', () => {
                    dataAtual.setDate(dataAtual.getDate() - 7);
                    gerarCalendarioSemanal(dataAtual);
                });
            }

            if (nextWeekBtn) {
                nextWeekBtn.addEventListener('click', () => {
                    dataAtual.setDate(dataAtual.getDate() + 7);
                    gerarCalendarioSemanal(dataAtual);
                });
            }
            gerarCalendarioSemanal(dataAtual);
        });
    </script>
</body>
</html>