<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador-SENAI</title>
    <link rel="stylesheet" href="./css/user.css">
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <div class="menu-box" id="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <img class="logo_senai" src="./images/logo_senai.png" alt="Logo SENAI">
    </header>

    <nav class="sidebar" id="sidebar">
        <ul>
             <li>
                <a href="./home.php">
                    <img src="./images/multiple-users-silhouette.png" alt="Ícone de perfil">
                    <span class="menu-texto">Painel de<br>Turmas</span>
                </a>
            </li>
            <li>
                <a href="./user.php">
                    <img src="./images/account.png" alt="Ícone de perfil">
                    <span class="menu-texto">Meu Perfil</span>
                </a>
            </li>
        </ul>
    </nav>


    <main id="conteudo-cadastro">
        <div class="titulos">
            <div class="gerenciar-titulo">
                <img src="./images/perfil.png" alt="icone_person">
                <h1>Meu Perfil</h1>
            </div>
            <div class="docentes">
                <h2>Dados Pessoais</h2>
                <hr>
            </div>

        
            <div class="icone_perfil">
    <div class="picture">
        <div class="foto">
            <img src="./images/foto_perfil.png" alt="Foto do Perfil">
        </div>
        <h2>Marcos Reis</h2>
        <p>marcosreis@senai.com</p>
        <a class="ativar" href="">
            <i class="bi bi-power"></i> Ativo
        </a>
    </div>

    <div class="informacoes">
        <div class="des">
            <p><strong>Matrícula:</strong> 00000000</p>
            <p><strong>Especialidades:</strong> Desenvolvedor</p>
            <p><strong>Data de Nascimento:</strong> 12/12/2006</p>
        </div>
        
        <div class="perfil-select-container">
            <label for="perfil"><strong>Perfil:</strong></label>
            <select name="perfil" id="perfil">
                <option value="gestor">Gestor</option>
                <option value="consultor">Consultor</option>
            </select>
        </div>
    </div>
</div>  

    </main>

    <div id="modalCriar" class="modal">
        <div class="modal-content">
            <span class="close" data-modal="modalCriar"><i class="bi bi-x-circle"></i></span>
            <div class="header-container">
                <h2>Criar Cursos</h2>
                <div class="header-underline"></div>
            </div>
            <p>Preencha os campos abaixo para realizar o cadastro de um novo Cursos.</p>
            <div class="input-group">
                <input type="text" id="addTipo" placeholder="Tipo">
                <input type="text" id="addCargaHoraria" placeholder="Carga Horária">
                <input type="text" id="addNome" placeholder="Nome do Curso">
            </div>
            <div class="button-container">
                <button class="btn-criar">Criar</button>
            </div>
        </div>
    </div>
    
    <script src="./js/curso.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM