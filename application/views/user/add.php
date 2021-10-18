<div id="content">
    <h1><?= $title_info; ?></h1>

    <?php echo validation_errors(); ?>
    
    <div id="content_padded">

        <?php echo form_open('user/newuser'); ?>

        <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
            <tbody>
                <tr>
                    <td class="fieldlabel">First Name</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="firstname" class="input-text" value="" />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Last Name</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="lastname" class="input-text" value="" />
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Email Address</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="useremail" class="input-text" value="" />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Login Name</td>
                    <td class="fieldarea">
                        <input type="text" size="40" name="username" class="input-text" value="<?php echo set_value("username") ?>" />
                    </td>
                </tr>

                <tr>
                    <td class="fieldlabel">Password</td>
                    <td class="fieldarea">
                        <input type="password" size="40" name="password" class="input-text" value="" />
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Confirm Password</td>
                    <td class="fieldarea">
                        <input type="password" size="40" name="confirmpassword" class="input-text" value="" />
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Location list</td>
                    <td class="fieldarea">
                        <table width="100%">
                            <?= $locationlist; ?>
                        </table>
                    </td>
                </tr>
                <tr style="">
                    <td class="fieldlabel">Permission list</td>
                    <td class="fieldarea">
                        <table width="100%" border="0" class="form">
                            <tbody>
                                <?=$permission;?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="15%" class="fieldlabel">Active</td>
                    <td class="fieldarea">
                        <input type="checkbox" id="useractive" name="useractive" checked />
                    </td>
                </tr>
            </tbody>
        </table>

        <p align="center"><input type="submit" class="button" value="Submit"></p>

        </form>

    </div>
</div>