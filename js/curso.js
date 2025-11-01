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
    const listaCursos = document.getElementById('lista-cursos');
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
    function criarCardCurso(curso) {
        const statusText = curso.status_curso == 1 ? 'Ativo' : 'Inativo';
        const statusClass = curso.status_curso == 1 ? 'ativo' : 'inativo';
        const statusIcon = curso.status_curso == 1 ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        const categoriaNome = curso.categorias_curso?.nome_categoria_curso ?? 'Não especificada';
        return `
            <div class="info_docente" data-id="${curso.id}">
                <div class="conteudo">
                    <p class="nome">Nome: <b>${curso.nome_curso}</b></p>
                    <p class="carga_horaria"><b><i class="bi bi-stopwatch"></i></b>Valor: ${curso.valor_curso}</p>
                    <p class="carga_horaria"><b><i class="bi bi-stopwatch"></i></b>Cor: ${curso.cor_curso}</p>
                    <p class="carga_horaria"><b><i class="bi bi-stopwatch"></i></b>Carga Horária: ${curso.carga_horaria_curso}</p>
                    <p class="tipo"><b><i class="bi bi-pc-display-horizontal"></i></b>Categoria: ${categoriaNome}</p>
                    <p class="status"><b><i class="bi bi-arrow-clockwise"></i></b>Status: ${statusText}</p>
                </div>
                <div class="funcoes_curso">
                    <button class="editar_docente" data-id="${curso.id}"><i class="bi bi-pen-fill"></i> Editar</button>
                    <button class="status_docente ${statusClass}" data-id="${curso.id}"><i class="bi ${statusIcon}"></i> ${statusText}</button>
                </div>
            </div>`;
    }

    async function carregarCursos() {
        try {
            const response = await fetch(`${API_URL}/cursos`, {
                headers: AUTH_HEADERS
            });
            if (!response.ok) throw new Error('Falha ao carregar cursos.');
            const data = await response.json();
            const cursos = data.data || data;
            listaCursos.innerHTML = '';
            if (cursos && cursos.length > 0) {
                cursos.forEach(curso => listaCursos.innerHTML += criarCardCurso(curso));
            } else {
                listaCursos.innerHTML = '<p>Nenhum curso encontrado.</p>';
            }
        } catch (error) {
            showError('Não foi possível carregar os cursos.');
            console.error(error);
        }
    }

    async function carregarCategorias(selectElement) {
        try {
            const response = await fetch(`${API_URL}/categorias-cursos`, {
                headers: AUTH_HEADERS
            });
            if (!response.ok) throw new Error('Falha ao carregar categorias.');
            const data = await response.json();
            const categorias = data.data || data;
            selectElement.innerHTML = '<option value="" disabled selected>Selecione uma Categoria</option>';
            categorias.forEach(cat => {
                selectElement.innerHTML += `<option value="${cat.id}">${cat.nome_categoria_curso}</option>`;
            });
        } catch (error) {
            console.error(error);
        }
    }

    abrirModalBtn.addEventListener('click', () => {
        formCriar.reset();
        document.getElementById('cor-quadrado').style.backgroundColor = '#CE000F';
        carregarCategorias(document.getElementById('categoria_curso_id'));
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
            nome_curso: document.getElementById('nome_curso').value,
            carga_horaria_curso: document.getElementById('carga_horaria_curso').value,
            valor_curso: document.getElementById('valor_curso').value,
            categoria_curso_id: document.getElementById('categoria_curso_id').value,
            cor_curso: document.getElementById('cor_curso').value,
        };
        try {
            const response = await fetch(`${API_URL}/cursos`, {
                method: 'POST',
                headers: AUTH_HEADERS,
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao criar curso.');
            showSuccess('Curso criado com sucesso!');
            modalCriar.style.display = 'none';
            carregarCursos();
        } catch (error) {
            showError(error.message);
        }
    });

    formEditar.addEventListener('submit', async (e) => {
        e.preventDefault();
        const cursoId = document.getElementById('edit_id_curso').value;
        const originais = JSON.parse(e.currentTarget.dataset.originalData);
        const payload = {};
        const nome = document.getElementById('edit_nome_curso').value;
        if (nome !== originais.nome_curso) payload.nome_curso = nome;
        const carga = document.getElementById('edit_carga_horaria_curso').value;
        if (carga !== originais.carga_horaria_curso) payload.carga_horaria_curso = carga;
        const valor = document.getElementById('edit_valor_curso').value;
        if (valor != originais.valor_curso) payload.valor_curso = valor;
        const categoria = document.getElementById('edit_categoria_curso_id').value;
        if (categoria != originais.categoria_curso_id) payload.categoria_curso_id = categoria;
        const cor = document.getElementById('edit_cor_curso').value;
        if (cor !== originais.cor_curso) payload.cor_curso = cor;
        if (Object.keys(payload).length === 0) {
            modalEditar.style.display = 'none';
            return;
        }
        try {
            const response = await fetch(`${API_URL}/cursos/${cursoId}`, {
                method: 'PUT',
                headers: AUTH_HEADERS,
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao editar curso.');
            showSuccess('Curso atualizado com sucesso!');
            modalEditar.style.display = 'none';
            carregarCursos();
        } catch (error) {
            showError(error.message);
        }
    });

    listaCursos.addEventListener('click', async (e) => {
        const editBtn = e.target.closest('.editar_docente');
        if (editBtn) {
            const cursoId = editBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/cursos/${cursoId}`, {
                    headers: AUTH_HEADERS
                });
                if (!response.ok) throw new Error('Falha ao buscar dados do curso.');
                const data = await response.json();
                const curso = data.data || data;
                formEditar.dataset.originalData = JSON.stringify(curso);
                document.getElementById('edit_id_curso').value = curso.id;
                document.getElementById('edit_nome_curso').value = curso.nome_curso;
                document.getElementById('edit_carga_horaria_curso').value = curso.carga_horaria_curso;
                document.getElementById('edit_valor_curso').value = curso.valor_curso;
                document.getElementById('edit_cor_curso').value = curso.cor_curso;
                document.getElementById('edit_cor_quadrado').style.backgroundColor = curso.cor_curso;
                await carregarCategorias(document.getElementById('edit_categoria_curso_id'));
                document.getElementById('edit_categoria_curso_id').value = curso.categoria_curso_id;
                modalEditar.style.display = 'block';
            } catch (error) {
                showError(error.message);
            }
        }
        const statusBtn = e.target.closest('.status_docente');
        if (statusBtn) {
            const cursoId = statusBtn.dataset.id;
            try {
                const response = await fetch(`${API_URL}/cursos/${cursoId}/toggle-status`, {
                    method: 'POST',
                    headers: AUTH_HEADERS
                });
                if (!response.ok) throw new Error('Falha ao alterar status.');
                showSuccess('Status alterado com sucesso!');
                carregarCursos();
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

    const setupColorPicker = (inputId, squareId) => {
        const colorInput = document.getElementById(inputId);
        const colorSquare = document.getElementById(squareId);
        if (!colorInput || !colorSquare) return;
        colorSquare.style.backgroundColor = colorInput.value;
        colorInput.addEventListener('input', () => colorSquare.style.backgroundColor = colorInput.value);
        colorSquare.addEventListener('click', () => colorInput.click());
    };
    setupColorPicker('cor_curso', 'cor-quadrado');
    setupColorPicker('edit_cor_curso', 'edit_cor_quadrado');

    carregarCursos();
});