<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/sobrenos.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include __DIR__ . '/includes/headerog.php';
    ?>
    <section class="sobre_nos">
        <img src="./ressources/img/LARI.png" alt="">
        <div class="membros">
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/eu.png" alt="">
                </div>
                <h2>Rosa Chagas</h2>
                <p>Front-End</p>
                <div class="git">
                    <a class="fa-brands fa-github"target="_blank" href="https://github.com/rosacl"></a>
                </div>
            </div>

            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/anna.jpg" alt="">
                </div>
                <h2>Anna Iris</h2>
                <p>Front-End</p>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/ansilv00"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/luan.jpg" alt="">
                </div>
                <h2>Luan Aquino</h2>
                <p>Back-End</p>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/Aquino-maker"></a>
                </div>
            </div>
            <div class="box">
                <div class="photo">
                    <img src="./ressources/img/isaque.jpg" alt="">
                </div>
                <h2>Isaque</h2>
                <p>Back-End</p>
                <div class="git">
                    <a class="fa-brands fa-github" target="_blank" href="https://github.com/BananaSpritee"></a>
                </div>
            </div>
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>
</body>

</html>