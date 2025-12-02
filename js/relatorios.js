// CONTEÚDO COMPLETO DO ARQUIVO relatorios.js
const apiBase = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api/';

// --- BUSCA O TOKEN DO LOCALSTORAGE ---
const TOKEN = localStorage.getItem('authToken');

// Se não tiver token, não adianta tentar buscar dados
if (!TOKEN) {
    console.warn("Token não encontrado. Redirecionando ou parando execução...");
    // window.location.href = './index.php'; // Opcional: Redirecionar se quiser
}

// Cabeçalho Padrão
const headers = {
    'Authorization': `Bearer ${TOKEN}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
};

async function atualizarCardAmbientes() {
    const elementoNumero = document.getElementById('ambientes-disponiveis-numero');
    const elementoBarra = document.getElementById('ambientes-disponiveis-barra');
    try {
        // --- ADICIONADO HEADERS AQUI ---
        const response = await fetch(`${apiBase}ambientes/ambientes-disponiveis`, { headers });

        if (!response.ok) {
            throw new Error('Falha ao buscar dados da API de ambientes.');
        }
        const dados = await response.json();
        const quantidadeDisponivel = dados.quantidade_disponiveis;
        const quantidadeTotal = dados.total_ambientes;
        let percentagem = 0;
        if (quantidadeTotal > 0) {
            percentagem = Math.round((quantidadeDisponivel / quantidadeTotal) * 100);
        }
        elementoNumero.textContent = quantidadeDisponivel;
        elementoBarra.style.width = `${percentagem}%`;
    } catch (error) {
        console.error('Falha ao atualizar o card de ambientes:', error);
        elementoNumero.textContent = 'Erro';
        elementoBarra.style.width = '100%';
        elementoBarra.style.backgroundColor = '#8B0000';
    }
}

async function atualizarTaxaOcupacao() {
    const elementoTaxa = document.getElementById('taxa-ocupacao-valor');
    try {
        // --- ADICIONADO HEADERS AQUI ---
        const response = await fetch(`${apiBase}ambientes/taxa-ocupacao`, { headers });

        if (!response.ok) {
            throw new Error('Falha ao buscar dados da taxa de ocupação.');
        }
        const dados = await response.json();
        // Ajuste para pegar 'taxa' ou 'taxa_ocupacao' dependendo do seu backend
        const taxa = dados.taxa ?? dados.taxa_ocupacao ?? 0;
        elementoTaxa.textContent = `${Math.round(taxa)}%`;
    } catch (error) {
        console.error('Falha ao atualizar o card de taxa de ocupação:', error);
        elementoTaxa.textContent = 'Erro';
    }
}

async function atualizarColaboradoresAtivos() {
    const elementoColaboradores = document.getElementById('colaboradores-ativos-valor');

    try {
        // --- ADICIONADO HEADERS AQUI ---
        const response = await fetch(`${apiBase}colaboradores/colaboradores-ativos`, { headers });

        if (!response.ok) {
            throw new Error('Falha ao buscar dados de colaboradores.');
        }

        const dados = await response.json();
        const total = dados.quantidade;

        elementoColaboradores.textContent = total;

    } catch (error) {
        console.error('Falha ao atualizar o card de colaboradores:', error);
        elementoColaboradores.textContent = 'Erro';
    }
}

const mapaDeCores = {
    'Automobilistica': '#D9B0B7',
    'Metalmecânica': '#FFB9B3',
    'Tecnologia da Informação': '#FB6D79',
    'Química': '#E3404E',
    'Panificação': '#E30613',
    'Eletroeletrônica': '#BA0615',
    'Descentralizado': '#8C1414',
    'Inativos': '#3B4148',
};

function gerarTomDeVermelhoAleatorio() {
    const hue = Math.floor(Math.random() * 11);
    const saturation = Math.floor(Math.random() * 41) + 55;
    const lightness = Math.floor(Math.random() * 51) + 30;
    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
}

async function criarGraficoOcupacaoPorTipo() {
    const canvas = document.getElementById('myChart');
    if (!canvas) return; // Segurança caso o elemento não exista
    const ctx = canvas.getContext('2d');

    try {
        // --- ADICIONADO HEADERS AQUI ---
        const response = await fetch(`${apiBase}ambientes/tipo-ambiente-taxa`, { headers });

        if (!response.ok) { throw new Error('Falha ao buscar dados para o gráfico.'); }

        const dadosApi = await response.json();

        // ADAPTAÇÃO PARA O FORMATO QUE SUA API RETORNA (LISTA SIMPLES)
        // Se a API retornar uma lista [{tipo: 'Lab', taxa: 10}, ...], mapeamos direto.
        let labels = [];
        let dataPoints = [];
        let backgroundColors = [];

        if (Array.isArray(dadosApi)) {
            labels = dadosApi.map(d => d.tipo);
            dataPoints = dadosApi.map(d => d.taxa);
        } else if (dadosApi.ocupacao_por_tipo) {
            // Mantém sua lógica antiga se a API retornar o objeto complexo
            const dadosDoGrafico = dadosApi.ocupacao_por_tipo;
            const contagemInativos = dadosApi.visao_geral?.total_ambientes_inativos || 0;
            labels = Object.keys(dadosDoGrafico);
            dataPoints = Object.values(dadosDoGrafico).map(item => item.quantidade_neste_tipo);
            if (contagemInativos > 0) {
                labels.push('Inativos');
                dataPoints.push(contagemInativos);
            }
        }

        // --- LÓGICA DE CORES ---
        labels.forEach(label => {
            if (!mapaDeCores[label]) {
                mapaDeCores[label] = gerarTomDeVermelhoAleatorio();
            }
        });
        backgroundColors = labels.map(label => mapaDeCores[label]);
        const borderColors = backgroundColors;

        new Chart(ctx, {
            type: 'doughnut', // ou 'bar' se preferir barras como no outro exemplo
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ocupação',
                    data: dataPoints,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 14 }
                        }
                    }
                }
            }
        });

    } catch (error) {
        console.error("Erro ao criar o gráfico de ocupação:", error);
        ctx.font = "16px Arial";
        ctx.fillStyle = "grey";
        ctx.textAlign = "center";
        ctx.fillText("Não foi possível carregar os dados do gráfico.", 150, 150);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    atualizarCardAmbientes();
    atualizarTaxaOcupacao();
    atualizarColaboradoresAtivos();
    criarGraficoOcupacaoPorTipo();
});