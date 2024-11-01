<?php
/*
 * Blocks Section
 */
?>

<textarea style="width: 95%;" wrap="soft" rows="20" id="<?php esc_attr_e($field['label_for']); ?>"
          name="<?php esc_attr_e('wpsi_settings[blocks][code]'); ?>"
          class="regular-text"><?php esc_attr_e($settings['blocks']['code']); ?></textarea>
