<div id="content">

    <h1><?= $title_info; ?></h1>

    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Search/Filter</a></li>
            </ul>
            
            <div id="tabs-1">
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
                    <div align="center">
                        <input type="submit" class="button" value="Search/Filter" />
                        <input type="button" onclick="location.href='<?php echo site_url("report/ipv6Export/locationid/{$location->location_id}"); ?>'" value="Report" />
                        <input type="button" onclick="location.href='<?php echo site_url("report/ipv6ExportAllList"); ?>'" value="Report All Office Download" />
                    </div>

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
                    <th style="width:20%">GS Products used</th>
                    <th style="width:15%">Min Version with IPv6 Support</th>
                    <th>Category</th>
                    <th>Version</th>
                    <th>Count Number</th>
                </tr>
                <?php
                foreach ($ipv6Data as $item) {
                    $count = 0;
                    $cagegoryName = $item->category_name;

                    //how to display list information.
                    $tmp = "<td>N/A</td><td>N/A</td</td></tr>";
                    //var_dump($categoryData);
                    if (isset($categoryData[$item->category_id])) {
                        $tmp = '';
                        foreach ($categoryData[$item->category_id] as $row) {
                            //
                            if ($count > 0)
                                $tmp .= "<tr>";
                            //
                            $version = !empty($row->category_version) ? $row->category_version : 'xx';

                            $tmp .= "<td>{$version}</td>";
                            $totalnum = ($row->num > 0) ? $row->num : '0';
                            $tmp .= "<td>{$totalnum}</td>";
                            $tmp .= "</tr>";
                            $count++;
                        }
                    }

                    if ($count > 0) {
                        $endLine = '';
                        if ($count > 1) {
                            $rowspan = "rowspan='{$count}'";
                            //$endLine = "</tr>";
                        } else {
                            $rowspan = '';
                            $endLine = '';
                        }

                        echo "<tr>
                        <td {$rowspan}>{$cagegoryName}</td>
                        <td {$rowspan}>{$item->ipv6_min_version}</td>
                        <td {$rowspan}>{$item->parent_category}</td> {$endLine}";
                        echo $tmp;
                    } else {
                        echo "<tr><td>{$cagegoryName}</td>
                        <td>{$item->ipv6_min_version}</td>
                        <td>{$item->parent_category}</td>
                        <td></td>
                        <td></td
                        </td>
                    </tr>";
                    }
                }
                ?>
                <?php
                foreach ($osdata as $item) {
                    ?>
                    <tr>
                        <td><?= empty($item->os) ? "os-xx" : $item->os; ?></td>
                        <td></td>
                        <td>OS & Monitoring</td>
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