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
        <div id="tab0box" class="tabbox">
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
       
        <br/>
        
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
                        <th>Application System</th>
                        <th>Located at</th>
                        <th>Business Purpose</th>

                        <th>Component Version</th>
                        <th>Market</th>

                        <th>Physical Vulnerability Score (Weight-20%)</th>
                        <th>Technical Vulnerability Score (Weight-25%)</th>
                        <th>Technology Obsolescence Score (Weight-25%)</th>
                        <th>Impact on Business Score (Weight-30%)</th>
                        <th>Overall Condition Score</th>
                        <th>Unit Cost to bring to acceptable condition for primary business purpose </th>
                        <th>Qty</th>
                        <th>No action required at this stage (Overall Condition score > 4</th>
                        <th>Action may be required (Overall condition score >3 - 4)</th>
                        <th>Candidate for retirement or upgrade (Overall condition score <=3)</th> 

                    </tr>
                    <?php
                    //var_dump($scorelist);
                    foreach ($applist as $item) {

                        //how to display the category list
                        $invArr = explode(",", $item->invidlist);

                        $rowItemArr = array();

                        foreach ($invArr as $invRelArr) {
                            if (isset($rellist[$invRelArr])) {
                                //var_dump($rellist[$invRelArr]);
                                foreach ($rellist[$invRelArr] as $cItem) {

                                    $tmpversion = !empty($cItem->category_version) ? $cItem->category_version : 'xx';
                                    $tmpcategory = !empty($cItem->category_name) ? $cItem->category_name : 'xx';
                                    $tmpkey = trim($tmpcategory) . '-' . trim($tmpversion);
                                    //if software exits, then add 1, else default value 1
                                    if (array_key_exists($tmpkey, $rowItemArr))
                                        $rowItemArr[$tmpkey] += 1;
                                    else
                                        $rowItemArr[$tmpkey] = 1;
                                    //$rowItemArr[$cItem]
                                    //echo $cItem->category_id."-".$cItem->category_version."<br/>";
                                }
                            }
                        }

                        //brand list
                        $brandlist = explode(",", $item->brand);
                        foreach ($brandlist as $bItem) {
                            $tempBrandKey = trim($bItem);
                            if (empty($bItem))
                                continue;
                            if (array_key_exists($tempBrandKey, $rowItemArr))
                                $rowItemArr[$tempBrandKey] += 1;
                            else
                                $rowItemArr[$tempBrandKey] = 1;
                        }

                        //os list
                        $oslist = explode(",", $item->os);
                        foreach ($oslist as $osItem) {
                            $tempOsKey = trim($osItem);
                            if (empty($osItem))
                                continue;
                            if (array_key_exists($tempOsKey, $rowItemArr))
                                $rowItemArr[$tempOsKey] += 1;
                            else
                                $rowItemArr[$tempOsKey] = 1;
                        }
                        
                        //var_dump($item);
                        $i = count($rowItemArr);
                        $rowspan = ($i > 1) ? "rowspan='{$i}'" : '';
                        echo "<tr>
                        <td {$rowspan}>{$item->application}</td>
                        <td {$rowspan}>{$location->location_name}</td>
                        <td {$rowspan}>----</td>";

                        //output result
                        //echo "<td>";
                        $j = 1;
                        foreach ($rowItemArr as $rowKey => $rowValue) {
                            //var_dump($rowItemArr[$rowKey]);
                            //$four2cost = 0;
                            if ($j == 1) {
                                echo "<td>", $rowKey, "</td>";
                                //echo "<td>---</td>";
                                //begin score
                                $OverallConditionScore = 0;
                                if (isset($scorelist[$rowKey])) {
                                    //$a = var_dump($scorelist[$rowKey]);
                                    //die($a);
                                    $OverallConditionScore = 0;
                                    for ($t = 1; $t < 7; $t++) {
                                        if ($t > 1 and $t < 6) {
                                            if ($t == 2) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.2;
                                            } elseif ($t == 3) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.25;
                                            } elseif ($t == 4) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.25;
                                            } elseif ($t == 5) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.3;
                                            }
                                        }
                                        if ($t == 6) {
                                            if ($OverallConditionScore > 0) {
                                                echo "<td>", $OverallConditionScore, "</td>";
                                            } else {
                                                echo "<td></td>";
                                            }
                                        }
                                        //$OverallConditionScore = 0;
                                        echo "<td>", $scorelist[$rowKey][$t], "</td>";
                                    }
                                } else {
                                    for ($t = 1; $t < 8; $t++) {
                                        echo "<td>---</td>";
                                    }
                                }
                                //end score

                                echo "<td>", $rowValue, "</td>";
                                //
                                if (isset($scorelist[$rowKey][6])) {
                                    if (preg_match("/freeware/i", $scorelist[$rowKey][6]))
                                        $cost = "Freeware";
                                    else
                                        $cost = "$" . $rowValue * $scorelist[$rowKey][6];
                                } else {
                                    $cost = '';
                                }
                                    
                                
                                if ($OverallConditionScore >= 4) {
                                    echo "<td>X</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                } elseif ($OverallConditionScore > 3 && $OverallConditionScore < 4) {
                                    //var_dump($scorelist[$rowKey][6]);

                                    echo "<td></td>";
                                    echo "<td>{$cost}</td>";
                                    echo "<td></td>";
                                } elseif ($OverallConditionScore <= 3) {
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "<td>{$cost}</td>";
                                }
                                //
                                echo "</tr>";
                            } else {
                                echo "<tr>";
                                echo "<td>", $rowKey, "</td>";
                                //echo "<td>---</td>";
                                //begin score
                                if (isset($scorelist[$rowKey])) {
                                    //$a = var_dump($scorelist[$rowKey]);
                                    //die($a);
                                    $OverallConditionScore = 0;
                                    for ($t = 1; $t < 7; $t++) {
                                        //echo "<td>", $scorelist[$rowKey][$t], "</td>";


                                        if ($t > 1 and $t < 6) {
                                            if ($t == 2) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.2;
                                            } elseif ($t == 3) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.25;
                                            } elseif ($t == 4) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.25;
                                            } elseif ($t == 5) {
                                                $OverallConditionScore += $scorelist[$rowKey][$t] * 0.3;
                                            }
                                        }
                                        if ($t == 6) {
                                            if ($OverallConditionScore > 0) {
                                                echo "<td>", $OverallConditionScore, "</td>";
                                            } else {
                                                echo "<td></td>";
                                            }
                                        }
                                        echo "<td>", $scorelist[$rowKey][$t], "</td>";
                                    }
                                } else {
                                    for ($t = 1; $t < 8; $t++) {
                                        echo "<td>---</td>";
                                    }
                                    $OverallConditionScore = "xx";
                                }
                                //end score
                                echo "<td>", $rowValue, "</td>";
                                //
                                if (isset($scorelist[$rowKey][6]) && $OverallConditionScore != 'xx') {
                                    if (preg_match("/freeware/i", $scorelist[$rowKey][6]))
                                        $cost = "Freeware";
                                    else
                                        $cost = "$" . $rowValue * $scorelist[$rowKey][6];
                                } else {
                                    $cost = '';
                                }

                                if ($OverallConditionScore >= 4) {
                                    echo "<td>X</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                } elseif ($OverallConditionScore > 3 && $OverallConditionScore < 4) {
                                    //$cost = "$" .$rowValue * $scorelist[$rowKey][6];
                                    echo "<td></td>";
                                    echo "<td>{$cost}</td>";
                                    echo "<td></td>";
                                } elseif ($OverallConditionScore <= 3) {
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "<td>{$cost}</td>";
                                }
                                //
                                echo "</tr>";
                            }

                            $j++;
                        }
                        //no category realtion
                        if ($j == 1) {
                            for ($t = 1; $t < 8; $t++) {
                                echo "<td>---</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>

                </tbody>
            </table>
            
        </div>
    
    </div>
</div>