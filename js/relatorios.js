// CONTEÚDO COMPLETO DO ARQUIVO relatorios.js
const apiBase = 'http://10.141.117.34:8024/arthur-pereira/api_sga/api/';

async function atualizarCardAmbientes() {
    const elementoNumero = document.getElementById('ambientes-disponiveis-numero');
    const elementoBarra = document.getElementById('ambientes-disponiveis-barra');
    try {
        const response = await fetch(`${apiBase}ambientes/ambientes-disponiveis`);
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
        const response = await fetch(`${apiBase}ambientes/taxa-ocupacao`);
        if (!response.ok) {
            throw new Error('Falha ao buscar dados da taxa de ocupação.');
        }
        const dados = await response.json();
        const taxa = dados.taxa_ocupacao;
        elementoTaxa.textContent = `${Math.round(taxa)}%`;
    } catch (error) {
        console.error('Falha ao atualizar o card de taxa de ocupação:', error);
        elementoTaxa.textContent = 'Erro';
    }
}

async function atualizarColaboradoresAtivos() {
    const elementoColaboradores = document.getElementById('colaboradores-ativos-valor');

    try {
        const response = await fetch(`${apiBase}colaboradores/colaboradores-ativos`);

        if (!response.ok) {
            throw new Error('Falha ao buscar dados de colaboradores.');
        }

        const dados = await response.json(); // dados agora é o objeto { quantidade: 5, colaboradores: [...] }

        // CORREÇÃO: Acessamos a chave "quantidade" que a sua API realmente envia.
        const total = dados.quantidade;

        // O resto do código funciona perfeitamente
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
    const hue = Math.floor(Math.random() * 11); // Matiz em torno do vermelho
    const saturation = Math.floor(Math.random() * 41) + 55; // Saturação de 55% a 95%
    const lightness = Math.floor(Math.random() * 51) + 30; // Luminosidade de 30% a 80%
    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
}

async function criarGraficoOcupacaoPorTipo() {
    const ctx = document.getElementById('myChart').getContext('2d');

    try {
        const response = await fetch(`${apiBase}ambientes/tipo-ambiente-taxa`);
        if (!response.ok) { throw new Error('Falha ao buscar dados para o gráfico.'); }

        const dadosApi = await response.json();

        const dadosDoGrafico = dadosApi.ocupacao_por_tipo;
        const contagemInativos = dadosApi.visao_geral.total_ambientes_inativos;
        const labels = Object.keys(dadosDoGrafico);
        const dataPoints = Object.values(dadosDoGrafico).map(item => item.quantidade_neste_tipo);

        if (contagemInativos > 0) {
            labels.push('Inativos');
            dataPoints.push(contagemInativos);
        }

        // --- LÓGICA DE CORES CORRIGIDA E FINAL ---

        // Para cada 'label' que veio da API, verifica se ele já tem uma cor no nosso mapa.
        labels.forEach(label => {
            // Se o 'label' NÃO (!) estiver no mapaDeCores...
            if (!mapaDeCores[label]) {
                // ...gera uma cor nova e a adiciona ao mapa.
                mapaDeCores[label] = gerarTomDeVermelhoAleatorio();
            }
        });

        // Agora que o mapa está completo, cria a lista de cores na ordem certa.
        const backgroundColors = labels.map(label => mapaDeCores[label]);
        const borderColors = backgroundColors; // Para cores sólidas, a borda pode ser a mesma.

        // --- FIM DA LÓGICA DE CORES ---

        // Cria o gráfico usando Chart.js
        new Chart(ctx, {
            type: 'doughnut',
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