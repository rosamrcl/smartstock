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
        <div class="form-produto">
            <div class="form-container-produto">
                <form action="" method="post">
                    <h3>Cadastro do produto</h3>
                    <label for="productname">Nome produto</label>
                    <input type="text" name="productname" id="">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao">
                    <label for="status">Status</label>
                    <select name="status" id="">
                        <option value="funcionando">Funcionando</option>
                        <option value="funcionando">Com defeito</option>
                        <option value="funcionando">Em uso</option>
                    </select>
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade" id="">
                    <input type="submit" value="Adicionar" class="btn" name="adicionar">
                </form>
            </div>
        </div>
        <div class="table-container">
            <h3>Gerenciar Produtos</h3>
            <div class="tabela">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="openTab('tab1')">Estoque</button>
                    <button class="tab-button" onclick="openTab('tab2')">Manutenção</button>
                    <button class="tab-button" onclick="openTab('tab3')">Em uso</button>
                </div>
                <div id="tab1" class="tab-content active">
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
                                <td>Dado 1-1</td>
                                <td>Dado 1-2</td>
                                <td>Dado 1-3</td>
                                <td>Dado 1-4</td>
                                <td>Dado 1-5</td>
                                <td><a href="#" class="btn"><i class="fa-solid fa-pencil"></i></a><a href="#" class="btn-delete"><i class="fa-solid fa-trash-can"></i></a></td> 
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
                                <td><a href="#" class="btn"><i class="fa-solid fa-pencil"></i></a><a href="#" class="btn-delete"><i class="fa-solid fa-trash-can"></i></a></td> 
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab3" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                                <th>Quantidade</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Produto X</td>
                                <td>R$ 10,00</td>
                                <td>5</td>
                                <td>Produto X</td>
                                <td>R$ 10,00</td>
                                <td>5</td>
                                <td><a href="#" class="btn"><i class="fa-solid fa-pencil"></i></a><a href="#" class="btn-delete"><i class="fa-solid fa-trash-can"></i></a></td> 
                            </tr>
                            
                        </tbody>
                    </table>
                </div>

    </section>

    <script src="./ressources/js/script.js"></script>

</body>

</html>