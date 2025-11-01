document.addEventListener('DOMContentLoaded', () => {
    const API_URL = 'http://127.0.0.1:8000/api';
    const TOKEN = localStorage.getItem('authToken');
    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    const listaAmbientes = document.getElementById('lista-ambientes');
    const modalCriar = document.getElementById('modalCriar');
    const modalEditar = document.getElementById('modalEditar');
    const abrirModalBtn = document.getElementById('abrirModal');
    const fecharModalBtns = document.querySelectorAll('.close');
    const formCriar = document.getElementById('formCriar');
    const formEditar = document.getElementById('formEditar');

    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    const showError = (msg) => Swal.fire('Erro!', msg, 'error');

    function criarCardAmbiente(ambiente) {
        const statusText = ambiente.status_ambiente == 1 ? 'Ativo' : 'Inativo';
        const statusClass = ambiente.status_ambiente == 1 ? 'ativo' : 'inativo';
        const statusIconClass = ambiente.status_ambiente == 1 ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        const tipoAmbienteNome = ambiente.tipo_ambiente?.nome_tipo_ambiente ?? 'Não especificado';
        return `
                <div class="info_docente" data-id="${ambiente.id}">
                    <div class="conteudo">
                        <p class="nome">Nome: <b>${ambiente.nome_ambiente}</b></p>
                        <p class="tipo"><i class="bi bi-geo-alt-fill"></i> Tipo: ${tipoAmbienteNome}</p>
                        <p class="capacidade"><i class="bi bi-people-fill"></i> Capacidade: ${ambiente.capacidade_ambiente} pessoas</p>
                        <p class="status"><i class="bi bi-arrow-clockwise"></i> Status: ${statusText}</p>
                    </div>
                    <div class="funcoes">
                        <button class="editar_docente" data-id="${ambiente.id}"><i class="bi bi-pen-fill"></i> Editar</button>
                        <button class="status_docente ${statusClass}" data-id="${ambiente.id}"><i class="bi ${statusIconClass}"></i> ${statusText}</button>
                    </div>
                </div>`;
    }

    async function carregarDados(endpoint, container, cardCreatorFn, notFoundMessage) {
        try {
            const response = await fetch(`${API_URL}/${endpoint}`, {
                headers: AUTH_HEADERS
            });
            if (!response.ok) throw new Error(`Falha ao carregar ${endpoint}.`);
            const data = await response.json();
            const items = data.data || data;
            container.innerHTML = '';
            if (items.length > 0) {
                items.forEach(item => container.innerHTML += cardCreatorFn(item));
            } else {
                container.innerHTML = `<p>${notFoundMessage}</p>`;
            }
        } catch (error) {
            showError(`Não foi possível carregar os dados. Tente novamente.`);
            console.error(error);
        }
    }

    async function carregarTiposAmbiente(selectElement) {
        try {
            const response = await fetch(`${API_URL}/tipos-ambientes`, {
                headers: AUTH_HEADERS
            });
            if (!response.ok) throw new Error('Falha ao carregar tipos.');
            const data = await response.json();
            const tipos = data.data || data;
            selectElement.innerHTML = '<option value="" disabled selected>Selecione um Tipo</option>';
            tipos.forEach(tipo => {
                selectElement.innerHTML += `<option value="${tipo.id}">${tipo.nome_tipo_ambiente}</option>`;
            });
        } catch (error) {
            console.error(error);
        }
    }

    abrirModalBtn.addEventListener('click', () => {
        formCriar.reset();
        carregarTiposAmbiente(document.getElementById('tipo_ambiente_id'));
        modalCriar.style.display = 'block';
    });

    fecharModalBtns.forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.modal').style.display = 'none');
    });

    window.addEventListener('click', (event) => {
        if (event.target == modalCriar) modalCriar.style.display = 'none';
        if (event.target == modalEditar) modalEditar.style.display = 'none';
    });

    formCriar.addEventListener('submit', async (event) => {
        event.preventDefault();
        const payload = {
            nome_ambiente: document.getElementById('nome_ambiente').value,
            num_ambiente: document.getElementById('num_ambiente').value || null,
            capacidade_ambiente: document.getElementById('capacidade_ambiente').value,
            tipo_ambiente_id: document.getElementById('tipo_ambiente_id').value,
        };
        try {
            const response = await fetch(`${API_URL}/ambientes`, {
                method: 'POST',
                headers: AUTH_HEADERS,
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao criar ambiente.');
            showSuccess('Ambiente criado com sucesso!');
            modalCriar.style.display = 'none';
            carregarDados('ambientes', listaAmbientes, criarCardAmbiente, 'Nenhum ambiente encontrado.');
        } catch (error) {
            showError(error.message);
        }
    });

    formEditar.addEventListener('submit', async (event) => {
        event.preventDefault();
        const ambienteId = document.getElementById('edit_ambiente_id').value;
        const originais = JSON.parse(event.currentTarget.dataset.originalData);
        const payload = {};

        const nomeAtual = document.getElementById('edit_nome_ambiente').value;
        if (nomeAtual !== originais.nome_ambiente) payload.nome_ambiente = nomeAtual;

        const numAtual = document.getElementById('edit_num_ambiente').value;
        if (numAtual != originais.num_ambiente) payload.num_ambiente = numAtual || null;

        const capacidadeAtual = document.getElementById('edit_capacidade_ambiente').value;
        if (capacidadeAtual != originais.capacidade_ambiente) payload.capacidade_ambiente = parseInt(capacidadeAtual);

        const tipoAtual = document.getElementById('edit_tipo_ambiente_id').value;
        if (tipoAtual != originais.tipo_ambiente_id) payload.tipo_ambiente_id = parseInt(tipoAtual);

        if (Object.keys(payload).length === 0) {
            modalEditar.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`${API_URL}/ambientes/${ambienteId}`, {
                method: 'PUT',
                headers: AUTH_HEADERS,
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao editar ambiente.');
            showSuccess('Ambiente editado com sucesso!');
            modalEditar.style.display = 'none';
            carregarDados('ambientes', listaAmbientes, criarCardAmbiente, 'Nenhum ambiente encontrado.');
        } catch (error) {
            showError(error.message);
        }
    });

    listaAmbientes.addEventListener('click', async (event) => {
        const editBtn = event.target.closest('.editar_docente');
        if (editBtn) {
            const ambienteId = editBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/ambientes/${ambienteId}`, {
                    headers: AUTH_HEADERS
                });
                if (!response.ok) throw new Error('Falha ao buscar dados do ambiente.');
                const data = await response.json();
                const ambiente = data.data || data;

                formEditar.dataset.originalData = JSON.stringify(ambiente); // Armazena os dados originais

                document.getElementById('edit_ambiente_id').value = ambiente.id;
                document.getElementById('edit_nome_ambiente').value = ambiente.nome_ambiente;
                document.getElementById('edit_num_ambiente').value = ambiente.num_ambiente;
                document.getElementById('edit_capacidade_ambiente').value = ambiente.capacidade_ambiente;

                await carregarTiposAmbiente(document.getElementById('edit_tipo_ambiente_id'));
                document.getElementById('edit_tipo_ambiente_id').value = ambiente.tipo_ambiente_id;

                modalEditar.style.display = 'block';
            } catch (error) {
                showError(error.message);
            }
        }

        const statusBtn = event.target.closest('.status_docente');
        if (statusBtn) {
            const ambienteId = statusBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/ambientes/${ambienteId}/toggle-status`, {
                    method: 'POST',
                    headers: AUTH_HEADERS
                });
                if (!response.ok) throw new Error('Falha ao alterar status.');
                showSuccess('Status alterado com sucesso!');
                carregarDados('ambientes', listaAmbientes, criarCardAmbiente, 'Nenhum ambiente encontrado.');
            } catch (error) {
                showError(error.message);
            }
        }
    });

    document.getElementById('searchInput').addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.info_docente').forEach(card => {
            const nome = card.querySelector('.nome b').textContent.toLowerCase();
            card.style.display = nome.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    carregarDados('ambientes', listaAmbientes, criarCardAmbiente, 'Nenhum ambiente encontrado.');
});