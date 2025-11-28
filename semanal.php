<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI - Semanal</title>

    <link rel="stylesheet" href="./css/semanal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">

    <style>
        /* --- ESTILOS ESPECÍFICOS INTERNOS --- */

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

        /* --- ESTILO DOS DIAS NÃO LETIVOS (VERMELHO) --- */
        .dia-nao-letivo {
            background-color: #ffebee;
            /* Vermelho claro */
            color: #d32f2f;
            /* Vermelho escuro */
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 0.95em;
            border: 1px solid #ffcdd2;
        }

        .room-name {
            background-color: #f4f4f4;
            font-weight: bold;
            padding: 10px;
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
                <h1>Calendário Semanal</h1>
            </div>
            <div class="sair">
                <a href="#" id="btn-sair"><img src="./images/logout (2).png" alt="Ícone de sair"> Sair</a>
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
                <label class="toggle-switch" title="Mudar para visualização Mensal">
                    <input type="checkbox" id="view-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h2 id="week-range-title">Carregando...</h2>
                <div class="nav-buttons">
                    <button class="nav-arrow" id="prev-week-btn">&lt;</button>
                    <button class="nav-arrow" id="next-week-btn">&gt;</button>
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
                        <td colspan="8">Carregando dados...</td>
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
                }).then(() => {
                    window.location.href = 'index.php';
                });
                return;
            }

            const AUTH_HEADERS = {
                'Authorization': `Bearer ${TOKEN}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            };

            // --- SELETORES DE ELEMENTOS ---
            const titleElement = document.getElementById('week-range-title');
            const headerDaysRow = document.getElementById('calendar-header-days');
            const calendarBody = document.getElementById('calendar-body');
            const prevWeekBtn = document.getElementById('prev-week-btn');
            const nextWeekBtn = document.getElementById('next-week-btn');

            // --- ESTADO DO CALENDÁRIO ---
            let dataAtual = new Date();

            // --- FUNÇÕES DE LÓGICA DO CALENDÁRIO ---

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

                    if (nomeTurno.includes('Manhã') || nomeTurno === 'Integral') {
                        porTurno.Manhã.push(s);
                    }
                    if (nomeTurno.includes('Tarde') || nomeTurno === 'Integral') {
                        porTurno.Tarde.push(s);
                    }
                    if (nomeTurno.includes('Noite')) {
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

            async function gerarCalendarioSemanal(dataBase) {
                if (!titleElement || !headerDaysRow || !calendarBody) return;

                calendarBody.innerHTML = '<tr><td colspan="8">Carregando calendário...</td></tr>';

                const dataQuery = formatarDataParaAPI(dataBase);
                let data;
                try {
                    const response = await fetch(`${API_URL}/turmas/semanal?data=${dataQuery}`, {
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
                    calendarBody.innerHTML = '<tr><td colspan="8">Erro ao carregar dados.</td></tr>';
                    return;
                }

                const listaDeAmbientes = data.ambientes;
                const agendamentos = data.agendamentos;

                headerDaysRow.innerHTML = '<th>Ambientes</th>';
                calendarBody.innerHTML = '';

                // Título do Mês/Ano
                const formatadorTitulo = {
                    month: 'long',
                    year: 'numeric'
                };
                let nomeMes = dataBase.toLocaleDateString('pt-BR', formatadorTitulo);
                nomeMes = nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1);
                titleElement.textContent = nomeMes;

                // Configura dias da semana (Começando de Domingo)
                const inicioSemana = new Date(dataBase);
                inicioSemana.setDate(dataBase.getDate() - dataBase.getDay());
                const diasDaSemanaNomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
                const diasDaSemana = [];

                // Monta o cabeçalho (dias)
                for (let i = 0; i < 7; i++) {
                    const diaCorrente = new Date(inicioSemana);
                    diaCorrente.setDate(inicioSemana.getDate() + i);
                    diasDaSemana.push(diaCorrente);

                    const th = document.createElement('th');
                    th.dataset.dataApi = formatarDataParaAPI(diaCorrente);
                    th.innerHTML = `${String(diaCorrente.getDate()).padStart(2, '0')}/${String(diaCorrente.getMonth() + 1).padStart(2, '0')} (${diasDaSemanaNomes[i]})`;
                    headerDaysRow.appendChild(th);
                }

                // Monta as linhas (Ambientes)
                listaDeAmbientes.forEach(ambiente => {
                    const tr = document.createElement('tr');
                    const thAmbiente = document.createElement('th');
                    thAmbiente.className = 'room-name';
                    thAmbiente.textContent = ambiente.nome_ambiente;
                    tr.appendChild(thAmbiente);

                    diasDaSemana.forEach(dia => {
                        const td = document.createElement('td');
                        const diaDaSemanaNum = dia.getDay();
                        const dataString = formatarDataParaAPI(dia);

                        // Pega TODOS os eventos do dia
                        const sessoesDoDia = agendamentos[dataString] || [];

                        // 1. Procura se existe feriado/dia não letivo vindo do banco
                        const eventoFeriado = sessoesDoDia.find(s => s.tipo_evento === 'nao_letivo');

                        // 2. Filtra as aulas normais para este ambiente
                        const sessoesDaCelula = sessoesDoDia.filter(s => s.ambiente_id === ambiente.id);

                        // --- DECISÃO DE EXIBIÇÃO ---
                        if (diaDaSemanaNum === 0) {
                            // Prioridade 1: Domingo
                            td.className = 'dia-nao-letivo';
                            td.textContent = 'Domingo';

                        } else if (eventoFeriado) {
                            // Prioridade 2: Feriado do Banco
                            td.className = 'dia-nao-letivo';
                            td.textContent = eventoFeriado.titulo || 'Dia não letivo';
                            td.title = eventoFeriado.descricao || '';

                        } else {
                            // Prioridade 3: Aulas Normais
                            td.innerHTML = criarSlotsAgendamento(sessoesDaCelula);
                        }

                        tr.appendChild(td);
                    });

                    calendarBody.appendChild(tr);
                });

                adicionarListenersVerMais();
            }

            // --- FUNÇÕES DO MODAL "VER MAIS" ---

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

                            if (!response.ok) {
                                throw new Error(`Falha ao buscar detalhes: ${response.statusText}`);
                            }

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

            // --- INTERAÇÕES (Menu, Toggle) ---

            const viewToggle = document.getElementById('view-toggle');
            if (viewToggle) {
                viewToggle.checked = true;
                viewToggle.addEventListener('change', function() {
                    if (!this.checked) {
                        window.location.href = 'mensal.php';
                    }
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

            // =========================================================================
            // LOGOUT VIA API (ATUALIZADO)
            // =========================================================================
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

                            Swal.fire({
                                title: 'Saindo...',
                                text: 'Encerrando sessão.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            try {
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
                                localStorage.removeItem('authToken');
                                localStorage.removeItem('user'); // Se houver dados de usuário salvo
                                window.location.href = 'index.php';
                            }
                        }
                    });
                });
            }

            // =========================================================================
            // BOTÃO DE GERENCIAR DIAS LETIVOS
            // =========================================================================
            const manageDaysBtn = document.querySelector('.manage-days-btn');
            if (manageDaysBtn) {
                manageDaysBtn.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Cadastrar Dia Não Letivo',
                        html: `
                    <div style="display: flex; flex-direction: column; gap: 15px; text-align: left;">
                        <div>
                            <label for="swal-input-data" style="font-weight: bold; display: block; margin-bottom: 5px;">Data</label>
                            <input type="date" id="swal-input-data" class="swal2-input" style="margin: 0; width: 100%; box-sizing: border-box;">
                        </div>
                        
                        <div>
                            <label for="swal-input-descricao" style="font-weight: bold; display: block; margin-bottom: 5px;">Descrição</label>
                            <input type="text" id="swal-input-descricao" class="swal2-input" placeholder="Ex: Feriado Municipal" style="margin: 0; width: 100%; box-sizing: border-box;">
                        </div>

                        <div>
                            <label for="swal-input-tipo" style="font-weight: bold; display: block; margin-bottom: 5px;">Tipo de Feriado</label>
                            <select id="swal-input-tipo" class="swal2-select" style="margin: 0; width: 100%; box-sizing: border-box; display: flex;">
                                <option value="Municipal">Municipal</option>
                                <option value="Estadual">Estadual</option>
                                <option value="Nacional">Nacional</option>
                                <option value="Recesso">Recesso Escolar</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                    </div>
                `,
                        showCancelButton: true,
                        confirmButtonText: 'Salvar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        focusConfirm: false,
                        preConfirm: () => {
                            const data = document.getElementById('swal-input-data').value;
                            const descricao = document.getElementById('swal-input-descricao').value;
                            const tipo = document.getElementById('swal-input-tipo').value;

                            if (!data || !descricao || !tipo) {
                                Swal.showValidationMessage('Por favor, preencha todos os campos');
                                return false;
                            }
                            return {
                                data_dia_nao_letivo: data,
                                descricao_dia_nao_letivo: descricao,
                                tipo_feriado_dia_nao_letivo: tipo
                            };
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            const dadosParaSalvar = result.value;

                            Swal.fire({
                                title: 'Salvando...',
                                text: 'Aguarde um momento.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            try {
                                const response = await fetch(`${API_URL}/dias-nao-letivos`, {
                                    method: 'POST',
                                    headers: AUTH_HEADERS,
                                    body: JSON.stringify(dadosParaSalvar)
                                });

                                if (!response.ok) {
                                    const errorData = await response.json().catch(() => ({}));
                                    throw new Error(errorData.message || `Erro ${response.status}: Falha ao salvar.`);
                                }

                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Dia não letivo cadastrado com sucesso.'
                                });

                                gerarCalendarioSemanal(dataAtual);

                            } catch (error) {
                                console.error(error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro ao salvar',
                                    text: error.message
                                });
                            }
                        }
                    });
                });
            }

            // --- LISTENERS DE NAVEGAÇÃO ---
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

            // --- INICIALIZAÇÃO ---
            gerarCalendarioSemanal(dataAtual);
        });
    </script>
</body>

</html>