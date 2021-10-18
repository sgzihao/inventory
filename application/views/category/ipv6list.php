<div id="content">

    <h1><?php echo $title_info; ?></h1>

    <div id="content_padded">

        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td width="50%" align="left"></td>
                    <td width="50%" align="right"></td>
                </tr>
            </tbody>
        </table>

        <div class="tablebg">
            <table style="width: 100%" class="datatable">
                <tbody>
                    <tr>
                        <th>Category Name</th>
                        <th>IPV6 Min Version</th>
                        <th>Parent Category</th>
                        <th>Used</th>
                        <th></th>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($ipv6Data as $item) {
                        $i++;
                        ?>
                        <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                            <td><?= $item->category_name; ?></td>
                            <td><?= $item->ipv6_min_version; ?></td>
                            <td><?= $item->parent_category; ?></td>
                            <td><?= ($item->flag == 'no') ? 'No More Using' : ''; ?></td>
                            <td><a href="<?= base_url(); ?>/category/editipv6/id/<?php echo $item->category_id;?>"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                        </tr>
                        <?
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>