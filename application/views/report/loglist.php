<div id="content">

    <h1><?php echo $title_info; ?></h1>
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
                                <td width="15%" class="fieldlabel">user</td>
                                <td class="fieldarea">
                                    <input type="text" value="" size="30" name="filterdescription">
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
                <td width="50%" align="left"><?= $totalNumber; ?> Records Found</td>
                <td width="50%" align="right"><?= $paginationlinks; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tablebg">
        <table style="width: 100%" class="datatable">
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>Log</th>
                    <th>Desc</th>
                    <th>User</th>
                    <th>Date</th>
                </tr>
                <?php
                $i = 1;
                foreach ($data as $item) {
                    $i++;
                    ?>
                    <tr <?php echo ($i % 2) ? "class='bg'" : ""; ?>>
                        <td><?= $item->id; ?></td>
                        <td><?= $item->title; ?> -- <?= $item->name; ?></td>
                        <td></td>
                        <td><?= $item->user_name; ?></td>
                        <td><?= $item->date; ?></td>
                    </tr>
                    <?
                }
                ?>

            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td width="50%" align="left"><?= $totalNumber; ?> Records Found</td>
                    <td width="50%" align="right"><?= $paginationlinks; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
</div>