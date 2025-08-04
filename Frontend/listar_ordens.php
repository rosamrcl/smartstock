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
    $fotoPerfil = './uploads/' . $dados['foto_perfil']; // imagem enviada pelo usuário
} else {
    $fotoPerfil = "./ressources/img/perfil.png"; // imagem padrão
}

// Função para verificar se é arquivo de imagem
function is_image_file($filename) {
    if (empty($filename)) return false;
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}

// Buscar ordens de serviço com dados do suporte
$stmt = $pdo->prepare("
    SELECT os.*, s.arquivo as arquivo_suporte, s.nome as nome_cliente 
    FROM ordens_servico os 
    LEFT JOIN suporte s ON os.id_suporte_origem = s.id_suport 
    WHERE os.deleted_at IS NULL 
    ORDER BY os.created_at DESC
");
$stmt->execute();
$ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Listar Ordens de Serviço</title>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="./ressources/css/listar-ordens.css">
    <style>
        /* Estilo para o modal de imagem */
        .modal-imagem {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
        }

        .modal-imagem-content {
            position: relative;
            margin: 5% auto;
            padding: 20px;
            background: white;
            width: 90%;
            max-width: 800px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease;
        }

        .modal-imagem img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            display: block;
            margin: 0 auto;
        }

        .modal-imagem .close {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
            z-index: 2001;
        }

        .modal-imagem .close:hover {
            color: #333;
        }

        .btn-mostrar-imagem {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        .btn-mostrar-imagem:hover {
            background: #138496;
            transform: translateY(-1px);
        }

        .btn-mostrar-imagem i {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .modal-imagem-content {
                margin: 10% auto;
                width: 95%;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="ordens-container">
        <div class="heading">
            <h1>Ordens de Serviço</h1>
            <p>Gerencie e acompanhe as ordens de serviço</p>
        </div>

        <?php if (empty($ordens)): ?>
            <div class="no-orders">
                <p>Nenhuma ordem de serviço encontrada.</p>
            </div>
        <?php else: ?>
            <?php foreach ($ordens as $ordem): ?>
                <div class="ordem-card <?php echo $ordem['status'] === 'Concluída' ? 'concluida' : ''; ?>" data-id="<?php echo $ordem['id_services']; ?>">
                    <div class="ordem-header">
                        <div class="ordem-info">
                            <h3><?php echo htmlspecialchars($ordem['solicitante']); ?></h3>
                            <p><strong>Categoria:</strong> <?php echo htmlspecialchars($ordem['categoria']); ?></p>
                            <p><strong>Observações:</strong> <?php echo htmlspecialchars(substr($ordem['observacoes'], 0, 100)) . (strlen($ordem['observacoes']) > 100 ? '...' : ''); ?></p>
                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($ordem['created_at'])); ?></p>
                            
                            <!-- Botão para mostrar imagem (apenas se houver arquivo de imagem) -->
                            <?php if (!empty($ordem['arquivo_suporte']) && is_image_file($ordem['arquivo_suporte'])): ?>
                                <button class="btn-mostrar-imagem" 
                                        data-arquivo="<?= htmlspecialchars($ordem['arquivo_suporte']) ?>"
                                        title="Ver imagem anexada">
                                    <i class="fas fa-image"></i> 📷 Mostrar Imagem
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="ordem-status status-<?php echo strtolower($ordem['status']); ?>">
                            <?php echo htmlspecialchars($ordem['status']); ?>
                        </div>
                    </div>
                    
                    <div class="ordem-content">
                        <div class="ordem-actions">
                            <button class="btn-checklist" onclick="abrirChecklist(<?php echo $ordem['id_services']; ?>)">
                                <i class="fas fa-clipboard-check"></i> Abrir Checklist
                            </button>
                            <?php if ($ordem['status'] !== 'Concluída'): ?>
                                <button class="btn-finalizar" onclick="finalizarOS(<?php echo $ordem['id_services']; ?>)" id="btn-finalizar-<?php echo $ordem['id_services']; ?>">
                                    <i class="fas fa-check"></i> Finalizar OS
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal do Checklist -->
    <div id="checklistModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Checklist - Ordem de Serviço</h2>
                <span class="close" onclick="fecharModal()">&times;</span>
            </div>
            
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 0%"></div>
            </div>
            <div class="progress-text" id="progressText">0% concluído</div>
            
            <div class="checklist-categories">
                <!-- Equipamentos -->
                <div class="category-card">
                    <div class="category-header">
                        <i class="fas fa-tools"></i>
                        <span>Equipamentos</span>
                    </div>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipamentos[]" value="verificar_funcionamento" onchange="atualizarProgresso()">
                        <span>Verificar funcionamento do equipamento</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipamentos[]" value="limpar_componentes" onchange="atualizarProgresso()">
                        <span>Limpar componentes</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipamentos[]" value="verificar_temperatura" onchange="atualizarProgresso()">
                        <span>Verificar temperatura</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipamentos[]" value="testar_perifericos" onchange="atualizarProgresso()">
                        <span>Testar periféricos</span>
                    </label>
                </div>

                <!-- Software -->
                <div class="category-card">
                    <div class="category-header">
                        <i class="fas fa-laptop-code"></i>
                        <span>Software</span>
                    </div>
                    <label class="checkbox-item">
                        <input type="checkbox" name="software[]" value="atualizar_firmware" onchange="atualizarProgresso()">
                        <span>Atualizar firmware</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="software[]" value="verificar_atualizacoes" onchange="atualizarProgresso()">
                        <span>Verificar atualizações do sistema</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="software[]" value="testar_aplicacoes" onchange="atualizarProgresso()">
                        <span>Testar aplicações críticas</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="software[]" value="verificar_antivirus" onchange="atualizarProgresso()">
                        <span>Verificar antivírus</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="software[]" value="fazer_backup" onchange="atualizarProgresso()">
                        <span>Fazer backup de dados</span>
                    </label>
                </div>

                <!-- Segurança -->
                <div class="category-card">
                    <div class="category-header">
                        <i class="fas fa-shield-alt"></i>
                        <span>Segurança</span>
                    </div>
                    <label class="checkbox-item">
                        <input type="checkbox" name="seguranca[]" value="verificar_cameras" onchange="atualizarProgresso()">
                        <span>Verificar câmeras de segurança</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="seguranca[]" value="testar_alarme" onchange="atualizarProgresso()">
                        <span>Testar sistema de alarme</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="seguranca[]" value="verificar_controle_acesso" onchange="atualizarProgresso()">
                        <span>Verificar controle de acesso</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="seguranca[]" value="testar_sensores" onchange="atualizarProgresso()">
                        <span>Testar sensores</span>
                    </label>
                </div>

                <!-- Documentação -->
                <div class="category-card">
                    <div class="category-header">
                        <i class="fas fa-file-alt"></i>
                        <span>Documentação</span>
                    </div>
                    <label class="checkbox-item">
                        <input type="checkbox" name="documentacao[]" value="atualizar_documentacao" onchange="atualizarProgresso()">
                        <span>Atualizar documentação técnica</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="documentacao[]" value="registrar_alteracoes" onchange="atualizarProgresso()">
                        <span>Registrar alterações realizadas</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="documentacao[]" value="fotografar_instalacao" onchange="atualizarProgresso()">
                        <span>Fotografar instalação</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="documentacao[]" value="gerar_relatorio" onchange="atualizarProgresso()">
                        <span>Gerar relatório de serviço</span>
                    </label>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn-checklist" onclick="salvarChecklist()">
                    <i class="fas fa-save"></i> Salvar Progresso
                </button>
                <button class="btn-finalizar" onclick="finalizarChecklist()">
                    <i class="fas fa-check"></i> Finalizar OS
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para exibir imagem -->
    <div id="modalImagem" class="modal-imagem">
        <div class="modal-imagem-content">
            <span class="close" onclick="fecharModalImagem()">&times;</span>
            <img id="imagemViewer" src="" alt="Imagem do suporte" style="max-width:100%; height:auto;">
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>

    <script>
        let ordemAtual = null;
        let checklistData = {};

        // Funcionalidade do modal de imagem
        document.addEventListener('DOMContentLoaded', function() {
            const btnsImagem = document.querySelectorAll('.btn-mostrar-imagem');
            const modal = document.getElementById('modalImagem');
            const img = document.getElementById('imagemViewer');
            const close = document.querySelector('#modalImagem .close');
            
            btnsImagem.forEach(btn => {
                btn.addEventListener('click', function() {
                    const arquivo = this.dataset.arquivo;
                    img.src = './uploads/' + arquivo; // Caminho relativo ao Frontend
                    modal.style.display = 'block';
                });
            });
            
            close.addEventListener('click', () => modal.style.display = 'none');
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.style.display = 'none';
            });
        });

        function fecharModalImagem() {
            document.getElementById('modalImagem').style.display = 'none';
        }

        function abrirChecklist(ordemId) {
            ordemAtual = ordemId;
            document.getElementById('checklistModal').style.display = 'block';
            
            // Carregar dados salvos se existirem
            carregarChecklist(ordemId);
        }

        function fecharModal() {
            document.getElementById('checklistModal').style.display = 'none';
            ordemAtual = null;
        }

        function atualizarProgresso() {
            const checkboxes = document.querySelectorAll('#checklistModal input[type="checkbox"]');
            const checked = document.querySelectorAll('#checklistModal input[type="checkbox"]:checked');
            const progresso = (checked.length / checkboxes.length) * 100;
            
            document.getElementById('progressFill').style.width = progresso + '%';
            document.getElementById('progressText').textContent = Math.round(progresso) + '% concluído';
        }

        function salvarChecklist() {
            const checkboxes = document.querySelectorAll('#checklistModal input[type="checkbox"]');
            const dados = {};
            
            checkboxes.forEach(checkbox => {
                const name = checkbox.name;
                if (!dados[name]) dados[name] = [];
                if (checkbox.checked) {
                    dados[name].push(checkbox.value);
                }
            });

            // Salvar via AJAX
            fetch('../Backend/salvar_checklist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ordem_id: ordemAtual,
                    checklist_data: dados
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Progresso Salvo!',
                        text: 'O progresso do checklist foi salvo com sucesso.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao salvar o progresso.'
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao salvar o progresso.'
                });
            });
        }

        function finalizarChecklist() {
            Swal.fire({
                title: 'Finalizar Ordem de Serviço?',
                text: 'Esta ação não pode ser desfeita.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, finalizar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../Backend/finalizar_ordem.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ordem_id: ordemAtual
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OS Finalizada!',
                                text: 'A ordem de serviço foi finalizada com sucesso.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro ao finalizar a ordem de serviço.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Erro ao finalizar a ordem de serviço.'
                        });
                    });
                }
            });
        }

        function finalizarOS(ordemId) {
            Swal.fire({
                title: 'Finalizar Ordem de Serviço?',
                text: 'Esta ação não pode ser desfeita.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, finalizar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../Backend/finalizar_ordem.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ordem_id: ordemId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OS Finalizada!',
                                text: 'A ordem de serviço foi finalizada com sucesso.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro ao finalizar a ordem de serviço.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Erro ao finalizar a ordem de serviço.'
                        });
                    });
                }
            });
        }

        function carregarChecklist(ordemId) {
            fetch('../Backend/carregar_checklist.php?ordem_id=' + ordemId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.checklist_data) {
                    const checkboxes = document.querySelectorAll('#checklistModal input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        const name = checkbox.name;
                        const value = checkbox.value;
                        if (data.checklist_data[name] && data.checklist_data[name].includes(value)) {
                            checkbox.checked = true;
                        } else {
                            checkbox.checked = false;
                        }
                    });
                    atualizarProgresso();
                }
            })
            .catch(error => {
                console.error('Erro ao carregar checklist:', error);
            });
        }

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('checklistModal');
            const modalImagem = document.getElementById('modalImagem');
            if (event.target === modal) {
                fecharModal();
            }
            if (event.target === modalImagem) {
                fecharModalImagem();
            }
        }
    </script>

    <?php include 'includes/alerts.php'; ?>

</body>

</html> 