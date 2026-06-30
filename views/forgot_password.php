<h2>Recuperar Senha</h2>

<?php if (isset($message)): ?>
    <div style="color: <?php echo ($message_type === 'error') ? 'red' : 'green'; ?>;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>/index.php?route=passwordReset_forgotAction" method="POST">
    <div style="margin-bottom: 15px;">
        <label for="email">E-mail cadastrado:</label><br>
        <input type="email" id="email" name="email" required style="width: 100%;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="phone">Telefone (DDD + Número):</label><br>
        <input type="text" id="phone" name="phone" required style="width: 100%;">
    </div>

    <button type="submit" style="width: 100%; padding: 10px; cursor: pointer;">
        Enviar Código de Recuperação
    </button>
</form>