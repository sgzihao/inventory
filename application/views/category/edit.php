<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.all.css'; ?>" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.base.css'; ?>" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/themes/ui-lightness/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/demos.css">


<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery-ui.min.js'; ?>"></script>

<script src="<?php echo base_url(); ?>assets/jqueryui/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.mouse.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.button.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.draggable.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.position.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.resizable.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.ui.dialog.js"></script>
<script src="<?php echo base_url(); ?>assets/jqueryui/ui/jquery.effects.core.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/demos.css" />

<style>
    body { font-size: 62.5%; }
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script>
    function editOption(name, id) {
        $( "#dialog-form" ).dialog( "open" );
        $("#editOptionName").val(name);
        $("#editOptionId").val(id);
    };
    $(function() {
        // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
        $( "#dialog:ui-dialog" ).dialog( "destroy" );
		
        $( "#dialog-form" ).dialog({
            autoOpen: false,
            height: 200,
            width: 350,
            modal: true,
            buttons: {
                "Save change": function() {
                    //allFields.removeClass( "ui-state-error" );
                    var option = $("#editOptionName").val();
                    if(option == '')
                        return false;
                    var id = $("#editOptionId").val();
                    var postData = {id:id, option:option};
                    
                    $.post(
                    "<?= site_url('category/editoption'); ?>", 
                    postData,
                    function(data) {
                        return;
                    },'json');
                    
                    $( this ).dialog( "close" );              
                    //window.location.href=window.location.href;
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                window.location.reload(true);
                //allFields.val( "" ).removeClass( "ui-state-error" );
            }
        });
    });
    //
    function delOption(obj,cid, option) {
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
            //
            if(data > 0) {
                alert("There are "+data+" item using this option.");
                return ;
            } 
            else if(data == 0) {
                alert("ok.");
                
                window.location.href="<?= base_url(); ?>"+"category/editcategory/id/<?= $categoryID; ?>";
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
    <h1><?= $title_info; ?></h1>
    <?php echo validation_errors(); ?>
    <?php $this->message->display(); ?>


    <div id="dialog-form" title="Edit Category Option">
        <form>
            <fieldset>
                <label for="name">Option Name</label>
                <input type="text" name="editOptionName" id="editOptionName" class="text ui-widget-content ui-corner-all" />
                <input type="hidden" name="editOptionId" id="editOptionId"  />
            </fieldset>
        </form>
    </div>


    <div id="content_padded">

        <?php echo form_open('category/editcategory/id/' . $categoryID); ?>
        <input type="hidden" name="categoryid" value="<?php echo $categoryID; ?>" />
        <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
            <tbody>
                <tr>
                    <td class="fieldlabel">Category Name</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="categoryname" class="input-text" value="<?php echo $categoryInfo->category_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Category</td>
                    <td class="fieldarea">
                        <?php echo $topcategorylist; ?>
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel"></td>
                    <td class="fieldarea">
                        <input type="submit" class="button" value="Save">
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

        <br/>

        <?php
        if (isset($parentID) and $parentID > 1) {
            ?>
            <h1>Category Option list</h1>
            <div id="addnewoption">
                <p>
                    <img border="0" align="absmiddle" src="<?php echo site_url(); ?>/assets/admin/images/add.png" />
                    <a href="javascript:void(0);return false;">Add New Option</a>
                </p>
                <?php echo form_open('category/newoption/id/' . $categoryID); ?>
                <input type="hidden" name="categoryid" value="<?php echo $categoryID; ?>" />
                <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">New Option</td>
                            <td class="fieldarea">
                                <input type="text" size="40" name="categoryoption" class="input-text" value="" />
                            </td>
                            <td class="fieldarea">
                                <input type="submit" class="button" value="Save">
                            </td>
                        </tr>
                    </tbody>
                </table>
                </form>
            </div>

            <br/>

            <table class="rounded-corner">
                <thead>
                    <tr>
                        <th>Option Name</th>
                        <th>Date Created</th>
                        <th>Date Modified</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($categoryVersionInfo as $item) {
                        $i++;
                        ?>
                        <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                            <td><?= $item->category_field_value; ?></td>
                            <td><?= $item->creation_date; ?></td>
                            <td><?= $item->modified_date; ?></td>
                            <td><a title='edit' href="javascript:editOption('<?= $item->category_field_value; ?>','<?= $item->category_field_value_id; ?>');"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                            <td><a title='delete' href="javascript:delOption(this,'<?= $categoryID; ?>','<?= $item->category_field_value; ?>')"><img border="0" width="16" height="16" alt="Delete" src="<?= base_url(); ?>assets/order/images/delete.gif"></a></td> 
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</div>
