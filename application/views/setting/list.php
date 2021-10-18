<div id="content">

    <h1><?= $title_info; ?></h1>

    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li class="tab tabselected" id="tab0">
                    <a href="javascript:;">Search/Filter</a>
                </li>
            </ul>
        </div>

        <div class="tabbox" id="tab0box" style="display: block;">

            <div id="tab_content">

                <form action="" method="post">

                    <table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">User Name</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="username">
                                </td>
                                <td class="fieldlabel">User Email</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="useremail">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <img width="1" height="10" src="images/spacer.gif"><br>
                    <div align="center"><input type="submit" class="button" value="Search/Filter"></div>

                </form>

            </div>
        </div>

    </div>
    <br>

    <table width="100%" cellspacing="0" cellpadding="3" border="0">
        <tbody>
            <tr>
                <td width="50%" align="left">10 Records Found</td>
                <td width="50%" align="right"></td>
            </tr>
        </tbody>
    </table>

    <div class="tablebg">
        <table style="width: 100%" class="datatable">
            <tbody>
                <tr>
                    <th>UserName</th>
                    <th>Name</th>
                    <th>User Email</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Date Modified</th>
                    <th></th>
                </tr>
                <tr>
                    <?php
                    //echo $categorylistinfo;
                    ?>   
                </tr>
                <?php
                $i = 1;
                foreach ($userData as $item) {
                    $i++;
                    ?>
                    <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                        <td><?= $item->username; ?></td>
                        <td><?= $item->first_name . ' - ' . $item->last_name; ?></td>
                        <td><?= $item->email; ?></td>
                        <td><?= ($item->status == 1) ? 'Active' : 'Inactive'; ?></td>
                        <td><?= $item->creation_date . ' - ' . $item->created_by; ?></td>
                        <td><?= $item->modified_date . ' - ' . $item->modified_by; ?></td>
                        <td></td>
                    </tr>
                    <?
                }
                ?>

            </tbody>
        </table>
    </div>

</div>