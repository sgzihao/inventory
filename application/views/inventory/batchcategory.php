<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.all.css'; ?>" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.base.css'; ?>" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/themes/ui-lightness/jquery.ui.all.css">

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
<script type="text/javascript">
    //
    function subForm(obj) {
        $("#cf option:selected").remove();
        $("#filterForm").submit();
    }
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
                    
                    var version = $("#categoryVersion").val();
                    if(version == '')
                        return false;
                    
                    //var cid = $("#calSoftwareList :selected").text();
                    var cid = $("select[name='calSoftwareList']").val() ;
                    if(cid < 1)
                        return false
                    
                    var invid = $("#inventoryId").val();
                    var postData = {cid:cid, version:version, invid:invid};
                    
                    $.post(
                    "<?= site_url('inventory/batchUpdateCalVersion'); ?>", 
                    postData,
                    function(data) {
                        if(data == 1) {
                            alert('ok');
                            $("input[name='version']").val(version) ;
                            subForm();
                        }
                        else {
                            alert(data);
                        }    
                    },'json');
                    $(this).dialog( "close" );
                    //window.location.href=window.location.href;
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                return ;
                //allFields.val( "" ).removeClass( "ui-state-error" );
            }
        });
        
        //update category version.
        $(".upcalversion").click(function(){
            var actionAll = $(this).attr('title');
            var invlist = "";
            //one or more need to update
            if(actionAll == "single") {
                var id = "inv_"+$(this).attr("inv");
                //alert(id);
                invlist += $(this).attr("inv");
                //alert(categoryID);
                $("#"+id).attr('checked',true);
                
            } else if (actionAll == "mutiple") {
                $(".checkBoxTabs").each(function(){
                    if($(this).attr('checked')){
                        invlist += $(this).attr("title")+",";
                    }
                });    
            }
            if(invlist == "") {
                alert("Please check someone.");
                return ;
            }
            //alert(invlist);
            $("#inventoryId").val(invlist);
            $( "#dialog-form" ).dialog( "open" );
        });
        
        //check all function by jquery each function
        //var invList = '';
        $("#tabs-checkall").click(function(){
            //
            $(".checkBoxTabs").each(function(index){
                $(this).attr('checked',true);
            });    
        });
        
        //uncheck all , function by jquery each function
        $("#tabs-uncheckAll").click(function(){
            //
            $(".checkBoxTabs").each(function(index){
                $(this).attr('checked',false);
            });    
        });
        
        //update category by jquery dialog function where inventory id from checked box
        
    });
</script>
<style>
    body { font-size: 62.5%; }
    label { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<div id="content">

    <h1><?php echo isset($locationName->location_name) ? $locationName->location_name : ''; ?> -- <?php echo $modelName; ?> -- <?php echo $title_info; ?></h1>
    
    <div id="dialog-form" title="Update Inventory Category">
        <form>
            <fieldset>
                <label for="name">Version</label>
                <input type="text" name="categoryVersion" id="categoryVersion" class="text ui-widget-content ui-corner-all" />
                <input type="hidden" name="inventoryId" id="inventoryId" />
            </fieldset>
        </form>
    </div>
    
    <div id="content_padded">

        <form action="<?php echo site_url("inventory/report"); ?>" method="post" name="reportForm">
            <?php
            foreach ($_REQUEST as $pkey => $pvalue) {
                echo "<input type='hidden' name='{$pkey}' value='{$pvalue}' id='{$pkey}' />";
            }
            ?>
        </form>

        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Search/Filter</a></li>
            </ul>

            <div id="tabs-1">
                <form id="filterForm" action="<?php echo site_url("inventory/batchupdatecategory/") ?>" method="post" >
                    <table cellspacing="2" cellpadding="3" border="0" width="100%" class="form">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">Location</td>
                                <td class="fieldarea">
                                    <?php echo $locationinfo; ?>
                                </td>
                                <td width="15%" class="fieldlabel">Inventory Model</td>
                                <td class="fieldarea">
                                    <?php echo $inventoryModelInfo; ?>
                                </td>
                            </tr>
							<tr>
								<td width="15%" class="fieldlabel">
									<?php
                                    echo "<select name='cf1' id='cf1'>";
                                    //all the custom field filter
									$i = 0;
                                    foreach ($modelRelCategory as $itemKey =>$itemOption) {
                                        if(isset ($_REQUEST['cf1'])) {
											if($_REQUEST['cf1'] == "cid_".$itemKey)
												$selected = "selected=''";	
											else {
												if($i = 0)
													$selected = "selected=''";	
												else
													$selected = "";	
											}
										} else {
											$selected = "";	
										}
										
										$i++;
                                        
										echo "<option value='cid_{$itemKey}' {$selected}>",$itemOption,"</option>";
                                    }
                                    echo '</select>';
                                    ?>
								</td>
                                <td class="fieldarea">
                                    <input type="text" value="<?php echo !empty($_REQUEST['cf1value'])?$_REQUEST['cf1value']:'';?>" size="30" id="cf1value" name="cf1value">
                                </td>
                                <td width="15%" class="fieldlabel">
                                     <?=$inventorySoftwareList; ?>
                                </td>
                                <td class="fieldarea">
                                    <input type="text" name="version" id="version" size="30" value="<?php echo isset($_REQUEST['version'])?$_REQUEST['version']:'';?>">
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
                    <td width="90%" align="left"><?= $totalNumber; ?> Records Found</td>
                </tr>
                <tr>
                    <td>
                        <input id="tabs-checkall" type="button" name="tabs-checkall" value="Check all" />
                        <input id="tabs-uncheckAll" type="button" name="tabs-uncheckAll" value="Uncheck all" />
                        <input id="up_all" type="button" name="up_all" value="Update Checked Item" title="mutiple" class="upcalversion" />
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!----How to display inventory information--->
        <table class="rounded-corner">
            <thead>
                <tr>
                    <th scope='col'></th>
                    <th scope='col'>Host Name</th>
					<th scope='col'>Application</th>
					<th scope='col'>Category</th>
					<th scope='col'>Online / Backend</th>
                    <th scope='col'>Software Name</th>
                    <th scope='col'>Version</th>
                    <th scope='col'>Primary SW Services (Current)</th>
                    <th scope='col'></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($inventoryData as $item) {
                ?>
                <tr>
                    <td><input id="inv_<?= $item->inventory_id; ?>" cal="<?=$item->category_id;?>" title="<?= $item->inventory_id; ?>" class="checkBoxTabs" type="checkbox" name="inv[]" value="inv_<?= $item->inventory_id; ?>"></td>
                    <td><?= $item->inventory_name; ?></td>
					<td><?= $item->cid_102; ?></td>
					<td><?= $item->cid_104; ?></td>
					<td><?= $item->cid_105; ?></td>
                    <td><?= $item->category_name; ?></td>
                    <td><?= $item->category_version; ?></td>
                    <td><?= $item->category; ?></td>
                    <td><input id="up_<?= $item->inventory_id; ?>" cal="<?=$item->category_id;?>" type="button" name="up_[]" value="update" inv="<?= $item->inventory_id; ?>" title="single" class="upcalversion" ></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td width="50%" align="left"><?= $totalNumber; ?> Records Found</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
