<div class="error_message" <?php if(strlen($error_message) < 1) { echo 'style="display: none;"'; } ?>>
    <?php echo isset($error_message) ? $error_message : '' ?>
</div>