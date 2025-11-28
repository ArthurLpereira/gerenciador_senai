document.addEventListener('DOMContentLoaded', function () {

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
        viewToggle.addEventListener('change', function () {
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