<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<script>
    function delCategory(obj,cid, option) {
        var r = confirm("Are you sure to delete this option?");
        if(r != true)
            return;
        
        var postData = {
            id:cid,
            option:option
        };
        $.post(
        "<?= site_url('category/delOption'); ?>", 
        postData,
        function(data) {
            
            if(data > 0) {
                alert("There are "+data+" item using this option.");
                return ;
            } 
            else if(data == 0) {
                alert("ok.");
                //remove this record
                //$(obj).parent().remove();
                return ;
            }
            else if(data == '-1') {
                alert("Please Try Again.");
                return false;
            }
            
        },'json');
    }
</script>

<div id="content">
    <h1>Category List</h1>

    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li class="tab" id="tab0">
                    <a href="javascript:;">Search/Filter</a>
                </li>
            </ul>
        </div>

        <div class="tabbox" id="tab0box">

            <div id="tab_content">

                <form action="<?php echo site_url("category/index"); ?>" method="post">

                    <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">Parent Category</td>
                                <td class="fieldarea">
                                    <?php echo $parentcategorylist; ?>
                                </td>
                                <td width="15%" class="fieldlabel">Category Name</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="category_name" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <img width="1" height="10" src="images/spacer.gif"><br>
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
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Parent Category</th>
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
                foreach ($category_data as $item) {
                    $i++;
                    ?>
                    <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                        <td><?= $item->category_id; ?></td>
                        <td><?= $item->category_name; ?></td>
                        <td>
                            <?php
                            echo isset($parenetcategory[$item->category_parent_id]) ? $parenetcategory[$item->category_parent_id]['category_name'] : '';
                            ?></td>
                        <td><?= $item->creation_date . ' - ' . $item->created_by; ?></td>
                        <td><?= $item->modified_date . ' - ' . $item->modified_by; ?></td>
                        <!---<td><a href="javascript:void(0);" onclick="createNewCategory(this);return;"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>!--->
                        <td><a href="<?= base_url(); ?>category/editcategory/id/<?= $item->category_id; ?>"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                        <td><a href=""><img border="0" width="16" height="16" alt="Delete" src="<?= base_url(); ?>assets/order/images/delete.gif"></a></td>
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