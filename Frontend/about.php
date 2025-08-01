<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php
    include __DIR__ . '/includes/headerog.php';
    ?>
    <section class="sobre_nos">
        <h1>Sobre Nós</h1>
        <div class="membros">
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/eu.png" alt="">
                </div>
                <div class="txt">
                    <h2>Rosa Chagas</h2>
                    <p>Front-End</p>
                </div>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/rosacl"></a>
                    <a class="fa-brands fa-linkedin" href="https://www.linkedin.com/in/maria-rosa-chagas-lima-16730a293/"></a>
                </div>
            </div>

            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/anna.jpg" alt="">
                </div>
                <div class="txt">
                    <h2>Anna Iris</h2>
                    <p>Front-End</p>
                </div>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/ansilv00"></a>
                    <a class="fa-brands fa-linkedin" target="_blank" href="https://www.linkedin.com/in/anna-iris-silva-60b55736a/"></a>
                </div>
            </div>

            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/isaque.jpg" alt="">
                </div>
                <div class="txt">
                    <h2>Isaque Newton</h2>
                    <p>Back-End</p>
                </div>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/BananaSpritee"></a>
                    <a class="fa-brands fa-linkedin" target="_blank" href="https://www.linkedin.com/in/isaque-newton-silva-774a72374/"></a>
                </div>
            </div>

            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/luan.jpg" alt="">
                </div>
                <div class="txt">
                    <h2>Luan Aquino</h2>
                    <p>Back-End</p>
                </div>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/Aquino-maker"></a>
                    <a class="fa-brands fa-linkedin" target="_blank" href="https://www.linkedin.com/in/luan-aquino/"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/menise.png" alt="">
                </div>
                <div class="txt">
                    <h2>Menise Farias</h2>
                    <p>Libras</p>
                </div>
                <div class="git">
                    <a id="disable" class="fa-brands fa-github" target="_blank" href="#"></a>
                    <a id="disable" class="fa-brands fa-linkedin" target="_blank" href="#"></a>
                </div>
            </div>

            <div class="box">                
                    <img src="./ressources/img/LARI.png" alt="">
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/rosamrcl/smartstock"></a>
                    <a id="disable" class="fa-brands fa-linkedin" target="_blank" href="#"></a>
                </div>
            </div>
        </div>
    </section>

    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    
    <?php include 'includes/alerts.php'; ?>

</body>
</html>
</html>