<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link type="text/css" href="<?php echo base_url(); ?>/style/menu/menu.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>/style/css/main.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>/style/jquery/css/ui-lightness/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
        <script type="text/javascript" src="<?php echo base_url(); ?>/style/jquery/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/style/jquery/js/jquery-ui-1.8.18.custom.min.js"></script>
        <script type="text/javascript">
            $(function(){
                // Tabs
                $('#tabs').tabs();
                
                //hover states on the static widgets
                $('#dialog_link, ul#icons li').hover(
                function() { $(this).addClass('ui-state-hover'); }, 
                function() { $(this).removeClass('ui-state-hover'); }
            );	
            });
        </script>
    </head>
    <body>
        <div id="container">
            <div id="headerinfo">
                <span class="sysinfo">
			<?php date_default_timezone_set('Asia/Singapore');echo date("D | M | j | G:i:s | T | Y"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
	                <span><a href="<?php echo site_url('welcome/logout'); ?>"><?php echo $administrator; ?>  - Logout</a></span>
		</span>
            </div>

            <div id="header">
                <img width="329" height="64" border="0" title="Inventory System" alt="example.com" class="logo" src="<?php echo base_url(); ?>/assets/GS2.GIF">
            </div>

            <div id="main_content">
                <div id="menu">
                    <ul class="menu">
                        <?php
                        //how to display the menu list.
                        $menuArr = array('inventory' => 'Inventory',
                            'model' => 'Inventory Model',
                            'category' => 'Software/Category',
                            'report' => 'Report',
							'rack' => 'Rack',
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

                <?php echo $template['body']; ?>

            </div>
        </div>

        <div id="copyright">Copyright &copy; 2012 <a href="http://www.example.com/">sgzihao</a></div>
</body>
</html>
