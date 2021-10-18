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
                <?php echo date("D | M | j | G:i:s | T | Y"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                <span><a href="<?php echo site_url('welcome/logout'); ?>"><?php echo $administrator; ?>  - Logout</a></span>
            </div>

            <div class="navigation"> 
                <a id="logo" href="http://www.example.com/">
                    <img width="329" height="64" border="0" title="sgzihao.com" alt="sgzihao.com" src="<?php echo site_url(); ?>/assets/GS2.GIF" />
                </a>
            </div>
        </div>
        <div id="menu">
            <ul class="menu">
                <?php
                //how to display the menu list.
                $menuArr = array('inventory' => 'Inventory',
                    'model' => 'Inventory Model',
                    'category' => 'Software/Category',
                    'report' => 'Report',
                    'setting' => 'System Setting');
                $userMenuList = unserialize($this->session->userdata('permission_list'));

                foreach ($userMenuList as $userKey => $userItem) {
                    //var_dump($submenulist);

                    if ($checkedMenu == $userKey) {
                        $classAction = "class='current'";
                    } else {
                        $classAction = "";
                    }
                    $menuName = isset($menuArr[$userKey]) ? $menuArr[$userKey] : 'xx';
                    if ($menuName == 'xx' and $menuName != "setting")
                        continue;

                    echo "<li><a href='", site_url($userKey), "' ", $classAction, "><span>", $menuArr[$userKey], "</span></a>";
                    //
                    if (array_key_exists($userKey, $submenulist)) {
                        echo "<div><ul>";

                        foreach ($submenulist[$userKey] as $subItem) {
                            echo $subItem;
                        }
                        echo "</ul></div>";
                    } else {
                        if ($classAction == "user" or $classAction == 'location') {
                            echo "<div><ul>";
                            echo "</ul></div>";
                        }
                    }
                    //
                    echo "</li>";
                }

                if ($checkedMenu == 'setting') {
                    $classAction = "class='current'";
                } else {
                    $classAction = "";
                }
                echo "<li><a href='", site_url('setting'), "' ", $classAction, "><span>Setting</span></a>";
                echo "<div><ul>";
                foreach ($submenulist['setting'] as $subItemArr) {
                    foreach ($subItemArr as $subItem) {
                        echo $subItem;
                    }
                }
                echo "</ul></div>";
                echo "</li>";
                //var_dump($submenulist);
                ?>
            </ul>
        </div>
    </div>
</div>