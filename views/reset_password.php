<?php
// Exibe a mensagem de erro se existir na sessão
if (isset($_SESSION['reset_msg'])): ?>
    <div style="background-color: #ffdddd; color: #d8000c; padding: 15px; margin-bottom: 20px; border: 1px solid #d8000c; border-radius: 5px;">
        <?php echo $_SESSION['reset_msg']; ?>
    </div>
<?php 
    // Limpa a mensagem para não aparecer de novo ao recarregar a página
    unset($_SESSION['reset_msg']);
    unset($_SESSION['reset_type']);
endif; 
?>
<form action="<?php echo BASE_URL; ?>/index.php?route=passwordReset_resetAction" method="POST">
    
    <div style="margin-bottom: 15px;">
        <label for="token">Código de Verificação (6 números):</label><br>
        <input type="text" id="token" name="token" pattern="\d{6}" maxlength="6"
               placeholder="Ex: 123456" required autocomplete="off" 
               style="text-align: center; font-size: 20px; letter-spacing: 4px; font-weight: bold; width: 100%;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="new_password">Nova Senha:</label><br>
        <input type="password" id="new_password" name="new_password" required style="width: 100%;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="confirm_password">Confirmar Nova Senha:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%;">
    </div>

    <button type="submit" style="width: 100%; padding: 10px; cursor: pointer;">
        Redefinir Senha
    </button>
</form>