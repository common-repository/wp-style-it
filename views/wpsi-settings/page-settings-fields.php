<?php
/*
 * Options Section
 */
?>

<?php
if ('wpsi_suppress-on-posts' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-posts]"
           id="wpsi_settings[options][suppress-on-posts]"
           value="1" <?php checked(1, $settings['options']['suppress-on-posts']) ?>>
<?php
elseif ('wpsi_suppress-on-pages' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-pages]"
           id="wpsi_settings[options][suppress-on-pages]"
           value="1" <?php checked(1, $settings['options']['suppress-on-pages']) ?>>
<?php
elseif ('wpsi_suppress-on-attachment' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-attachment]"
           id="wpsi_settings[options][suppress-on-attachment]"
           value="1" <?php checked(1, $settings['options']['suppress-on-attachment']) ?>>
<?php
elseif ('wpsi_suppress-on-category' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-category]"
           id="wpsi_settings[options][suppress-on-category]"
           value="1" <?php checked(1, $settings['options']['suppress-on-category']) ?>>
<?php
elseif ('wpsi_suppress-on-tag' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-tag]" id="wpsi_settings[options][suppress-on-tag]"
           value="1" <?php checked(1, $settings['options']['suppress-on-tag']) ?>>
<?php
elseif ('wpsi_suppress-on-home' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-home]" id="wpsi_settings[options][suppress-on-home]"
           value="1" <?php checked(1, $settings['options']['suppress-on-home']) ?>>
<?php
elseif ('wpsi_suppress-on-front' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-front]"
           id="wpsi_settings[options][suppress-on-front]"
           value="1" <?php checked(1, $settings['options']['suppress-on-front']) ?>>
<?php
elseif ('wpsi_suppress-on-archive' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-archive]"
           id="wpsi_settings[options][suppress-on-archive]"
           value="1" <?php checked(1, $settings['options']['suppress-on-archive']) ?>>
<?php
elseif ('wpsi_suppress-on-author' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-author]"
           id="wpsi_settings[options][suppress-on-author]"
           value="1" <?php checked(1, $settings['options']['suppress-on-author']) ?>>
<?php
elseif ('wpsi_suppress-on-error' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-error]"
           id="wpsi_settings[options][suppress-on-error]"
           value="1" <?php checked(1, $settings['options']['suppress-on-error']) ?>>
<?php
elseif ('wpsi_suppress-on-wptouch' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-wptouch]"
           id="wpsi_settings[options][suppress-on-wptouch]"
           value="1" <?php checked(1, $settings['options']['suppress-on-wptouch']) ?>>
<?php
elseif ('wpsi_suppress-on-logged-in' == $field['label_for']) : ?>
    <input type="checkbox" name="wpsi_settings[options][suppress-on-logged-in]"
           id="wpsi_settings[options][suppress-on-logged-in]"
           value="1" <?php checked(1, $settings['options']['suppress-on-logged-in']) ?>>
<?php
elseif ('wpsi_suppress-post-id' == $field['label_for']) : ?>
    <input type="text" name="wpsi_settings[options][suppress-post-id]"
           id="wpsi_settings[options][suppress-post-id]"
           value="<?php echo $settings['options']['suppress-post-id']; ?>" placeholder="e.g. 32,9-19,33">
<?php
elseif ('wpsi_suppress-category' == $field['label_for']) : ?>
    <?php $categories = get_terms('category'); ?>
    <select style="min-width: 190px;" id="wpsi_settings[options][suppress-category]"
            name="wpsi_settings[options][suppress-category][]" size="4"
            multiple="multiple">
        <?php foreach ($categories as $category) { ?>
            <option
                value="<?php echo esc_attr($category->term_id); ?>" <?php echo(in_array($category->term_id, (array)$settings['options']['suppress-category']) ? 'selected="selected"' : ''); ?>><?php echo esc_html($category->name); ?></option>
        <?php } ?>
    </select>
    <button id="clear-category" class="button-secondary"
            onclick="jQuery('#wpsi_settings\\[options\\]\\[suppress-category\\]')[0].selectedIndex = -1;return false;">
        Clear
    </button>
<?php
elseif ('wpsi_suppress-tag' == $field['label_for']) : ?>
    <?php $tags = get_terms('post_tag'); ?>
    <select style="min-width: 190px;" id="wpsi_settings[options][suppress-tag]"
            name="wpsi_settings[options][suppress-tag][]" size="4"
            multiple="multiple">
        <?php foreach ($tags as $tag) { ?>
            <option
                value="<?php echo esc_attr($tag->term_id); ?>" <?php echo(in_array($tag->term_id, (array)$settings['options']['suppress-tag']) ? 'selected="selected"' : ''); ?>><?php echo esc_html($tag->name); ?></option>
        <?php } ?>
    </select>
    <button id="clear-tag" class="button-secondary"
            onclick="jQuery('#wpsi_settings\\[options\\]\\[suppress-tag\\]')[0].selectedIndex = -1;return false;">
        Clear
    </button>
<?php
elseif ('wpsi_suppress-user' == $field['label_for']) : ?>
    <?php
    $allUsers = get_users('orderby=post_count&order=DESC');
    $users = array();
    // Remove subscribers from the list as they won't write any articles
    foreach ($allUsers as $currentUser) {
        if (!in_array('subscriber', $currentUser->roles)) {
            $users[] = $currentUser;
        }
    }
    ?>
    <select style="min-width: 190px;" id="wpsi_settings[options][suppress-user]"
            name="wpsi_settings[options][suppress-user][]" size="4"
            multiple="multiple">
        <?php foreach ($users as $user) { ?>
            <option
                value="<?php echo esc_attr($user->ID); ?>" <?php echo(in_array($user->ID, (array)$settings['options']['suppress-user']) ? 'selected="selected"' : ''); ?>><?php echo esc_html($user->display_name); ?></option>
        <?php } ?>
    </select>
    <button id="clear-user" class="button-secondary"
            onclick="jQuery('#wpsi_settings\\[options\\]\\[suppress-user\\]')[0].selectedIndex = -1;return false;">
        Clear
    </button>
<?php
elseif ('wpsi_suppress-format' == $field['label_for']) : ?>
    <?php $formats = get_theme_support('post-formats'); ?>
    <select style="min-width: 190px;" id="wpsi_settings[options][suppress-format]"
            name="wpsi_settings[options][suppress-format][]" size="4"
            multiple="multiple">
        <?php
        if (is_array($formats) && count($formats) > 0) {
            ?>
            <option
                value="0" <?php echo(in_array('0', (array)$settings['options']['suppress-format']) ? 'selected="selected"' : ''); ?>><?php echo get_post_format_string('standard'); ?></option>
            <?php
            foreach ($formats[0] as $format_name) {
                ?>
                <option
                    value="<?php echo esc_attr($format_name); ?>" <?php echo(in_array($format_name, (array)$settings['options']['suppress-format']) ? 'selected="selected"' : ''); ?>><?php echo esc_html(get_post_format_string($format_name)); ?></option>
            <?php
            }
        }
        ?>
    </select>
    <button id="clear-format" class="button-secondary"
            onclick="jQuery('#wpsi_settings\\[options\\]\\[suppress-format\\]')[0].selectedIndex = -1;return false;">
        Clear
    </button>
<?php
elseif ('wpsi_suppress-post-type' == $field['label_for']) : ?>
    <?php $post_types = get_post_types(); ?>
    <select style="min-width: 190px;" id="wpsi_settings[options][suppress-post-type]"
            name="wpsi_settings[options][suppress-post-type][]" size="4"
            multiple="multiple">
        <?php
        foreach ($post_types as $post_type_name) {
            ?>
            <option
                value="<?php echo esc_attr($post_type_name); ?>" <?php echo(in_array($post_type_name, (array)$settings['options']['suppress-post-type']) ? 'selected="selected"' : ''); ?>><?php echo esc_html(get_post_type_object($post_type_name)->labels->name); ?></option>
        <?php
        }
        ?>
    </select>
    <button id="clear-post-type" class="button-secondary"
            onclick="jQuery('#wpsi_settings\\[options\\]\\[suppress-post-type\\]')[0].selectedIndex = -1;return false;">
        Clear
    </button>
<?php
elseif ('wpsi_suppress-language' == $field['label_for'] && function_exists('qtrans_getSortedLanguages')) : ?>
    <?php $languages = qtrans_getSortedLanguages(); ?>
    <select style="min-width: 190px;" id="wpsi_settings[options][suppress-language]"
            name="wpsi_settings[options][suppress-language][]" size="4"
            multiple="multiple">
        <?php
        foreach ($languages as $language_name) {
            ?>
            <option
                value="<?php echo esc_attr($language_name); ?>" <?php echo(in_array($language_name, (array)$settings['options']['suppress-language']) ? 'selected="selected"' : ''); ?>><?php echo $q_config['language_name'][$language_name]; ?></option>
        <?php
        }
        ?>
    </select>
    <button id="clear-language" class="button-secondary"
            onclick="jQuery('#wpsi_settings\\[options\\]\\[suppress-language\\]')[0].selectedIndex = -1;return false;">
        Clear
    </button>
<?php
elseif ('wpsi_suppress-language' == $field['label_for']) : ?>
    <p>This option is only available with the plugin <a href="https://wordpress.org/plugins/qtranslate/">qTranslate</a>
        or <a href="https://wordpress.org/plugins/mqtranslate/">mqTranslate</a>.</p>
<?php
elseif ('wpsi_suppress-url' == $field['label_for']) : ?>
    <input type="text" name="wpsi_settings[options][suppress-url]"
           id="wpsi_settings[options][suppress-url]"
           value="<?php echo $settings['options']['suppress-url']; ?>">
<?php
elseif ('wpsi_suppress-referrer' == $field['label_for']) : ?>
    <input type="text" name="wpsi_settings[options][suppress-referrer]"
           id="wpsi_settings[options][suppress-referrer]"
           value="<?php echo $settings['options']['suppress-referrer']; ?>">
<?php
elseif ('wpsi_suppress-ipaddress' == $field['label_for']) : ?>
    <input type="text" name="wpsi_settings[options][suppress-ipaddress]"
           id="wpsi_settings[options][suppress-ipaddress]"
           value="<?php echo $settings['options']['suppress-ipaddress']; ?>">
<?php endif; ?>