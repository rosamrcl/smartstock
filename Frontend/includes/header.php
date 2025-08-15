<?php
$isLoggedIn = isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
?>

<header class="header">
    <section class="flex">
        <div class="smartstock">
            <?php if ($isLoggedIn): ?>
                <div id="menu-btn" class="fas fa-bars-staggered"></div>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>
                <a href="home.php" class="home"><i class="fa-solid fa-house"></i>
                </a>
            <?php endif; ?>
        </div>
        <div class="smart-logo">
            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
        </div>
        <a  href="suporte.php" aria-label="Suporte">
            <i class="fa-solid fa-question-circle"></i>
            <span>Posso ajudar</span>
        </a>

    </section>

</header>

<?php if ($isLoggedIn): ?>
<div class="side-bar">
    <nav class="navbar" role="navigation" aria-label="Menu principal">
        <div id="close-btn" class="fas fa-times"></div>
        <a href="gerenciarprodutos.php" aria-label="Gerenciar produtos">
            <i class="fa-solid fa-box"></i>
            <span>Gerenciamento de Estoque</span>
        </a>
        <a href="listar_ordens.php" aria-label="Ordem de serviço">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Ordem de Serviço</span>
        </a>
        
        <a href="notificacoes.php" aria-label="Notificações" id="notificacoes-link">
            <i class="fa-solid fa-bell"></i>
            <span>Notificações</span>
            <span class="contador-notificacoes" id="contador-notificacoes" style="display: none;">0</span>
        </a>

    </nav>
</div>
<?php endif; ?>