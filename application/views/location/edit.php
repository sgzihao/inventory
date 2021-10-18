<div id="content">
    <h1><?php echo $title_info; ?></h1>
    <?php $this->message->display(); ?>
    <?php echo validation_errors(); ?>
    <div id="content_padded">

        <?php echo form_open("location/edit/id/{$locationinfo->location_id}"); ?>

        <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
            <tbody>
                <tr>
                    <td width="15%" class="fieldlabel">Office Name</td>
                    <td class="fieldarea">
                        <input type="text" value="<?php echo $locationinfo->location_name ?>" size="30" name="gsofficename">
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Desc</td>
                    <td class="fieldarea">
                        <textarea name="gsofficedesc" rows="6" cols="60%"><?php echo $locationinfo->long_description;?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>

        <p align="center"><input type="submit" class="button" value="Submit"></p>

        </form>
    </div>
</div>