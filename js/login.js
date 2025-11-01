document.addEventListener('DOMContentLoaded', () => {

    const API_URL = 'http://127.0.0.1:8000/api';

    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const email = document.getElementById('email_colaborador').value;
            const password = document.getElementById('senha_colaborador').value;

            Swal.fire({
                title: 'Aguarde...',
                text: 'Verificando suas credenciais.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                // --- ETAPA 1: TENTATIVA DE LOGIN ---
                const loginResponse = await fetch(`${API_URL}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email_colaborador: email,
                        senha_colaborador: password,
                    }),
                });

                const loginData = await loginResponse.json();
                if (!loginResponse.ok) {
                    throw new Error(loginData.message || 'Email ou senha inválidos.');
                }

                const authToken = loginData.access_token;
                const user = loginData.user;

                // Guarda os dados no localStorage
                localStorage.setItem('authToken', authToken);
                localStorage.setItem('user', JSON.stringify(user));

                // Mostra o alerta de sucesso e, DEPOIS, verifica o nível
                await Swal.fire({
                    icon: 'success',
                    title: 'Login bem-sucedido!',
                    text: `Bem-vindo, ${user.nome_colaborador}!`,
                    timer: 1500, // Diminuí o tempo para a transição ser mais rápida
                    showConfirmButton: false
                });

                // --- ETAPA 2: VERIFICAR NÍVEL E REDIRECIONAR ---
                await verificarNivelERedirecionar(user.id, authToken);

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Falha no Login',
                    text: error.message,
                });
            }
        });
    }

    async function verificarNivelERedirecionar(userId, token) {
        try {
            const nivelResponse = await fetch(`${API_URL}/colaboradores/${userId}/verificar-nivel`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!nivelResponse.ok) {
                throw new Error('Não foi possível verificar seu nível de acesso.');
            }

            const nivelData = await nivelResponse.json();
            const nivel = nivelData.tipo_colaborador_id;

            // --- LÓGICA DE REDIRECIONAMENTO ---
            switch (nivel) {
                case 1:
                    // Altere 'admin_dashboard.html' para a sua página de admin
                    window.location.href = 'home.php';
                    break;
                case 2:
                    // Altere 'instrutor_home.html' para a sua página de instrutor
                    window.location.href = 'adm_turma.php';
                    break;
                case 3:
                    // Altere 'coordenador_area.html' para a sua página de coordenador
                    window.location.href = 'perfil.php';
                    break;
                default:
                    // Se o nível não for reconhecido, vai para uma página padrão
                    window.location.href = 'ambientes.php';
                    break;
            }

        } catch (error) {
            // Se a verificação de nível falhar, exibe um erro
            Swal.fire({
                icon: 'error',
                title: 'Erro de Permissão',
                text: error.message,
            });
        }
    }
});