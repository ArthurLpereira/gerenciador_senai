<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/mensal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">

    <style>
        .calendar-header {
            display: flex;
            justify-content: start;
            gap: 500px;
            align-items: center;
            width: 352.9vw !important;
        }

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

        /* Estilos do modal dinâmico (do semanal.html) */
        .info-modal-turma-section {
            text-align: left;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-modal-turma-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-modal-turma-section h4 {
            color: #d33;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .info-modal-turma-section p {
            margin: 2px 0;
            font-size: 0.95em;
        }

        .swal2-html-container {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px !important;
            margin-bottom: 0 !important;
        }

        /* Classe CSS para estilizar a célula de feriado (igual ao domingo se quiser) */
        .dia-nao-letivo {
            background-color: #f0f0f0;
            /* Cinza claro ou use #ffcccc para vermelho claro */
            color: #d33;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 0.9em;
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
                <button class="manage-days-btn">
                    <img src="./images/gear.png" alt="Ícone de Engrenagem">
                    Gerenciar Dias Letivos
                </button>
                <label class="toggle-switch" title="Mudar para visualização Semanal">
                    <input type="checkbox" id="view-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h2 id="month-year-title">Carregando...</h2>

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
                    <tr>
                        <td>Carregando dados...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- CONFIGURAÇÕES GLOBAIS DA API ---
            const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
            const TOKEN = localStorage.getItem('authToken');

            if (!TOKEN) {
                Swal.fire({
                    icon: 'error',
                    title: 'Não autenticado!',
                    text: 'Você precisa fazer login para ver o calendário.',
                });
                return;
            }

            const AUTH_HEADERS = {
                'Authorization': `Bearer ${TOKEN}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            };

            // --- SELETORES DE ELEMENTOS ---
            const titleElement = document.getElementById('month-year-title');
            const headerDaysRow = document.getElementById('calendar-header-days');
            const calendarBody = document.getElementById('calendar-body');
            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');

            // --- ESTADO DO CALENDÁRIO ---
            let dataAtual = new Date(); // Guarda a data atual do calendário

            // --- FUNÇÕES AUXILIARES ---

            function formatarDataParaAPI(data) {
                const ano = data.getFullYear();
                const mes = String(data.getMonth() + 1).padStart(2, '0');
                const dia = String(data.getDate()).padStart(2, '0');
                return `${ano}-${mes}-${dia}`;
            }

            function criarSlotsAgendamento(sessoes) {
                const porTurno = {
                    'Manhã': [],
                    'Tarde': [],
                    'Noite': []
                };

                sessoes.forEach(s => {
                    const nomeTurno = s.turno?.nome_turno;
                    if (!nomeTurno) return;

                    if (nomeTurno === 'Manhã') {
                        porTurno.Manhã.push(s);
                    } else if (nomeTurno === 'Tarde') {
                        porTurno.Tarde.push(s);
                    } else if (nomeTurno === 'Noite') {
                        porTurno.Noite.push(s);
                    } else if (nomeTurno === 'Manhã-Tarde' || nomeTurno === 'Integral') {
                        porTurno.Manhã.push(s);
                        porTurno.Tarde.push(s);
                    } else if (nomeTurno === 'Manhã-Noite') {
                        porTurno.Manhã.push(s);
                        porTurno.Noite.push(s);
                    } else if (nomeTurno === 'Tarde-Noite') {
                        porTurno.Tarde.push(s);
                        porTurno.Noite.push(s);
                    }
                });

                let html = '<div class="schedule-slot">';
                let temAgendamento = false;

                if (porTurno.Manhã.length > 0) {
                    const nomesTurmas = [...new Set(porTurno.Manhã.map(s => s.nome_turma))].join(', ');
                    html += `<p><span class="time-initial">M</span> ${nomesTurmas}</p>`;
                    temAgendamento = true;
                }
                if (porTurno.Tarde.length > 0) {
                    const nomesTurmas = [...new Set(porTurno.Tarde.map(s => s.nome_turma))].join(', ');
                    html += `<p><span class="time-initial">T</span> ${nomesTurmas}</p>`;
                    temAgendamento = true;
                }
                if (porTurno.Noite.length > 0) {
                    const nomesTurmas = [...new Set(porTurno.Noite.map(s => s.nome_turma))].join(', ');
                    html += `<p><span class="time-initial">N</span> ${nomesTurmas}</p>`;
                    temAgendamento = true;
                }

                if (temAgendamento) {
                    html += '<button class="ver-mais-btn">Ver Mais</button>';
                }

                html += '</div>';
                return temAgendamento ? html : '';
            }

            // --- LÓGICA PRINCIPAL DO CALENDÁRIO MENSAL ---

            async function gerarCalendario(dataBase) {
                if (!titleElement || !headerDaysRow || !calendarBody) return;

                const ano = dataBase.getFullYear();
                const mes = dataBase.getMonth(); // 0-11
                const mesParaApi = mes + 1; // 1-12

                // Feedback de carregamento
                calendarBody.innerHTML = '<tr><td colspan="32">Carregando dados da API...</td></tr>';

                // 1. Buscar os DADOS COMPLETOS
                let data;
                try {
                    const response = await fetch(`${API_URL}/turmas/mensal?ano=${ano}&mes=${mesParaApi}`, {
                        headers: AUTH_HEADERS
                    });
                    if (!response.ok) {
                        throw new Error(`Falha ao buscar dados do calendário: ${response.statusText}`);
                    }
                    data = await response.json();

                    if (!data.ambientes || !data.agendamentos) {
                        throw new Error("A API não retornou a estrutura 'ambientes' e 'agendamentos' esperada.");
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Erro de API', error.message, 'error');
                    calendarBody.innerHTML = `<tr><td colspan="32">Erro ao carregar dados.</td></tr>`;
                    return;
                }

                // 2. Extrair dados da resposta
                const listaDeAmbientes = data.ambientes;
                const agendamentos = data.agendamentos;

                // 3. Limpar o calendário anterior
                headerDaysRow.innerHTML = '<th>Ambientes</th>';
                calendarBody.innerHTML = '';

                // 4. Definir o título (Mês e Ano)
                const nomeMes = dataBase.toLocaleString('pt-BR', {
                    month: 'long'
                });
                titleElement.textContent = `${nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1)} de ${ano}`;

                // 5. Gerar cabeçalhos (Dias do Mês)
                const diasNoMes = new Date(ano, mes + 1, 0).getDate();
                const diasDaSemanaNomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

                for (let dia = 1; dia <= diasNoMes; dia++) {
                    const diaCorrente = new Date(ano, mes, dia);
                    const diaSemanaNome = diasDaSemanaNomes[diaCorrente.getDay()];

                    const th = document.createElement('th');
                    th.dataset.dataApi = formatarDataParaAPI(diaCorrente);
                    th.innerHTML = `${String(dia).padStart(2, '0')}/${String(mesParaApi).padStart(2, '0')} (${diaSemanaNome})`;
                    headerDaysRow.appendChild(th);
                }

                // 6. Gerar as linhas e preencher as células
                listaDeAmbientes.forEach(ambiente => {
                    const tr = document.createElement('tr');
                    const thAmbiente = document.createElement('th');
                    thAmbiente.className = 'room-name';
                    thAmbiente.textContent = ambiente.nome_ambiente;
                    tr.appendChild(thAmbiente);

                    for (let dia = 1; dia <= diasNoMes; dia++) {
                        const diaCorrente = new Date(ano, mes, dia);
                        const diaDaSemanaNum = diaCorrente.getDay(); // 0 = Domingo
                        const dataString = formatarDataParaAPI(diaCorrente); // YYYY-MM-DD

                        const td = document.createElement('td');

                        // Pega TODOS os eventos do dia
                        const sessoesDoDia = agendamentos[dataString] || [];

                        // -------------------------------------------------------------------------
                        // [NOVA LOGICA AQUI] Verifica se tem algum evento "nao_letivo"
                        // -------------------------------------------------------------------------
                        const eventoFeriado = sessoesDoDia.find(s => s.tipo_evento === 'nao_letivo');

                        // Filtra as turmas normais para este ambiente
                        const sessoesDaCelula = sessoesDoDia.filter(s => s.ambiente_id === ambiente.id);

                        if (diaDaSemanaNum === 0) { // Prioridade 1: Domingo
                            td.className = 'dia-nao-letivo';
                            td.textContent = 'Domingo';

                        } else if (eventoFeriado) { // Prioridade 2: FERIADO (vinda do Back-end)
                            td.className = 'dia-nao-letivo';
                            // Usa o título que veio do PHP (ex: "Dia não letivo")
                            td.textContent = eventoFeriado.titulo || 'Dia não letivo';
                            // Coloca a descrição (ex: "Feriado Municipal") como tooltip
                            td.title = eventoFeriado.descricao || '';

                        } else { // Prioridade 3: Aulas normais
                            td.innerHTML = criarSlotsAgendamento(sessoesDaCelula);
                        }

                        tr.appendChild(td);
                    }
                    calendarBody.appendChild(tr);
                });

                // 7. Adiciona os listeners aos botões "Ver Mais"
                adicionarListenersVerMais();
            }


            // --- FUNÇÕES DE INTERAÇÃO ---

            function buildDynamicModalHtml(turmasDoDia) {
                if (turmasDoDia.length === 0) {
                    return '<p>Nenhuma turma encontrada para este dia.</p>';
                }
                let html = '';
                turmasDoDia.forEach(turma => {
                    const nomeTurma = turma.nome_turma ?? 'N/A';
                    const nomeCurso = turma.curso?.nome_curso ?? 'N/A';
                    const nomeAmbiente = turma.ambiente?.nome_ambiente ?? 'N/A';
                    const nomeTurno = turma.turno?.nome_turno ?? 'N/A';

                    let nomesDocentes = 'Nenhum docente alocado';
                    if (turma.colaboradores && turma.colaboradores.length > 0) {
                        nomesDocentes = turma.colaboradores.map(c => c.nome_colaborador).join(', ');
                    }
                    const dataInicio = new Date(turma.data_inicio_turma + 'T00:00:00').toLocaleDateString('pt-BR');

                    html += `
                        <div class="info-modal-turma-section">
                            <h4>${nomeTurma} (${nomeTurno})</h4>
                            <p><b>Curso:</b> ${nomeCurso}</p>
                            <p><b>Ambiente:</b> ${nomeAmbiente}</p>
                            <p><b>Docente(s):</b> ${nomesDocentes}</p>
                            <p><b>Início da Turma:</b> ${dataInicio}</p>
                        </div>
                    `;
                });
                return html;
            }

            function adicionarListenersVerMais() {
                const verMaisBotoes = document.querySelectorAll('.ver-mais-btn');

                verMaisBotoes.forEach(botao => {
                    botao.addEventListener('click', async (event) => {
                        const cell = event.target.closest('td');
                        const cellIndex = cell.cellIndex;
                        const headerCell = headerDaysRow.querySelectorAll('th')[cellIndex];

                        const dataParaApi = headerCell.dataset.dataApi;
                        const dataTitulo = headerCell.textContent;

                        Swal.fire({
                            title: `Detalhes do Dia: ${dataTitulo}`,
                            html: 'Buscando informações...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            const response = await fetch(`${API_URL}/turmas/diario?data=${dataParaApi}`, {
                                headers: AUTH_HEADERS
                            });
                            if (!response.ok) throw new Error(`Falha ao buscar detalhes: ${response.statusText}`);

                            const turmasDoDia = await response.json();
                            const modalHtml = buildDynamicModalHtml(turmasDoDia);

                            Swal.update({
                                html: modalHtml,
                                showConfirmButton: false,
                                showCloseButton: true,
                                customClass: {
                                    popup: 'custom-swal-popup',
                                    title: 'custom-swal-title',
                                    closeButton: 'custom-swal-close-button',
                                    htmlContainer: 'custom-swal-html-container'
                                }
                            });
                            Swal.hideLoading();

                        } catch (error) {
                            console.error(error);
                            Swal.hideLoading();
                            Swal.fire('Erro', error.message, 'error');
                        }
                    });
                });
            }

            // --- LÓGICA DE INTERAÇÃO ORIGINAL ---

            const viewToggle = document.getElementById('view-toggle');
            if (viewToggle) {
                viewToggle.checked = false;
                viewToggle.addEventListener('change', function() {
                    if (this.checked) {
                        window.location.href = 'semanal.php';
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

            const manageDaysBtn = document.querySelector('.manage-days-btn');
            if (manageDaysBtn) {
                manageDaysBtn.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Gerenciar Dias Letivos',
                        html: `... (Seu formulário HTML estático aqui) ...`,
                    });
                });
            }

            // --- LISTENERS DOS BOTÕES DE NAVEGAÇÃO ---
            if (prevMonthBtn) {
                prevMonthBtn.addEventListener('click', () => {
                    dataAtual.setMonth(dataAtual.getMonth() - 1);
                    gerarCalendario(dataAtual);
                });
            }

            if (nextMonthBtn) {
                nextMonthBtn.addEventListener('click', () => {
                    dataAtual.setMonth(dataAtual.getMonth() + 1);
                    gerarCalendario(dataAtual);
                });
            }

            // --- INICIALIZAÇÃO DA PÁGINA ---
            gerarCalendario(dataAtual);
        });
    </script>
</body>

</html>