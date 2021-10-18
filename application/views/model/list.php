<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<div id="content">

    <h1>Inventory Model List</h1>
    <div id="content_padded">

        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Search/Filter</a></li>
            </ul>
        </div>

        <div id="tabs-1">
            <form action="" method="post">
                <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                    <tbody>
                        <tr>
                            <td width="15%" class="fieldlabel">Inventory Model Name</td>
                            <td class="fieldarea">
                                <input type="text" value="" size="30" name="filterdescription">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div align="center"><input type="submit" class="button" value="Search/Filter"></div>
            </form>
        </div>

    </div>

    <table width="100%" cellspacing="0" cellpadding="3" border="0">
        <tbody>
            <tr>
                <td width="50%" align="left"><?= $totalNumber; ?> Records Found</td>
                <td width="50%" align="right"><?= $paginationlinks; ?></td>
            </tr>
        </tbody>
    </table>

    <table class="rounded-corner">
        <thead>
            <tr>
                <th>ID</th>
                <th>Model Code</th>
                <th>Model Name</th>
                <th>Manufacturer</th>
                <th width="35%">Inventory Desc</th>
                <th>Date Created</th>
                <th>Date Modified</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($model_data as $item) {
                $i++;
                ?>
                <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                    <td><?= $item->inventory_model_id; ?></td>
                    <td><?= $item->inventory_model_code; ?></td>
                    <td><?= $item->inventory_model_name; ?></td>
                    <td><?= $item->brand_model_id; ?></td>
                    <td><?= $item->long_description; ?></td>
                    <td><?= $item->creation_date . ' - ' . $item->created_by; ?></td>
                    <td><?= $item->modified_date . ' - ' . $item->modified_by; ?></td>
                    <td><a href="<?= base_url(); ?>model/editmodel/id/<?= $item->inventory_model_id; ?>"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                    <td><a href="<?= base_url(); ?>model/delmodel/id/<?= $item->inventory_model_id; ?>"><img border="0" width="16" height="16" alt="Delete" src="<?= base_url(); ?>assets/order/images/delete.gif"></a></td>
                </tr>
                <?
            }
            ?>

        </tbody>
    </table>

    <table width="100%" cellspacing="0" cellpadding="3" border="0">
        <tbody>
            <tr>
                <td width="50%" align="left"><?= $totalNumber; ?> Records Found</td>
                <td width="50%" align="right"><?= $paginationlinks; ?></td>
            </tr>
        </tbody>
    </table>
    
</div>
