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
                                <td width="15%" class="fieldlabel">Location</td>
                                <td class="fieldarea">
                                    <?php echo $locationinfo; ?>
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
                <td width="50%" align="left"></td>
                <td width="50%" align="right"></td>
            </tr>
        </tbody>
    </table>

    <div class="tablebg">
        <table style="width: 100%" class="datatable">
            <tbody>
                <tr>
                    <th>OS</th>
                    <th>Kernel Version</th>
                    <th>Total Number</th>
                </tr>
                <?php
                foreach ($osdata as $item) {
                ?>
                    <tr>
                        <td><?= $item->os; ?></td>
                        <td><?= $item->kernel; ?></td>
                        <td><?= $item->num; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>