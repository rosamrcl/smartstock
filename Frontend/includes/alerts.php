<?php
// Sistema de Alertas SmartStock - Integração com SweetAlert2

// Verificar mensagens de sessão
if(isset($_SESSION['success_msg'])){
    foreach($_SESSION['success_msg'] as $success_msg){
        echo '<script>
            smartStockAlerts.showSuccess("Sucesso!", "' . addslashes($success_msg) . '");
        </script>';
    }
    unset($_SESSION['success_msg']);
}

if(isset($_SESSION['warning_msg'])){
    foreach($_SESSION['warning_msg'] as $warning_msg){
        echo '<script>
            smartStockAlerts.showWarning("Atenção!", "' . addslashes($warning_msg) . '");
        </script>';
    }
    unset($_SESSION['warning_msg']);
}

if(isset($_SESSION['info_msg'])){
    foreach($_SESSION['info_msg'] as $info_msg){
        echo '<script>
            smartStockAlerts.showInfo("Informação", "' . addslashes($info_msg) . '");
        </script>';
    }
    unset($_SESSION['info_msg']);
}

if(isset($_SESSION['error_msg'])){
    foreach($_SESSION['error_msg'] as $error_msg){
        echo '<script>
            smartStockAlerts.showError("Erro!", "' . addslashes($error_msg) . '");
        </script>';
    }
    unset($_SESSION['error_msg']);
}

// Verificar mensagens locais (para compatibilidade)
if(isset($success_msg)){
    foreach($success_msg as $success_msg){
        echo '<script>
            smartStockAlerts.showSuccess("Sucesso!", "' . addslashes($success_msg) . '");
        </script>';
    }
}

if(isset($warning_msg)){
    foreach($warning_msg as $warning_msg){
        echo '<script>
            smartStockAlerts.showWarning("Atenção!", "' . addslashes($warning_msg) . '");
        </script>';
    }
}

if(isset($info_msg)){
    foreach($info_msg as $info_msg){
        echo '<script>
            smartStockAlerts.showInfo("Informação", "' . addslashes($info_msg) . '");
        </script>';
    }
}

if(isset($error_msg)){
    foreach($error_msg as $error_msg){
        echo '<script>
            smartStockAlerts.showError("Erro!", "' . addslashes($error_msg) . '");
        </script>';
    }
}

?>
