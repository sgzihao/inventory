<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/themes/ui-lightness/jquery.ui.all.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/menulist/menu.css">

<style>
    #sortable1, #sortable2, #sortable3 { 
        list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 30px; background: #B9C9FE; padding: 10px; width: 175px;
    }
    #sortable1 li, #sortable2 li{ margin: 2px; padding: 2px; font-size: 1.2em; width: 150px; }
</style>
<div align="center">
    <div id="bodyContentWrapper" align="left">
        <div id="topnav">
            <div id="date">
                <?php 
			$MNTTZ = new DateTimeZone('Asia/Singapore');
			$dt = new DateTime('11/24/2009 2:00 pm', $MNTTZ);
			echo $dt->format(DATE_RFC822), $dt->format('U');
		?>&nbsp;&nbsp;&nbsp;&nbsp;--
                <span><a href="<?php echo site_url('welcome/logout'); ?>"><?php echo $administrator; ?>  - Logout</a></span>
            </div>

            <div class="navigation"> 
                <a id="logo" href="http://www.example.com/">
                    <img width="329" height="64" border="0" title="sgzihao.com" alt="sgzihao.com" src="<?php echo site_url(); ?>/assets/GS2.GIF" />
                </a>
            </div>
        </div>
        
    </div>
</div>
