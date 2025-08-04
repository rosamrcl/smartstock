<?php

session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../Frontend/login.php');
    exit;
}

require_once('../Backend/conexao.php');

$id = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT foto_perfil FROM usuarios WHERE id_user = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se tem foto cadastrada
if (!empty($dados['foto_perfil'])) {
    $fotoPerfil = './uploads/' . $dados['foto_perfil']; // imagem enviada pelo usuário
} else {
    $fotoPerfil = "./ressources/img/perfil.png"; // imagem padrão
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Página Inicial</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ===== SEÇÃO PRINCIPAL - HOME ===== -->
    <section class="usuario">
        <!-- ===== CARD DE PERFIL CENTRALIZADO ===== -->
        <div class="logo_foto">
            <img class="logo" src="./ressources/img/smartstock.png" alt="SmartStock Logo">
            
            <div class="perfil">
                <div class="image">
                    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil">
                </div>
                
                <p class="welcome">
                    Bem Vindo, <strong><?= htmlspecialchars($_SESSION['nome']) ?> <?= htmlspecialchars($_SESSION['sobrenome']) ?></strong>.
                </p>
                
                <div>
                    <a class="btn" href="updateperfil.php" title="Editar Perfil">
                        <i class="fa-solid fa-pencil"></i>
                        <span>Editar</span>
                    </a>
                    
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <a class="btn-delete" href="../Backend/logout.php" title="Sair do Sistema">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Sair</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===== SEÇÃO DE FUNCIONALIDADES ===== -->
        <div class="box-container">
            <!-- ===== CARD GERENCIAR PRODUTOS ===== -->
            <div class="box">
                <i class="fas fa-box"></i>
                <h3>Gerenciar Produtos</h3>
                <p>Adicione, edite e gerencie seus produtos de forma eficiente</p>
                <a href="gerenciarprodutos.php" class="btn">
                    Acessar
                </a>
            </div>

            <!-- ===== CARD ORDEM DE SERVIÇO ===== -->
            <div class="box">
                <i class="fas fa-clipboard-check"></i>
                <h3>Ordem de Serviço</h3>
                <p>Complete checklists para finalizar ordens de serviço</p>
                <a href="listar_ordens.php" class="btn">
                    Acessar
                </a>
            </div>

            <!-- ===== CARD SUPORTE ===== -->
            <div class="box">
                <i class="fas fa-question-circle"></i>
                <h3>Suporte</h3>
                <p>Entre em contato conosco para suporte técnico</p>
                <a href="suporte.php" class="btn">
                    Acessar
                </a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    
    <?php include 'includes/alerts.php'; ?>

    <script>
        // ===== ANIMAÇÃO DE ENTRADA DOS CARDS =====
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.box');
            const profileCard = document.querySelector('.logo_foto');
            
            // Animar card de perfil
            if (profileCard) {
                profileCard.style.opacity = '0';
                profileCard.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    profileCard.style.transition = 'all 0.8s ease-out';
                    profileCard.style.opacity = '1';
                    profileCard.style.transform = 'translateY(0)';
                }, 100);
            }
            
            // Animar cards de funcionalidades
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 300 + (index * 200));
            });
        });
        
        // ===== SCROLL SUAVE PARA OS CARDS =====
        document.querySelectorAll('.box .btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Adicionar efeito de loading
                const card = this.closest('.box');
                card.classList.add('loading');
                
                // Remover loading após navegação
                setTimeout(() => {
                    card.classList.remove('loading');
                }, 1000);
            });
        });
        
        // ===== HOVER EFFECTS MELHORADOS =====
        document.querySelectorAll('.box').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>