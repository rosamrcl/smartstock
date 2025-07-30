<?php
session_start();
require_once("conexao.php");

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    $_SESSION['error_msg'] = ["Você precisa estar logado para atualizar seu perfil."];
    header('Location: ../Frontend/login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

// Configurar diretório de uploads
$diretorioUploads = __DIR__ . "/../Frontend/uploads";
if (!file_exists($diretorioUploads)) {
    mkdir($diretorioUploads, 0777, true);
}

// Função para log de debug
function logDebug($message, $data = []) {
    $logFile = __DIR__ . '/../logs/debug.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => $message,
        'data' => $data
    ];
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    logDebug("Iniciando processo de atualização de perfil", [
        'user_id' => $id_user,
        'post_data' => $_POST,
        'files' => isset($_FILES['foto']) ? 'foto_enviada' : 'sem_foto'
    ]);

    try {
        // Buscar dados atuais do usuário
        $stmtOld = $pdo->prepare("SELECT nome, sobrenome, email, foto_perfil FROM usuarios WHERE id_user = :id");
        $stmtOld->bindParam(':id', $id_user);
        $stmtOld->execute();
        $dadosAntigos = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$dadosAntigos) {
            logDebug("Usuário não encontrado no banco", ['user_id' => $id_user]);
            $_SESSION['error_msg'] = ["Usuário não encontrado. Faça login novamente."];
            header('Location: ../Frontend/login.php');
            exit;
        }

        // Sanitizar e validar dados do formulário
        $nome = !empty($_POST['nome']) ? trim(filter_var($_POST['nome'], FILTER_SANITIZE_STRING)) : $dadosAntigos['nome'];
        $sobrenome = !empty($_POST['sobrenome']) ? trim(filter_var($_POST['sobrenome'], FILTER_SANITIZE_STRING)) : $dadosAntigos['sobrenome'];
        $email = !empty($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : $dadosAntigos['email'];

        logDebug("Dados processados", [
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email
        ]);

        // Validação de campos obrigatórios
        if (empty($nome) || empty($sobrenome) || empty($email)) {
            logDebug("Campos obrigatórios vazios", [
                'nome_vazio' => empty($nome),
                'sobrenome_vazio' => empty($sobrenome),
                'email_vazio' => empty($email)
            ]);
            $_SESSION['error_msg'] = ["Por favor, preencha todos os campos obrigatórios."];
            header('Location: ../Frontend/updateperfil.php');
            exit;
        }

        // Validação de formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            logDebug("Email inválido", ['email' => $email]);
            $_SESSION['error_msg'] = ["Por favor, insira um email válido."];
            header('Location: ../Frontend/updateperfil.php');
            exit;
        }

        // Verificar se o email já está sendo usado por outro usuário
        $verificaEmail = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = :email AND id_user != :id_user AND deleted_at IS NULL");
        $verificaEmail->execute([':email' => $email, ':id_user' => $id_user]);
        if ($verificaEmail->rowCount() > 0) {
            logDebug("Email já em uso por outro usuário", ['email' => $email]);
            $_SESSION['error_msg'] = ["Este email já está sendo usado por outro usuário."];
            header('Location: ../Frontend/updateperfil.php');
            exit;
        }

        // Processamento do upload de imagem
        $foto = null;
        $fotoAntiga = $dadosAntigos['foto_perfil'];
        
        if (!empty($_FILES['foto']['name'])) {
            logDebug("Processando upload de foto", [
                'file_name' => $_FILES['foto']['name'],
                'file_size' => $_FILES['foto']['size'],
                'file_type' => $_FILES['foto']['type']
            ]);
            
            $uploadResult = processImageUpload($_FILES['foto'], $diretorioUploads);
            
            if ($uploadResult['success']) {
                $foto = $uploadResult['filename'];
                logDebug("Upload de foto bem-sucedido", ['filename' => $foto]);
                
                // Remover foto antiga se existir e não for a padrão
                if (!empty($fotoAntiga) && $fotoAntiga !== 'perfil.png') {
                    $caminhoFotoAntiga = $diretorioUploads . "/" . $fotoAntiga;
                    if (file_exists($caminhoFotoAntiga)) {
                        unlink($caminhoFotoAntiga);
                        logDebug("Foto antiga removida", ['foto_antiga' => $fotoAntiga]);
                    }
                }
            } else {
                logDebug("Erro no upload de foto", ['error' => $uploadResult['message']]);
                $_SESSION['error_msg'] = [$uploadResult['message']];
                header('Location: ../Frontend/updateperfil.php');
                exit;
            }
        }

        // Preparar query de atualização
        $query = "UPDATE usuarios SET nome = :nome, sobrenome = :sobrenome, email = :email";
        $params = [
            ':nome' => $nome,
            ':sobrenome' => $sobrenome,
            ':email' => $email,
            ':id_user' => $id_user
        ];
        
        if ($foto) {
            $query .= ", foto_perfil = :foto";
            $params[':foto'] = $foto;
        }
        
        $query .= ", updated_at = NOW() WHERE id_user = :id_user";

        logDebug("Executando query de atualização", [
            'query' => $query,
            'params' => array_keys($params)
        ]);

        $stmt = $pdo->prepare($query);
        
        // Executar a query
        $result = $stmt->execute($params);
        
        if ($result && $stmt->rowCount() > 0) {
            logDebug("Atualização bem-sucedida", [
                'rows_affected' => $stmt->rowCount(),
                'user_id' => $id_user
            ]);
            
            // Atualizar dados da sessão
            $_SESSION['nome'] = $nome;
            $_SESSION['sobrenome'] = $sobrenome;
            $_SESSION['email'] = $email;
            if ($foto) {
                $_SESSION['foto'] = $foto;
            }

            $_SESSION['success_msg'] = ["Perfil atualizado com sucesso!"];
            header("Location: ../Frontend/home.php");
            exit;
        } else {
            logDebug("Falha na atualização - nenhuma linha afetada", [
                'result' => $result,
                'row_count' => $stmt->rowCount(),
                'user_id' => $id_user
            ]);
            $_SESSION['error_msg'] = ["Erro ao atualizar o perfil. Nenhuma alteração foi feita."];
            header('Location: ../Frontend/updateperfil.php');
            exit;
        }

    } catch (PDOException $e) {
        logDebug("Erro PDO na atualização", [
            'error_code' => $e->getCode(),
            'error_message' => $e->getMessage(),
            'user_id' => $id_user
        ]);
        $_SESSION['error_msg'] = ["Erro interno do sistema. Tente novamente mais tarde."];
        header('Location: ../Frontend/updateperfil.php');
        exit;
    } catch (Exception $e) {
        logDebug("Erro geral na atualização", [
            'error_message' => $e->getMessage(),
            'user_id' => $id_user
        ]);
        $_SESSION['error_msg'] = ["Erro inesperado. Tente novamente mais tarde."];
        header('Location: ../Frontend/updateperfil.php');
        exit;
    }
} else {
    // Método não permitido
    logDebug("Tentativa de acesso com método não permitido", [
        'method' => $_SERVER['REQUEST_METHOD'],
        'user_id' => $id_user ?? 'não_logado'
    ]);
    header('Location: ../Frontend/updateperfil.php');
    exit;
}

/**
 * Processa o upload de imagem com validações
 * @param array $file
 * @param string $uploadDir
 * @return array
 */
function processImageUpload($file, $uploadDir) {
    // Configurações
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Verificar se houve erro no upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => "O arquivo excede o tamanho máximo permitido pelo servidor.",
            UPLOAD_ERR_FORM_SIZE => "O arquivo excede o tamanho máximo permitido pelo formulário.",
            UPLOAD_ERR_PARTIAL => "O upload do arquivo foi feito parcialmente.",
            UPLOAD_ERR_NO_FILE => "Nenhum arquivo foi enviado.",
            UPLOAD_ERR_NO_TMP_DIR => "Falta uma pasta temporária.",
            UPLOAD_ERR_CANT_WRITE => "Falha ao escrever o arquivo em disco.",
            UPLOAD_ERR_EXTENSION => "Uma extensão PHP parou o upload do arquivo."
        ];
        
        return [
            'success' => false,
            'message' => $errorMessages[$file['error']] ?? "Erro desconhecido no upload."
        ];
    }
    
    // Verificar tamanho do arquivo
    if ($file['size'] > $maxFileSize) {
        return [
            'success' => false,
            'message' => "O arquivo é muito grande. Tamanho máximo permitido: 5MB."
        ];
    }
    
    // Verificar tipo MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return [
            'success' => false,
            'message' => "Tipo de arquivo não permitido. Formatos aceitos: JPG, PNG, GIF, WEBP."
        ];
    }
    
    // Verificar extensão do arquivo
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        return [
            'success' => false,
            'message' => "Extensão de arquivo não permitida. Formatos aceitos: JPG, PNG, GIF, WEBP."
        ];
    }
    
    // Verificar se é realmente uma imagem
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return [
            'success' => false,
            'message' => "O arquivo não é uma imagem válida."
        ];
    }
    
    // Verificar dimensões da imagem
    $maxWidth = 2048;
    $maxHeight = 2048;
    
    if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
        return [
            'success' => false,
            'message' => "A imagem é muito grande. Dimensões máximas: 2048x2048 pixels."
        ];
    }
    
    // Gerar nome único para o arquivo
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . '/' . $filename;
    
    // Mover arquivo para o diretório de uploads
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true,
            'filename' => $filename,
            'message' => "Upload realizado com sucesso."
        ];
    } else {
        return [
            'success' => false,
            'message' => "Erro ao salvar o arquivo. Tente novamente."
        ];
    }
}
?>