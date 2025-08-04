// ===== FUNCIONALIDADES DA PÁGINA DE SUPORTE =====

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== CONTADOR DE CARACTERES =====
    window.contarCaracteres = function(textarea) {
        const contador = textarea.nextElementSibling;
        const atual = textarea.value.length;
        const minimo = 30;
        
        if (atual < minimo) {
            contador.textContent = `${atual}/${minimo} caracteres (mínimo)`;
            contador.style.color = '#e74c3c';
        } else {
            contador.textContent = `${atual} caracteres ✓`;
            contador.style.color = '#27ae60';
        }
    };

    // ===== PREVIEW DE ARQUIVO =====
    const fileInput = document.getElementById('arquivo');
    const preview = document.getElementById('preview-arquivo');
    
    if (fileInput && preview) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = `<p>📄 ${file.name}</p>`;
                }
            } else {
                preview.innerHTML = '';
            }
        });
    }

    // ===== MELHORAR EXPERIÊNCIA DO UPLOAD =====
    const uploadLabel = document.querySelector('.upload-label');
    if (uploadLabel && fileInput) {
        uploadLabel.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // ===== DRAG AND DROP PARA UPLOAD =====
    const uploadArea = document.querySelector('.upload-area');
    
    if (uploadArea && fileInput) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#007bff';
            uploadArea.style.backgroundColor = '#e3f2fd';
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '#f8f9fa';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '#f8f9fa';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // ===== VALIDAÇÃO EM TEMPO REAL =====
    const form = document.querySelector('form');
    const categoriaSelect = document.querySelector('select[name="categoria"]');
    const mensagemTextarea = document.querySelector('textarea[name="mensagem"]');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Validar categoria
            if (!categoriaSelect.value) {
                errorMessage += 'Selecione uma categoria.\n';
                isValid = false;
            }
            
            // Validar mensagem
            if (mensagemTextarea.value.length < 30) {
                errorMessage += 'A descrição deve ter pelo menos 30 caracteres.\n';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, corrija os seguintes erros:\n' + errorMessage);
            }
        });
    }

    // ===== ANIMAÇÕES E FEEDBACK VISUAL =====
    
    // Animar campos quando focados
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#007bff';
            this.style.boxShadow = '0 0 0 2px rgba(0,123,255,0.25)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = '#ddd';
            this.style.boxShadow = 'none';
        });
    });

    // Feedback visual para upload
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const uploadLabel = document.querySelector('.upload-label');
            if (this.files.length > 0) {
                uploadLabel.style.borderColor = '#28a745';
                uploadLabel.style.backgroundColor = '#d4edda';
                uploadLabel.innerHTML = '📎 Arquivo selecionado ✓<small>Clique para trocar</small>';
            } else {
                uploadLabel.style.borderColor = '#ddd';
                uploadLabel.style.backgroundColor = '#f8f9fa';
                uploadLabel.innerHTML = '📎 Clique para anexar arquivo ou imagem<small>Formatos: JPG, PNG, PDF, DOC (máx. 5MB)</small>';
            }
        });
    }

    // ===== AUTOCOMPLETE PARA SETOR =====
    const setorInput = document.querySelector('input[name="setor"]');
    if (setorInput) {
        const setoresComuns = [
            'TI', 'Administrativo', 'Financeiro', 'RH', 'Marketing', 
            'Vendas', 'Produção', 'Logística', 'Qualidade', 'Manutenção'
        ];
        
        setorInput.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            if (value.length > 0) {
                const matches = setoresComuns.filter(setor => 
                    setor.toLowerCase().includes(value)
                );
                
                // Remover sugestões anteriores
                const existingSuggestions = document.querySelector('.sugestoes-setor');
                if (existingSuggestions) {
                    existingSuggestions.remove();
                }
                
                if (matches.length > 0) {
                    const suggestions = document.createElement('div');
                    suggestions.className = 'sugestoes-setor';
                    suggestions.style.cssText = `
                        position: absolute;
                        background: white;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        max-height: 150px;
                        overflow-y: auto;
                        z-index: 1000;
                        width: 100%;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    `;
                    
                    matches.forEach(setor => {
                        const option = document.createElement('div');
                        option.textContent = setor;
                        option.style.cssText = `
                            padding: 8px 12px;
                            cursor: pointer;
                            border-bottom: 1px solid #eee;
                        `;
                        option.addEventListener('click', function() {
                            setorInput.value = setor;
                            suggestions.remove();
                        });
                        option.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = '#f8f9fa';
                        });
                        option.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = 'white';
                        });
                        suggestions.appendChild(option);
                    });
                    
                    setorInput.parentElement.style.position = 'relative';
                    setorInput.parentElement.appendChild(suggestions);
                }
            } else {
                const existingSuggestions = document.querySelector('.sugestoes-setor');
                if (existingSuggestions) {
                    existingSuggestions.remove();
                }
            }
        });
        
        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!setorInput.contains(e.target)) {
                const existingSuggestions = document.querySelector('.sugestoes-setor');
                if (existingSuggestions) {
                    existingSuggestions.remove();
                }
            }
        });
    }

    console.log('Funcionalidades da página de suporte inicializadas!');
}); 