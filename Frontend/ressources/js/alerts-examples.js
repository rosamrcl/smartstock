/**
 * Exemplos de Uso do Sistema de Alertas SmartStock
 * Este arquivo demonstra como usar o sistema de alertas em diferentes cenários
 */

// ============================================================================
// EXEMPLO 1: Validação de Formulário Simples
// ============================================================================

function exemploValidacaoSimples() {
    const form = document.getElementById('formSimples');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value.trim();
        
        // Validação básica
        if (!email || !senha) {
            smartStockAlerts.showError('Campos obrigatórios', 'Por favor, preencha todos os campos.');
            return;
        }
        
        if (!smartStockAlerts.validateEmail(email)) {
            smartStockAlerts.showError('Email inválido', 'Por favor, insira um email válido.');
            return;
        }
        
        // Sucesso
        smartStockAlerts.showSuccess('Formulário enviado!', 'Dados processados com sucesso.');
    });
}

// ============================================================================
// EXEMPLO 2: Validação com Confirmação
// ============================================================================

function exemploValidacaoComConfirmacao() {
    const form = document.getElementById('formComConfirmacao');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const dados = {
            nome: document.getElementById('nome').value.trim(),
            email: document.getElementById('email').value.trim(),
            telefone: document.getElementById('telefone').value.trim()
        };
        
        // Validar dados
        const erros = [];
        if (!dados.nome) erros.push('Nome é obrigatório');
        if (!dados.email) erros.push('Email é obrigatório');
        if (!smartStockAlerts.validateEmail(dados.email)) erros.push('Email inválido');
        
        if (erros.length > 0) {
            smartStockAlerts.showError('Dados inválidos', erros[0]);
            return;
        }
        
        // Confirmar antes de enviar
        smartStockAlerts.showConfirm(
            'Confirmar envio?',
            `Deseja enviar os dados para ${dados.email}?`,
            'Sim, enviar!',
            'Cancelar'
        ).then((result) => {
            if (result.isConfirmed) {
                smartStockAlerts.showLoading('Enviando...', 'Processando dados...');
                
                // Simular envio
                setTimeout(() => {
                    smartStockAlerts.close();
                    smartStockAlerts.showSuccess('Enviado!', 'Dados enviados com sucesso.');
                }, 2000);
            }
        });
    });
}

// ============================================================================
// EXEMPLO 3: Validação de Senha com Feedback
// ============================================================================

function exemploValidacaoSenha() {
    const senhaInput = document.getElementById('senha');
    const confirmarSenhaInput = document.getElementById('confirmarSenha');
    
    senhaInput.addEventListener('input', function() {
        const senha = this.value;
        const validation = smartStockAlerts.validatePassword(senha);
        
        if (!validation.isValid) {
            smartStockAlerts.showFieldError(this, validation.errors[0]);
        } else {
            smartStockAlerts.clearFieldError(this);
            
            // Mostrar sugestões se houver
            if (validation.suggestions.length > 0) {
                smartStockAlerts.showInfo('Dica de senha', validation.suggestions[0]);
            }
        }
    });
    
    confirmarSenhaInput.addEventListener('blur', function() {
        const confirmarSenha = this.value;
        const senha = senhaInput.value;
        
        if (confirmarSenha !== senha) {
            smartStockAlerts.showFieldError(this, 'As senhas não coincidem');
        } else {
            smartStockAlerts.clearFieldError(this);
        }
    });
}

// ============================================================================
// EXEMPLO 4: Upload de Arquivo com Progresso
// ============================================================================

function exemploUploadArquivo() {
    const fileInput = document.getElementById('arquivo');
    const uploadBtn = document.getElementById('uploadBtn');
    
    uploadBtn.addEventListener('click', function() {
        const file = fileInput.files[0];
        
        if (!file) {
            smartStockAlerts.showError('Nenhum arquivo selecionado', 'Por favor, selecione um arquivo para upload.');
            return;
        }
        
        // Validar tipo de arquivo
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            smartStockAlerts.showError('Tipo de arquivo inválido', 'Por favor, selecione apenas imagens (JPG, PNG, GIF).');
            return;
        }
        
        // Validar tamanho (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            smartStockAlerts.showError('Arquivo muito grande', 'O arquivo deve ter no máximo 5MB.');
            return;
        }
        
        // Confirmar upload
        smartStockAlerts.showConfirm(
            'Confirmar upload?',
            `Deseja fazer upload do arquivo "${file.name}"?`,
            'Sim, fazer upload!',
            'Cancelar'
        ).then((result) => {
            if (result.isConfirmed) {
                const loading = smartStockAlerts.showLoading('Fazendo upload...', 'Aguarde...');
                
                // Simular upload
                setTimeout(() => {
                    smartStockAlerts.close();
                    smartStockAlerts.showSuccess('Upload realizado!', 'Arquivo enviado com sucesso.');
                }, 3000);
            }
        });
    });
}

// ============================================================================
// EXEMPLO 5: Exclusão com Confirmação
// ============================================================================

function exemploExclusao() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            
            smartStockAlerts.showConfirm(
                'Confirmar exclusão?',
                `Deseja realmente excluir "${itemName}"? Esta ação não pode ser desfeita.`,
                'Sim, excluir!',
                'Cancelar'
            ).then((result) => {
                if (result.isConfirmed) {
                    const loading = smartStockAlerts.showLoading('Excluindo...', 'Removendo item...');
                    
                    // Simular exclusão
                    setTimeout(() => {
                        smartStockAlerts.close();
                        smartStockAlerts.showSuccess('Excluído!', 'Item removido com sucesso.');
                        
                        // Remover elemento da página
                        const item = document.querySelector(`[data-id="${itemId}"]`);
                        if (item) {
                            item.remove();
                        }
                    }, 1500);
                }
            });
        });
    });
}

// ============================================================================
// EXEMPLO 6: Validação de Formulário Completo
// ============================================================================

function exemploFormularioCompleto() {
    const form = document.getElementById('formCompleto');
    
    // Validação em tempo real
    const inputs = form.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            
            if (!value) {
                smartStockAlerts.showFieldError(this, 'Este campo é obrigatório');
                return;
            }
            
            // Validações específicas
            if (this.type === 'email' && !smartStockAlerts.validateEmail(value)) {
                smartStockAlerts.showFieldError(this, 'Email inválido');
                return;
            }
            
            if (this.type === 'tel' && value.length < 10) {
                smartStockAlerts.showFieldError(this, 'Telefone inválido');
                return;
            }
            
            smartStockAlerts.clearFieldError(this);
        });
        
        // Limpar erro quando começar a digitar
        input.addEventListener('input', function() {
            smartStockAlerts.clearFieldError(this);
        });
    });
    
    // Envio do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const dados = {};
        
        for (let [key, value] of formData.entries()) {
            dados[key] = value.trim();
        }
        
        // Validação completa
        const erros = [];
        
        if (!dados.nome) erros.push('Nome é obrigatório');
        if (!dados.email) erros.push('Email é obrigatório');
        if (!smartStockAlerts.validateEmail(dados.email)) erros.push('Email inválido');
        if (!dados.telefone) erros.push('Telefone é obrigatório');
        if (dados.telefone.length < 10) erros.push('Telefone inválido');
        
        if (erros.length > 0) {
            smartStockAlerts.showError('Dados inválidos', erros[0]);
            return;
        }
        
        // Mostrar loading
        const loading = smartStockAlerts.showLoading('Processando...', 'Enviando dados...');
        
        // Simular envio
        setTimeout(() => {
            smartStockAlerts.close();
            smartStockAlerts.showSuccess('Sucesso!', 'Formulário enviado com sucesso.');
            form.reset();
        }, 2000);
    });
}

// ============================================================================
// EXEMPLO 7: Alertas Informativos
// ============================================================================

function exemploAlertasInformativos() {
    // Alerta de boas-vindas
    setTimeout(() => {
        smartStockAlerts.showInfo('Bem-vindo!', 'Este é um exemplo de alerta informativo.');
    }, 1000);
    
    // Alerta de aviso
    setTimeout(() => {
        smartStockAlerts.showWarning('Atenção!', 'Este é um exemplo de alerta de aviso.');
    }, 3000);
    
    // Alerta de erro
    setTimeout(() => {
        smartStockAlerts.showError('Erro!', 'Este é um exemplo de alerta de erro.');
    }, 5000);
}

// ============================================================================
// EXEMPLO 8: Validação de CPF
// ============================================================================

function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]/g, '');
    
    if (cpf.length !== 11) return false;
    
    // Verificar se todos os dígitos são iguais
    if (/^(\d)\1{10}$/.test(cpf)) return false;
    
    // Validar primeiro dígito verificador
    let soma = 0;
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let resto = 11 - (soma % 11);
    let dv1 = resto < 2 ? 0 : resto;
    
    // Validar segundo dígito verificador
    soma = 0;
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
    }
    resto = 11 - (soma % 11);
    let dv2 = resto < 2 ? 0 : resto;
    
    return parseInt(cpf.charAt(9)) === dv1 && parseInt(cpf.charAt(10)) === dv2;
}

function exemploValidacaoCPF() {
    const cpfInput = document.getElementById('cpf');
    
    cpfInput.addEventListener('blur', function() {
        const cpf = this.value.trim();
        
        if (!cpf) {
            smartStockAlerts.showFieldError(this, 'CPF é obrigatório');
            return;
        }
        
        if (!validarCPF(cpf)) {
            smartStockAlerts.showFieldError(this, 'CPF inválido');
            return;
        }
        
        smartStockAlerts.clearFieldError(this);
    });
}

// ============================================================================
// EXEMPLO 9: Validação de Data
// ============================================================================

function validarData(data) {
    const regex = /^\d{2}\/\d{2}\/\d{4}$/;
    if (!regex.test(data)) return false;
    
    const partes = data.split('/');
    const dia = parseInt(partes[0]);
    const mes = parseInt(partes[1]);
    const ano = parseInt(partes[2]);
    
    const dataObj = new Date(ano, mes - 1, dia);
    
    return dataObj.getDate() === dia &&
           dataObj.getMonth() === mes - 1 &&
           dataObj.getFullYear() === ano &&
           dataObj <= new Date();
}

function exemploValidacaoData() {
    const dataInput = document.getElementById('dataNascimento');
    
    dataInput.addEventListener('blur', function() {
        const data = this.value.trim();
        
        if (!data) {
            smartStockAlerts.showFieldError(this, 'Data é obrigatória');
            return;
        }
        
        if (!validarData(data)) {
            smartStockAlerts.showFieldError(this, 'Data inválida');
            return;
        }
        
        smartStockAlerts.clearFieldError(this);
    });
}

// ============================================================================
// EXEMPLO 10: Sistema de Notificações
// ============================================================================

class SistemaNotificacoes {
    constructor() {
        this.notificacoes = [];
    }
    
    adicionarNotificacao(titulo, mensagem, tipo = 'info') {
        this.notificacoes.push({ titulo, mensagem, tipo, timestamp: new Date() });
        this.mostrarNotificacao(titulo, mensagem, tipo);
    }
    
    mostrarNotificacao(titulo, mensagem, tipo) {
        switch (tipo) {
            case 'success':
                smartStockAlerts.showSuccess(titulo, mensagem);
                break;
            case 'error':
                smartStockAlerts.showError(titulo, mensagem);
                break;
            case 'warning':
                smartStockAlerts.showWarning(titulo, mensagem);
                break;
            default:
                smartStockAlerts.showInfo(titulo, mensagem);
        }
    }
    
    mostrarHistorico() {
        if (this.notificacoes.length === 0) {
            smartStockAlerts.showInfo('Histórico vazio', 'Nenhuma notificação encontrada.');
            return;
        }
        
        const mensagens = this.notificacoes
            .map(n => `${n.titulo}: ${n.mensagem}`)
            .join('\n');
        
        smartStockAlerts.showInfo('Histórico de Notificações', mensagens);
    }
}

// Instância global do sistema de notificações
const notificacoes = new SistemaNotificacoes();

// Exemplo de uso
function exemploSistemaNotificacoes() {
    notificacoes.adicionarNotificacao('Novo usuário', 'João Silva foi cadastrado', 'success');
    notificacoes.adicionarNotificacao('Sistema', 'Backup realizado com sucesso', 'info');
    notificacoes.adicionarNotificacao('Atenção', 'Espaço em disco baixo', 'warning');
}

// ============================================================================
// INICIALIZAÇÃO DOS EXEMPLOS
// ============================================================================

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar exemplos se os elementos existirem
    if (document.getElementById('formSimples')) {
        exemploValidacaoSimples();
    }
    
    if (document.getElementById('formComConfirmacao')) {
        exemploValidacaoComConfirmacao();
    }
    
    if (document.getElementById('senha')) {
        exemploValidacaoSenha();
    }
    
    if (document.getElementById('arquivo')) {
        exemploUploadArquivo();
    }
    
    if (document.querySelector('.btn-delete')) {
        exemploExclusao();
    }
    
    if (document.getElementById('formCompleto')) {
        exemploFormularioCompleto();
    }
    
    if (document.getElementById('cpf')) {
        exemploValidacaoCPF();
    }
    
    if (document.getElementById('dataNascimento')) {
        exemploValidacaoData();
    }
    
    // Exemplo de alertas informativos (comentado para não interferir)
    // exemploAlertasInformativos();
    
    // Exemplo de sistema de notificações
    // exemploSistemaNotificacoes();
});

// ============================================================================
// FUNÇÕES UTILITÁRIAS
// ============================================================================

// Formatar CPF
function formatarCPF(cpf) {
    return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

// Formatar telefone
function formatarTelefone(telefone) {
    return telefone.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
}

// Formatar data
function formatarData(data) {
    const [ano, mes, dia] = data.split('-');
    return `${dia}/${mes}/${ano}`;
}

// Validar URL
function validarURL(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

// Validar telefone brasileiro
function validarTelefone(telefone) {
    const numero = telefone.replace(/\D/g, '');
    return numero.length >= 10 && numero.length <= 11;
} 