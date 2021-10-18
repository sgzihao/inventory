<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jqueryui/autocomplete/themes/ui-lightness/jquery.ui.all.css" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() . 'assets/jqueryui/css/jquery.ui.base.css';?>" />

<div id="content">
    <h1><?= $title_info; ?></h1>
    <?php echo $error; ?>
    <?php echo validation_errors(); ?>
    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Step 1</a></li>
                <li><a href="#tabs-2">Step 2</a></li>
            </ul>

            <div id="tabs-1">
                <?php echo form_open_multipart('report/sources'); ?>

                <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">
                                Generate Source Table List
                            </td>
                        </tr>
                    </tbody>
                </table>

                <img width="1" height="10" src="images/spacer.gif"><br>
                <div align="center"><input type="submit" value="Download" /></div>
                </form>
            </div>
            
            <div id="tabs-2">
                <?php echo form_open_multipart('report/do_upload'); ?>

                <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                    <tbody>
                        <tr>
                            <td width="25%" class="fieldlabel">Upload Score Source Table File(CSV)</td>
                            <td class="fieldarea"><input type="file" name="userfile" size="40" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <img width="1" height="10" src="images/spacer.gif"><br>
                <div align="center"><input type="submit" value="upload" /></div>
                </form>
            </div>
        </div>
    </div>
</div>