<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.all.css';?>" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.base.css';?>" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/themes/ui-lightness/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/demos.css">
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery-ui.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.ui.sortable.js';?>"></script>
<style>
    #sortable1, #sortable2, #sortable3 {
        list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 30px; background: #B9C9FE; padding: 10px; width: 175px;
    }
    #sortable1 li, #sortable2 li{ margin: 2px; padding: 2px; font-size: 1.2em; width: 150px; }
</style>

<script type="text/javascript">
    $(function() {
        $( "ul.droptrue" ).sortable({
            connectWith: "ul"
        });

        $( "ul.dropfalse" ).sortable({
            connectWith: "ul",
            dropOnEmpty: false
        });

        $( "#sortable1, #sortable2").disableSelection();
    });
    $(document).ready(function(){
        $("#newmodel").click(function(){
            //sortable2
            var hardwarelist = '';
            $("#sortable2 li").each(function(index){
                hardwarelist += index+'_'+$(this).attr('id')+';';
            });
           
            $("#hardwarelistarr").val(hardwarelist);
            this.form.submit();
        });
    });
</script>
<div id="content">
    <?php echo form_open('model/newmodel'); ?>
    <h1><?php echo $title_info; ?></h1>
    <?php echo validation_errors(); ?>
    <?php $this->message->display(); ?>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Model info</a></li>
            <li><a href="#tabs-2">Hardware</a></li>
            <li><a href="#tabs-3">Software</a></li>
        </ul>
        <div id="tabs-1">
            <table cellspacing="2" cellpadding="3" border="0" width="100%" class="form">
                <tbody>
                    <tr>
                        <td width="15%" class="fieldlabel">Inventory Model Code</td>
                        <td class="fieldarea"><input type="text" name="inventory_model_code" size="40" id="inventory_model_code"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="fieldlabel">Inventory Model Name</td>
                        <td class="fieldarea"><input type="text" name="inventory_model_name" size="40" id="inventory_model_name"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Inventory Model Desc</td>
                        <td class="fieldarea">
                            <textarea tabindex="28" style="width:60%;" rows="4" name="long_description"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <input type="hidden" value='' name="hardwarelistarr" id="hardwarelistarr" />

        <div id="tabs-2">
            <div id="hardware">
                <p><b>Hardware/information</b></p>

                <div id="productconfigoptions0"></div>

                <div class="demo">
                    <ul id="sortable1" class='droptrue'>
                        <?php echo $sortablehardware; ?>
                    </ul>

                    <ul id="sortable2" class='dropfalse'>
                    </ul>
                    <br clear="both" />
                </div>
            </div>
        </div>

        <div id="tabs-3">

            <p><b>Software/information</b></p>

            <table cellspacing="2" cellpadding="3" border="0" width="100%" class="form">
                <tbody>
                    <tr><td width="15%" class="fieldlabel">
                            <table width="100%">
                                <tbody>
                                    <?php echo $softwarelist; ?>
                                </tbody>
                            </table>
                        </td></tr>
                </tbody>
            </table>
        </div>

        <p align="center"><input type="button" class="button" value="Submit" id="newmodel"></p>
        </form>
        
    </div>
</div>