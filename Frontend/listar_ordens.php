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

// Buscar ordens de serviço
$stmt = $pdo->prepare("SELECT * FROM ordens_servico WHERE deleted_at IS NULL ORDER BY created_at DESC");
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

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>

    <script>
        let ordemAtual = null;
        let checklistData = {};

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
            if (event.target === modal) {
                fecharModal();
            }
        }
    </script>

    <?php include 'includes/alerts.php'; ?>

</body>

</html> 