<div id="content">
    <h3>Your file was successfully uploaded!</h3>


    <div id="content_padded">
        
        <ul>
            <?php foreach ($upload_data as $item => $value): ?>
                <li><?php echo $item; ?>: <?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>

        <p><?php echo anchor('setting/upload', 'Upload Another File!'); ?></p>


    </div>
</div>