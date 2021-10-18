<div id="content">
    <?php echo isset($error) ? $error : ''; ?>

    <h1><?php echo $title_info; ?></h1>

    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li id="tab1" class="tab">
                    <a href="javascript:;">Update IPV6 Min Version</a>
                </li>
            </ul>
        </div>
        <div style="" id="tab1box" class="tabbox">

            <div id="tab_content">
                <form enctype="multipart/form-data" accept-charset="utf-8" method="post" action="<?= site_url('report/ipv6MinImport'); ?>">
                    <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                        <tbody>
                            <tr>
                                <td width="25%" class="fieldlabel">Upload IPV6 Min Version Table File(CSV)</td>
                                <td class="fieldarea"><input type="file" size="40" name="userfile">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <img width="1" height="10" src="images/spacer.gif"><br>
                    <div align="center"><input type="submit" value="upload"></div>

                </form>

            </div>

        </div>

        <div id="content_padded">

            <ul>
                <?php
                if(isset ($upload_data)) {
                    foreach ($upload_data as $item => $value): ?>
                    <li><?php echo $item; ?>: <?php echo $value; ?></li>
                
                <?php endforeach;
                }
                ?>
            </ul>
        </div>
        
    </div>
</div>