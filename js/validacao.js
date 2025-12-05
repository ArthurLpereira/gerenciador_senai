document.addEventListener('DOMContentLoaded', () => {
    // --- CONFIGURAÇÕES GLOBAIS ---
    const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';

    // 1. RECUPERA O TOKEN
    const TOKEN = localStorage.getItem('authToken');

    // 2. VERIFICA SE EXISTE (Proteção de Rota)
    if (!TOKEN) {
        window.location.href = './index.php';
        return;
    }

    // 3. CRIA O HEADER PADRÃO COM O BEARER TOKEN
    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    // --- LÓGICA DO MENU LATERAL (SIDEBAR) ---
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('conteudo-cadastro');
    if (menuBtn && sidebar && mainContent) {
        menuBtn.addEventListener('click', () => {
            menuBtn.classList.toggle('active');
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('push');
        });
    }

    // --- LÓGICA DO BOTÃO DE LOGOUT ---
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', async (event) => {
            event.preventDefault();
            const result = await Swal.fire({
                title: 'Você tem certeza?',
                text: "Sua sessão será encerrada com segurança.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, quero sair!',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    // Envia o token no Logout também
                    await fetch(`${API_URL}/logout`, {
                        method: 'POST',
                        headers: AUTH_HEADERS
                    });
                } catch (error) {
                    console.error('Falha ao comunicar com a API de logout:', error);
                } finally {
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('user');
                    window.location.href = './index.php';
                }
            }
        });
    }

    // --- SELETORES DE ELEMENTOS ---
    const listaTurmas = document.getElementById('lista-turmas');
    const modalCriar = document.getElementById('modalCriar');
    const modalEditar = document.getElementById('modalEditar');
    const abrirModalBtn = document.getElementById('abrirModal');
    const fecharModalBtns = document.querySelectorAll('.close');
    const formCriar = document.getElementById('formCriar');
    const formEditar = document.getElementById('formEditar');
    const searchInput = document.getElementById('searchInput');

    // --- FUNÇÕES AUXILIARES ---
    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    const showError = (msg) => Swal.fire('Erro!', msg, 'error');

    // --- FUNÇÕES DE LÓGICA PRINCIPAL ---

    function criarCardTurma(turma) {
        const statusText = turma.status_turma?.nome_status_turma ?? 'Indefinido';
        const statusClass = statusText.toLowerCase().replace(' ', '-');
        const cursoNome = turma.curso?.nome_curso ?? 'N/A';
        const ambienteNome = turma.ambiente?.nome_ambiente ?? 'N/A';
        const turno = turma.turno?.nome_turno ?? 'N/A'
        const dataFormatadaInicio = new Date(turma.data_inicio_turma + 'T00:00:00').toLocaleDateString('pt-BR');
        const dataFormatadaTermino = new Date(turma.data_termino_turma + 'T00:00:00').toLocaleDateString('pt-BR');
        const Capacidade = turma?.capacidade_turma;
        const CapacidadeAtual = turma?.capacidade_atual ?? 0;
        return `
                <div class="info_docente" data-id="${turma.id}">
                    <div class="conteudo">
                        <p class="nome"><b>${turma.nome_turma}</b> (${cursoNome})</p>
                        <p><i class="bi bi-calendar-event"></i> Início: ${dataFormatadaInicio}</p>
                        <p><i class="bi bi-calendar-event"></i> Término: ${dataFormatadaTermino}</p>
                        <p><i class="bi bi-geo-alt-fill"></i> Ambiente: ${ambienteNome}</p>
                        <p><i class="bi bi-geo-alt-fill"></i> Capacidade Máxima: ${Capacidade}</p>
                        <p><i class="bi bi-geo-alt-fill"></i> Capacidade Atual: ${CapacidadeAtual}</p>
                        <p><i class="bi bi-geo-alt-fill"></i> Turno: ${turno}</p>
                        <p><i class="bi bi-person-check-fill"></i> Status: ${statusText}</p>
                    </div>
                    <div class="funcoes">
                        <button class="editar_docente" data-id="${turma.id}"><i class="bi bi-pen-fill"></i> Editar</button>
                        <button class="status_docente ${statusClass}" data-id="${turma.id}">${statusText}</button>
                    </div>
                </div>
            `;
    }

    async function carregarTurmas() {
        try {
            // Passa o Header com Token
            const response = await fetch(`${API_URL}/turmas`, {
                headers: AUTH_HEADERS
            });
            if (!response.ok) throw new Error('Falha ao carregar turmas.');
            const data = await response.json();
            const turmas = data.data || data;
            listaTurmas.innerHTML = '';
            if (turmas.length > 0) {
                turmas.forEach(turma => listaTurmas.innerHTML += criarCardTurma(turma));
            } else {
                listaTurmas.innerHTML = '<p>Nenhuma turma encontrada.</p>';
            }
        } catch (error) {
            showError('Não foi possível carregar as turmas.');
            console.error(error);
        }
    }

    async function popularSelect(selectId, endpoint, textField, valueField = 'id') {
        const select = document.getElementById(selectId);
        try {
            // Passa o Header com Token
            const response = await fetch(`${API_URL}/${endpoint}`, {
                headers: AUTH_HEADERS
            });
            if (!response.ok) throw new Error(`Falha ao carregar ${endpoint}.`);
            const data = await response.json();
            const items = data.data || data;
            select.innerHTML = `<option value="" disabled selected>Selecione</option>`;
            items.forEach(item => {
                select.innerHTML += `<option value="${item[valueField]}">${item[textField]}</option>`;
            });
        } catch (error) {
            console.error(error);
        }
    }

    // --- EVENT LISTENERS ---
    abrirModalBtn.addEventListener('click', () => {
        formCriar.reset();
        popularSelect('curso_id', 'cursos', 'nome_curso');
        popularSelect('colaborador_id', 'colaboradores', 'nome_colaborador');
        popularSelect('ambiente_id', 'ambientes', 'nome_ambiente');
        popularSelect('turno_id', 'turnos', 'nome_turno');
        popularSelect('minuto_aula_id', 'minutos-aulas', 'quant_minuto_aula');
        popularSelect('status_turma_id', 'status-turmas', 'nome_status_turma');
        modalCriar.style.display = 'block';
    });

    fecharModalBtns.forEach(btn => btn.addEventListener('click', () => btn.closest('.modal').style.display = 'none'));
    window.addEventListener('click', (e) => {
        if (e.target == modalCriar) modalCriar.style.display = 'none';
        if (e.target == modalEditar) modalEditar.style.display = 'none';
    });

    // =========================================================================
    //              CRIAR TURMA (COM TOKEN)
    // =========================================================================
    formCriar.addEventListener('submit', async (e) => {
        e.preventDefault();
        const diasSelecionados = Array.from(document.querySelectorAll('#dias_semana_criar input:checked')).map(cb => cb.value);

        const payload = {
            nome_turma: document.getElementById('nome_turma').value,
            curso_id: document.getElementById('curso_id').value,
            ambiente_id: document.getElementById('ambiente_id').value,
            data_inicio_turma: document.getElementById('data_inicio_turma').value,
            data_termino_turma: document.getElementById('data_termino_turma').value,
            turno_id: document.getElementById('turno_id').value,
            minuto_aula_id: document.getElementById('minuto_aula_id').value,
            capacidade_turma: document.getElementById('capacidade_turma').value,
            capacidade_atual: document.getElementById('capacidade_atual').value,
            status_turma_id: document.getElementById('status_turma_id').value,
            colaboradores_ids: [document.getElementById('colaborador_id').value],
            dias_da_semana_ids: diasSelecionados
        };

        try {
            const response = await fetch(`${API_URL}/turmas`, {
                method: 'POST',
                headers: AUTH_HEADERS, // Token Aqui 
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao criar turma.');
            showSuccess('Turma criada com sucesso!');
            modalCriar.style.display = 'none';
            carregarTurmas();
        } catch (error) {
            showError(error.message);
        }
    });

    listaTurmas.addEventListener('click', async (e) => {
        const editBtn = e.target.closest('.editar_docente');
        if (editBtn) {
            const turmaId = editBtn.dataset.id;
            try {
                await Promise.all([
                    popularSelect('edit_curso_id', 'cursos', 'nome_curso'),
                    popularSelect('edit_colaborador_id', 'colaboradores', 'nome_colaborador'),
                    popularSelect('edit_ambiente_id', 'ambientes', 'nome_ambiente'),
                    popularSelect('edit_turno_id', 'turnos', 'nome_turno'),
                    popularSelect('edit_minuto_aula_id', 'minutos-aulas', 'quant_minuto_aula'),
                    popularSelect('edit_status_turma_id', 'status-turmas', 'nome_status_turma')
                ]);

                // Passa o Header com Token
                const response = await fetch(`${API_URL}/turmas/${turmaId}`, {
                    headers: AUTH_HEADERS
                });
                if (!response.ok) throw new Error('Falha ao buscar dados da turma.');
                const data = await response.json();
                const turma = data.data || data;

                document.getElementById('edit_turma_id').value = turma.id;
                document.getElementById('edit_nome_turma').value = turma.nome_turma;
                document.getElementById('edit_curso_id').value = turma.curso_id;
                document.getElementById('edit_ambiente_id').value = turma.ambiente_id;
                document.getElementById('edit_capacidade_turma').value = turma.capacidade_turma;

                // --- PREENCHE A NOVA COLUNA NO EDITAR ---
                document.getElementById('edit_capacidade_atual').value = turma.capacidade_atual;

                document.getElementById('edit_data_inicio_turma').value = turma.data_inicio_turma;
                document.getElementById('edit_data_termino_turma').value = turma.data_termino_turma;
                document.getElementById('edit_turno_id').value = turma.turno_id;
                document.getElementById('edit_minuto_aula_id').value = turma.minuto_aula_id;
                document.getElementById('edit_status_turma_id').value = turma.status_turma_id;

                if (turma.colaboradores && turma.colaboradores.length > 0) {
                    document.getElementById('edit_colaborador_id').value = turma.colaboradores[0].id;
                }

                document.querySelectorAll('#dias_semana_editar input[type="checkbox"]').forEach(cb => cb.checked = false);

                if (turma.dias_da_semana && Array.isArray(turma.dias_da_semana)) {
                    turma.dias_da_semana.forEach(dia => {
                        const checkbox = document.querySelector(`#dias_semana_editar input[value="${dia.id}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }

                modalEditar.style.display = 'block';
            } catch (error) {
                showError(error.message);
            }
        }
    });

    // =========================================================================
    //              EDITAR TURMA (COM TOKEN)
    // =========================================================================
    formEditar.addEventListener('submit', async (e) => {
        e.preventDefault();
        const turmaId = document.getElementById('edit_turma_id').value;
        const diasSelecionados = Array.from(document.querySelectorAll('#dias_semana_editar input:checked')).map(cb => cb.value);

        const payload = {
            nome_turma: document.getElementById('edit_nome_turma').value,
            curso_id: document.getElementById('edit_curso_id').value,
            ambiente_id: document.getElementById('edit_ambiente_id').value,
            data_inicio_turma: document.getElementById('edit_data_inicio_turma').value,
            data_termino_turma: document.getElementById('edit_data_termino_turma').value,
            turno_id: document.getElementById('edit_turno_id').value,
            minuto_aula_id: document.getElementById('edit_minuto_aula_id').value,
            capacidade_turma: document.getElementById('edit_capacidade_turma').value,

            // --- ENVIA A NOVA COLUNA NA EDIÇÃO ---
            capacidade_atual: document.getElementById('edit_capacidade_atual').value,

            status_turma_id: document.getElementById('edit_status_turma_id').value,
            colaboradores_ids: [document.getElementById('edit_colaborador_id').value],
            dias_da_semana_ids: diasSelecionados
        };

        try {
            const response = await fetch(`${API_URL}/turmas/${turmaId}`, {
                method: 'PUT',
                headers: AUTH_HEADERS, // Token Aqui
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao editar turma.');
            showSuccess('Turma atualizada com sucesso!');
            modalEditar.style.display = 'none';
            carregarTurmas();
        } catch (error) {
            showError(error.message);
        }
    });

    // --- LÓGICA DE PESQUISA ---
    searchInput.addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('#lista-turmas .info_docente').forEach(card => {
            const nomeTexto = card.querySelector('.nome').textContent.toLowerCase();
            if (nomeTexto.includes(searchTerm)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // --- INICIALIZAÇÃO ---
    carregarTurmas();
});