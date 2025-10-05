document.addEventListener('DOMContentLoaded', () => {
    // --- CONFIGURAÇÕES GLOBAIS ---
    const API_URL = 'http://127.0.0.1:8000/api';
    const TOKEN = localStorage.getItem('authToken');
    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    // --- SELETORES DE ELEMENTOS ---
    const listaTurmasContainer = document.getElementById('lista-turmas');
    const inputPesquisa = document.getElementById('input-pesquisa-turma');

    let todosOsAmbientes = []; // Armazena os ambientes para usar nos modais

    // --- FUNÇÕES AUXILIARES ---
    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    const showError = (msg) => Swal.fire('Erro!', msg, 'error');

    // --- FUNÇÕES DA API ---

    // Carrega a lista de ambientes uma única vez para usar nos modais
    async function carregarAmbientes() {
        if (todosOsAmbientes.length > 0) return; // Não carrega se já tiver os dados
        try {
            const response = await fetch(`${API_URL}/ambientes`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar ambientes.');
            const data = await response.json();
            todosOsAmbientes = data.data || data;
        } catch (error) {
            console.error(error);
            showError('Não foi possível carregar a lista de ambientes.');
        }
    }

    // Cria o HTML de um card de turma
    function criarCardTurma(turma) {
        const docenteNome = turma.colaboradores && turma.colaboradores.length > 0
            ? turma.colaboradores[0].nome_colaborador
            : 'Nenhum docente alocado';

        const ambienteAtual = turma.ambiente?.nome_ambiente ?? 'Nenhum';

        return `
            <div class="turma-card" data-id="${turma.id}">
                <h3>${turma.nome_turma}</h3>
                <p><i class="bi bi-person"></i> Docente: ${docenteNome}</p>
                <p><i class="bi bi-geo-alt-fill"></i> Atual Ambiente: ${ambienteAtual}</p>
                <button class="alterar-btn" data-id="${turma.id}" data-nome="${turma.nome_turma}">Alterar</button>
            </div>
        `;
    }

    // Carrega todas as turmas da API e as exibe na tela
    async function carregarTurmas() {
        await carregarAmbientes(); // Garante que os ambientes estão disponíveis para os modais
        try {
            const response = await fetch(`${API_URL}/turmas`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar turmas.');
            const data = await response.json();
            const turmas = data.data || data;

            listaTurmasContainer.innerHTML = '';
            if (turmas.length > 0) {
                turmas.forEach(turma => listaTurmasContainer.innerHTML += criarCardTurma(turma));
            } else {
                listaTurmasContainer.innerHTML = '<p>Nenhuma turma encontrada.</p>';
            }
        } catch (error) { showError(error.message); }
    }

    // Envia a requisição para alterar o ambiente de uma turma
    async function confirmarAlteracao(turmaId, novoAmbienteId) {
        try {
            const response = await fetch(`${API_URL}/turmas/${turmaId}/update-ambiente`, {
                method: 'PATCH',
                headers: AUTH_HEADERS,
                body: JSON.stringify({ ambiente_id: novoAmbienteId })
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao alterar alocação.');

            showSuccess('Alocação alterada com sucesso!');
            carregarTurmas(); // Recarrega a lista para mostrar a alteração

        } catch (error) { showError(error.message); }
    }

    // --- EVENT LISTENERS ---

    // Filtro de pesquisa
    inputPesquisa.addEventListener('keyup', () => {
        const termo = inputPesquisa.value.toLowerCase();
        document.querySelectorAll('.turma-card').forEach(card => {
            const nome = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = nome.includes(termo) ? 'block' : 'none';
        });
    });

    // Delegação de evento para os botões "Alterar"
    listaTurmasContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('alterar-btn')) {
            const turmaId = e.target.dataset.id;
            const nomeTurma = e.target.dataset.nome;

            // Cria um objeto com os ambientes para o SweetAlert2 usar
            const inputOptions = new Promise((resolve) => {
                const options = {};
                todosOsAmbientes.forEach(ambiente => {
                    options[ambiente.id] = ambiente.nome_ambiente;
                });
                resolve(options);
            });

            Swal.fire({
                title: `Alterar ambiente de:<br>${nomeTurma}`,
                input: 'select',
                inputOptions: inputOptions,
                inputPlaceholder: 'Selecione o novo ambiente',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    confirmarAlteracao(turmaId, result.value);
                }
            });
        }
    });

    // --- INICIALIZAÇÃO ---
    carregarTurmas();
});

