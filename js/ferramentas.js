document.addEventListener('DOMContentLoaded', () => {
    // --- CONFIGURAÇÕES GLOBAIS E AUTENTICAÇÃO ---
    const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
    const TOKEN = localStorage.getItem('authToken');
    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    // --- SELETORES DE ELEMENTOS ---
    const userTableBody = document.getElementById('user-table-body');
    const searchInput = document.getElementById('searchInput');
    let tiposColaboradores = []; // Armazena os tipos para usar nos dropdowns

    // --- FUNÇÕES AUXILIARES ---
    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    const showError = (msg) => Swal.fire('Erro!', msg, 'error');

    // --- FUNÇÕES PRINCIPAIS ---

    // Função para buscar os tipos de colaboradores (cargos) da API
    async function carregarTipos() {
        try {
            const response = await fetch(`${API_URL}/tipos-colaboradores`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar tipos de colaboradores.');
            const data = await response.json();
            tiposColaboradores = data.data || data;
        } catch (error) {
            showError(error.message);
        }
    }

    // Função para criar a linha (<tr>) de um usuário na tabela
    function criarLinhaUsuario(user) {
        const tipoAtual = tiposColaboradores.find(t => t.id === user.tipo_colaborador_id);
        const nomeTipoAtual = tipoAtual ? tipoAtual.nome_tipo_colaborador : 'Indefinido';

        // Cria as opções do dropdown para outros tipos
        let dropdownOptions = '';
        tiposColaboradores.forEach(tipo => {
            if (tipo.id !== user.tipo_colaborador_id) { // Não mostra o tipo atual na lista
                dropdownOptions += `<button data-user-id="${user.id}" data-role-id="${tipo.id}">${tipo.nome_tipo_colaborador}</button>`;
            }
        });

        return `
            <tr data-user-id="${user.id}">
                <td>
                    <img src="./images/foto_perfil.png" alt="Foto do Docente" class="profile-pic">
                </td>
                <td data-filter-name>${user.nome_colaborador}</td>
                <td data-filter-email>${user.email_colaborador}</td>
                <td>
                    <div class="action-dropdown">
                        <button class="action-btn">${nomeTipoAtual} <span class="arrow"></span></button>
                        <div class="dropdown-content">
                            ${dropdownOptions}
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }

    // Função principal para carregar e exibir todos os usuários
    async function carregarUsuarios() {
        await carregarTipos(); // Garante que os tipos já foram carregados
        try {
            const response = await fetch(`${API_URL}/colaboradores`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar usuários.');
            const data = await response.json();
            const usuarios = data.data || data;

            userTableBody.innerHTML = ''; // Limpa a tabela
            if (usuarios.length > 0) {
                usuarios.forEach(user => userTableBody.innerHTML += criarLinhaUsuario(user));
            } else {
                userTableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Nenhum usuário encontrado.</td></tr>';
            }
        } catch (error) {
            showError(error.message);
        }
    }

    // Função para atualizar o nível de um usuário
    async function atualizarNivel(userId, novoTipoId) {
        try {
            const response = await fetch(`${API_URL}/colaboradores/${userId}/update-nivel`, {
                method: 'PUT',
                headers: AUTH_HEADERS,
                body: JSON.stringify({ tipo_colaborador_id: novoTipoId })
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao atualizar nível.');

            showSuccess('Nível do usuário atualizado com sucesso!');
            carregarUsuarios(); // Recarrega a lista para mostrar a alteração
        } catch (error) {
            showError(error.message);
        }
    }

    // --- EVENT LISTENERS ---

    // Filtro de pesquisa
    searchInput.addEventListener('keyup', () => {
        const searchTerm = searchInput.value.toLowerCase();
        document.querySelectorAll('#user-table-body tr').forEach(row => {
            const nome = row.querySelector('[data-filter-name]').textContent.toLowerCase();
            const email = row.querySelector('[data-filter-email]').textContent.toLowerCase();
            row.style.display = (nome.includes(searchTerm) || email.includes(searchTerm)) ? '' : 'none';
        });
    });

    // Delegação de eventos para os dropdowns de ação
    userTableBody.addEventListener('click', (e) => {
        // Lógica para abrir/fechar o dropdown
        const actionBtn = e.target.closest('.action-btn');
        if (actionBtn) {
            // Fecha todos os outros dropdowns abertos
            document.querySelectorAll('.action-dropdown.open').forEach(dropdown => {
                if (dropdown !== actionBtn.parentElement) {
                    dropdown.classList.remove('open');
                }
            });
            // Alterna o estado do dropdown clicado
            actionBtn.parentElement.classList.toggle('open');
        }

        // Lógica para quando uma opção do dropdown é selecionada
        const optionBtn = e.target.closest('.dropdown-content button');
        if (optionBtn) {
            const userId = optionBtn.dataset.userId;
            const roleId = optionBtn.dataset.roleId;
            atualizarNivel(userId, roleId);
        }
    });

    // Fecha os dropdowns se clicar fora deles
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.action-dropdown')) {
            document.querySelectorAll('.action-dropdown.open').forEach(dropdown => {
                dropdown.classList.remove('open');
            });
        }
    });

    // --- INICIALIZAÇÃO ---
    carregarUsuarios();
});

