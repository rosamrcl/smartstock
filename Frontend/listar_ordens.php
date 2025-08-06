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

$where_adicional = "";

$stmt = $pdo->prepare("
    SELECT os.*, s.arquivo as arquivo_suporte, s.nome as nome_cliente 
    FROM ordens_servico os 
    LEFT JOIN suporte s ON os.id_suporte_origem = s.id_suport 
    WHERE os.deleted_at IS NULL
    ORDER BY 
        FIELD(os.urgencia, 'critica', 'alta', 'media', 'baixa'),
        os.created_at DESC
");
$stmt->execute();
$ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$counts_query = "
    SELECT urgencia, COUNT(*) as total 
    FROM ordens_servico 
    WHERE deleted_at IS NULL
    GROUP BY urgencia
";
$counts = $pdo->query($counts_query)->fetchAll(PDO::FETCH_KEY_PAIR);

$count_critica = $counts['critica'] ?? 0;
$count_alta = $counts['alta'] ?? 0;
$count_media = $counts['media'] ?? 0;
$count_baixa = $counts['baixa'] ?? 0;

$count_total = $pdo->query("
    SELECT COUNT(*) FROM ordens_servico 
    WHERE deleted_at IS NULL
")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Listar Ordens de Serviço</title>
    <?php include 'includes/head.php'; ?>   

</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="ordens-container">
        <div class="heading">
            <h1>Ordens de Serviço</h1>
            <p>Gerencie e acompanhe as ordens de serviço</p>
        </div>

        <div class="dashboard-urgencias">
            <div class="card-urgencia critica">
                <h5 class="text-danger"><?= $count_critica ?></h5>
                <p>🔴 Críticas</p>
            </div>
            <div class="card-urgencia alta">
                <h5 class="text-warning"><?= $count_alta ?></h5>
                <p>🟠 Altas</p>
            </div>
            <div class="card-urgencia media">
                <h5 class="text-primary"><?= $count_media ?></h5>
                <p>🟡 Médias</p>
            </div>
            <div class="card-urgencia baixa">
                <h5 class="text-success"><?= $count_baixa ?></h5>
                <p>🟢 Baixas</p>
            </div>
        </div>

        <div class="filtros-urgencia">
            <button class="btn-outline-danger" onclick="filtrarUrgencia('critica')">
                🔴 Críticas (<?= $count_critica ?>)
            </button>
            <button class="btn-outline-warning" onclick="filtrarUrgencia('alta')">
                🟠 Altas (<?= $count_alta ?>)
            </button>
            <button class="btn-outline-primary" onclick="filtrarUrgencia('media')">
                🟡 Médias (<?= $count_media ?>)
            </button>
            <button class="btn-outline-success" onclick="filtrarUrgencia('baixa')">
                🟢 Baixas (<?= $count_baixa ?>)
            </button>
            <button class="btn-secondary active" onclick="filtrarUrgencia('todas')">
                📋 Todas
            </button>
        </div>

        <?php if (empty($ordens)): ?>
            <div class="no-orders">
                <p>Nenhuma ordem de serviço encontrada.</p>
            </div>
        <?php else: ?>
            <?php foreach ($ordens as $ordem): ?>
                <?php 
                // Definir classes de urgência
                $urgencia_class = 'urgencia-' . ($ordem['urgencia'] ?? 'media');
                
                // Definir ícones e textos de urgência
                $urgencias = [
                    'critica' => '🔴 Crítica',
                    'alta' => '🟠 Alta', 
                    'media' => '🟡 Média',
                    'baixa' => '🟢 Baixa'
                ];
                $urgencia_text = $urgencias[$ordem['urgencia'] ?? 'media'] ?? '🟡 Média';
                
                // Definir ícones de categoria
                $categorias = [
                    'hardware' => '🖥️ Hardware',
                    'software' => '💾 Software',
                    'rede' => '🌐 Rede',
                    'email' => '📧 Email',
                    'impressora' => '🖨️ Impressora',
                    'outros' => '❓ Outros'
                ];
                $categoria_text = $categorias[$ordem['categoria']] ?? $ordem['categoria'];
                ?>
                <?php 
                // Verificar se este chamado deve ser destacado
                $highlight_id = $_GET['highlight'] ?? null;
                $is_highlighted = $highlight_id == $ordem['id_services'];
                $highlight_class = $is_highlighted ? 'chamado-destacado' : '';
                ?>
                <div class="ordem-card <?= $urgencia_class ?> <?= $highlight_class ?>" data-id="<?php echo $ordem['id_services']; ?>">
                    <div class="ordem-header">
                        <div class="ordem-info">
                            <div class="ordem-urgencia">
                                <span class="badge badge-<?= $ordem['urgencia'] ?? 'media' ?>"><?= $urgencia_text ?></span>
                            </div>
                            <h3><?php echo htmlspecialchars($ordem['solicitante']); ?></h3>
                            <p><strong>📂 Categoria:</strong> <?= $categoria_text ?></p>
                            <?php if (!empty($ordem['setor'])): ?>
                                <p><strong>🏢 Setor:</strong> <?php echo htmlspecialchars($ordem['setor']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($ordem['equipamento'])): ?>
                                <p><strong>💻 Equipamento:</strong> <?php echo htmlspecialchars($ordem['equipamento']); ?></p>
                            <?php endif; ?>
                            <p><strong>📝 Observações:</strong> 
                                <div class="observacao-container">
                                    <span class="observacao-texto" id="obs-<?php echo $ordem['id_services']; ?>">
                                        <?php echo htmlspecialchars(substr($ordem['observacoes'], 0, 150)); ?>
                                    </span>
                                    <span class="observacao-completa" id="obs-completa-<?php echo $ordem['id_services']; ?>" style="display: none;">
                                        <?php echo htmlspecialchars($ordem['observacoes']); ?>
                                    </span>
                                    <?php if (strlen($ordem['observacoes']) > 150): ?>
                                        <a href="#" class="mostrar-mais" onclick="toggleObservacao('<?php echo $ordem['id_services']; ?>')">mostrar mais</a>
                                    <?php endif; ?>
                                </div>
                            </p>
                            <p><strong>📅 Data:</strong> <?php echo date('d/m/Y H:i', strtotime($ordem['created_at'])); ?></p>
                            
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
                <div class="progress-fill" id="progressFill"></div>
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
            <img id="imagemViewer" src="" alt="Imagem do suporte">
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

        // Função para filtrar por urgência
        function filtrarUrgencia(urgencia) {
            const linhas = document.querySelectorAll('.ordem-card');
            
            linhas.forEach(linha => {
                if (urgencia === 'todas') {
                    linha.style.display = '';
                } else {
                    const temUrgencia = linha.classList.contains(`urgencia-${urgencia}`);
                    linha.style.display = temUrgencia ? '' : 'none';
                }
            });
            
            // Atualizar botão ativo
            document.querySelectorAll('.filtros-urgencia button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
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

        // Função para expandir/contrair observações
        function toggleObservacao(id) {
            const textoTruncado = document.getElementById('obs-' + id);
            const textoCompleto = document.getElementById('obs-completa-' + id);
            const link = textoTruncado.nextElementSibling.nextElementSibling; // Link "mostrar mais"
            
            if (textoCompleto.style.display === 'none') {
                // Expandir
                textoTruncado.style.display = 'none';
                textoCompleto.style.display = 'inline';
                link.textContent = 'mostrar menos';
            } else {
                // Contrair
                textoCompleto.style.display = 'none';
                textoTruncado.style.display = 'inline';
                link.textContent = 'mostrar mais';
            }
        }

        // Scroll automático para chamado destacado
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const highlightId = urlParams.get('highlight');
            
            if (highlightId) {
                const chamadoDestacado = document.querySelector(`[data-id="${highlightId}"]`);
                if (chamadoDestacado) {
                    // Scroll suave até o chamado destacado
                    setTimeout(() => {
                        chamadoDestacado.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    }, 500);
                    
                    // Remover o parâmetro highlight da URL após 3 segundos
                    setTimeout(() => {
                        const url = new URL(window.location);
                        url.searchParams.delete('highlight');
                        window.history.replaceState({}, '', url);
                    }, 3000);
                }
            }
        });
    </script>

    <?php include 'includes/alerts.php'; ?>

</body>

</html> 