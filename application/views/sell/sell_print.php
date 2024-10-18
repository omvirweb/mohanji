<html>
    <head>
        <title>Sell/Purchase Print</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>-->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <script src="<?= base_url('assets/plugins/jQuery/jquery.min.js'); ?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
        <?php if(isset($isimage)){ ?>
            <script src="<?php echo base_url('assets/dist/js/html2canvas.js');?>"></script>
        <?php } ?>
        <style>
            html{
                background-color: #ffffff;
            }
            @media print {
                body { 
                    font-family: arial-bold; 
                    font-size: 9.5pt; 
                }
                table{
                    border-spacing: 0;
                }
                .text-center {
                    text-align: center !important;
                }
                .text-right {
                    text-align: right !important;
                }
                .text-left {
                    text-align: left !important;
                }
                .text-bold {
                    font-weight: bold;
                }
                .text-underline {
                    text-decoration: underline;
                }
                .section-title{
                    font-size: 20px;
                    font-weight: 900;
                    margin: 15px 10px;

                }
                .no-margin{
                    margin: 0;
                }
                table.table-item-detail > thead > tr > th:last-child{
                    border-right: solid 0.5px #000000;
                }
                table.table-item-detail > thead > tr > th:first-child{
                    border-left: solid 0.5px #000000;
                }
                table.table-item-detail > tbody > tr > th:last-child,table.table-item-detail > tbody > tr > td:last-child{
                    border-right: solid 0.5px #000000;
                }
                table.table-item-detail > tbody > tr > th:first-child,table.table-item-detail > tbody > tr > td:first-child{
                    border-left: solid 0.5px #000000;
                }
                table.table-item-detail > thead > tr > th{
                    border-top: solid 0.5px #000000;
                    border-bottom: solid 0.5px #000000;
                }
                table.table-item-detail > tbody > tr > th{
                    border-top: solid 0.5px #000000;
                    border-bottom: solid 0.5px #000000;
                }
            }
            table.table-item-detail > thead > tr > th:last-child{
                border-right: solid 0.5px #000000;
            }
            table.table-item-detail > thead > tr > th:first-child{
                border-left: solid 0.5px #000000;
            }
            table.table-item-detail > tbody > tr > th:last-child,table.table-item-detail > tbody > tr > td:last-child{
                border-right: solid 0.5px #000000;
            }
            table.table-item-detail > tbody > tr > th:first-child,table.table-item-detail > tbody > tr > td:first-child{
                border-left: solid 0.5px #000000;
            }
            table.table-item-detail > thead > tr > th{
                border-top: solid 0.5px #000000;
                border-bottom: solid 0.5px #000000;
            }
            table.table-item-detail > tbody > tr > th{
                border-top: solid 0.5px #000000;
                border-bottom: solid 0.5px #000000;
            }
            table{
                border-spacing: 0;
            }
            table tr td {
                /*padding-left: 5px;
                padding-right: 5px;*/
                padding-top: 0px;
                padding-bottom: 2px;
                padding-left: 1px;
                padding-right: 1px;
            }
            .border{
                border: 1px solid grey;
/*                border-radius: 25px !important;*/
            }
            .border-right{
                border-right: none;
            }
            .border-left{
                border-left: none;
            }
            .line-hight {
                line-height: 3;
            }
            <?php if(isset($isimage)){ ?>
                table {
                    width:100%;
                }
            <?php } else { ?>
                table {
                    width:100%;
                    margin-left: 2%;
                }
            <?php } ?>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                font-weight: bolder;
                size: 10px;
            }
            th, td {
                text-align: left;
                line-height: 25px;
            }
            .no-border{
                border: none;
            }

        </style>
    </head>
    <body>
        <?php if(isset($isimage)){ ?>
            <div id="html-content-holder" style="background-color: #ffffff; width:95%;">
        <?php } ?>
        <table class="border">
            <tr>
                <td>Buyer :</td>
                <td><?php echo $sell_data->account_name; ?></td>
                <td>Date :</td>
                <td><?php echo !empty($sell_data->sell_date) ? date('d-M-Y', strtotime($sell_data->sell_date)) : ''; ?></td>
            </tr>
            <?php if(PACKAGE_FOR == 'mohanji') { ?>
                <tr>
                    <td>Voucher No. :</td>
                    <td colspan="3"><?php echo $sell_data->sell_no; ?></td>
                </tr>
            <?php } ?>
            <?php if($remark_2 == '1') { ?>
                <tr>
                    <td>Remark :</td>
                    <td colspan="3"><?php echo $sell_data->sell_remark; ?></td>
                </tr>
            <?php } ?>
        </table>
        <table class="border">
            <tr>
                <td width="40">Sr.No.</td>
                <td width="<?php if(PACKAGE_FOR != 'mohanji') { ?> 240 <?php } else { ?> 240 <?php } ?>">Category</td>
                <td class="text-right">Gr.Wt.</td>
                <?php if($less_netwt_2 == '1') { ?>
                    <td class="text-right">Less</td>
                    <td class="text-right">Net Wt.</td>
                <?php } ?>
            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                <td class="text-right">Tunch</td>
                <?php if($wstg_2 == '1') { ?>
                    <td class="text-right">Wstg.</td>
                <?php } ?>
            <?php } ?>
                <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                    <td class="text-right" width="70">Gold Fine</td>
                <?php } ?>
                <td class="text-right" width="70">Silver Fine</td>
                <td class="text-right" width="70">Amount</td>
            </tr>
            <?php 
                $row_inc = 1;
                $total_grwt = 0;
                $total_less = 0;
                $total_net_wt = 0;
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                $total_amount = 0;
            ?>
            <?php 
                if(!empty($sell_item)){
                    foreach ($sell_item as $sell_item_row){
                        $total_grwt = $total_grwt + $sell_item_row->grwt;
                        $total_less = $total_less + $sell_item_row->less;
                        $total_net_wt = $total_net_wt + $sell_item_row->net_wt;
                        if($sell_item_row->group_name == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $sell_item_row->fine;
                            $total_gold_fine = $total_gold_fine + $gold_fine;
                        }
                        if($sell_item_row->group_name == CATEGORY_GROUP_SILVER_ID){
                            $silver_fine = $sell_item_row->fine;
                            $total_silver_fine = $total_silver_fine + $silver_fine;
                        }
                        $total_amount = $total_amount + $sell_item_row->charges_amt;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td>
                                <?php echo $sell_item_row->type_name . ' - ' . $sell_item_row->category_name . ' - ' . $sell_item_row->item_name; ?>
                                <?php if($line_item_remark == '1' && $display_line_item_remark_in_print == '1') { ?>
                                    <?php if(isset($sell_item_row->li_narration) && !empty($sell_item_row->li_narration)) { echo '<small> - ' . $sell_item_row->li_narration . '</small>'; } ?>
                                <?php } ?>
                            </td>
                            <td class="text-right"><?php echo $sell_item_row->grwt; ?></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td class="text-right"><?php echo $sell_item_row->less; ?></td>
                                <td class="text-right"><?php echo $sell_item_row->net_wt; ?></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td class="text-right"><?php echo $sell_item_row->touch_id; ?></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td class="text-right"><?php echo $sell_item_row->wstg; ?></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td class="text-right"><?php echo ($sell_item_row->group_name == CATEGORY_GROUP_GOLD_ID) ? $gold_fine : ''; ?></td>
                            <?php } ?>
                            <td class="text-right"><?php echo ($sell_item_row->group_name == CATEGORY_GROUP_SILVER_ID) ? $silver_fine : ''; ?></td>
                            <td class="text-right"><?php echo $sell_item_row->charges_amt; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>

            <?php
                if(!empty($ad_charges_data)){
                    foreach ($ad_charges_data as $ad_charges_data_row){
                        $total_amount = $total_amount + $ad_charges_data_row->ad_amount;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo 'Charges - ' . $ad_charges_data_row->ad_name . ' - ' . $ad_charges_data_row->ad_pcs . ' @ ' . $ad_charges_data_row->ad_rate; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td></td>
                            <?php } ?>
                            <td></td>
                            <td class="text-right"><?php echo $ad_charges_data_row->ad_amount; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>

            <tr>
                <th></th>
                <th class="text-right">Total &nbsp;</th>
                <th class="text-right"><?php echo number_format($total_grwt, 3, '.', ''); ?></th>
                <?php if($less_netwt_2 == '1') { ?>
                    <th class="text-right"><?php echo number_format($total_less, 3, '.', ''); ?></th>
                    <th class="text-right"><?php echo number_format($total_net_wt, 3, '.', ''); ?></th>
                <?php } ?>
                <?php if(PACKAGE_FOR != 'mohanji') { ?>
                    <th></th>
                    <?php if($wstg_2 == '1') { ?>
                        <th></th>
                    <?php } ?>
                <?php } ?>
                <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                    <th class="text-right"><?php echo number_format($total_gold_fine, 3, '.', ''); ?></th>
                <?php } ?>
                <th class="text-right"><?php echo number_format($total_silver_fine, 3, '.', ''); ?></th>
                <th class="text-right"><?php echo number_format($total_amount, 2, '.', ''); ?></th>
            </tr>

            <?php 
                if(!empty($pay_rec_data)){
                    foreach ($pay_rec_data as $pay_rec_data_row){
                        $total_amount = $total_amount + $pay_rec_data_row->amount;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo $pay_rec_data_row->payment_receipt_name . ' - ' . $pay_rec_data_row->cash_cheque . $pay_rec_data_row->bank_name . ' - ' . $pay_rec_data_row->narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td></td>
                            <?php } ?>
                            <td></td>
                            <td class="text-right"><?php echo $pay_rec_data_row->amount; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>

            <?php 
                if(!empty($metal_data)){
                    foreach ($metal_data as $metal_data_row){
                        if($metal_data_row->group_name == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $metal_data_row->metal_fine;
                            $total_gold_fine = $total_gold_fine + $gold_fine;
                        }
                        if($metal_data_row->group_name == CATEGORY_GROUP_SILVER_ID){
                            $silver_fine = $metal_data_row->metal_fine;
                            $total_silver_fine = $total_silver_fine + $silver_fine;
                        }
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo $metal_data_row->metal_payment_receipt_name . ' - ' . $metal_data_row->metal_item_name . ' - ' . $metal_data_row->metal_narration; ?></td>
                            <td class="text-right"><?php echo $metal_data_row->metal_grwt; ?></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td class="text-right"><?php echo $metal_data_row->metal_grwt; ?></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td class="text-right"><?php echo $metal_data_row->metal_tunch; ?></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td class="text-right"><?php echo ($metal_data_row->group_name == CATEGORY_GROUP_GOLD_ID) ? $gold_fine : ''; ?></td>
                            <?php } ?>
                            <td class="text-right"><?php echo ($metal_data_row->group_name == CATEGORY_GROUP_SILVER_ID) ? $silver_fine : ''; ?></td>
                            <td></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>

            <?php 
                if(!empty($gold_data)){
                    foreach ($gold_data as $gold_data_row){
                        $gold_fine = $gold_data_row->gold_weight;
                        $total_gold_fine = $total_gold_fine + $gold_fine;
                        $total_amount = $total_amount + $gold_data_row->gold_value;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo 'Gold Bhav - ' . $gold_data_row->gold_sale_purchase_name . ' @ ' . $gold_data_row->gold_rate . ' - ' . $gold_data_row->gold_narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td class="text-right"><?php echo $gold_fine; ?></td>
                            <?php } ?>
                            <td></td>
                            <td class="text-right"><?php echo $gold_data_row->gold_value; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>

            <?php 
                if(!empty($silver_data)){
                    foreach ($silver_data as $silver_data_row){
                        $silver_fine = $silver_data_row->silver_weight;
                        $total_silver_fine = $total_silver_fine + $silver_fine;
                        $total_amount = $total_amount + $silver_data_row->silver_value;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo 'Silver Bhav - ' . $silver_data_row->silver_sale_purchase_name . ' @ ' . $silver_data_row->silver_rate . ' - ' . $silver_data_row->silver_narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td></td>
                            <?php } ?>
                            <td class="text-right"><?php echo $silver_fine; ?></td>
                            <td class="text-right"><?php echo $silver_data_row->silver_value; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>

            <?php 
                if(!empty($transfer_data)){
                    foreach ($transfer_data as $transfer_data_row){
                        $total_gold_fine = $total_gold_fine + $transfer_data_row->transfer_gold;
                        $total_silver_fine = $total_silver_fine + $transfer_data_row->transfer_silver;
                        $total_amount = $total_amount + $transfer_data_row->transfer_amount;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo 'Transfer - ' . $transfer_data_row->naam_jama_name . ' - ' . $transfer_data_row->party_name . ' - ' . $transfer_data_row->transfer_narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <?php if(PACKAGE_FOR != 'mohanji') { ?>
                                <td></td>
                                <?php if($wstg_2 == '1') { ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                                <td class="text-right"><?php echo $transfer_data_row->transfer_gold; ?></td>
                            <?php } ?>
                            <td class="text-right"><?php echo $transfer_data_row->transfer_silver; ?></td>
                            <td class="text-right"><?php echo $transfer_data_row->transfer_amount; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>
            <?php 
                $colspan = '7';
                if(PACKAGE_FOR != 'mohanji') {
                    $colspan = '7';
                } else {
                    $colspan = '5';
                }
                if($less_netwt_2 == '1') {} else {
                    $colspan = $colspan - 2;
                }
                if($wstg_2 == '1') {} else {
                    $colspan = $colspan - 1;
                }
            ?>
            <?php if($ask_discount_in_sell_purchase == '1') { ?>
            <tr>
                <td class="text-right" colspan="<?php echo $colspan; ?>" >Discount :</td>
                <?php if($sell_purchase_print_display_gold_fine_column == '1') { ?>
                    <td class="text-right"></td>
                <?php } ?>
                <td class="text-right"></td>
                <td class="text-right">
                    <?php
                        echo number_format($sell_data->discount_amount, 2, '.', '');
                        $total_amount = $total_amount - $sell_data->discount_amount;
                    ?>
                </td>
            </tr>
            <?php } ?>
            
        </table>
        <table class="border">
            <tr>
                <td class="text-right" style="width:47px;">Bill Bal.</td>
                <td class="text-right" ><?php echo number_format($total_gold_fine, 3, '.', ''); ?></td>
                <td class="text-right" ><?php echo number_format($total_silver_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($total_amount, 2, '.', ''); ?></td>
                <td class="text-right" style="width:50px;">Old Bal.</td>
                <td class="text-right"><?php echo number_format($sell_data->old_gold_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($sell_data->old_silver_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($sell_data->old_amount, 2, '.', ''); ?></td>
                <td class="text-right" style="width:50px;">Net Bal.</td>
                <td class="text-right"><?php echo number_format(($sell_data->old_gold_fine + $total_gold_fine), 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format(($sell_data->old_silver_fine + $total_silver_fine), 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format(($sell_data->old_amount + $total_amount), 2, '.', ''); ?></td>
            </tr>
        </table>
        <?php if(isset($isimage)){ ?>
            </div>
        <?php } ?>
        <?php if(isset($isimage)){ ?>
            <a id="btn-Convert-Html2Image" href="#" class="btn btn-primary hidden" style="" >Download</a>
            <script>
                var element = $("#html-content-holder"); // global variable
                var getCanvas; // global variable
                $(document).ready(function(){
                    html2canvas(element, {
                        onrendered: function (canvas) {
                           $("#previewImage").append(canvas);
                           getCanvas = canvas;
                        }
                    });
                    $("#btn-Convert-Html2Image").on('click', function () {
                        createImageData();
                    });
                    setTimeout(function () {
//                        $("#btn-Convert-Html2Image").click();
                        createImageData();
                    }, 1000);
                });
                function createImageData(){
                    var imgageData = getCanvas.toDataURL("image/png");
                    // Now browser starts downloading it instead of just showing it
                    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
//                    $("#btn-Convert-Html2Image").attr("download", "sell_print_<?php echo $sell_data->sell_no; ?>.png").attr("href", newData);
                    var a = $("<a>")
                        .attr("href", newData)
                        .attr("download", "sell_print_<?php echo $sell_data->sell_no; ?>.png")
                        .appendTo("body");
                    a[0].click();
                    a.remove();
                }
            </script>
        <?php } ?>
    </body>
</html>


