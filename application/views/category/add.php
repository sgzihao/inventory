<div id="content">
    <h1>Add New Category</h1>

    <?php echo validation_errors(); ?>
    <div id="content_padded">

        <?php echo form_open('category/newcategory'); ?>

        <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
            <tbody>
                <tr>
                    <td class="fieldlabel">Category Name</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="categoryname" class="input-text" value="<?php echo set_value("categoryname") ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Category</td>
                    <td class="fieldarea">
                        <?php echo $topcategorylist; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <p align="center"><input type="submit" class="button" value="Submit"></p>

        </form>

    </div>
</div>