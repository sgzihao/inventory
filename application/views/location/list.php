<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<script type="text/javascript">
    function removeAction(id,name) {
        var r = confirm("Are you sure to delete "+name+"?");
        if(r != true)
            return;
        
        var postData = {
            id:id
        };
        $.post(
        "<?= site_url('location/deletelocation'); ?>", 
        postData,
        function(data) {
            if(data == 0) {
                alert("ok.");
                window.location = "<?php echo current_url(); ?>";
            }
            else if(data > 0) {
                alert("There are "+data+" inventory in this location! Please remove them firstly.");
                return;
            }
            else {
                alert("Please Try Again.");
                return;
            }
        },'json');
    }
</script>
<div id="content">

    <h1>Location List</h1>
    <?php echo validation_errors(); ?>
    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li class="tab tabselected" id="tab0">
                    <a href="javascript:;">Search/Filter</a>
                </li>
            </ul>
        </div>

        <div class="tabbox" id="tab0box">

            <div id="tab_content">

                <form action="" method="post">

                    <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">location Name</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="location_name">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br>
                    <div align="center"><input type="submit" class="button" value="Search/Filter"></div>

                </form>

            </div>
        </div>
        <br>

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
                    <th>Location Name</th>
                    <th>Desc</th>
                    <th>Inventory Total Number</th>
                    <th>Date Created</th>
                    <th>Date Modified</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($officeData as $item) {
                    $i++;
                    ?>
                    <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                        <td><?= $item->location_name; ?></td>
                        <td><?= $item->long_description; ?></td>
                        <td></td>
                        <td><?= $item->creation_date . ' ' . $item->created_by; ?></td>
                        <td><?= $item->modified_date . ' ' . $item->modified_by; ?></td>
                        <td><a href="<?php echo site_url("location/edit/id"); ?>/<?= $item->location_id; ?>"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                        <td><a href="javascript:removeAction(<?= $item->location_id; ?>,'<?= $item->location_name; ?>');"><img border="0" width="16" height="16" alt="Delete" src="<?= base_url(); ?>assets/order/images/delete.gif"></a></td>
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

</div>