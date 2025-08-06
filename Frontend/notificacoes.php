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