<div id="content">

    <h1><?= $title_info; ?></h1>

    <div id="content_padded">
        <div id="tabs">
            <ul>
                <li class="tab" id="tab0">
                    <a href="javascript:;">Search/Filter</a>
                </li>
            </ul>
        </div>

        <div class="tabbox" id="tab0box">

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
                    <th style="width:8%">Component/Version</th>
                    <th>Market</th>
                    <th style="width:12%">Application System</th>
                    <th style="width:8%">Located at</th>
                    <th style="width:10%">Business Purpose</th>
                    <th>Physical Vulnerability Score (Weight-20%)</th>
                    <th>Technical Vulnerability Score (Weight-25%)</th>
                    <th>Technology Obsolescence Score (Weight-25%)</th>
                    <th>Impact on Business Score (Weight-30%)</th>
                    <th>Overall Condition Score</th>
                    <th>Unit Cost</th>
                    <th>Qty</th>
                    <th>No action required at this stage (Overall Condition score > 4</th>
                    <th>Action may be required (Overall condition score >3 - 4)</th>
                    <th>Candidate for retirement or upgrade (Overall condition score <=3)</th>
                    <th>Action Plan</th>
                </tr>
                <?php
                foreach ($overall as $key => $item) {
                    foreach ($item as $rowkey => $rowItem) {
                        //var_dump($rowItem);
                        echo "<tr>";
                        echo "<td>", $key, "</td>";
                        echo "<td>--</td>";
                        echo "<td>", $rowkey, "</td>";
                        echo "<td>", $location->location_name, "</td>";
                        echo "<td>--</td>";
                        //begin score
                        //$OverallConditionScore = 0;
                        if (isset($scorelist[$key])) {
                            //var_dump($scorelist[$key]);
                            $OverallConditionScore = 0;
                            for ($i = 2; $i < 7; $i++) {
                                if ($i > 1 and $i < 6) {
                                    if ($i == 2) {
                                        $OverallConditionScore += $scorelist[$key][$i] * 0.2;
                                    } elseif ($i == 3) {
                                        $OverallConditionScore += $scorelist[$key][$i] * 0.25;
                                    } elseif ($i == 4) {
                                        $OverallConditionScore += $scorelist[$key][$i] * 0.25;
                                    } elseif ($i == 5) {
                                        $OverallConditionScore += $scorelist[$key][$i] * 0.3;
                                    }
                                }
                                if ($i == 6) {
                                    if ($OverallConditionScore > 0) {
                                        echo "<td>", $OverallConditionScore, "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                echo "<td>", $scorelist[$key][$i], "</td>";
                            }
                        } else {
                            for ($i = 2; $i < 8; $i++) {
                                echo "<td>---</td>";
                            }
                            $OverallConditionScore = "xx";
                        }
                        //end score

                        echo "<td>", $rowItem['qty'], "</td>";
                        if (isset($scorelist[$key][6]) && $OverallConditionScore != 'xx') {
                            if (preg_match("/freeware/i", $scorelist[$key][6]))
                                $cost = "Freeware";
                            else
                                $cost = "$" . $rowItem['qty'] * $scorelist[$key][6];
                        } else {
                            $cost = '';
                        }
                        
                        if ($OverallConditionScore >= 4) {
                            echo "<td>X</td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                        } elseif ($OverallConditionScore > 3 && $OverallConditionScore < 4) {
                            //var_dump($scorelist[$rowKey][6]);
                            echo "<td></td>";
                            echo "<td>{$cost}</td>";
                            echo "<td></td>";
                            echo "<td></td>";
                        } elseif ($OverallConditionScore <= 3) {
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td>{$cost}</td>";
                            echo "<td></td>";
                        } 
                        
                        echo "</tr>";
                    }
                    //var_dump($scorelist[$key]);
                    //break;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>