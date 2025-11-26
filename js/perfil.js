document.addEventListener('DOMContentLoaded', () => {
    // --- CONFIGURAÇÕES GLOBAIS ---
    const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
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

    // Função corrigida para corresponder ao novo HTML (sem o user-status)
    function preencherDadosUsuario(usuarioAtualizado) {
        document.getElementById('user-name').textContent = usuarioAtualizado.nome_colaborador;
        document.getElementById('user-email-display').textContent = usuarioAtualizado.email_colaborador;
        document.getElementById('user-email').textContent = usuarioAtualizado.email_colaborador;
        document.getElementById('user-specialty').textContent = usuarioAtualizado.especialidade_colaborador || 'Não informado';

        // Lógica para exibir a cor com um swatch visual
        const userColorSpan = document.getElementById('user-color');
        if (userColorSpan) {
            const cor = usuarioAtualizado.cor_colaborador || '#cccccc'; // Cor padrão caso não haja
            userColorSpan.innerHTML = `
                ${cor.toUpperCase()} 
                <span style="display: inline-block; width: 15px; height: 15px; background-color: ${cor}; border: 1px solid #ccc; vertical-align: middle; border-radius: 3px;"></span>
            `;
        }

        // AS LINHAS PARA ATUALIZAR O STATUS FORAM REMOVIDAS DAQUI
        /*
        const statusElement = document.getElementById('user-status').querySelector('span');
        statusElement.textContent = usuarioAtualizado.status_colaborador == 1 ? 'Ativo' : 'Inativo';
        */

        const dataCadastro = new Date(usuarioAtualizado.created_at);
        document.getElementById('user-created').textContent = dataCadastro.toLocaleDateString('pt-BR');
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
        } catch (error) {
            Swal.fire('Erro!', error.message, 'error');
        }
    }

    // --- INICIALIZAÇÃO ---
    carregarDadosDoPerfil();
});