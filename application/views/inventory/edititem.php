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
    var myArr=new Array(); // regular array (add an optional integer
<?php
//var_dump($inventoryInfoData);
foreach ($inventoryInfoData as $k => $v) {
    if (preg_match("/^cid_/", $k)) {
        ?>
            myArr['<?php echo $k; ?>'] = "<?php echo $v; ?>";
        <?php
    }
}
?>
    var versionlistArr;
    function multipleselect(name, target, flag) {
        //hardware
        var target = $("#"+target);
        $("#"+name).multiselect({
            noneSelectedText: 'Select Information',
            open:  function(){
                $(".autoClass input").each(function(){
                    //alert($(this).val());
                    var tmpNameId = $(this).attr('name');
                    //alert(tmpNameId);
                    myArr[tmpNameId] = $(this).val();
                });
            },
            close: function(){
                autoInputValue();
            }
        }).multiselectfilter().bind("multiselectclick multiselectcheckall multiselectuncheckall", function( event, ui ){
            var checkedValues = $.map($(this).multiselect("getChecked"), function( input ){
                //alert(input.title);
                //<tr><td width="15%" class="fieldlabel">Inventory Location</td><td class="fieldarea"></td></tr>
                
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
                return returnString;
                //return input.value + ":" + input.title;
            });
            
            // update the target based on option selected.
            target.html(
            checkedValues.length
                ? checkedValues.join('')
            : 'Please select category information.'
        );
        }).triggerHandler("multiselectclick"); // trigger above logic when page first loads
    }

    //ajax to get the software version list.
    function getVersionList(obj) {
        var str = $(obj).attr('name');
        var substr = str.split('_');
        //alert(substr[1]);
        $.ajax({
            url: 	"<?= site_url('inventory/getCategoryVersionList'); ?>/id/"+substr[1],
            type: 	"post",
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
    function autoInputValue () {
        $(".autoClass input").each(function(){
            var inventoryName = $(this).attr('name');
            //alert(inventoryName);
            if(myArr[inventoryName] != undefined) {
                //alert(tmpValue);
                $(this).val(myArr[inventoryName]);
            }
        });
    }
    function autoPushArray(obj) {
        ;
    }
    //
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
        multipleselect("inventorySoftwareList", "target2",true);
        //$("#inventorySoftwareList").multiselect().multiselectfilter();
        //auto given value from inventory data information
        autoInputValue();
        
        //edit inventory
        $("#editInventory").click(function(){
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
                inventoryid:<?php echo $inventoryid;?>,
                locationid:locationid,
                invmodel:invmodel,
                hard:hardwareArr,
                soft:softwareArr
            }
            $.post(
            "<?= site_url('inventory/editNewItem'); ?>", 
            postData,
            function(data) {
                if(data > 0) {
                    messagePopAlert("Inventory updated.",1000);
                    window.location="<?php echo site_url('inventory/edititem/id/'); ?>/<?php echo $inventoryid;?>"+'/modelid/'+invmodel;
                }
                else if(data == 0) {
                    messagePopAlert("Name already exites in system.",1000);
                    return false;
                }
                else {
                    messagePopAlert("There is something wrong with the database, please try again.",1000);
                    return false;
                }
            },'json');
        })
    });
    function cloneInv(obj) {
       $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
 
        setTimeout($.unblockUI, 2000); 
    }
    function subForm(obj) {
        //$("#cf option:selected").remove();
        $("#filterForm").submit();
    }
</script>
<div id="content">
    <h1><?= $title_info; ?></h1>
    <div id="content_padded">
        <form action="" method="post" id="filterForm">
        <table cellspacing="2" cellpadding="3" border="0" width="100%" class="form">
            <tbody>
                <tr>
                    <td width="15%" class="fieldlabel">Inventory Location</td>
                    <td>
                        <?= $locationinfo; ?>
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Inventory Model</td>
                    <td class="fieldarea">
                        <?= $inventoryModelInfo; ?>
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Created By</td>
                    <td class="fieldarea">
                        <input type="text" id="created_by" size="40" name="created_by" value="<?php echo $invInfo->creation_date ." ---- " .$invInfo->created_by ;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Modified_by</td>
                    <td class="fieldarea">
                        <input type="text" id="modified_by" size="40" name="modified_by" value="<?php echo $invInfo->modified_date ." ---- " .$invInfo->modified_by ;?>" />
                    </td>
                </tr>
                
                <tr style="display:none;">
                    <td width="15%" class="fieldlabel"></td>
                    <td class="fieldarea">
                        <input type="button" onclick="javascript:cloneInv(this);" id="clone-<?=$inventoryid;?>" name="clone-<?=$inventoryid;?>" value="Clone New One" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h1>Inventory Info</h1>

    <table class="form" cellspacing="2" cellpadding="3" border="0" width="100%">
        <tbody>
            <tr>
                <td width="50%"><p><b>Hardware/information</b>   <?= $inventoryHardwareList; ?></p></td>
                <td class="fieldarea"><p><b>Software/information</b><?= $inventorySoftwareList; ?></p></td>
            </tr>
            <tr>
                <td width="50%">
                    <table class="form" cellspacing="2" cellpadding="3" border="0" width="100%">
                        <tbody id="target">
                            <tr>
                                <td width="30%" style="align:top;">
                                </td>
                                <td class="fieldarea"><div id="hardwareversionlist" style="display:none;"></div></td>
                            </tr>
                        </tbody>
                    </table>    
                </td>
                <td class="fieldarea">

                    <table class="form" cellspacing="2" cellpadding="3" border="0" width="100%">
                        <tbody id="target2">
                            <tr>
                                <td width="30%" class="fieldlabel"></td>
                                <td class="fieldarea">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    
    <p align="center">
        <input type="button" class="button" value="Save" id="editInventory" />
        <input type="button" class="button" value="Return back" id="returnButton" />	
    </p>
</form>
</div>
