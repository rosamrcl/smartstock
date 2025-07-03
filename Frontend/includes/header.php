<header>
        <a href="login.php" class="home"><i class="fa-solid fa-house"></i></a>
        <img src="./ressources/img/smartstock.png" alt="logo">
        <nav class="navbar"> 
            
            
            <a href="cadastrogerencia.php">Grenciamento de Estoque</a>
            <a href="cadastrogerenciafuncionario.php">Ordem de Serviço</a>
            <?php if (isset($_SESSION['id_user'])): ?>
                <form action="../Backend/logout.php" method="post">
                    <button type="submit" class="btn-delete">Sair <i class="fa-solid fa-right-to-bracket"></i></button>
                    </form>
            <?php endif; ?>            
            <a href=""><i class="fa-solid fa-bell"></i></a>
        </nav>
        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
        </div>        
    </header>