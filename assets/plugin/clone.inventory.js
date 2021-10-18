//
    function messagePopAlert(mssageInfo,timeOut) {
        $.blockUI({ 
            message: "<h1>"+mssageInfo+"</h1>", 
            timeout: timeOut 
         });
    }
    function cloneInv(invId,invModel) {
        var name=prompt("Please enter Inventory name","");
        if(name == null || name == "") {
            messagePopAlert("Please enter Inventory name.",100000);
            return ;
        }
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
        //return false;
        var postData = {
            inventoryId:invId,
            inventoryName:name
        }
        $.post(
        "<?= site_url('inventory/cloneNewInventory'); ?>", 
        postData,
        function(data) {
            if(data > 0) {
                messagePopAlert("Clone Successfuly.......",2000);
                //window.location="<?php echo site_url('inventory/edititem/id/'); ?>";
                window.location="<?php echo site_url('inventory/edititem/id/'); ?>/"+data+"/modelid/"+invModel;
            } else {
                //close the popup windows
                messagePopAlert("Clone Failure.",2000);
                return;
            }
        },'json');
    }