
<html>
    <head>
        <title>XRF RECEIPT</title>
        <style>
            body { margin:0px; padding:0px;font-size:5px; font-weight:normal; font-family:"Times New Roman", Times, serif;}
            @media print {
                body { margin:0px; padding:0px;font-size:4px; font-weight:normal; font-family:"Times New Roman", Times, serif;}
            }
            td {
                font-size:16px;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td style="padding-top: 25px;"><center><?=$first_line?></center></td>
            </tr>
            <tr>
                <td style="padding-top: 5px;"><center>XRF TESTING RECEIPT</center></td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">
                    Receipt No: <?=$xrf_row->receipt_no;?><br/>
                    Box No: <?=$xrf_row->box_no;?><br/>
                    Date/Time: <?=date('D M d',strtotime($xrf_row->receipt_date));?> <?=date('h:i A',strtotime($xrf_row->receipt_time));?> <?=date('Y',strtotime($xrf_row->receipt_date));?>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 5px;padding-bottom: 5px;">M/S. <?=$xrf_row->taken_by_same == 1?$xrf_row->account_name:$xrf_row->taken_by_name;?></td>
            </tr>
            <?php 
                if(!empty($xrf_item_res)) {
                    ?>
                    <tr>
                        <td>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: left;border-top: dashed 1px;border-bottom: dashed 1px;">ARTICLES</td>
                                        <td style="text-align: right;border-top: dashed 1px;border-bottom: dashed 1px;">QTY</td>
                                        <td style="text-align: right;border-top: dashed 1px;border-bottom: dashed 1px;">WEIGHT</td>
                                    </tr>
                                    <?php
                                    $total_rec_qty = 0;
                                    $total_weight = 0;
                                    foreach ($xrf_item_res as $key => $xrf_item_row) {
                                        ?>
                                        <tr>
                                            <td style="text-align: left;"><?=$xrf_item_row->item_name;?></td>
                                            <td style="text-align: right;"><?=$xrf_item_row->rec_qty;?></td>
                                            <td style="text-align: right;"><?=number_format($xrf_item_row->weight, '2', '.', '').'gm';?></td>
                                        </tr>
                                        <?php
                                        $total_rec_qty += $xrf_item_row->rec_qty;
                                        $total_weight += $xrf_item_row->weight;
                                    }
                                    ?>
                                    <tr><td colspan="3">&nbsp;</td></tr>
                                    <tr>
                                        <td style="text-align: left;border-top: dashed 1px;border-bottom: dashed 1px;">Total</td>
                                        <td style="text-align: right;border-top: dashed 1px;border-bottom: dashed 1px;"><?=$total_rec_qty;?></td>
                                        <td style="text-align: right;border-top: dashed 1px;border-bottom: dashed 1px;"><?=number_format($total_weight, '2', '.', '').'gm';?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </table>
        <script type="text/javascript">
            window.print();
            setTimeout(function(){
                window.close();
            },2000);
        </script>
    </body>
</html>


