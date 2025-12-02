<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI - Mensal</title>
    <link rel="stylesheet" href="./css/mensal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">

    <style>
        .calendar-header {
            display: flex;
            justify-content: start;
            gap: 500px;
            align-items: center;
            width: 352.9vw !important;
            /* Isso garante o scroll horizontal */
        }

        .calendar-header h2 {
            white-space: nowrap;
            position: fixed;
            position: absolute;
            top: 50%;
        }

        .nav-buttons {
            display: flex;
            position: fixed;
            left: 93%;
            position: absolute;
            top: 50%;
        }

        .nav-arrow {
            background: none;
            border: none;
            color: white;
            font-size: 2em;
            cursor: pointer;
            padding: 0 5px;
        }

        /* Estilos do modal */
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

        /* ------------------------------------------------ */
        /* --- ESTILOS CORRIGIDOS PARA O NOVO REQUISITO --- */
        /* ------------------------------------------------ */

        /* --- ESTILO DOS DIAS NÃO LETIVOS (Fundo Cinza Claro, Texto Vermelho) --- */
        .dia-nao-letivo {
            background-color: #EAEAEA !important; /* Cinza claro */
            color: #D62828 !important; /* Texto em vermelho (#D62828) */
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 0.9em;
            border: 1px solid #D1D1D1; /* Borda mais clara */
        }

        /* --- ESTILO DOS FERIADOS (Fundo Cinza Claro, Texto Laranja) --- */
        .dia-feriado {
            background-color: #EAEAEA !important; /* Cinza claro (mesmo do dia não letivo) */
            color: #F77F00 !important; /* Texto em laranja (#F77F00) */
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 0.9em;
            border: 1px solid #D1D1D1; /* Borda mais clara */
        }

        /* Garante que o texto dentro das células coloridas mantenha a cor definida acima */
        .dia-nao-letivo p, .dia-feriado p {
             color: inherit !important; 
        }

        /* Estilo básico caso não carregue o css externo */
        .room-name {
            background-color: #f4f4f4;
            padding: 10px;
            position: sticky;
            left: 0;
            z-index: 10;
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

            // --- CONFIGURAÇÕES DA API ---
            const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
            const TOKEN = localStorage.getItem('authToken');

            if (!TOKEN) {
                Swal.fire({
                    icon: 'error',
                    title: 'Não autenticado!',
                    text: 'Faça login.'
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

            // --- SELETORES ---
            const titleElement = document.getElementById('month-year-title');
            const headerDaysRow = document.getElementById('calendar-header-days');
            const calendarBody = document.getElementById('calendar-body');
            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');

            let dataAtual = new Date();

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
                    if (nomeTurno.includes('Manhã') || nomeTurno === 'Integral') porTurno.Manhã.push(s);
                    if (nomeTurno.includes('Tarde') || nomeTurno === 'Integral') porTurno.Tarde.push(s);
                    if (nomeTurno.includes('Noite')) porTurno.Noite.push(s);
                });

                let html = '<div class="schedule-slot">';
                let temAgendamento = false;

                if (porTurno.Manhã.length > 0) {
                    const nomes = [...new Set(porTurno.Manhã.map(s => s.nome_turma))].join(', ');
                    html += `<p><span class="time-initial">M</span> ${nomes}</p>`;
                    temAgendamento = true;
                }
                if (porTurno.Tarde.length > 0) {
                    const nomes = [...new Set(porTurno.Tarde.map(s => s.nome_turma))].join(', ');
                    html += `<p><span class="time-initial">T</span> ${nomes}</p>`;
                    temAgendamento = true;
                }
                if (porTurno.Noite.length > 0) {
                    const nomes = [...new Set(porTurno.Noite.map(s => s.nome_turma))].join(', ');
                    html += `<p><span class="time-initial">N</span> ${nomes}</p>`;
                    temAgendamento = true;
                }

                if (temAgendamento) html += '<button class="ver-mais-btn">Ver Mais</button>';
                html += '</div>';
                return temAgendamento ? html : '';
            }

            // --- LÓGICA MENSAL (ARRASTÁVEL) ---
            async function gerarCalendario(dataBase) {
                if (!titleElement || !headerDaysRow || !calendarBody) return;

                const ano = dataBase.getFullYear();
                const mes = dataBase.getMonth();
                const mesParaApi = mes + 1;

                calendarBody.innerHTML = '<tr><td colspan="32">Carregando dados da API...</td></tr>';

                // 1. GET NA API
                let data;
                try {
                    const response = await fetch(`${API_URL}/turmas/mensal?ano=${ano}&mes=${mesParaApi}`, {
                        headers: AUTH_HEADERS
                    });
                    if (!response.ok) throw new Error(response.statusText);
                    data = await response.json();
                } catch (error) {
                    console.error(error);
                    Swal.fire('Erro', error.message, 'error');
                    calendarBody.innerHTML = `<tr><td colspan="32">Erro ao carregar dados.</td></tr>`;
                    return;
                }

                const listaDeAmbientes = data.ambientes;
                const agendamentos = data.agendamentos;

                // 2. TÍTULO E CABEÇALHO
                headerDaysRow.innerHTML = '<th>Ambientes</th>';
                calendarBody.innerHTML = '';

                const nomeMes = dataBase.toLocaleString('pt-BR', {
                    month: 'long'
                });
                titleElement.textContent = `${nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1)} de ${ano}`;

                const diasNoMes = new Date(ano, mes + 1, 0).getDate();
                const diasDaSemanaNomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

                // Cria colunas para CADA dia do mês (1 a 30/31)
                for (let dia = 1; dia <= diasNoMes; dia++) {
                    const diaCorrente = new Date(ano, mes, dia);
                    const diaSemanaNome = diasDaSemanaNomes[diaCorrente.getDay()];

                    const th = document.createElement('th');
                    th.dataset.dataApi = formatarDataParaAPI(diaCorrente);
                    th.innerHTML = `${String(dia).padStart(2, '0')}/${String(mesParaApi).padStart(2, '0')} (${diaSemanaNome})`;
                    headerDaysRow.appendChild(th);
                }

                // 3. CRIA AS LINHAS (AMBIENTES)
                listaDeAmbientes.forEach(ambiente => {
                    const tr = document.createElement('tr');

                    // Coluna Fixa do Ambiente
                    const thAmbiente = document.createElement('th');
                    thAmbiente.className = 'room-name';
                    thAmbiente.textContent = ambiente.nome_ambiente;
                    tr.appendChild(thAmbiente);

                    // Células dos dias
                    for (let dia = 1; dia <= diasNoMes; dia++) {
                        const diaCorrente = new Date(ano, mes, dia);
                        const diaDaSemanaNum = diaCorrente.getDay();
                        const dataString = formatarDataParaAPI(diaCorrente);
                        const td = document.createElement('td');

                        const sessoesDoDia = agendamentos[dataString] || [];

                        // [LÓGICA DOS DIAS NÃO LETIVOS E FERIADOS]
                        const eventoNaoLetivo = sessoesDoDia.find(s => s.tipo_evento === 'nao_letivo');
                        const sessoesDaCelula = sessoesDoDia.filter(s => s.ambiente_id === ambiente.id);

                        // 1. Verifica se é DOMINGO
                        if (diaDaSemanaNum === 0) { 
                            td.className = 'dia-nao-letivo'; // Usa a classe para DOMINGO (fundo cinza, texto vermelho)
                            td.textContent = 'Domingo';
                            
                        // 2. Verifica se é um Dia Não Letivo/Feriado cadastrado
                        } else if (eventoNaoLetivo) { 
                            const titulo = eventoNaoLetivo.titulo || 'Dia não letivo';
                            const tipo = eventoNaoLetivo.tipo_feriado_dia_nao_letivo || ''; 

                            // Lógica para diferenciar Feriado de Dia Não Letivo genérico
                            if (tipo === 'Municipal' || tipo === 'Estadual' || tipo === 'Nacional' || titulo.toLowerCase().includes('feriado')) {
                                td.className = 'dia-feriado'; // Feriado (fundo cinza, texto laranja)
                            } else {
                                td.className = 'dia-nao-letivo'; // Dia Não Letivo (fundo cinza, texto vermelho)
                            }
                            
                            td.textContent = titulo;
                            td.title = eventoNaoLetivo.descricao || '';
                            
                        // 3. Caso contrário, carrega agendamentos
                        } else {
                            td.innerHTML = criarSlotsAgendamento(sessoesDaCelula);
                        }

                        tr.appendChild(td);
                    }
                    calendarBody.appendChild(tr);
                });

                adicionarListenersVerMais();
            }

            // --- MODAL VER MAIS ---
            function adicionarListenersVerMais() {
                document.querySelectorAll('.ver-mais-btn').forEach(botao => {
                    botao.addEventListener('click', async (event) => {
                        const cell = event.target.closest('td');
                        const headerCell = headerDaysRow.querySelectorAll('th')[cell.cellIndex];
                        const dataParaApi = headerCell.dataset.dataApi;

                        Swal.fire({
                            title: `Detalhes`,
                            html: 'Carregando...',
                            didOpen: () => Swal.showLoading()
                        });

                        try {
                            const res = await fetch(`${API_URL}/turmas/diario?data=${dataParaApi}`, {
                                headers: AUTH_HEADERS
                            });
                            const turmas = await res.json();

                            let html = turmas.length ? '' : '<p>Sem turmas.</p>';
                            turmas.forEach(t => {
                                html += `<div class="info-modal-turma-section">
                                    <h4>${t.nome_turma ?? ''} (${t.turno?.nome_turno ?? ''})</h4>
                                    <p>Curso: ${t.curso?.nome_curso ?? ''}</p>
                                    <p>Ambiente: ${t.ambiente?.nome_ambiente ?? ''}</p>
                                </div>`;
                            });

                            Swal.update({
                                html: html,
                                showConfirmButton: false,
                                showCloseButton: true
                            });
                            Swal.hideLoading();
                        } catch (err) {
                            Swal.fire('Erro', err.message, 'error');
                        }
                    });
                });
            }

            // --- BOTÃO GERENCIAR DIAS (POST) ---
            const manageDaysBtn = document.querySelector('.manage-days-btn');
            if (manageDaysBtn) {
                manageDaysBtn.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Cadastrar Dia Não Letivo',
                        html: `
                            <div style="display: flex; flex-direction: column; gap: 15px; text-align: left;">
                                <div><label style="font-weight:bold">Data</label><input type="date" id="swal-input-data" class="swal2-input" style="width:100%;margin:0"></div>
                                <div><label style="font-weight:bold">Descrição</label><input type="text" id="swal-input-descricao" class="swal2-input" placeholder="Ex: Feriado" style="width:100%;margin:0"></div>
                                <div><label style="font-weight:bold">Tipo</label>
                                    <select id="swal-input-tipo" class="swal2-select" style="width:100%;margin:0;display:flex">
                                        <option value="Municipal">Municipal</option>
                                        <option value="Estadual">Estadual</option>
                                        <option value="Nacional">Nacional</option>
                                        <option value="Emenda">Emenda</option>
                                        <option value="Ponto Facultativo">Ponto Facultativo</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                            </div>`,
                        showCancelButton: true,
                        confirmButtonText: 'Salvar',
                        preConfirm: () => {
                            const data = document.getElementById('swal-input-data').value;
                            const descricao = document.getElementById('swal-input-descricao').value;
                            const tipo = document.getElementById('swal-input-tipo').value;
                            if (!data || !descricao || !tipo) return Swal.showValidationMessage('Preencha tudo');
                            return {
                                data_dia_nao_letivo: data,
                                descricao_dia_nao_letivo: descricao,
                                tipo_feriado_dia_nao_letivo: tipo
                            };
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Salvando...',
                                didOpen: () => Swal.showLoading()
                            });
                            try {
                                const res = await fetch(`${API_URL}/dias-nao-letivos`, {
                                    method: 'POST',
                                    headers: AUTH_HEADERS,
                                    body: JSON.stringify(result.value)
                                });
                                if (!res.ok) throw new Error('Erro ao salvar');

                                await Swal.fire('Sucesso!', 'Dia cadastrado.', 'success');
                                gerarCalendario(dataAtual); // Recarrega o calendário Mensal
                            } catch (err) {
                                Swal.fire('Erro', err.message, 'error');
                            }
                        }
                    });
                });
            }

            // --- NAVEGAÇÃO MENSAL ---
            if (prevMonthBtn) prevMonthBtn.addEventListener('click', () => {
                dataAtual.setMonth(dataAtual.getMonth() - 1);
                gerarCalendario(dataAtual);
            });
            if (nextMonthBtn) nextMonthBtn.addEventListener('click', () => {
                dataAtual.setMonth(dataAtual.getMonth() + 1);
                gerarCalendario(dataAtual);
            });

            // Toggle para Semanal
            const viewToggle = document.getElementById('view-toggle');
            if (viewToggle) {
                viewToggle.checked = false;
                viewToggle.addEventListener('change', function() {
                    if (this.checked) window.location.href = 'semanal.php';
                });
            }

            // =========================================================================
            // LOGOUT API (ATUALIZADO)
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
                                // 1. Chama a API de Logout (POST)
                                if (TOKEN) {
                                    await fetch(`${API_URL}/logout`, {
                                        method: 'POST',
                                        headers: AUTH_HEADERS
                                    });
                                }
                            } catch (error) {
                                console.error("Erro na comunicação com API de logout:", error);
                            } finally {
                                // 2. Limpa o token e redireciona
                                localStorage.removeItem('authToken');
                                localStorage.removeItem('user');
                                window.location.href = 'index.php';
                            }
                        }
                    });
                });
            }

            // Menu Mobile
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('conteudo');
            if (menuBtn) menuBtn.addEventListener('click', () => {
                menuBtn.classList.toggle('active');
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('push');
            });

            // Início
            gerarCalendario(dataAtual);
        });
    </script>
</body>

</html>