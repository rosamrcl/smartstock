/**
 * Componentes de Checklist - SmartStock
 * 
 * Este arquivo contém funções JavaScript para manipulação
 * de checklists, interações e estados.
 */

// ===== CONFIGURAÇÕES GLOBAIS =====
const CHECKLIST_CONFIG = {
    animationDuration: 300,
    storageKey: 'checklist_state'
};

// ===== FUNÇÕES DE MANIPULAÇÃO DE CHECKLIST =====

/**
 * Inicializa seções de checklist
 */
function initChecklistSections() {
    const sections = document.querySelectorAll('.checklist-section-header');
    
    sections.forEach(section => {
        section.addEventListener('click', function() {
            const itemsContainer = this.nextElementSibling;
            const isActive = itemsContainer.classList.contains('active');
            
            // Fechar todas as seções
            document.querySelectorAll('.checklist-items').forEach(items => {
                items.classList.remove('active');
            });
            
            // Abrir seção clicada se não estava ativa
            if (!isActive) {
                itemsContainer.classList.add('active');
            }
            
            // Atualizar ícone
            const icon = this.querySelector('.checklist-section-icon i');
            if (icon) {
                icon.classList.toggle('fa-chevron-down', !isActive);
                icon.classList.toggle('fa-chevron-right', isActive);
            }
        });
    });
}

/**
 * Inicializa checkboxes de itens
 */
function initChecklistItems() {
    const checkboxes = document.querySelectorAll('.checklist-item-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('click', function() {
            const item = this.closest('.checklist-item');
            const isChecked = this.classList.contains('checked');
            
            if (!isChecked) {
                this.classList.add('checked');
                item.classList.add('completed');
                
                // Atualizar status do item
                const statusElement = item.querySelector('.checklist-item-status');
                if (statusElement) {
                    statusElement.textContent = 'Concluído';
                    statusElement.className = 'checklist-item-status completed';
                }
                
                // Salvar estado
                saveChecklistState();
                
                // Atualizar contadores
                updateSectionCounters();
            }
        });
    });
}

/**
 * Atualiza contadores das seções
 */
function updateSectionCounters() {
    const sections = document.querySelectorAll('.checklist-section');
    
    sections.forEach(section => {
        const items = section.querySelectorAll('.checklist-item');
        const completedItems = section.querySelectorAll('.checklist-item.completed');
        const counter = section.querySelector('.checklist-section-count');
        
        if (counter) {
            counter.textContent = `${completedItems.length}/${items.length}`;
        }
        
        // Atualizar status da seção
        const statusElement = section.querySelector('.checklist-section-status');
        if (statusElement && completedItems.length === items.length) {
            statusElement.innerHTML = '<i class="fas fa-check-circle"></i> Concluída';
        }
    });
}

/**
 * Salva estado do checklist no localStorage
 */
function saveChecklistState() {
    const state = {};
    const checkboxes = document.querySelectorAll('.checklist-item-checkbox');
    
    checkboxes.forEach((checkbox, index) => {
        if (checkbox.classList.contains('checked')) {
            state[index] = true;
        }
    });
    
    localStorage.setItem(CHECKLIST_CONFIG.storageKey, JSON.stringify(state));
}

/**
 * Carrega estado do checklist do localStorage
 */
function loadChecklistState() {
    const savedState = localStorage.getItem(CHECKLIST_CONFIG.storageKey);
    
    if (savedState) {
        const state = JSON.parse(savedState);
        const checkboxes = document.querySelectorAll('.checklist-item-checkbox');
        
        checkboxes.forEach((checkbox, index) => {
            if (state[index]) {
                checkbox.classList.add('checked');
                const item = checkbox.closest('.checklist-item');
                item.classList.add('completed');
                
                // Atualizar status do item
                const statusElement = item.querySelector('.checklist-item-status');
                if (statusElement) {
                    statusElement.textContent = 'Concluído';
                    statusElement.className = 'checklist-item-status completed';
                }
            }
        });
        
        updateSectionCounters();
    }
}

/**
 * Limpa estado do checklist
 */
function clearChecklistState() {
    localStorage.removeItem(CHECKLIST_CONFIG.storageKey);
    
    const checkboxes = document.querySelectorAll('.checklist-item-checkbox');
    const items = document.querySelectorAll('.checklist-item');
    
    checkboxes.forEach(checkbox => {
        checkbox.classList.remove('checked');
    });
    
    items.forEach(item => {
        item.classList.remove('completed');
        
        const statusElement = item.querySelector('.checklist-item-status');
        if (statusElement) {
            statusElement.textContent = 'Pendente';
            statusElement.className = 'checklist-item-status pending';
        }
    });
    
    updateSectionCounters();
}

// ===== FUNÇÕES DE INTERAÇÃO =====

/**
 * Marca item como concluído via AJAX
 * @param {HTMLElement} button - Botão de concluir
 * @param {string} idChamado - ID do chamado
 */
function marcarConcluido(button, idChamado) {
    // Mostrar estado de carregamento
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch('../Backend/marcar_concluido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(idChamado)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const linha = button.closest('tr');
            linha.classList.add('tr-concluida');
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-check-double"></i>';
            
            // Salvar estado
            saveChecklistState();
        } else {
            button.innerHTML = originalText;
            button.disabled = false;
            alert("Erro ao marcar como concluído.");
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        alert("Erro de comunicação com o servidor.");
    });
}

/**
 * Abre primeira seção por padrão
 */
function openFirstSection() {
    const firstSection = document.querySelector('.checklist-section');
    if (firstSection) {
        const itemsContainer = firstSection.querySelector('.checklist-items');
        const header = firstSection.querySelector('.checklist-section-header');
        
        if (itemsContainer && header) {
            itemsContainer.classList.add('active');
            
            const icon = header.querySelector('.checklist-section-icon i');
            if (icon) {
                icon.classList.add('fa-chevron-down');
                icon.classList.remove('fa-chevron-right');
            }
        }
    }
}

// ===== FUNÇÕES DE UTILIDADE =====

/**
 * Ajusta tabelas para dispositivos móveis
 */
function adjustTablesForMobile() {
    if (window.innerWidth <= 768) {
        document.querySelectorAll('.table-container').forEach(container => {
            container.style.overflowX = 'hidden';
        });
    } else {
        document.querySelectorAll('.table-container').forEach(container => {
            container.style.overflowX = 'auto';
        });
    }
}

/**
 * Formata data para exibição
 * @param {string} dateString - String da data
 * @returns {string} - Data formatada
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Formata status para exibição
 * @param {string} status - Status do item
 * @returns {string} - Status formatado
 */
function formatStatus(status) {
    const statusMap = {
        'pending': 'Pendente',
        'completed': 'Concluído',
        'in_progress': 'Em Andamento'
    };
    
    return statusMap[status] || status;
}

// ===== INICIALIZAÇÃO =====

/**
 * Inicializa componentes de checklist
 */
function initChecklistComponents() {
    // Inicializar seções
    initChecklistSections();
    
    // Inicializar itens
    initChecklistItems();
    
    // Carregar estado salvo
    loadChecklistState();
    
    // Abrir primeira seção
    openFirstSection();
    
    // Ajustar tabelas
    adjustTablesForMobile();
    
    // Event listeners para responsividade
    window.addEventListener('resize', adjustTablesForMobile);
}

// ===== EVENT LISTENERS GLOBAIS =====

/**
 * Configura event listeners globais
 */
function setupGlobalEventListeners() {
    // Botões de ação
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-concluir')) {
            const idChamado = e.target.getAttribute('data-id');
            if (idChamado) {
                marcarConcluido(e.target, idChamado);
            }
        }
        
        if (e.target.matches('.btn-limpar-checklist')) {
            if (confirm('Tem certeza que deseja limpar todo o progresso?')) {
                clearChecklistState();
            }
        }
    });
}

// Exportar funções para uso global
window.ChecklistComponents = {
    initChecklistSections,
    initChecklistItems,
    updateSectionCounters,
    saveChecklistState,
    loadChecklistState,
    clearChecklistState,
    marcarConcluido,
    openFirstSection,
    adjustTablesForMobile,
    formatDate,
    formatStatus,
    initChecklistComponents,
    setupGlobalEventListeners
};

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initChecklistComponents();
    setupGlobalEventListeners();
}); 