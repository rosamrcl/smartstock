<?php
/**
 * Sistema Centralizado de UX/UI - SmartStock
 * 
 * Este arquivo contém funções para melhorar a experiência do usuário
 * e manter consistência visual em todo o sistema.
 */

/**
 * Geração de Loading State
 * @param string $text Texto do botão
 * @param string $loadingText Texto durante loading
 * @param string $icon Ícone do botão
 * @param string $loadingIcon Ícone durante loading
 * @return string HTML do botão com loading state
 */
function generateLoadingButton($text, $loadingText = 'Processando...', $icon = 'fa-check', $loadingIcon = 'fa-spinner fa-spin') {
    return "
    <button type='submit' class='btn' id='submitBtn'>
        <span class='btn-text'>
            <i class='fa-solid $icon'></i>
            $text
        </span>
        <span class='btn-loading' style='display: none;'>
            <i class='fa-solid $loadingIcon'></i>
            $loadingText
        </span>
    </button>";
}

/**
 * Geração de Form Group
 * @param string $label Label do campo
 * @param string $type Tipo do input
 * @param string $name Nome do campo
 * @param string $id ID do campo
 * @param string $placeholder Placeholder
 * @param bool $required Se é obrigatório
 * @param string $icon Ícone do campo
 * @param array $attributes Atributos adicionais
 * @return string HTML do form group
 */
function generateFormGroup($label, $type, $name, $id, $placeholder = '', $required = false, $icon = '', $attributes = []) {
    $requiredAttr = $required ? 'required' : '';
    $requiredClass = $required ? 'required' : '';
    
    $attrString = '';
    foreach ($attributes as $key => $value) {
        $attrString .= " $key=\"$value\"";
    }
    
    $iconHtml = $icon ? "<i class='fa-solid $icon'></i>" : '';
    
    return "
    <div class='form-group $requiredClass'>
        <label for='$id'>$label</label>
        <div class='input-wrapper'>
            $iconHtml
            <input type='$type' name='$name' id='$id' placeholder='$placeholder' $requiredAttr$attrString>
        </div>
        <span class='error-message' id='$id-error'></span>
    </div>";
}

/**
 * Geração de Select Group
 * @param string $label Label do campo
 * @param string $name Nome do campo
 * @param string $id ID do campo
 * @param array $options Array de opções [value => text]
 * @param string $selectedValue Valor selecionado
 * @param bool $required Se é obrigatório
 * @param string $icon Ícone do campo
 * @return string HTML do select group
 */
function generateSelectGroup($label, $name, $id, $options, $selectedValue = '', $required = false, $icon = '') {
    $requiredAttr = $required ? 'required' : '';
    $requiredClass = $required ? 'required' : '';
    $iconHtml = $icon ? "<i class='fa-solid $icon'></i>" : '';
    
    $optionsHtml = '';
    foreach ($options as $value => $text) {
        $selected = ($value == $selectedValue) ? 'selected' : '';
        $optionsHtml .= "<option value='$value' $selected>$text</option>";
    }
    
    return "
    <div class='form-group $requiredClass'>
        <label for='$id'>$label</label>
        <div class='input-wrapper'>
            $iconHtml
            <select name='$name' id='$id' $requiredAttr>
                <option value=''>Selecione...</option>
                $optionsHtml
            </select>
        </div>
        <span class='error-message' id='$id-error'></span>
    </div>";
}

/**
 * Geração de Checkbox Group
 * @param string $label Label do checkbox
 * @param string $name Nome do campo
 * @param string $value Valor do checkbox
 * @param bool $checked Se está marcado
 * @param string $id ID do campo
 * @return string HTML do checkbox
 */
function generateCheckbox($label, $name, $value, $checked = false, $id = '') {
    $checkedAttr = $checked ? 'checked' : '';
    $id = $id ?: $name;
    
    return "
    <label class='checkbox-item'>
        <input type='checkbox' name='$name' value='$value' id='$id' $checkedAttr>
        <span class='checkmark'></span>
        <span class='item-text'>$label</span>
    </label>";
}

/**
 * Geração de Card
 * @param string $title Título do card
 * @param string $content Conteúdo do card
 * @param string $icon Ícone do card
 * @param string $class Classe CSS adicional
 * @return string HTML do card
 */
function generateCard($title, $content, $icon = '', $class = '') {
    $iconHtml = $icon ? "<i class='fa-solid $icon'></i>" : '';
    
    return "
    <div class='card $class'>
        <div class='card-header'>
            $iconHtml
            <h4>$title</h4>
        </div>
        <div class='card-content'>
            $content
        </div>
    </div>";
}

/**
 * Geração de Alert
 * @param string $message Mensagem
 * @param string $type Tipo (success, error, warning, info)
 * @param string $title Título (opcional)
 * @return string HTML do alert
 */
function generateAlert($message, $type = 'info', $title = '') {
    $iconMap = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle'
    ];
    
    $icon = $iconMap[$type] ?? 'fa-info-circle';
    $title = $title ?: ucfirst($type);
    
    return "
    <div class='alert alert-$type'>
        <div class='alert-icon'>
            <i class='fa-solid $icon'></i>
        </div>
        <div class='alert-content'>
            <h5>$title</h5>
            <p>$message</p>
        </div>
    </div>";
}

/**
 * Geração de Breadcrumb
 * @param array $items Array de itens [['url' => '', 'text' => '']]
 * @return string HTML do breadcrumb
 */
function generateBreadcrumb($items) {
    $breadcrumbHtml = "<nav class='breadcrumb'><ol>";
    
    foreach ($items as $index => $item) {
        $isLast = $index === count($items) - 1;
        $class = $isLast ? 'active' : '';
        
        if ($isLast || empty($item['url'])) {
            $breadcrumbHtml .= "<li class='$class'>" . htmlspecialchars($item['text']) . "</li>";
        } else {
            $breadcrumbHtml .= "<li><a href='" . htmlspecialchars($item['url']) . "'>" . htmlspecialchars($item['text']) . "</a></li>";
        }
    }
    
    $breadcrumbHtml .= "</ol></nav>";
    return $breadcrumbHtml;
}

/**
 * Geração de Paginação
 * @param int $currentPage Página atual
 * @param int $totalPages Total de páginas
 * @param string $baseUrl URL base
 * @param array $params Parâmetros adicionais
 * @return string HTML da paginação
 */
function generatePagination($currentPage, $totalPages, $baseUrl, $params = []) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $paginationHtml = "<nav class='pagination'><ul>";
    
    // Botão anterior
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $prevUrl = $baseUrl . '?' . http_build_query(array_merge($params, ['page' => $prevPage]));
        $paginationHtml .= "<li><a href='$prevUrl'><i class='fa-solid fa-chevron-left'></i></a></li>";
    }
    
    // Páginas
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $class = $i === $currentPage ? 'active' : '';
        $url = $baseUrl . '?' . http_build_query(array_merge($params, ['page' => $i]));
        $paginationHtml .= "<li class='$class'><a href='$url'>$i</a></li>";
    }
    
    // Botão próximo
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $nextUrl = $baseUrl . '?' . http_build_query(array_merge($params, ['page' => $nextPage]));
        $paginationHtml .= "<li><a href='$nextUrl'><i class='fa-solid fa-chevron-right'></i></a></li>";
    }
    
    $paginationHtml .= "</ul></nav>";
    return $paginationHtml;
}

/**
 * Geração de Modal
 * @param string $id ID do modal
 * @param string $title Título do modal
 * @param string $content Conteúdo do modal
 * @param string $footer Footer do modal (opcional)
 * @return string HTML do modal
 */
function generateModal($id, $title, $content, $footer = '') {
    $footerHtml = $footer ? "<div class='modal-footer'>$footer</div>" : '';
    
    return "
    <div class='modal' id='$id'>
        <div class='modal-overlay'></div>
        <div class='modal-container'>
            <div class='modal-header'>
                <h3>$title</h3>
                <button class='modal-close' onclick='closeModal(\"$id\")'>
                    <i class='fa-solid fa-times'></i>
                </button>
            </div>
            <div class='modal-content'>
                $content
            </div>
            $footerHtml
        </div>
    </div>";
}

/**
 * Geração de Tooltip
 * @param string $text Texto do tooltip
 * @param string $position Posição (top, bottom, left, right)
 * @param string $class Classe CSS adicional
 * @return string HTML do tooltip
 */
function generateTooltip($text, $position = 'top', $class = '') {
    return "<div class='tooltip tooltip-$position $class' data-tooltip='$text'></div>";
}

/**
 * Geração de Progress Bar
 * @param int $progress Progresso (0-100)
 * @param string $label Label do progresso
 * @param string $class Classe CSS adicional
 * @return string HTML da progress bar
 */
function generateProgressBar($progress, $label = '', $class = '') {
    $labelHtml = $label ? "<span class='progress-label'>$label</span>" : '';
    
    return "
    <div class='progress-bar $class'>
        $labelHtml
        <div class='progress-track'>
            <div class='progress-fill' style='width: $progress%'></div>
        </div>
        <span class='progress-text'>$progress%</span>
    </div>";
}

/**
 * Geração de Badge
 * @param string $text Texto do badge
 * @param string $type Tipo (primary, secondary, success, danger, warning, info)
 * @param string $class Classe CSS adicional
 * @return string HTML do badge
 */
function generateBadge($text, $type = 'primary', $class = '') {
    return "<span class='badge badge-$type $class'>$text</span>";
}

/**
 * Geração de Table
 * @param array $headers Array de headers
 * @param array $rows Array de rows
 * @param string $class Classe CSS adicional
 * @param bool $responsive Se deve ser responsivo
 * @return string HTML da table
 */
function generateTable($headers, $rows, $class = '', $responsive = true) {
    $responsiveClass = $responsive ? 'table-responsive' : '';
    
    $headersHtml = '';
    foreach ($headers as $header) {
        $headersHtml .= "<th>$header</th>";
    }
    
    $rowsHtml = '';
    foreach ($rows as $row) {
        $rowsHtml .= "<tr>";
        foreach ($row as $cell) {
            $rowsHtml .= "<td>$cell</td>";
        }
        $rowsHtml .= "</tr>";
    }
    
    return "
    <div class='$responsiveClass'>
        <table class='table $class'>
            <thead>
                <tr>$headersHtml</tr>
            </thead>
            <tbody>
                $rowsHtml
            </tbody>
        </table>
    </div>";
}

/**
 * Geração de JavaScript para Loading States
 * @return string JavaScript para loading states
 */
function generateLoadingScript() {
    return "
    <script>
        // Função para mostrar loading state
        function showLoading(buttonId) {
            const button = document.getElementById(buttonId);
            if (button) {
                const btnText = button.querySelector('.btn-text');
                const btnLoading = button.querySelector('.btn-loading');
                
                if (btnText && btnLoading) {
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'flex';
                    button.disabled = true;
                }
            }
        }
        
        // Função para esconder loading state
        function hideLoading(buttonId) {
            const button = document.getElementById(buttonId);
            if (button) {
                const btnText = button.querySelector('.btn-text');
                const btnLoading = button.querySelector('.btn-loading');
                
                if (btnText && btnLoading) {
                    btnText.style.display = 'flex';
                    btnLoading.style.display = 'none';
                    button.disabled = false;
                }
            }
        }
        
        // Função para mostrar modal
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Função para fechar modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        
        // Fechar modal ao clicar no overlay
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                const modal = e.target.closest('.modal');
                if (modal) {
                    closeModal(modal.id);
                }
            }
        });
        
        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activeModal = document.querySelector('.modal.active');
                if (activeModal) {
                    closeModal(activeModal.id);
                }
            }
        });
        
        // Função para mostrar tooltip
        function showTooltip(element, text, position = 'top') {
            const tooltip = document.createElement('div');
            tooltip.className = `tooltip tooltip-${position}`;
            tooltip.textContent = text;
            tooltip.style.position = 'absolute';
            tooltip.style.zIndex = '1000';
            
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            
            let left, top;
            switch (position) {
                case 'top':
                    left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                    top = rect.top - tooltipRect.height - 5;
                    break;
                case 'bottom':
                    left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                    top = rect.bottom + 5;
                    break;
                case 'left':
                    left = rect.left - tooltipRect.width - 5;
                    top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                    break;
                case 'right':
                    left = rect.right + 5;
                    top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                    break;
            }
            
            tooltip.style.left = left + 'px';
            tooltip.style.top = top + 'px';
            
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 3000);
        }
        
        // Função para validar formulário
        function validateForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return false;
            
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    showError(input, 'Este campo é obrigatório');
                    isValid = false;
                } else {
                    clearError(input);
                }
            });
            
            return isValid;
        }
        
        // Função para mostrar erro
        function showError(input, message) {
            const errorElement = document.getElementById(input.id + '-error');
            if (errorElement) {
                errorElement.textContent = message;
                input.classList.add('error');
            }
        }
        
        // Função para limpar erro
        function clearError(input) {
            const errorElement = document.getElementById(input.id + '-error');
            if (errorElement) {
                errorElement.textContent = '';
                input.classList.remove('error');
            }
        }
    </script>";
}

/**
 * Geração de CSS para Componentes
 * @return string CSS para componentes
 */
function generateComponentCSS() {
    return "
    <style>
        /* Loading States */
        .btn-loading {
            display: none;
        }
        
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .form-group.required label::after {
            content: ' *';
            color: #e74c3c;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #ddd;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .input-wrapper input:focus,
        .input-wrapper select:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .input-wrapper input.error,
        .input-wrapper select.error {
            border-color: #e74c3c;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        /* Cards */
        .card {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1rem;
        }
        
        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-content {
            padding: 1rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: 1rem;
        }
        
        .breadcrumb ol {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .breadcrumb li {
            display: flex;
            align-items: center;
        }
        
        .breadcrumb li:not(:last-child)::after {
            content: '/';
            margin: 0 0.5rem;
            color: #6c757d;
        }
        
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        
        .breadcrumb li.active {
            color: #6c757d;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }
        
        .pagination ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            color: #007bff;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .pagination a:hover {
            background-color: #e9ecef;
        }
        
        .pagination li.active a {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-container {
            background: #fff;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            z-index: 1001;
        }
        
        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-content {
            padding: 1rem;
        }
        
        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: #6c757d;
        }
        
        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block;
        }
        
        .tooltip::before {
            content: attr(data-tooltip);
            position: absolute;
            background: #333;
            color: #fff;
            padding: 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .tooltip:hover::before {
            opacity: 1;
        }
        
        .tooltip-top::before {
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 0.5rem;
        }
        
        .tooltip-bottom::before {
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 0.5rem;
        }
        
        .tooltip-left::before {
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-right: 0.5rem;
        }
        
        .tooltip-right::before {
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 0.5rem;
        }
        
        /* Progress Bar */
        .progress-bar {
            margin: 1rem 0;
        }
        
        .progress-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .progress-track {
            width: 100%;
            height: 0.5rem;
            background-color: #e9ecef;
            border-radius: 0.25rem;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #007bff;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.25rem;
            text-transform: uppercase;
        }
        
        .badge-primary {
            background-color: #007bff;
            color: #fff;
        }
        
        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: #fff;
        }
        
        /* Table */
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modal-container {
                width: 95%;
                margin: 1rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .pagination a {
                width: 2rem;
                height: 2rem;
                font-size: 0.875rem;
            }
        }
    </style>";
}
?> 