document.addEventListener('DOMContentLoaded', () => {
    // --- CONFIGURAÇÕES GLOBAIS ---
    const API_URL = 'http://127.0.0.1:8000/api';
    const TOKEN = localStorage.getItem('authToken');
    const USER_STRING = localStorage.getItem('user');

    if (!USER_STRING || !TOKEN) {
        window.location.href = './index.php'; // Redireciona se não estiver logado
        return;
    }

    let user = JSON.parse(USER_STRING); // Usamos 'let' para poder atualizar o objeto

    const AUTH_HEADERS = {
        'Authorization': `Bearer ${TOKEN}`,
        'Content-Type': 'application/json', 'Accept': 'application/json',
    };

    // --- FUNÇÕES ---

    // Função corrigida para corresponder ao novo HTML
    function preencherDadosUsuario(usuarioAtualizado) {
        document.getElementById('user-name').textContent = usuarioAtualizado.nome_colaborador;
        document.getElementById('user-email-display').textContent = usuarioAtualizado.email_colaborador;
        document.getElementById('user-email').textContent = usuarioAtualizado.email_colaborador;
        document.getElementById('user-specialty').textContent = usuarioAtualizado.especialidade_colaborador || 'Não informado';

        // Adiciona a lógica para exibir a cor com um swatch visual
        const userColorSpan = document.getElementById('user-color');
        if (userColorSpan) {
            const cor = usuarioAtualizado.cor_colaborador || '#cccccc'; // Cor padrão caso não haja
            userColorSpan.innerHTML = `
                ${cor.toUpperCase()} 
                <span style="display: inline-block; width: 15px; height: 15px; background-color: ${cor}; border: 1px solid #ccc; vertical-align: middle; border-radius: 3px;"></span>
            `;
        }

        const statusElement = document.getElementById('user-status').querySelector('span');
        statusElement.textContent = usuarioAtualizado.status_colaborador == 1 ? 'Ativo' : 'Inativo';

        const dataCadastro = new Date(usuarioAtualizado.created_at);
        document.getElementById('user-created').textContent = dataCadastro.toLocaleDateString('pt-BR');
    }

    async function carregarPerfis(tipoAtualId) {
        const select = document.getElementById('perfil-select');
        try {
            const response = await fetch(`${API_URL}/tipos-colaboradores`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Falha ao carregar perfis.');
            const data = await response.json();
            const perfis = data.data || data;

            select.innerHTML = '';
            perfis.forEach(perfil => {
                const option = document.createElement('option');
                option.value = perfil.id;
                option.textContent = perfil.nome_tipo_colaborador;
                if (perfil.id === tipoAtualId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option>Erro ao carregar</option>';
        }
    }

    async function carregarDadosDoPerfil() {
        try {
            const response = await fetch(`${API_URL}/colaboradores/${user.id}`, { headers: AUTH_HEADERS });
            if (!response.ok) throw new Error('Não foi possível carregar os dados do perfil.');

            const data = await response.json();
            const usuarioAtualizado = data.data || data;

            user = usuarioAtualizado;
            localStorage.setItem('user', JSON.stringify(user));

            preencherDadosUsuario(user);
            carregarPerfis(user.tipo_colaborador_id);

        } catch (error) {
            Swal.fire('Erro!', error.message, 'error');
        }
    }

    async function atualizarPerfil(novoPerfilId) {
        const result = await Swal.fire({
            title: 'Confirmar alteração',
            text: "Você tem certeza que deseja alterar seu nível de acesso?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, alterar!',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${API_URL}/colaboradores/${user.id}/update-nivel`, {
                    method: 'PUT',
                    headers: AUTH_HEADERS,
                    body: JSON.stringify({ tipo_colaborador_id: novoPerfilId })
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Erro ao atualizar perfil.');

                user.tipo_colaborador_id = parseInt(novoPerfilId);
                localStorage.setItem('user', JSON.stringify(user));

                Swal.fire('Sucesso!', 'Seu perfil foi atualizado.', 'success');
            } catch (error) {
                Swal.fire('Erro!', error.message, 'error');
                carregarPerfis(user.tipo_colaborador_id);
            }
        } else {
            carregarPerfis(user.tipo_colaborador_id);
        }
    }

    // --- EVENT LISTENERS ---
    document.getElementById('perfil-select').addEventListener('change', (event) => {
        atualizarPerfil(event.target.value);
    });

    // --- INICIALIZAÇÃO ---
    carregarDadosDoPerfil();
});

