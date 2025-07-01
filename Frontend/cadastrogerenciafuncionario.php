<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/form.css">
    <link rel="stylesheet" href="./ressources/css/cadastrogerencia.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include '/laragon/www/smartstock/Frontend/includes/header.php';?>


    <section class="cadastrogerencia">      
            
            <div class="perfil-cadastro">
                <div class="image">
                    <img src="./ressources/img/perfil.png" alt="">
                </div>
                <p>Bem Vindo, Ambrosio</p>
                <button type="submit" class="btn-delete">Sair <i class="fa-solid fa-right-to-bracket"></i></button>
            </div>       
        <div class="table-container">
            <h3>Gerenciar Produtos</h3>
            <div class="tabela">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="openTab('tab1')">Lista de Chamados</button>
                    <button class="tab-button" onclick="openTab('tab2')">Estoque</button>
                    <button class="tab-button" onclick="openTab('tab3')">Check-List</button>
                </div>
                <div id="tab1" class="tab-content active">
                    <table>
                        <thead>
                            <tr>
                                <th>OS</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dado 1-1</td>
                                <td>Dado 1-2</td>
                                <td>Dado 1-3</td>
                                <td>Dado 1-4</td>
                                <td>Dado 1-5</td>
                                <td><a href="#" class="btn"><i class="fa-solid fa-check"></i></a></td> 
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab2" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                                
                            </tr>
                        </thead>
                        <tbody>                
                            <tr>
                                <td>Info A2</td>
                                <td>Info B2</td>
                                <td>Info B2</td>
                                <td>Info B2</td>
                                <td>Info B2</td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab3" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Manutenção</th>
                                <th>Status</th>
                                <th>Estapa</th>              
                                
                                <th>Descrição da tarefa</th>
                                <th>Data da verificação</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="" id="">
                                        <option value="">Preventiva</option>
                                        <option value="">Corretiva</option>
                                        <option value="">Instalação</option>
                                        <option value="">Configuração</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="" id="">
                                        <option value="">Pendente</option>
                                        <option value="">Em andamento</option>
                                        <option value="">Concluído</option>
                                    </select>
                                </td>
                                <td><select name="" id="">
                                    <option value="">
                                        Verificar conectividade
                                    </option>
                                    <option value="">
                                        Testar alimentação alétrica
                                    </option>
                                </select></td>
                                <td><input type="file" name="" id=""></td>                                
                                <td><input type="date" name="" id=""></td>
                                <td><a href="#" class="btn"><i class="fa-solid fa-check"></i></a></td> 
                            </tr>
                            
                        </tbody>
                    </table>
                </div>

    </section>

    <script src="./ressources/js/script.js"></script>

</body>

</html>