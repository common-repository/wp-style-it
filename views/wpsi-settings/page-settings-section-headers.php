<?php if ('wpsi_section-blocks' == $section['id']) { ?>
    <p>Define here the CSS styling code you want to be applied.</p>
<?php } elseif ('wpsi_section-options' == $section['id']) { ?>
    <p>Set options influencing when the style will be applied.</p>
    <input type="hidden" name="wpsi_settings[options][suppress-on-posts]" value="0">
<?php } ?>
