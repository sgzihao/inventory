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
	//delete inventory by invemtoryId and modelId
    function delinventory(inventoryid, modelid,obj) {
        
        var r = confirm("Are you sure to delete this inventory?");
        if(r != true)
            return;
        
        var postData = {
            id:inventoryid,
            modelid:modelid
        };
		
        $.post(
        "<?= site_url('inventory/delitem'); ?>", 
        postData,
        function(data) {
            if(data) {
                alert("ok.");
				$("#"+inventoryid).children("td").removeClass('fieldlabel');
				$("#"+inventoryid).css('background-color', 'red');
                //window.location = "<?php echo current_url(); ?>";
            }
            else {
                alert("Please Try Again.");
                return false;
            }
        },'json');
    }
    //
    function subForm(obj) {
        //$("#cf option:selected").remove();
        $("#filterForm").submit();
    }
	
	//Ajax post to clone Inventory Item
	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-form" ).dialog({
            autoOpen: false,
            height: 200,
            width: 350,
            modal: true,
            buttons: {
                "Save change": function() {
					var invid = $("#lastInvId").val();
					var lastInvName = $("#lastInvName").val();
					var invModel = $("#lastModelId").val();
					
					//get the name to be cloned.
					var invName = $("#cloneInvName").val();
					
					if(invName == null || invName == "") {
						alert("Name cannot be empty!");
						return ;
					}
					if(lastInvName == invName) {
						alert("Please enter another inventory name.");
						return ;
					}
					var postData = {
						inventoryId:invid,
						inventoryName:invName
					}
					//ajax submit.
					$.post(
						"<?= site_url('inventory/cloneNewInventory'); ?>", 
						postData,
						function(data) {
							if(data > 0) {
								alert("Clone Successfuly.");
								window.location="<?php echo site_url('inventory/edititem/id/'); ?>/"+data+"/modelid/"+invModel;
							} else {
								alert("Clone Failure.");
								return;
							}
						},'json');
					//
                    $( this ).dialog( "close" );
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
		
		$( ".cloneInventoryButton" )
			.button()
			.click(function() {
				//$("#inventoryId").val(invlist);
				var invid = $(this).attr("invid");
				$("#lastInvId").val(invid);
				
				var invname = $(this).attr("invname");
				$("#lastInvName").val(invname);
				
				var modelid = $(this).attr("modelid");
				$("#lastModelId").val(modelid);
				
				$( "#dialog-form" ).dialog( "open" );
			});
	});
	
	//
	function restoreInv(invId) {
		var r = confirm("Are you sure to Restore it?");
        if(r != true)
            return;
        
        var postData = {id:invId};
		
		$.post(
        "<?= site_url('inventory/restore'); ?>", 
        postData,
        function(data) {
            if(data) {
                alert("ok.");
				var rowid = "restore-"+invId;
				$("#"+rowid).parent().parent().remove();
				return ;
            }
            else {
                alert("Please Try Again.");
                return false;
            }
        },'json');
	}
	function removeInv(invID, modelid) {
		var r = confirm("Are you sure to remove this item from Database?");
		if (r != true)
			return;
		var postData = {id:invID,modelid:modelid};
		$.post(
        		"<?= site_url('inventory/removeItemfromDB'); ?>",
        		postData,
        		function(data) {
            		if(data) {
                		alert("ok");
				window.location = "<?php echo current_url(); ?>"
                                return ;
            		}
            		else {
                		alert("Please Try Again.");
                		return false;
            		}
        	},'json');
	}
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
	.rowRedHighlight {bgcolor:#FF0000;}
</style>
<div id="content">

    <h1><?php echo isset($locationName->location_name) ? $locationName->location_name : ''; ?> -- <?php echo $modelName; ?> -- <?php echo $title_info; ?></h1>

	<div id="dialog-form" title="Clone New Inventory">
        <form>
            <fieldset>
                <label for="cloneInvName">The New Inventory Name</label>
                <input type="text" name="cloneInvName" id="cloneInvName" class="text ui-widget-content ui-corner-all" />
				<input type="hidden" id="lastInvName" />
				<input type="hidden" id="lastInvId" />
				<input type="hidden" id="lastModelId" />
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
                <form id="filterForm" action="<?php echo site_url("inventory/index/") ?>" method="post">
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
                                    echo "<select name='cf' id='cf'>";
                                    //all the custom field filter
									$i = 0;
                                    foreach ($modelRelCategory as $itemKey =>$itemOption) {
                                        if(isset ($_REQUEST['cf'])) {
											if($_REQUEST['cf'] == "cid_".$itemKey)
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
                                	<input type="text" name="cfv" size="20" value="<?php echo isset($_REQUEST['cfv'])?$_REQUEST['cfv']:'';?>" />
					<?php echo $sortOrderList;?>
                                </td>
				<td width="15%" class="fieldlabel">Inventory Status</td>
				<td class="fieldarea">
                                    <?php echo $inventoryStatusSelectList;?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div align="center">
                        <input type="submit" class="button" value="Search/Filter">
                        <input type="button" onclick="document.reportForm.submit();" value="Report Download" />
                    </div>
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

        <!----How to display inventory information--->
        <table class="form" style="max-width:100%; overflow-x: scroll; white-space: normal;">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <?php
                    $ci = 0;

                    foreach ($modelRelCategory as $mitem) {
					
                        if ($mitem == "Invoice Amount") {
                            if (array_key_exists($this->locationId, $this->locationCurrency))
                                echo "<th>{$mitem} ({$this->locationCurrency[$this->locationId]})</th>";
                            else
                                echo "<th>{$mitem}</th>";
                        } elseif($mitem == "Configuration") {
							echo "<th width='120px'>{$mitem}</th>";
						}
                        else
                            echo "<th>{$mitem}</th>";
                        $ci++;
                    }
                    ?>
                    <th>Created By</th>
                    <th>Modified By</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //
                foreach ($inventoryData as $item) {
					$rowBgcolor = "";
					if($item->status == "deleted") {
						//style='bgcolor;#FF0000'
						$rowBgcolor = "class='rowRedHighlight'";
					}
                ?>
                    <tr <?php echo $rowBgcolor;?> id="<?= $item->inventory_id; ?>">
					
					<?php
						if($item->status == "active") {
					?>
					<td class="fieldlabel">
						<input class="cloneInventoryButton" type="button" invid="<?= $item->inventory_id; ?>" modelid="<?=$item->inventory_model_id;?>" invname ="<?=$item->inventory_name;?>" id="clone-<?= $item->inventory_id; ?>" name="clone-<?= $item->inventory_id; ?>" value="Clone" />
					</td class="fieldlabel">
                    <td class="fieldlabel"><a title='Edit Item' target="_blank"  href="<?= site_url("inventory/edititem/id/{$item->inventory_id}/modelid/{$item->inventory_model_id}"); ?>"><img border="0" width="16" height="16" alt="Edit" src="<?= base_url(); ?>assets/order/images/edit.gif"></a></td>
                    <td class="fieldlabel"><a title='Remove from active list' href="javascript:delinventory(<?php echo $item->inventory_id; ?>,<?php echo $item->inventory_model_id; ?>);"><img border="0" width="16" height="16" alt="Remove from active list" src="<?= base_url(); ?>assets/order/images/delete.gif"></a></td>
					<?php
					} else {
					?>
					
					<td class="fieldlabel">
						<input onclick="javascript:restoreInv(<?= $item->inventory_id; ?>);" class="restoreInv" type="button" id="restore-<?= $item->inventory_id; ?>" name="restore-<?= $item->inventory_id; ?>" value="Restore" />
					</td class="fieldlabel">
					<td class="fieldlabel"><input onclick="javascript:removeInv(<?= $item->inventory_id; ?>, <?php echo $item->inventory_model_id; ?>);" class="removeInv" type="button" id="removeitem<?= $item->inventory_id; ?>" name="removeitem<?= $item->inventory_id; ?>" type="button" value="Remove" /></td>
					<td class="fieldlabel"></td>
					<?php
					}
					?>
                    <?php
                        foreach ($modelRelCategory as $mkey => $mitem) {
							//echo "<td>{$item->$tmpKey}</td>";
                            if ($mkey == 113) {
                                echo "<td class='fieldlabel'>", $item->primary_services, "</td>";
							} 
							else {
								$tmpKey = "cid_" . $mkey;
								echo "<td class='fieldlabel'>{$item->$tmpKey}</td>";
                            }
                        }
                        ?>
                        <td class='fieldlabel'><?php echo $item->created_by . ' ' . $item->creation_date ?></td>
                        <td class='fieldlabel'><?php echo $item->modified_by . ' ' . $item->modified_date ?></td>
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
                    <td width="50%" align="right"><?= $paginationlinks; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
