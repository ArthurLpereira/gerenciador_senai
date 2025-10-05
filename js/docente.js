document.addEventListener('DOMContentLoaded', () => {
    // --- CONFIGURAÇÕES GLOBAIS ---
    const API_URL = 'http://127.0.0.1:8000/api';
    const TOKEN = localStorage.getItem('authToken');
    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    // --- SELETORES DE ELEMENTOS DO DOM ---
    const listaColaboradores = document.getElementById('lista-colaboradores');
    const modalCriar = document.getElementById('modalCriar');
    const modalEditar = document.getElementById('modalEditar');
    const abrirModalBtn = document.getElementById('abrirModal');
    const fecharModalBtns = document.querySelectorAll('.close');
    const formCriar = document.getElementById('formCriar');
    const formEditar = document.getElementById('formEditar');

    // --- FUNÇÕES AUXILIARES ---
    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    const showError = (msg) => Swal.fire('Erro!', msg, 'error');

    // --- FUNÇÕES PRINCIPAIS ---
    function criarCardColaborador(colaborador) {
        const statusText = colaborador.status_colaborador == 1 ? 'Ativo' : 'Inativo';
        const statusClass = colaborador.status_colaborador == 1 ? 'ativo' : 'inativo';
        const statusIconClass = colaborador.status_colaborador == 1 ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        const tipoColaboradorNome = colaborador.tipos_colaboradore?.nome_tipo_colaborador ?? 'Não especificado'; // Ajuste o nome da relação se necessário

        return `
            <div class="info_docente" data-id="${colaborador.id}">
                <div class="conteudo">
                    <p class="nome">Nome: <b>${colaborador.nome_colaborador}</b></p>
                    <p class="especialidade"><b><i class="bi bi-briefcase-fill"></i></b> Especialidade: ${colaborador.especialidade_colaborador || 'N/A'}</p>
                    <p class="especialidade"><b><i class="bi bi-briefcase-fill"></i></b> Email: ${colaborador.email_colaborador || 'N/A'}</p>
                    <p class="tipo"><b><i class="bi bi-person-badge"></i></b> Cargo: ${tipoColaboradorNome}</p>
                    <p class="tipo"><b><i class="bi bi-person-badge"></i></b> Cor: ${colaborador.cor_colaborador}</p>
                    <p class="status"><b><i class="bi bi-arrow-clockwise"></i></b> Status: ${statusText}</p>
                </div>
                <div class="funcoes">
                    <button class="editar_docente" data-id="${colaborador.id}"><i class="bi bi-pen-fill"></i> Editar</button>
                    <button class="status_docente ${statusClass}" data-id="${colaborador.id}"><i class="bi ${statusIconClass}"></i> ${statusText}</button>
                </div>
            </div>`;
    }

    async function carregarColaboradores() {
        try {
            const response = await fetch(`${API_URL}/colaboradores`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar colaboradores.');
            const data = await response.json();
            const colaboradores = data.data || data;

            listaColaboradores.innerHTML = '';
            if (colaboradores.length > 0) {
                colaboradores.forEach(col => listaColaboradores.innerHTML += criarCardColaborador(col));
            } else {
                listaColaboradores.innerHTML = '<p>Nenhum colaborador encontrado.</p>';
            }
        } catch (error) { showError('Não foi possível carregar os colaboradores.'); console.error(error); }
    }

    async function carregarTipos(selectElement) {
        try {
            const response = await fetch(`${API_URL}/tipos-colaboradores`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar tipos.');
            const data = await response.json();
            const tipos = data.data || data;
            selectElement.innerHTML = '<option value="" disabled selected>Selecione um Tipo</option>';
            tipos.forEach(tipo => {
                selectElement.innerHTML += `<option value="${tipo.id}">${tipo.nome_tipo_colaborador}</option>`;
            });
        } catch (error) { console.error(error); }
    }

    abrirModalBtn.addEventListener('click', () => {
        formCriar.reset();
        carregarTipos(document.getElementById('tipo_colaborador_id'));
        modalCriar.style.display = 'block';
    });

    fecharModalBtns.forEach(btn => btn.addEventListener('click', () => btn.closest('.modal').style.display = 'none'));
    window.addEventListener('click', (e) => {
        if (e.target == modalCriar) modalCriar.style.display = 'none';
        if (e.target == modalEditar) modalEditar.style.display = 'none';
    });

    formCriar.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = {
            nome_colaborador: document.getElementById('nome_colaborador').value,
            email_colaborador: document.getElementById('email_colaborador').value,
            senha_colaborador: document.getElementById('senha_colaborador').value,
            especialidade_colaborador: document.getElementById('especialidade_colaborador').value,
            cor_colaborador: document.getElementById('cor_colaborador').value,
            tipo_colaborador_id: document.getElementById('tipo_colaborador_id').value,
        };
        try {
            const response = await fetch(`${API_URL}/colaboradores`, { method: 'POST', headers: AUTH_HEADERS, body: JSON.stringify(payload) });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao criar colaborador.');
            showSuccess('Colaborador criado com sucesso!');
            modalCriar.style.display = 'none';
            carregarColaboradores();
        } catch (error) { showError(error.message); }
    });

    formEditar.addEventListener('submit', async (e) => {
        e.preventDefault();
        const colaboradorId = document.getElementById('edit_colaborador_id').value;
        const originais = JSON.parse(e.currentTarget.dataset.originalData);
        const payload = {};

        const nome = document.getElementById('edit_nome_colaborador').value;
        if (nome !== originais.nome_colaborador) payload.nome_colaborador = nome;
        const email = document.getElementById('edit_email_colaborador').value;
        if (email !== originais.email_colaborador) payload.email_colaborador = email;
        const especialidade = document.getElementById('edit_especialidade_colaborador').value;
        if (especialidade !== originais.especialidade_colaborador) payload.especialidade_colaborador = especialidade;
        const tipo = document.getElementById('edit_tipo_colaborador_id').value;
        if (tipo != originais.tipo_colaborador_id) payload.tipo_colaborador_id = tipo;
        const cor = document.getElementById('edit_cor_colaborador').value;
        if (cor !== originais.cor_colaborador) payload.cor_colaborador = cor;

        if (Object.keys(payload).length === 0) {
            modalEditar.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`${API_URL}/colaboradores/${colaboradorId}`, { method: 'PUT', headers: AUTH_HEADERS, body: JSON.stringify(payload) });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao editar colaborador.');
            showSuccess('Colaborador atualizado com sucesso!');
            modalEditar.style.display = 'none';
            carregarColaboradores();
        } catch (error) { showError(error.message); }
    });

    listaColaboradores.addEventListener('click', async (e) => {
        const editBtn = e.target.closest('.editar_docente');
        if (editBtn) {
            const colaboradorId = editBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/colaboradores/${colaboradorId}`, { headers: AUTH_HEADERS });
                if (!response.ok) throw new Error('Falha ao buscar dados do colaborador.');
                const data = await response.json();
                const colaborador = data.data || data;

                formEditar.dataset.originalData = JSON.stringify(colaborador);
                document.getElementById('edit_colaborador_id').value = colaborador.id;
                document.getElementById('edit_nome_colaborador').value = colaborador.nome_colaborador;
                document.getElementById('edit_email_colaborador').value = colaborador.email_colaborador;
                document.getElementById('edit_especialidade_colaborador').value = colaborador.especialidade_colaborador;
                document.getElementById('edit_cor_colaborador').value = colaborador.cor_colaborador;

                await carregarTipos(document.getElementById('edit_tipo_colaborador_id'));
                document.getElementById('edit_tipo_colaborador_id').value = colaborador.tipo_colaborador_id;

                modalEditar.style.display = 'block';
            } catch (error) { showError(error.message); }
        }

        const statusBtn = e.target.closest('.status_docente');
        if (statusBtn) {
            const colaboradorId = statusBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/colaboradores/${colaboradorId}/toggle-status`, { method: 'POST', headers: AUTH_HEADERS });
                if (!response.ok) throw new Error('Falha ao alterar status.');
                showSuccess('Status alterado com sucesso!');
                carregarColaboradores();
            } catch (error) { showError(error.message); }
        }
    });

    document.getElementById('searchInput').addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.info_docente').forEach(card => {
            const nome = card.querySelector('.nome b').textContent.toLowerCase();
            card.style.display = nome.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    // --- INICIALIZAÇÃO ---
    carregarColaboradores();
});