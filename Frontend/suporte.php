<?php

require_once __DIR__ . '/../Backend/conexao.php'; // ajuste o caminho conforme seu projeto

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $setor = $_POST['setor'] ?? '';
    $equipamento = $_POST['equipamento'] ?? '';
    $urgencia = $_POST['urgencia'] ?? 'media';
    $mensagem = $_POST['mensagem'] ?? '';
    $arquivo_nome = null;

    // Validações
    if (empty($nome) || empty($email) || empty($mensagem) || empty($categoria)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif (strlen($mensagem) < 30) {
        $erro = 'A descrição deve ter pelo menos 30 caracteres.';
    } else {

        // Lidar com o arquivo se existir
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {

            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
            $info = pathinfo($_FILES['arquivo']['name']);
            $extensao = strtolower($info['extension']);

            if (in_array($extensao, $extensoes_permitidas)) {
                // Verificar tamanho do arquivo (5MB)
                if ($_FILES['arquivo']['size'] <= 5 * 1024 * 1024) {
                    $novo_nome = uniqid('arquivo_') . '.' . $extensao;
                    $caminho_destino = __DIR__ . '/uploads/' . $novo_nome;

                    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_destino)) {
                        $arquivo_nome = $novo_nome;
                    }
                } else {
                    $erro = 'O arquivo deve ter no máximo 5MB.';
                }
            } else {
                $erro = 'Formato de arquivo não permitido. Use: JPG, PNG, PDF, DOC, DOCX.';
            }
        }

        if (empty($erro)) {
            try {
                // Iniciar transação
                $pdo->beginTransaction();

                // Preparar informações adicionais
                $info_adicional = json_encode([
                    'categoria' => $categoria,
                    'setor' => $setor,
                    'equipamento' => $equipamento,
                    'urgencia' => $urgencia
                ]);

                // Inserir no banco - suporte
                $stmt = $pdo->prepare("INSERT INTO suporte (nome, email, mensagem, arquivo, observacoes) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $email, $mensagem, $arquivo_nome, $info_adicional]);
                
                // Obter o ID do suporte recém-criado
                $id_suporte = $pdo->lastInsertId();

                // Criar ordem de serviço automaticamente
                $stmt_os = $pdo->prepare("INSERT INTO ordens_servico (solicitante, categoria, observacoes, status, id_suporte_origem) VALUES (?, ?, ?, ?, ?)");
                $stmt_os->execute([$nome, 'Suporte Técnico - ' . ucfirst($categoria), $mensagem, 'Pendente', $id_suporte]);

                // Commit da transação
                $pdo->commit();

                $sucesso = 'Mensagem enviada com sucesso! Uma ordem de serviço foi criada automaticamente.';
            } catch (Exception $e) {
                // Rollback em caso de erro
                $pdo->rollBack();
                $erro = 'Erro ao processar solicitação. Tente novamente.';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Suporte</title>
    <?php include 'includes/head.php'; ?>
    <style>
        /* Estilos adicionais para as melhorias */
        .campo-problema {
            position: relative;
        }

        .contador-chars {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            transition: color 0.3s;
        }

        .dicas-laterais {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-top: 10px;
            font-size: 14px;
            border-radius: 0 8px 8px 0;
        }

        .dicas-laterais ul {
            margin: 10px 0 0 20px;
            padding: 0;
        }

        .dicas-laterais li {
            margin-bottom: 5px;
        }

        .upload-area {
            margin-top: 2rem;
        }

        .upload-label {
            display: block;
            padding: 20px;
            border: 2px dashed #ddd;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .upload-label:hover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .upload-label small {
            display: block;
            margin-top: 5px;
            color: #666;
        }

        #preview-arquivo {
            margin-top: 10px;
        }

        #preview-arquivo img {
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .categoria-select {
            font-size: 1.6rem;
            border-radius: 1.25rem;
            padding: 1.5rem;
            width: 100%;
            margin-bottom: 2rem;
            border: 1px solid #ddd;
        }

        .info-adicional {
            margin-bottom: 2rem;
        }

        .urgencia-select {
            font-size: 1.6rem;
            border-radius: 1.25rem;
            padding: 1.5rem;
            width: 100%;
            margin-bottom: 2rem;
            border: 1px solid #ddd;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid transparent;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="suporte">
        <div class="suporte-container">
            <div class="heading">
                <h1> Como podemos ajudar você?</h1>
            </div>
    
            <div class="container">
                <?php if ($erro): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>
                
                <?php if ($sucesso): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <input name="nome" type="text" placeholder="Digite seu nome" required>
                        <input name="email" type="email" placeholder="Digite seu email" required>
                    </div>

                    <!-- Categorização -->
                    <select name="categoria" required class="categoria-select">
                        <option value="">Selecione o tipo do problema</option>
                        <option value="hardware">🖥️ Hardware (Computador, Monitor, Teclado)</option>
                        <option value="software">💾 Software (Programas, Aplicativos)</option>
                        <option value="rede">🌐 Rede (Internet, WiFi, Conexão)</option>
                        <option value="email">📧 Email (Outlook, Webmail)</option>
                        <option value="impressora">🖨️ Impressora (Problemas de impressão)</option>
                        <option value="outros">❓ Outros</option>
                    </select>

                    <!-- Informações contextuais -->
                    <div class="info-adicional">
                        <div class="input-group">
                            <input type="text" name="setor" placeholder="Setor/Departamento (opcional)">
                            <input type="text" name="equipamento" placeholder="Equipamento afetado (opcional)">
                        </div>
                    </div>

                    <select name="urgencia" class="urgencia-select">
                        <option value="baixa">🟢 Baixa - Posso esperar</option>
                        <option value="media" selected>🟡 Média - Quando possível</option>
                        <option value="alta">🟠 Alta - Preciso hoje</option>
                        <option value="critica">🔴 Crítica - Urgente!</option>
                    </select>

                    <!-- Campo de descrição melhorado -->
                    <div class="campo-problema">
                        <textarea name="mensagem" required 
                                  placeholder="Descreva detalhadamente seu problema:
- O que você estava fazendo quando aconteceu?
- Que mensagem de erro apareceu?
- Quando começou o problema?
- Já tentou reiniciar?"
                                  minlength="30" 
                                  oninput="contarCaracteres(this)"></textarea>
                        <small class="contador-chars">Mínimo 30 caracteres</small>
                        
                        <div class="dicas-laterais">
                            <strong>💡 Dicas para uma boa descrição:</strong>
                            <ul>
                                <li>Seja específico sobre o erro</li>
                                <li>Mencione quando aconteceu</li>
                                <li>Diga o que já tentou fazer</li>
                                <li>Inclua mensagens de erro exatas</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Upload melhorado -->
                    <div class="upload-area">
                        <input type="file" name="arquivo" id="arquivo" accept="image/*,.pdf,.doc,.docx" hidden>
                        <label for="arquivo" class="upload-label">
                            📎 Clique para anexar arquivo ou imagem
                            <small>Formatos: JPG, PNG, PDF, DOC (máx. 5MB)</small>
                        </label>
                        <div id="preview-arquivo"></div>
                    </div>

                    <input type="submit" class="btn" value="Enviar">
                </form>
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