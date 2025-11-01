// Ficheiro: ./js/cadastro.js

document.addEventListener('DOMContentLoaded', () => {

    // --- CONFIGURAÇÕES GLOBAIS ---
    const baseUrl = 'http://127.0.0.1:8000/api';

    // --- SELETORES DE ELEMENTOS ---
    const formCadastro = document.getElementById('formCadastro');
    const submitButton = document.getElementById('submit-button');

    // --- FUNÇÕES AUXILIARES ---
    const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
    // A função de erro agora vai formatar melhor as mensagens
    const showError = (msg) => Swal.fire({
        icon: 'error',
        title: 'Erro!',
        html: msg // Usamos 'html' para permitir quebras de linha (<br>)
    });

    // --- EVENT LISTENER PRINCIPAL ---

    formCadastro.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitButton.disabled = true;
        submitButton.textContent = 'Aguarde...';

        // =========================================================
        //                 CORREÇÃO FINAL DO PAYLOAD
        // =========================================================

        // Coleta dos dados do formulário
        const payload = {
            // Estes dois já estavam corretos (baseado no erro anterior)
            nome_colaborador: document.getElementById('nome').value,
            email_colaborador: document.getElementById('email').value,

            // CORRIGIDO: Adicionado o sufixo "_colaborador"
            especialidade_colaborador: document.getElementById('especialidade').value,

            // CORRIGIDO: Adicionado o sufixo "_colaborador"
            cor_colaborador: document.getElementById('color-input').value,

            // CORRIGIDO: Alterado de "password" para "senha_colaborador"
            senha_colaborador: document.getElementById('senha').value,
        };

        // =========================================================

        try {
            const response = await fetch(`${baseUrl}/colaboradores`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (!response.ok) {
                // Se a API retornar múltiplos erros, 'data.errors' existe
                if (data.errors) {
                    // Transforma a lista de erros da API numa string com quebras de linha
                    const errorMessages = Object.values(data.errors).flat().join('<br>');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Erro ao realizar o cadastro.');
            }

            await showSuccess('Cadastro realizado com sucesso! Você será redirecionado para o login.');

            window.location.href = 'index.php';

        } catch (error) {
            // Mostra o erro formatado
            showError(error.message);

            // Reativa o botão em caso de erro
            submitButton.disabled = false;
            submitButton.textContent = 'Cadastrar';
        }
    });

});