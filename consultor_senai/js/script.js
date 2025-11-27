
document.addEventListener('DOMContentLoaded', function () {

    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const conteudo = document.getElementById('conteudo');

    if (menuBtn && sidebar && conteudo) {
        menuBtn.addEventListener('click', () => {
            menuBtn.classList.toggle('active');
            sidebar.classList.toggle('active');
            conteudo.classList.toggle('push');
        });
    }

    const containerPesquisa = document.getElementById('container-pesquisa');
    const containerResultado = document.getElementById('resultado-alocacao');
    const btnVoltar = document.getElementById('btn-voltar');
    const btnConfirmar = document.querySelector('.btn-confirmar');
    const spanCursoSelecionado = document.getElementById('curso-selecionado-nome');
    const inputPesquisa = document.getElementById('input-pesquisa-turma');
    const listaTurmas = document.getElementById('lista-turmas');
    if (inputPesquisa && listaTurmas && containerPesquisa && containerResultado && btnVoltar && btnConfirmar) {

        const todosOsItens = listaTurmas.querySelectorAll('.item-turma');

        inputPesquisa.addEventListener('input', function () {
            const termoPesquisado = inputPesquisa.value.toLowerCase();
            if (termoPesquisado.length === 0) {
                listaTurmas.classList.add('hidden');
                return;
            }
            listaTurmas.classList.remove('hidden');
            todosOsItens.forEach(function (item) {
                const textoItem = item.textContent.toLowerCase();
                if (textoItem.includes(termoPesquisado)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        todosOsItens.forEach(function (item) {
            item.addEventListener('click', function () {
                const nomeDaTurma = item.textContent.trim();
                inputPesquisa.value = nomeDaTurma;
                listaTurmas.classList.add('hidden');
                spanCursoSelecionado.textContent = nomeDaTurma;
                containerPesquisa.style.display = 'none';
                containerResultado.style.display = 'flex';
            });
        });
        document.addEventListener('click', function (event) {
            if (!inputPesquisa.contains(event.target) && !listaTurmas.contains(event.target)) {
                listaTurmas.classList.add('hidden');
            }
        });
        btnVoltar.addEventListener('click', function () {
            containerResultado.style.display = 'none';
            containerPesquisa.style.display = 'block';
            inputPesquisa.value = '';
        });
        btnConfirmar.addEventListener('click', function () {
            Swal.fire({
                title: 'Alteração Efetuada!',
                text: 'As alocações foram atualizadas com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6a2c2c'
            }).then((result) => {
                if (result.isConfirmed) {
                    containerResultado.style.display = 'none';
                    containerPesquisa.style.display = 'block';
                    inputPesquisa.value = '';
                }
            });
        });
    }
});

