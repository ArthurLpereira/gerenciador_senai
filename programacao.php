<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/programacao.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">

    <style>
        .calendar-header {
            display: flex;
            justify-content: start;
            gap: 500px;
            align-items: center;
            width: 352.9vw !important;
        }

        /* --- CORREÇÃO ADICIONADA AQUI --- */
        .calendar-header h2 {
            white-space: nowrap;
        }

        .nav-buttons {
            display: flex;
        }

        .nav-arrow {
            background: none;
            border: none;
            color: white;
            font-size: 2em;
            cursor: pointer;
            padding: 0 5px;
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
                <h1>Calendário Mensal</h1>
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

                <label class="toggle-switch" title="Mudar para visualização Semanal">
                    <input type="checkbox" id="view-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h2 id="month-year-title"></h2>

                <div class="nav-buttons">
                    <button class="nav-arrow" id="prev-month-btn">&lt;</button>
                    <button class="nav-arrow" id="next-month-btn">&gt;</button>
                </div>
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

            const viewToggle = document.getElementById('view-toggle');
            if (viewToggle) {
                viewToggle.checked = false;

                viewToggle.addEventListener('change', function() {
                    if (this.checked) {
                        window.location.href = 'semanal.php'; // Redireciona para a semanal
                    }
                });
            }
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
            // --- CÓDIGO PARA A GERAÇÃO DINÂMICA DO CALENDÁRIO ---
            const titleElement = document.getElementById('month-year-title');
            const headerDaysRow = document.getElementById('calendar-header-days');
            const calendarBody = document.getElementById('calendar-body');
            const ambientes = ["Lab. 202", "Lab. 203"];

            // --- LÓGICA DE NAVEGAÇÃO ADICIONADA ---
            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');
            let dataAtual = new Date(); // Guarda a data atual do calendário
            // --- FIM DA LÓGICA DE NAVEGAÇÃO ---


            function criarSlotAgendamento() {
                return `
                <div class="schedule-slot">
                    <p><span class="time-initial">M</span> Framework</p>
                    <p><span class="time-initial">T</span> Eletrônica</p>
                    <p><span class="time-initial">N</span> Google</p>
                    <button class="ver-mais-btn">Ver Mais</button>
                </div>
                `;
            }

            function gerarCalendario(ano, mes) {
                if (!titleElement || !headerDaysRow || !calendarBody) return;

                headerDaysRow.innerHTML = '<th>Ambientes</th>';
                calendarBody.innerHTML = '';

                const data = new Date(ano, mes);
                const nomeMes = data.toLocaleString('pt-BR', {
                    month: 'long'
                });
                const anoCompleto = data.getFullYear();

                titleElement.textContent = `${nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1)} de ${anoCompleto}`;

                const diasNoMes = new Date(ano, mes + 1, 0).getDate();
                const diasDaSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

                for (let dia = 1; dia <= diasNoMes; dia++) {
                    const dataAtual = new Date(ano, mes, dia);
                    const diaSemana = diasDaSemana[dataAtual.getDay()];
                    const th = document.createElement('th');
                    th.innerHTML = `${String(dia).padStart(2, '0')}/${String(mes + 1).padStart(2, '0')} (${diaSemana})`;
                    headerDaysRow.appendChild(th);
                }

                ambientes.forEach(ambiente => {
                    const tr = document.createElement('tr');
                    const thAmbiente = document.createElement('th');
                    thAmbiente.className = 'room-name';
                    thAmbiente.textContent = ambiente;
                    tr.appendChild(thAmbiente);

                    for (let dia = 1; dia <= diasNoMes; dia++) {
                        const td = document.createElement('td');
                        const dataAtual = new Date(ano, mes, dia);
                        const diaDaSemana = dataAtual.getDay();

                        if (diaDaSemana === 0) {
                            td.className = 'dia-nao-letivo';
                            td.textContent = 'Dia Não Letivo';
                        } else {
                            td.innerHTML = criarSlotAgendamento();
                        }
                        tr.appendChild(td);
                    }
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

            // --- LISTENERS DOS BOTÕES ADICIONADOS ---
            if (prevMonthBtn) {
                prevMonthBtn.addEventListener('click', () => {
                    dataAtual.setMonth(dataAtual.getMonth() - 1); // Volta 1 mês
                    gerarCalendario(dataAtual.getFullYear(), dataAtual.getMonth());
                });
            }

            if (nextMonthBtn) {
                nextMonthBtn.addEventListener('click', () => {
                    dataAtual.setMonth(dataAtual.getMonth() + 1); // Avança 1 mês
                    gerarCalendario(dataAtual.getFullYear(), dataAtual.getMonth());
                });
            }
            // --- FIM DOS LISTENERS ---

            // Inicializa o calendário com a data atual
            gerarCalendario(dataAtual.getFullYear(), dataAtual.getMonth());
        });
    </script>
</body>

</html>