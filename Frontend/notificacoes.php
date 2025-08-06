<?php
session_start();
include '../Backend/painel.php';

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
    $fotoPerfil = './uploads/' . $dados['foto_perfil'];
} else {
    $fotoPerfil = "./ressources/img/perfil.png";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Notificações</title>
    <?php include 'includes/head.php'; ?>
    <style>
        .notificacoes-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .notificacoes-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .notificacoes-header h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .notificacoes-header p {
            color: #666;
        }

        .notificacoes-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .notificacao-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: relative;
        }

        .notificacao-item:hover {
            background-color: #f8f9fa;
        }

        .notificacao-item.nao-lida {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }

        .notificacao-item.nao-lida:hover {
            background-color: #ffeaa7;
        }

        .notificacao-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .notificacao-titulo {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .notificacao-data {
            color: #666;
            font-size: 12px;
        }

        .notificacao-mensagem {
            color: #555;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .notificacao-info {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #666;
        }

        .notificacao-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .badge-urgencia {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-critica { background-color: #dc3545; color: white; }
        .badge-alta { background-color: #fd7e14; color: white; }
        .badge-media { background-color: #ffc107; color: black; }
        .badge-baixa { background-color: #28a745; color: white; }

        .sem-notificacoes {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }

        .sem-notificacoes i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .contador-notificacoes {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .loading i {
            font-size: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="notificacoes-container">
        <div class="notificacoes-header">
            <h1>Notificações</h1>
            <p>Acompanhe as atualizações dos chamados</p>
        </div>

        <div class="notificacoes-list" id="notificacoesList">
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <p>Carregando notificações...</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>

    <script>
        // Carregar notificações ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            carregarNotificacoes();
        });

        function carregarNotificacoes() {
            fetch('../Backend/listar_notificacoes.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exibirNotificacoes(data.notificacoes, data.nao_lidas);
                } else {
                    mostrarErro('Erro ao carregar notificações');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarErro('Erro ao carregar notificações');
            });
        }

        function exibirNotificacoes(notificacoes, naoLidas) {
            const container = document.getElementById('notificacoesList');
            
            if (notificacoes.length === 0) {
                container.innerHTML = `
                    <div class="sem-notificacoes">
                        <i class="fas fa-bell-slash"></i>
                        <h3>Nenhuma notificação</h3>
                        <p>Você não tem notificações no momento.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notificacoes.forEach(notif => {
                const data = new Date(notif.data_criacao);
                const dataFormatada = data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
                
                const urgenciaClass = `badge-${notif.urgencia || 'media'}`;
                const urgenciaText = {
                    'critica': '🔴 Crítica',
                    'alta': '🟠 Alta',
                    'media': '🟡 Média',
                    'baixa': '🟢 Baixa'
                }[notif.urgencia] || '🟡 Média';

                const naoLidaClass = notif.lida == 0 ? 'nao-lida' : '';
                
                html += `
                    <div class="notificacao-item ${naoLidaClass}" onclick="abrirChamado(${notif.chamado_id}, ${notif.id})">
                        <div class="notificacao-header">
                            <div class="notificacao-titulo">${notif.titulo}</div>
                            <div class="notificacao-data">${dataFormatada}</div>
                        </div>
                        <div class="notificacao-mensagem">${notif.mensagem}</div>
                        <div class="notificacao-info">
                            <span><i class="fas fa-user"></i> ${notif.solicitante}</span>
                            <span><i class="fas fa-tag"></i> ${notif.categoria}</span>
                            <span class="badge-urgencia ${urgenciaClass}">${urgenciaText}</span>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function abrirChamado(chamadoId, notificacaoId) {
            // Marcar como lida via AJAX
            fetch('../Backend/marcar_notificacao_lida.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    notificacao_id: notificacaoId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirecionar para o chamado específico
                    window.location.href = `listar_ordens.php?highlight=${chamadoId}`;
                }
            })
            .catch(error => {
                console.error('Erro ao marcar notificação como lida:', error);
                // Mesmo com erro, redirecionar para o chamado
                window.location.href = `listar_ordens.php?highlight=${chamadoId}`;
            });
        }

        function mostrarErro(mensagem) {
            const container = document.getElementById('notificacoesList');
            container.innerHTML = `
                <div class="sem-notificacoes">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Erro</h3>
                    <p>${mensagem}</p>
                    <button onclick="carregarNotificacoes()" class="btn btn-primary">Tentar novamente</button>
                </div>
            `;
        }
    </script>

    <?php include 'includes/alerts.php'; ?>

</body>

</html> 