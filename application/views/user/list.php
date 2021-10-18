<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<script type="text/javascript">
    function removeAction(userid,username) {
        var r = confirm("Are you sure to delete "+username+"?");
        if(r != true)
            return;
        
        var postData = {
            userid:userid
        };
        $.post(
        "<?= site_url('user/deleteuser'); ?>", 
        postData,
        function(data) {
            if(data) {
                alert("ok.");
                window.location = "<?php echo current_url(); ?>";
            }
            else {
                alert("Please Try Again.");
                return false;
            }
        },'json');
    }
</script>
<div id="content">

    <h1><?= $title_info; ?></h1>

    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li class="tab tabselected" id="tab0">
                    <a href="javascript:;">Search/Filter</a>
                </li>
            </ul>
        </div>

        <div class="tabbox" id="tab0box" style="display: block;">

            <div id="tab_content">

                <form action="" method="post">

                    <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">User Name</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="username">
                                </td>
                                <td class="fieldlabel">User Email</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="useremail">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <img width="1" height="10" src="images/spacer.gif"><br>
                    <div align="center"><input type="submit" class="button" value="Search/Filter"></div>

                </form>

            </div>
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
                <th>UserName</th>
                <th>Name</th>
                <th>User Email</th>
                <th>Location List</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Date Modified</th>
                <th></th>
                <th></th>
        </thead>
        <tbody>
            <tr>
                <?php
                //echo $categorylistinfo;
                ?>   
            </tr>
            <?php
            $i = 1;
            foreach ($userData as $item) {
                $i++;
                ?>
                <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                    <td><?= $item->username; ?></td>
                    <td><?= $item->first_name . ' - ' . $item->last_name; ?></td>
                    <td><?= $item->email; ?></td>
                    <td>
                        <?php
                        $location = unserialize($item->location_id);
                        foreach ($locationArr as $ll) {
                            if (in_array($ll->location_id, $location))
                                echo $ll->location_name, ',';
                        }
                        ?>
                    </td>
                    <td><?= $item->status; ?></td>
                    <td><?= $item->creation_date . ' - ' . $item->created_by; ?></td>
                    <td><?= $item->modified_date . ' - ' . $item->modified_by; ?></td>
                    <td><a href="<?php echo site_url("user/edituser/userid"); ?>/<?= $item->user_id; ?>"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                    <td><a href="javascript:removeAction(<?= $item->user_id; ?>,'<?= $item->username; ?>');"><img border="0" width="16" height="16" alt="Delete" src="<?= base_url(); ?>assets/order/images/delete.gif"></a></td>
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