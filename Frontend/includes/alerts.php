<?php
// Sistema de Alertas SmartStock - Integração com SweetAlert2

// Verificar mensagens de sessão
if(isset($_SESSION['success_msg'])){
    if(is_array($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])){
        foreach($_SESSION['success_msg'] as $success_msg){
            echo '<script>
                smartStockAlerts.showSuccess("Sucesso!", "' . addslashes($success_msg) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showSuccess("Sucesso!", "' . addslashes($_SESSION['success_msg']) . '");
        </script>';
    }
    unset($_SESSION['success_msg']);
}

if(isset($_SESSION['warning_msg'])){
    if(is_array($_SESSION['warning_msg']) && !empty($_SESSION['warning_msg'])){
        foreach($_SESSION['warning_msg'] as $warning_msg){
            echo '<script>
                smartStockAlerts.showWarning("Atenção!", "' . addslashes($warning_msg) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showWarning("Atenção!", "' . addslashes($_SESSION['warning_msg']) . '");
        </script>';
    }
    unset($_SESSION['warning_msg']);
}

if(isset($_SESSION['info_msg'])){
    if(is_array($_SESSION['info_msg']) && !empty($_SESSION['info_msg'])){
        foreach($_SESSION['info_msg'] as $info_msg){
            echo '<script>
                smartStockAlerts.showInfo("Informação", "' . addslashes($info_msg) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showInfo("Informação", "' . addslashes($_SESSION['info_msg']) . '");
        </script>';
    }
    unset($_SESSION['info_msg']);
}

if(isset($_SESSION['error_msg'])){
    if(is_array($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])){
        foreach($_SESSION['error_msg'] as $error_msg){
            echo '<script>
                smartStockAlerts.showError("Erro!", "' . addslashes($error_msg) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showError("Erro!", "' . addslashes($_SESSION['error_msg']) . '");
        </script>';
    }
    unset($_SESSION['error_msg']);
}

// Verificar mensagens locais (para compatibilidade)
if(isset($success_msg)){
    if(is_array($success_msg) && !empty($success_msg)){
        foreach($success_msg as $success_msg_item){
            echo '<script>
                smartStockAlerts.showSuccess("Sucesso!", "' . addslashes($success_msg_item) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showSuccess("Sucesso!", "' . addslashes($success_msg) . '");
        </script>';
    }
}

if(isset($warning_msg)){
    if(is_array($warning_msg) && !empty($warning_msg)){
        foreach($warning_msg as $warning_msg_item){
            echo '<script>
                smartStockAlerts.showWarning("Atenção!", "' . addslashes($warning_msg_item) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showWarning("Atenção!", "' . addslashes($warning_msg) . '");
        </script>';
    }
}

if(isset($info_msg)){
    if(is_array($info_msg) && !empty($info_msg)){
        foreach($info_msg as $info_msg_item){
            echo '<script>
                smartStockAlerts.showInfo("Informação", "' . addslashes($info_msg_item) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showInfo("Informação", "' . addslashes($info_msg) . '");
        </script>';
    }
}

if(isset($error_msg)){
    if(is_array($error_msg) && !empty($error_msg)){
        foreach($error_msg as $error_msg_item){
            echo '<script>
                smartStockAlerts.showError("Erro!", "' . addslashes($error_msg_item) . '");
            </script>';
        }
    } else {
        // Tratar caso seja string única
        echo '<script>
            smartStockAlerts.showError("Erro!", "' . addslashes($error_msg) . '");
        </script>';
    }
}

?>
