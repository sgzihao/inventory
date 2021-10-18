<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


    <head>
    <?php echo $template['metadata']; ?>
</head>
<body>

    <div id="main">

        <?php echo $template['partials']['header']; ?>
        <!-- Columns -->
        <div id="cols" class="box">
            <!-- Aside (Left Column) -->
            <?php echo $template['partials']['menu']; ?>

            <!-- Content (Right Column) -->

            <?php echo $template['body']; ?>
        </div> <!-- /cols -->   
        <?php echo $template['partials']['footer']; ?>

    </div> <!-- /main -->

</body>
</html>