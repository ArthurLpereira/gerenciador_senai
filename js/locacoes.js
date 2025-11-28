// Conteúdo do seu arquivo locacoes.js (Otimizado)

document.addEventListener('DOMContentLoaded', () => {

 // =======================================================
 // ⚙️ 1. CONFIGURAÇÕES GLOBAIS E CONSTANTES
 // =======================================================
 const API_URL = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api';
 const TOKEN = localStorage.getItem('authToken');
 const AUTH_HEADERS = {
  'Authorization': `Bearer ${TOKEN}`,
  'Content-Type': 'application/json',
  'Accept': 'application/json',
 };

 // =======================================================
 // 🔗 2. SELETORES DE ELEMENTOS DO DOM
 // =======================================================
 const listaTurmasContainer = document.getElementById('lista-turmas');
 const inputPesquisa = document.getElementById('input-pesquisa-turma');

 let todosOsAmbientes = []; 

 // =======================================================
 // 🛠️ 3. FUNÇÕES AUXILIARES
 // =======================================================
 const showSuccess = (msg) => Swal.fire('Sucesso!', msg, 'success');
 const showError = (msg) => Swal.fire('Erro!', msg, 'error');

 // =======================================================
 // 📦 4. FUNÇÕES DA API E RENDERIZAÇÃO
 // =======================================================

 // Carrega a lista de ambientes ativos
 async function carregarAmbientes() {
  if (todosOsAmbientes.length > 0) return;
  try {
   const response = await fetch(`${API_URL}/ambientes`, { headers: AUTH_HEADERS });
   if (!response.ok) throw new Error('Falha ao carregar ambientes.');
   const data = await response.json();
   // Filtra apenas ambientes com status_ambiente == 1 (ativo)
   todosOsAmbientes = (data.data || data).filter(a => a.status_ambiente == 1); 
  } catch (error) {
   console.error(error);
   // Se houver erro, apenas exibe o erro e continua a execução
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
    <h3>Nome: ${turma.nome_turma}</h3>
    <p><i class="bi bi-person"></i> Docente: ${docenteNome}</p>
    <p><i class="bi bi-geo-alt-fill"></i> Atual Ambiente: ${ambienteAtual}</p>
    <button class="alterar-btn" data-id="${turma.id}" data-nome="${turma.nome_turma}">Alterar</button>
   </div>
  `;
 }

 // Carrega todas as turmas da API e as exibe na tela
 async function carregarTurmas() {
  listaTurmasContainer.innerHTML = '<p style="text-align: center; color: #666;">Carregando turmas...</p>';
  
  // Tenta carregar os ambientes primeiro
  await carregarAmbientes();
  
  try {
   const response = await fetch(`${API_URL}/turmas`, { headers: AUTH_HEADERS });
   // Garante que a resposta seja lida mesmo em caso de falha de rede/API
   if (!response.ok) {
        let errorData = await response.json();
        throw new Error(errorData.message || `Falha ao carregar turmas. Status: ${response.status}`);
      }
   
   const data = await response.json();
   const turmas = data.data || data;

   listaTurmasContainer.innerHTML = ''; 
   
   if (turmas.length > 0) {
    turmas.forEach(turma => listaTurmasContainer.innerHTML += criarCardTurma(turma));
   } else {
    listaTurmasContainer.innerHTML = '<p style="text-align: center; color: #666;">Nenhuma turma encontrada.</p>';
   }
  } catch (error) { 
   listaTurmasContainer.innerHTML = `<p style="text-align: center; color: #C8000C; font-weight: bold;">Erro ao carregar turmas. Verifique a API. (${error.message})</p>`;
   // Se a API retornar um erro (ex: 401, 500), mostra o erro para debug
   showError(error.message); 
  }
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
   // Recarrega as turmas para atualizar o ambiente no card
   carregarTurmas();

  } catch (error) { showError(error.message); }
 }

 // =======================================================
 // 👂 5. EVENT LISTENERS
 // =======================================================
 
 function initializeListeners() {
  // Filtro de pesquisa
  inputPesquisa.addEventListener('keyup', () => {
   const termo = inputPesquisa.value.toLowerCase();
   document.querySelectorAll('.turma-card').forEach(card => {
    const nome = card.querySelector('h3').textContent.toLowerCase(); 
    
    // ✅ CORREÇÃO: Usa a classe para esconder, mantendo o layout flexbox
    if (nome.includes(termo)) {
            card.classList.remove('hidden-by-search');
        } else {
            card.classList.add('hidden-by-search');
        }
   });
  });

  // Delegação de evento para os botões "Alterar"
  listaTurmasContainer.addEventListener('click', (e) => {
   if (e.target.classList.contains('alterar-btn')) {
    const turmaId = e.target.dataset.id;
    const nomeTurma = e.target.dataset.nome;

    // Mapeia os ambientes carregados (apenas ativos) para o SweetAlert2
    const inputOptions = new Promise((resolve) => {
     const options = {};
     todosOsAmbientes.forEach(ambiente => {
      options[ambiente.id] = `${ambiente.nome_ambiente} (Capacidade: ${ambiente.capacidade_ambiente})`;
     });
     resolve(options);
    });

    Swal.fire({
     title: `Alterar ambiente de:<br><strong>${nomeTurma}</strong>`,
     input: 'select',
     inputOptions: inputOptions,
     inputPlaceholder: 'Selecione o novo ambiente',
     showCancelButton: true,
     confirmButtonText: 'Confirmar',
     cancelButtonText: 'Cancelar',
          customClass: {
            title: 'swal-title-custom'
          }
    }).then((result) => {
     if (result.isConfirmed && result.value) {
      confirmarAlteracao(turmaId, result.value);
     }
    });
   }
  });
 }

 // =======================================================
 // 🏁 6. PONTO DE INÍCIO DA EXECUÇÃO
 // =======================================================
 
 initializeListeners();
 carregarTurmas();
});