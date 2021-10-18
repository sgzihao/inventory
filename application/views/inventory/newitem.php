<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.all.css';?>" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.base.css';?>" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.multiselect.css';?>" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.multiselect.filter.css';?>" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/themes/ui-lightness/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/demos.css">
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery-ui.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.multiselect.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.multiselect.filter.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/plugin/jquery.blockUI.js'; ?>"></script>
<script type="text/javascript">
    var versionlistArr;
    function multipleselect(name, target, flag) {
        //hardware
        var target = $("#"+target);
        $("#"+name).multiselect({
            noneSelectedText: 'Select Software Information'
        }).multiselectfilter().bind("multiselectclick multiselectcheckall multiselectuncheckall", function( event, ui ){
            var checkedValues = $.map($(this).multiselect("getChecked"), function( input ){
                //don't display primary sw services information.
                if(input.value == '113') {
                    //alert(input.value);
                    return '';
                }
                var returnString =  "<tr><td width='30%' class='fieldlabel'>"+input.title+"</td>";
                returnString += "<td class='fieldarea autoClass'>";
                if(flag)
                    var onfocusstring = "onfocus='javascript:getVersionList(this);' onkeydown='autocompleteText(this);return;'";
                else
                    var onfocusstring = '';
                returnString += "<input "+onfocusstring+" type='text' name='cid_"+input.value+"' size='40' id='cid_"+input.value+"' title='"+input.title+"' />";
                returnString += "</td></tr>";
	
                if(flag) {
                    //alert(input.title);
                    //$("#alertinfopri").html('');
                    //$("#alertinfopri").append(input.title);
			
                }
                return returnString;
            });
            target.html(checkedValues.length? checkedValues.join(''): 'Please select category information.');
        }).triggerHandler("multiselectclick"); // trigger above logic when page first loads
    }

    //ajax to get the software version list.
    function getVersionList(obj) {
        var str = $(obj).attr('name');
        var substr = str.split('_');
        //alert(substr[1]);
        $.ajax({
            url: 		"<?= site_url('inventory/getCategoryVersionList'); ?>/id/"+substr[1],
            type: 		"post",
            dataType:	"json",
            success:	function(data) {
                versionlistArr = data;
            }
        });
    }

    //autocomplete effect result --2011-11-23 by szihao@example.com
    function autocompleteText (obj) {
        var availableTags = versionlistArr;
        var str = $(obj).attr('name');
        var substr = str.split('_');
		
        //alert(substr[1]);
        // ajax call back function to get the version list information from the database.
        //http://inventory.example.com/inventory/getCategoryVersionList
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        $( obj ).bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
                $( this ).data( "autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: function( request, response ) {
                // delegate back to autocomplete, but extract the last term
                response( $.ui.autocomplete.filter(
                availableTags, extractLast( request.term ) ) );
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }
        });
    }
    
    //
    function messagePopAlert(mssageInfo,timeOut) {
        $.blockUI({ 
            message: "<h1>"+mssageInfo+"</h1>", 
            timeout: timeOut 
         });
    }
    //
    $(document).ready(function(){
        //hardware
        multipleselect("inventoryHardwareList", "target", false);
        //software
        multipleselect("inventorySoftwareList", "target2", true);
        //$("#inventorySoftwareList").multiselect().multiselectfilter();
        
        $("#addnewitem").click(function(){
            //alert('');
            var locationid = $("select[name=locationid] option:selected").val();
            
            if(!parseInt(locationid)) {
                messagePopAlert("Please select location.",1000);
                $("select[name=locationid]").focus();
                return false;
            }
            var invName = $("#cid_101").val();
            if(invName == '') {
                messagePopAlert("Please enter Host name.",1000);
                $("#cid_101").focus();
                return false;
            }
            //alert(locationid);
            var invmodel = $("select[name=inventoryModelList] option:selected").val();
            //alert(invmodel);
            var hardwareArr = new Array();
            $("#target input").each(function(){
                hardwareArr[hardwareArr.length] = [$(this).attr('id'),$(this).val()];
            });
            
            //var hardwarestring = serialize(hardwareArr);
            //alert(hardwarestring);
            var softwareArr = new Array();
            $("#target2 input").each(function(){
                var parentobj = $(this).parent().parent();
                var categoryName = $(parentobj).children('.fieldlabel').html();
                //alert($(this).val());
                softwareArr[softwareArr.length] = [$(this).attr('id'),$(this).val()];
            });
            //return false;
            var postData = {
                locationid:locationid,
                invmodel:invmodel,
                hard:hardwareArr,
                soft:softwareArr
            }
            $.post(
            "<?= site_url('inventory/addNewItem'); ?>", 
            postData,
            function(data) {
                if(data > 0) {
                    messagePopAlert("New Inventory Added.",1000);
                    window.location="<?php echo site_url('inventory/edititem/id/'); ?>/"+data+'/modelid/'+invmodel;
                }
                else if(data == 0) {
                    messagePopAlert("Inventory Name already exites in system.",1000);
                    return false;
                }
                else {
                    messagePopAlert("There is something wrong with the database, please try again.",1000);
                    return false;
                }
            },'json');
        })
    });
    function subForm(obj) {
        //$("#cf option:selected").remove();
        $("#filterForm").submit();
    }
</script>
<div id="content">
    <h1><?= $title_info; ?></h1>
    <form action="" method="post" id="filterForm">
        <div id="content_padded">
            <table cellspacing="2" cellpadding="3" border="0" width="100%" class="form">
                <tbody>
                    <tr>
                        <td width="15%" class="fieldlabel">Inventory Location</td>
                        <td>
                            <?= $locationinfo; ?>
                            <span id="locationmsg"></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" class="fieldlabel">Inventory Model</td>
                        <td class="fieldarea">
                            <?= $inventoryModelInfo; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h1>Inventory Info</h1>

        <span id="alertinfopri" align='right'></span>
        <br/>

        <table class="form" cellspacing="2" cellpadding="3" border="0" width="100%">
            <tbody>
                <tr>
                    <td width="50%"><p><b>Hardware/information</b>   <?= $inventoryHardwareList; ?></p></td>
                    <td class="fieldarea"><p><b>Software/information</b>   <?= $inventorySoftwareList; ?></p></td>
                </tr>
                <tr>
                    <td width="50%">
                        <table class="form" cellspacing="2" cellpadding="3" border="0" width="100%">
                            <tbody id="target">
                                <tr>
                                    <td width="30%" style="valign:top;">
                                    </td>
                                    <td class="fieldarea">
                                        <div id="hardwareversionlist" style="display:none;"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>    
                    </td>
                    <td>
                        <table class="form" cellspacing="2" cellpadding="3" border="0" width="100%">
                            <tbody id="target2">
                                <tr>
                                    <td width="30%" style="valign:top;"></td>
                                    <td class="fieldarea">
                                        <div id="softwareversionlist" style="display:none;"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <p align="center"><input type="button" name="addnewitem" id="addnewitem" class="button" value="Submit"></p>
    </form>
</div>
