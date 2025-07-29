<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<header>
    <a href="home.php" class="home">
        <i class="fa-solid fa-house"></i>
        <span>SmartStock</span>
    </a>
    
    <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
    
    <nav class="navbar" role="navigation" aria-label="Menu principal">
        <a href="gerenciarprodutos.php" aria-label="Gerenciar produtos">
            <i class="fa-solid fa-box"></i>
            <span>Gerenciamento de Estoque</span>
        </a>
        <a href="ordemdeserviço.php" aria-label="Ordem de serviço">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Ordem de Serviço</span>
        </a>
        <a href="suporte.php" aria-label="Suporte">
            <i class="fa-solid fa-question-circle"></i>
            <span>Posso ajudar</span>
        </a>
        <a href="#" aria-label="Notificações">
            <i class="fa-solid fa-bell"></i>
            <span>Notificações</span>
        </a>
    </nav>
    
    <div class="icons">
        <button id="menu-btn" aria-label="Abrir menu" aria-expanded="false" aria-controls="navbar">
            <i class="fas fa-bars" id="menu-bars"></i>
        </button>
    </div>
</header>