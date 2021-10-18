<div id="content">
    <h1><?= $title_info; ?></h1>
    <div id="content_padded">
    <div class="tablebg">
        <table style="width: 100%" class="datatable">
            <tbody>
                <tr>
                    <th style="width:8%">Component/Version</th>
                    <th>Market</th>
                    <th style="width:12%">Application System</th>

                    <th style="width:10%">Business Purpose</th>
                    <th style="width:8%">Located at</th>
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
                </tr>
                <?php
                foreach ($overall as $key => $item) {
                    //
                    $singleRecord = '';
                    $i = 0;
                    foreach ($item as $rowkey => $rowItem) {
                        //var_dump($rowItem);
                        //break;
                        $lid = $rowItem['location'];
                        $locationName = isset($location[$lid]) ? $location[$lid] : '---';
                        if ($i > 1)
                            $singleRecord .= "<tr>";

                        //
                        $singleRecord .= "<td>{$rowkey}</td>";
                        $singleRecord .= "<td>---</td>";
                        $singleRecord .= "<td>{$locationName}</td>";
                        $OverallConditionScore = 0;
                        //begin score
                        if (isset($scorelist[$key])) {
                            //var_dump($scorelist[$key]);
                            $OverallConditionScore = 0;
                            for ($t = 2; $t < 7; $t++) {

                                if ($t > 1 and $t < 6) {
                                    if ($t == 2) {
                                        $OverallConditionScore += $scorelist[$key][$t] * 0.2;
                                    } elseif ($t == 3) {
                                        $OverallConditionScore += $scorelist[$key][$t] * 0.25;
                                    } elseif ($t == 4) {
                                        $OverallConditionScore += $scorelist[$key][$t] * 0.25;
                                    } elseif ($t == 5) {
                                        $OverallConditionScore += $scorelist[$key][$t] * 0.3;
                                    }
                                }
                                if ($t == 6) {
                                    if ($OverallConditionScore > 0) {
                                        $singleRecord .= "<td>{$OverallConditionScore}</td>";
                                    } else {
                                        $singleRecord .= "<td></td>";
                                    }
                                }

                                $singleRecord .= "<td>{$scorelist[$key][$t]}</td>";
                            }
                        } else {
                            for ($t = 2; $t < 8; $t++) {
                                $singleRecord .= "<td>---</td>";
                            }
                        }
                        //end score

                        $singleRecord .= "<td>{$rowItem['qty']}</td>";
                        if (isset($scorelist[$key][6]) && $OverallConditionScore != 'xx') {
                            if (preg_match("/freeware/i", $scorelist[$key][6]))
                                $cost = "Freeware";
                            else
                                $cost = "$" . $rowItem['qty'] * $scorelist[$key][6];
                        } else {
                            $cost = '';
                        }
                        
                        if ($OverallConditionScore >= 4) {
                            $singleRecord .= "<td>X</td>";
                            $singleRecord .= "<td></td>";
                            $singleRecord .= "<td></td>";
                        } elseif ($OverallConditionScore > 3 && $OverallConditionScore < 4) {
                            //var_dump($scorelist[$rowKey][6]);

                            $singleRecord .= "<td></td>";
                            $singleRecord .= "<td>{$cost}</td>";
                            $singleRecord .= "<td></td>";
                        } elseif ($OverallConditionScore <= 3) {
                            $singleRecord .= "<td></td>";
                            $singleRecord .= "<td></td>";
                            $singleRecord .= "<td>{$cost}</td>";
                        }

                        $singleRecord .= "</tr>";

                        $i++;
                    }
                    //$i = count($rowItemArr);
                    $rowspan = ($i > 1) ? "rowspan='{$i}'" : '';
                    //row tilte
                    $rowTitle = "<tr>";
                    $rowTitle .= "<td {$rowspan}>{$key}</td>";
                    $rowTitle .= "<td {$rowspan}>---</td>";
                    //break;
                    echo $rowTitle;
                    echo $singleRecord;
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
</div>