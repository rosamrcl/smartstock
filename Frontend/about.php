

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock </title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="summit" id="summit">
        <div class="image">
            <img src="./ressources/img/smartstock.png" alt="">
        </div>
        <div class="content">
            
            <p>Somos um sistema completo de controle de estoque inteligente desenvolvido em PHP, projetado para
                gerenciar produtos, ordens de serviço e fornecer suporte técnico. O sistema oferece uma interface
                intuitiva e responsiva para controle eficiente de estoque com funcionalidades avançadas de autenticação
                e gestão de usuários.</p>
        </div>
    </section>

    <section class="servicing" id="servicing">
        <h1 class="billhead">Nossos <span>Serviços</span></h1>
        <div class="box-container">
            <div class="box">
                <i class="fa-solid fa-shield"></i>
                <h3>Autenticação</h3>

                <li>Login seguro com validação em tempo real</li>
                <li>Sistema de recuperação de senha via email</li>
                <li>Controle de sessão com proteção</li>
                <li>Cadastro de novos usuários</li>
                <li>Alteração de senha</li>

            </div>
            <div class="box">
                <i class="fa-solid fa-laptop"></i>
                <h3>Dashboard</h3>
                <li>Visão geral do estoque</li>
                <li>Perfil do usuário com foto</li>
                <li>Navegação intuitiva</li>
                <li>Acesso rápido às funcionalidades</li>

            </div>
            <div class="box">
                <i class="fa-solid fa-boxes-stacked"></i>
                <h3>Gestão de Estoque</h3>
                <li>Cadastro de produtos</li>
                <li>Controle de status (Estoque, Manutenção, Em uso)</li>
                <li>Gestão de quantidades</li>
                <li>Visualização por categorias</li>
                <li>Edição e exclusão de produtos</li>

            </div>
            <div class="box">
                <i class="fa-solid fa-clipboard-list"></i>
                <h3>Ordens de Serviço</h3>
                <li>Criação de ordens de serviço</li>
                <li>Sistema de checklist</li>
                <li>Controle de estoque por ordem</li>
                <li>Acompanhamento de status</li>
                <li>
            </div>
            </li>
            <div class="box">
                <i class="fa-solid fa-gear"></i>
                <h3>Suporte</h3>
                <li>Sistema de tickets de suporte</li>
                <li>Upload de arquivos</li>
                <li>Comunicação direta</li>
            </div>
            <div class="box">
                <i class="fa-solid fa-microchip"></i>
                <h3>Tecnologias Utilizadas</h3>
                <li><b>Back-End:</b> PHP, MySQL 8.0, PDO Sessions </li>
                <li>Front-End: HTML5/CSS3, JavaScript ES6+, Font Awesome,SweetAlert2 </li>
            </div>
        </div>
    </section>
    <section class="team" id="team">
        <h1 class="billhead">Sobre<span> Nós</span></h1>
        <div class="box-container">
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/eu.png" alt="">
                </div>
                <h2>Rosa Chagas</h2>
                <span>Full Stack</span>

                <div class="share">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/rosacl"></a>
                    <a class="fa-brands fa-linkedin" href="https://www.linkedin.com/in/maria-rosa-chagas-lima-16730a293/"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/anna.jpg" alt="">
                </div>
                <h2>Anna Iris</h2>
                <span>Full Stack</span>
                <div class="share">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/ansilv00"></a>
                    <a class="fa-brands fa-linkedin" target="_blank" href="https://www.linkedin.com/in/anna-iris-silva-60b55736a/"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/luan.jpg" alt="">
                </div>
                <h2>Luan Aquino</h2>
                <span>Full Stack</span>
                <div class="share">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/Aquino-maker"></a>
                    <a class="fa-brands fa-linkedin" target="_blank" href="https://www.linkedin.com/in/luan-aquino/"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/isaque.jpg" alt="">
                </div>
                <h2>Isaque Newton</h2>
                <span>Full Stack</span>
                <div class="share">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/BananaSpritee"></a>
                    <a class="fa-brands fa-linkedin" target="_blank" href="https://www.linkedin.com/in/isaque-newton-silva-774a72374/"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/menise.png" alt="">

                </div>
                <h2>Menise Farias</h2>
                <span>Intérprete de Libras</span>
                <div class="share">
                    <a id="disable" class="fa-brands fa-github" target="_blank" href="#"></a>
                    <a id="disable" class="fa-brands fa-linkedin" target="_blank" href="#"></a>
                </div>
            </div>
            <div class="box">
                <img src="./ressources/img/LARI.png" alt="">
                <h3>LARIM</h3>
                <div class="share">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/rosamrcl/smartstock"></a>
                    <a id="disable" class="fa-brands fa-linkedin" target="_blank" href="#"></a>
                </div>

            </div>
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script src="/Frontend/ressources/js/script.js"></script>
<script src="/Frontend/ressources/js/suporte.js"></script>
<?php
include 'includes/alerts.php';
?>

</html>