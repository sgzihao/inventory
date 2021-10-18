<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="icon" href="<?php echo base_url() . '/assets/EXICON.GIF';?>" type="image/x-icon">
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/admin/css/style.css';?>" />
        <?php echo $template['metadata']; ?>
    </head>
    <body>

        <?php echo $template['partials']['header2']; ?>

        <div id="content_container">
            <div id="">

                <?php echo $template['body']; ?>

            </div>
            <div class="clear"></div>

        </div>
        <?php echo $template['partials']['footer']; ?>
    </body>
</html>