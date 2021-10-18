<div align="center">
    <div id="bodyContentWrapper" align="left">
        <div id="topnav">
            <div id="date"><?php echo date("D | M | j | G:i:s | T | Y"); ?></div>

            <div class="navigation"> 
                <a id="logo" href="http://www.example.com/">
                    <img width="329" height="64" border="0" title="sgzihao.com" alt="sgzihao.com" src="<?php echo site_url(); ?>/assets/GS2.GIF" />
                </a>
            </div>

            <div class="clear"></div>
        </div>
        <div>
            <ul id='menu_dropdown'>
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
                    echo "<li><a href='", site_url($userKey), "' ", $classAction, ">", $menuArr[$userKey], "</a></li>";
                }

                if ($checkedMenu == 'setting') {
                    $classAction = "class='current'";
                } else {
                    $classAction = "";
                }
                echo "<li><a href='", site_url('setting'), "' ", $classAction, ">Setting</a></li>";
                ?>
            </ul>
        </div>
    </div>
</div>