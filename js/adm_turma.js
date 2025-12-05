document.addEventListener('DOMContentLoaded', () => {

    // --- CONFIGURAÇÕES GLOBAIS ---
    const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
    const TOKEN = localStorage.getItem('authToken');

    if (!TOKEN) {
        Swal.fire({
            icon: 'error',
            title: 'Não autenticado!',
            text: 'Você precisa fazer login para ver as turmas.',
        });
        return;
    }

    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json', 'Accept': 'application/json',
    };

    // --- SELETORES DE ELEMENTOS ---
    const listaTurmas = document.getElementById('lista-turmas');
    const modalEditar = document.getElementById('modalEditar');
    const fecharModalBtns = document.querySelectorAll('.close');
    const formEditar = document.getElementById('formEditar');
    const searchInput = document.getElementById('searchInput');

    // --- FUNÇÕES AUXILIARES ---
    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    const showError = (msg) => Swal.fire('Erro!', msg, 'error');

    // --- FUNÇÕES DE LÓGICA PRINCIPAL ---

    function criarCardTurma(turma) {
        const statusText = turma.status_turma?.nome_status_turma ?? 'Indefinido';
        const cursoNome = turma.curso?.nome_curso ?? 'N/A';
        const ambienteNome = turma.ambiente?.nome_ambiente ?? 'N/A';
        const quantidade_turma = turma.capacidade_turma ?? '0';
        const quantidade_atual = turma.capacidade_atual ?? '0';
        const dataFormatadaInicio = new Date(turma.data_inicio_turma + 'T00:00:00').toLocaleDateString('pt-BR');
        const dataFormatadaTermino = new Date(turma.data_termino_turma + 'T00:00:00').toLocaleDateString('pt-BR');

        let diasDaSemanaTexto = 'Dias não definidos';

        if (turma.dias_da_semana && turma.dias_da_semana.length > 0) {
            diasDaSemanaTexto = turma.dias_da_semana
                .map(dia => dia.nome_dia_da_semana)
                .join(', ');
        }

        return `
        <div class="info_docente" data-id="${turma.id}">
            <div class="conteudo">
                <p class="nome"><b>Turma: </b> ${turma.nome_turma} (${cursoNome})</p>
                <p><i class="bi bi-calendar-event"></i> Início: ${dataFormatadaInicio}</p>
                <p><i class="bi bi-calendar-event-fill"></i> Término: ${dataFormatadaTermino}</p>
                <p><i class="bi bi-calendar-event-fill"></i> Quantidade Máxima: ${quantidade_turma}</p>
                <p><i class="bi bi-calendar-event-fill"></i> Quantidade Atual: ${quantidade_atual}</p>
                <p><i class="bi bi-geo-alt-fill"></i> Ambiente: ${ambienteNome}</p>
                <p><i class="bi bi-calendar-week-fill"></i> Dias: ${diasDaSemanaTexto}</p> 
                <p><i class="bi bi-person-check-fill"></i> Status: ${statusText}</p>
            </div>
            <div class="funcoes">
                <button class="editar_docente" data-id="${turma.id}"><i class="bi bi-pen-fill"></i> Editar</button>
            </div>
        </div>
    `;
    }

    async function carregarTurmas() {
        if (!listaTurmas) {
            console.error("Erro Crítico: O elemento com ID 'lista-turmas' não foi encontrado no HTML.");
            showError("Erro de configuração da página. Contacte o administrador.");
            return;
        }
        try {
            const response = await fetch(`${API_URL}/turmas`, { headers: AUTH_HEADERS });
            if (!response.ok) {
                const errorData = await response.json().catch(() => null);
                const errorMessage = errorData?.message || `Falha ao carregar turmas. Status: ${response.status}`;
                throw new Error(errorMessage);
            }
            const data = await response.json();
            const turmas = data.data || data;
            listaTurmas.innerHTML = '';
            if (turmas.length > 0) {
                turmas.forEach(turma => {
                    listaTurmas.innerHTML += criarCardTurma(turma);
                });
            } else {
                listaTurmas.innerHTML = '<p>Nenhuma turma encontrada.</p>';
            }
        } catch (error) {
            showError(error.message || 'Não foi possível carregar as turmas.');
            console.error(error);
        }
    }

    // --- EVENT LISTENERS ---

    // Fechar modais
    fecharModalBtns.forEach(btn => btn.addEventListener('click', () => {
        btn.closest('.modal').style.display = 'none';
    }));
    window.addEventListener('click', (e) => {
        if (e.target == modalEditar) modalEditar.style.display = 'none';
    });

    // Abrir e popular modal de edição
    listaTurmas.addEventListener('click', async (e) => {
        const editBtn = e.target.closest('.editar_docente');
        if (editBtn) {
            const turmaId = editBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/turmas/${turmaId}`, { headers: AUTH_HEADERS });
                if (!response.ok) throw new Error('Falha ao buscar dados da turma.');
                const data = await response.json();
                const turma = data.data || data;

                document.getElementById('edit_turma_id').value = turma.id;
                document.getElementById('edit_nome_turma').value = turma.nome_turma;

                // --- NOVO: PREENCHE A CAPACIDADE ATUAL ---
                document.getElementById('edit_capacidade_atual').value = turma.capacidade_atual;

                modalEditar.style.display = 'block';
            } catch (error) {
                showError(error.message || 'Erro ao carregar dados da turma.');
                console.error(error);
            }
        }
    });

    // Enviar formulário de edição
    formEditar.addEventListener('submit', async (e) => {
        e.preventDefault();
        const turmaId = document.getElementById('edit_turma_id').value;

        const payload = {
            nome_turma: document.getElementById('edit_nome_turma').value,
            // --- NOVO: ENVIA A CAPACIDADE ATUAL ---
            capacidade_atual: document.getElementById('edit_capacidade_atual').value
        };

        try {
            const response = await fetch(`${API_URL}/turmas/${turmaId}`, {
                method: 'PUT',
                headers: AUTH_HEADERS,
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao editar turma.');
            showSuccess('Turma atualizada com sucesso!');
            modalEditar.style.display = 'none';
            carregarTurmas();
        } catch (error) {
            showError(error.message || 'Falha ao atualizar a turma.');
            console.error(error);
        }
    });

    // Barra de Pesquisa
    searchInput.addEventListener('keyup', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll('#lista-turmas .info_docente');
        cards.forEach(card => {
            const nomeTexto = card.querySelector('.nome').textContent.toLowerCase();
            if (nomeTexto.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // --- INICIALIZAÇÃO ---
    carregarTurmas();
});