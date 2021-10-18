
<?php
foreach ($submenulist as $arr) {
    echo "<ul class='menu'>";
    foreach ($arr as $key => $value) {
        echo $value;
    }
    echo "</ul>";
}
?>

<br style="clear: both">

<?php echo $inventoryMenuList ;?>

<br style="clear: both">

<span class="plain_header">Administrator Online</span>
<div class="smallfont"><a href="<?php echo site_url('welcome/logout'); ?>"><?php echo $administrator; ?>  - Logout</a></div>

<br style="clear: both">