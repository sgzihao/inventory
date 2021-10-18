<div id="content">

    <h1><?= $title_info; ?></h1>
    <?php echo validation_errors(); ?>
    <?php $this->message->display(); ?>
    <div id="content_padded">

        <?php echo form_open('category/editipv6/id/' . $categoryID); ?>
        <input type="hidden" name="categoryid" value="<?php echo $categoryID; ?>" />
        <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
            <tbody>
                <tr>
                    <td width="20%"  class="fieldlabel">Category Name</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="categoryname" class="input-text" value="<?php echo $categoryInfo->category_name; ?>" readonly />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Category Ipv6 Min  Version</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="ipv6_min_version" class="input-text" value="<?php echo $categoryInfo->ipv6_min_version; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Parent Category</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="parent_category" class="input-text" value="<?php echo $categoryInfo->parent_category; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Used</td>
                    <td class="fieldarea">
                        Yes <input type="radio" size="40" name="flag" class="input-text" value="yes" <?php echo ($categoryInfo->flag == 'yes')?"checked":''; ?> />
                        No <input type="radio" size="40" name="flag" class="input-text" value="no" <?php echo ($categoryInfo->flag == 'no')?"checked":''; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel"></td>
                    <td class="fieldarea">
                        <input type="submit" class="button" value="Save">
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
        <br/>
    </div>
</div>