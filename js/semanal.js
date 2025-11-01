document.addEventListener('DOMContentLoaded', function () {

    // --- CONFIGURAÇÕES GLOBAIS DA API ---
    const API_URL = 'http://127.0.0.1:8000/api';
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

        const formatadorTitulo = {
            month: 'long',
            year: 'numeric'
        };
        let nomeMes = dataBase.toLocaleDateString('pt-BR', formatadorTitulo);
        nomeMes = nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1);
        titleElement.textContent = nomeMes;

        const inicioSemana = new Date(dataBase);
        inicioSemana.setDate(dataBase.getDate() - dataBase.getDay());
        const diasDaSemanaNomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        const diasDaSemana = [];

        for (let i = 0; i < 7; i++) {
            const diaCorrente = new Date(inicioSemana);
            diaCorrente.setDate(inicioSemana.getDate() + i);
            diasDaSemana.push(diaCorrente);

            const th = document.createElement('th');
            th.dataset.dataApi = formatarDataParaAPI(diaCorrente);
            th.innerHTML = `${String(diaCorrente.getDate()).padStart(2, '0')}/${String(diaCorrente.getMonth() + 1).padStart(2, '0')} (${diasDaSemanaNomes[i]})`;
            headerDaysRow.appendChild(th);
        }

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

                const sessoesDoDia = agendamentos[dataString] || [];
                const sessoesDaCelula = sessoesDoDia.filter(s => s.ambiente_id === ambiente.id);

                if (diaDaSemanaNum === 0) {
                    td.className = 'dia-nao-letivo';
                    td.textContent = 'Dia Não Letivo';
                } else {
                    td.innerHTML = criarSlotsAgendamento(sessoesDaCelula);
                }
                tr.appendChild(td);
            });

            calendarBody.appendChild(tr);
        });

        adicionarListenersVerMais();
    }

    // --- LÓGICA DE INTERAÇÃO (Botões, Modais, etc.) ---
    // (Nenhuma alteração nesta seção)

    const viewToggle = document.getElementById('view-toggle');
    if (viewToggle) {
        viewToggle.checked = true;
        viewToggle.addEventListener('change', function () {
            if (!this.checked) {
                window.location.href = 'mensal.php';
            }
        });
    }

    const btnSair = document.getElementById('btn-sair');
    if (btnSair) {
        btnSair.addEventListener('click', function (event) {
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
                html: `... (seu HTML de formulário aqui) ...`,
                showCancelButton: true,
                confirmButtonText: 'Salvar Alterações',
                // ... (resto da sua configuração original) ...
            });
        });
    }


    // ====================================================================
    // == FUNÇÕES DO MODAL "VER MAIS" (COM A CORREÇÃO DO LOADING)
    // ====================================================================

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


    /**
     * [MODIFICADO]
     * Modal "Ver Mais" (Agora para de carregar corretamente)
     */
    function adicionarListenersVerMais() {
        const verMaisBotoes = document.querySelectorAll('.ver-mais-btn');

        verMaisBotoes.forEach(botao => {
            botao.addEventListener('click', async (event) => {
                const cell = event.target.closest('td');
                const cellIndex = cell.cellIndex;
                const headerCell = headerDaysRow.querySelectorAll('th')[cellIndex];
                const dataParaApi = headerCell.dataset.dataApi;
                const dataTitulo = headerCell.textContent;

                // 1. Abre o modal de "Carregando"
                Swal.fire({
                    title: `Detalhes do Dia: ${dataTitulo}`,
                    html: 'Buscando informações...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    // 2. Chama a API /turmas/diario
                    const response = await fetch(`${API_URL}/turmas/diario?data=${dataParaApi}`, {
                        headers: AUTH_HEADERS
                    });

                    if (!response.ok) {
                        throw new Error(`Falha ao buscar detalhes: ${response.statusText}`);
                    }

                    const turmasDoDia = await response.json();

                    // 3. Constrói o HTML dinâmico
                    const modalHtml = buildDynamicModalHtml(turmasDoDia);

                    // 4. Atualiza o modal com os dados reais
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

                    // *** ALTERAÇÃO ESSENCIAL ***
                    // Para o spinner de loading
                    Swal.hideLoading();

                } catch (error) {
                    console.error(error);

                    // *** ALTERAÇÃO ESSENCIAL ***
                    // Para o spinner de loading em caso de erro
                    Swal.hideLoading();

                    Swal.fire('Erro', error.message, 'error');
                }
            });
        });
    }

    // --- LISTENERS DE NAVEGAÇÃO (BOTÕES < >) ---
    if (prevWeekBtn) {
        prevWeekBtn.addEventListener('click', () => {
            dataAtual.setDate(dataAtual.getDate() - 7);
            gerarCalendarioSemanal(dataAtual); // Recarrega os dados da API
        });
    }

    if (nextWeekBtn) {
        nextWeekBtn.addEventListener('click', () => {
            dataAtual.setDate(dataAtual.getDate() + 7);
            gerarCalendarioSemanal(dataAtual); // Recarrega os dados da API
        });
    }

    // --- INICIALIZAÇÃO DA PÁGINA ---
    gerarCalendarioSemanal(dataAtual);
});