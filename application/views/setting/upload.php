<div id="content">
    <h1><?= $title_info; ?></h1>

    <?php echo validation_errors(); ?>
    
    <div id="content_padded">


        <?php echo $error; ?>

        <?php echo form_open_multipart('setting/do_upload'); ?>

        <input type="file" name="userfile" size="20" />

        <br /><br />

        <input type="submit" value="upload" />

        </form>

    </div>
</div>