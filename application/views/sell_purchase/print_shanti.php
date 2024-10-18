
<html>
    <head>
        <title>Sell/Purchase Print</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <script src="<?= base_url('assets/plugins/jQuery/jquery.min.js'); ?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
        <style>
            @media print {
                body { 
                    font-family: arial-bold; 
                    font-size: 10pt; 
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
                border-radius: 25px !important;
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
            table {
                width:100%;
                margin-left: 2%;


            }
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
        <table class="border">
            <tr>
                <td style="text-align: center;">
                    <h5><img src="<?php echo base_url(); ?>assets/dist/img/shree.jpg" style="width:20px;" alt="Shree" title="Shree" ></h5>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <h4><?php echo $company_name; ?></h4>
                    <?php echo $company_address; ?>, Contact : <?php echo $company_contact; ?>
                </td>
            </tr>
        </table>
        <table class="border">
            <tr>
                <td>Bill No. :</td>
                <td><?php echo 'SJ/' . $sell_data->bill_financial_year . '/' . $sell_data->process_name . '/' . $sell_data->sell_no; ?></td>
                <td>Date :</td>
                <td><?php echo !empty($sell_data->sell_date) ? date('d-M-Y', strtotime($sell_data->sell_date)) : ''; ?></td>
            </tr>
            <tr>
                <td>Buyer :</td>
                <?php if($remark_2 == '1') { ?>
                    <td><?php echo $sell_data->account_name; ?></td>
                    <td>Remark :</td>
                    <td><?php echo $sell_data->sell_remark; ?></td>
                <?php } else { ?>
                    <td colspan="3"><?php echo $sell_data->account_name; ?></td>
                <?php } ?>
            </tr>
        </table>
        <table class="border">
            <tr>
                <td width="40">Sr</td>
                <td width="200">Description</td>
                <td class="text-right">Gr.Wt.</td>
                <?php if($less_netwt_2 == '1') { ?>
                    <td class="text-right">Less</td>
                    <td class="text-right">Tunch</td>
                    <td class="text-right">Net Wt.</td>
                <?php } ?>
                <th class="text-right" width="">Rate</th>
                <th class="text-right">G/S Amt.</th>
                <th class="text-right">Charges</th>
                <td class="text-right" width="70">Gold</td>
                <td class="text-right" width="70">Silver</td>
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
                if(!empty($sell_lineitems)){
                    foreach ($sell_lineitems as $sell_item_row){
                        $total_grwt = $total_grwt + $sell_item_row->grwt;
                        $total_less = $total_less + $sell_item_row->less;
                        $total_net_wt = $total_net_wt + $sell_item_row->net_wt;
                        $gold_fine = '';
                        $silver_fine = '';
                        if(empty($sell_item_row->spi_rate) || empty($sell_item_row->spi_rate)){
                            if($sell_item_row->group_name == CATEGORY_GROUP_GOLD_ID){
                                $gold_fine = $sell_item_row->fine;
                                $total_gold_fine = $total_gold_fine + $gold_fine;
                            }
                            if($sell_item_row->group_name == CATEGORY_GROUP_SILVER_ID){
                                $silver_fine = $sell_item_row->fine;
                                $total_silver_fine = $total_silver_fine + $silver_fine;
                            }
                        }
                        $total_amount = $total_amount + $sell_item_row->amount;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo $sell_item_row->type_name . ' - ' . $sell_item_row->category_name . ' - ' . $sell_item_row->item_name; ?></td>
                            <td class="text-right"><?php echo $sell_item_row->grwt; ?></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td class="text-right"><?php echo $sell_item_row->less; ?></td>
                                <td class="text-right"><?php echo $sell_item_row->touch_id; ?></td>
                                <td class="text-right"><?php echo $sell_item_row->net_wt; ?></td>
                            <?php } ?>
                            <td class="text-right"><?php echo $sell_item_row->rate_per_1_gram; ?></td>
                            <td class="text-right"><?php echo $sell_item_row->gross_amount; ?></td>
                            <td class="text-right"><?php echo $sell_item_row->labout_other_charges; ?></td>
                            <td class="text-right"><?php echo ($sell_item_row->group_name == CATEGORY_GROUP_GOLD_ID) ? $gold_fine : ''; ?></td>
                            <td class="text-right"><?php echo ($sell_item_row->group_name == CATEGORY_GROUP_SILVER_ID) ? $silver_fine : ''; ?></td>
                            <td class="text-right"><?php echo $sell_item_row->amount; ?></td>
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
                                <td></td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                    <th class="text-right"></th>
                    <th class="text-right"><?php echo number_format($total_net_wt, 3, '.', ''); ?></th>
                <?php } ?>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right"><?php echo number_format($total_gold_fine, 3, '.', ''); ?></th>
                <th class="text-right"><?php echo number_format($total_silver_fine, 3, '.', ''); ?></th>
                <th class="text-right"><?php echo number_format($total_amount, 2, '.', ''); ?></th>
            </tr>

            <?php 
                if(!empty($pay_rec_data)){
                    foreach ($pay_rec_data as $pay_rec_data_row){
                        if($pay_rec_data_row->payment_receipt == '1'){
                            $payment_receipt_name = 'Payment';
                        } else { 
                            $payment_receipt_name = 'Receipt';
                            $pay_rec_data_row->amount = ZERO_VALUE - $pay_rec_data_row->amount;
                        }
                        $total_amount = $total_amount + $pay_rec_data_row->amount;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo $payment_receipt_name . ' - ' . $pay_rec_data_row->cash_cheque . $pay_rec_data_row->bank_name . ' - ' . $pay_rec_data_row->narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                        if($metal_data_row->metal_payment_receipt == '1'){
                            $metal_payment_receipt_name = 'Metal Issue';
                        } else if($metal_data_row->metal_payment_receipt == '2'){
                            $metal_payment_receipt_name = 'Metal Receive';
                            $metal_data_row->metal_grwt = ZERO_VALUE - $metal_data_row->metal_grwt;
                            $metal_data_row->metal_tunch = ZERO_VALUE - $metal_data_row->metal_tunch;
                            $metal_data_row->metal_fine =  ZERO_VALUE - $metal_data_row->metal_fine;
                        }
                        if($metal_data_row->group_name == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $metal_data_row->metal_fine;
                            $total_gold_fine = $total_gold_fine + $gold_fine;
                        }
                        if($metal_data_row->group_name == CATEGORY_GROUP_SILVER_ID){
                            $silver_fine = $metal_data_row->metal_fine;
                            $total_silver_fine = $total_silver_fine + $silver_fine;
                        }
                        $total_grwt = $total_grwt + $metal_data_row->metal_grwt;
                        $total_net_wt = $total_net_wt + $metal_data_row->metal_grwt;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo $metal_payment_receipt_name . ' - ' . $metal_data_row->metal_item_name . ' - ' . $metal_data_row->metal_narration; ?></td>
                            <td class="text-right"><?php echo $metal_data_row->metal_grwt; ?></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td class="text-right"><?php echo $metal_data_row->metal_tunch; ?></td>
                                <td class="text-right"><?php echo $metal_data_row->metal_fine; ?></td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><?php echo ($metal_data_row->group_name == CATEGORY_GROUP_GOLD_ID) ? $gold_fine : ''; ?></td>
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
                        if($gold_data_row->gold_sale_purchase == '1') {
                            $gold_sale_purchase_name = 'Sell';
                            $gold_data_row->gold_weight = ZERO_VALUE - $gold_data_row->gold_weight;
                        } else if($gold_data_row->gold_sale_purchase == '2'){
                            $gold_sale_purchase_name = 'Purchase';
                            $gold_data_row->gold_value = ZERO_VALUE - $gold_data_row->gold_value;
                        }
                        $gold_fine = $gold_data_row->gold_weight;
                        $total_gold_fine = $total_gold_fine + $gold_fine;
                        $total_amount = $total_amount + $gold_data_row->gold_value;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo 'Gold Bhav - ' . $gold_sale_purchase_name . ' @ ' . $gold_data_row->gold_rate . ' - ' . $gold_data_row->gold_narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><?php echo $gold_fine; ?></td>
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
                        if($silver_data_row->silver_sale_purchase == '1') {
                            $silver_sale_purchase_name = 'Sell';
                            $silver_data_row->silver_weight = ZERO_VALUE - $silver_data_row->silver_weight;
                        } else if($silver_data_row->silver_sale_purchase == '2'){
                            $silver_sale_purchase_name = 'Purchase';
                            $silver_data_row->silver_value = ZERO_VALUE - $silver_data_row->silver_value;
                        }
                        $silver_fine = $silver_data_row->silver_weight;
                        $total_silver_fine = $total_silver_fine + $silver_fine;
                        $total_amount = $total_amount + $silver_data_row->silver_value;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo 'Silver Bhav - ' . $silver_sale_purchase_name . ' @ ' . $silver_data_row->silver_rate . ' - ' . $silver_data_row->silver_narration; ?></td>
                            <td></td>
                            <?php if($less_netwt_2 == '1') { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><?php echo $silver_fine; ?></td>
                            <td class="text-right"><?php echo $silver_data_row->silver_value; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>
            
            <?php 
                $colspan = '9';
                if($less_netwt_2 == '1') {} else {
                    $colspan = $colspan - 2;
                }
                if($wstg_2 == '1') {} else {
                    $colspan = $colspan - 1;
                }
            ?>
            <tr>
                <td class="text-right" colspan="<?php echo $colspan; ?>" >Bill Balance :</td>
                <td class="text-right"><?php echo number_format($total_gold_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($total_silver_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($total_amount, 2, '.', ''); ?></td>
            </tr>
            <tr>
                <td class="text-right" colspan="<?php echo $colspan; ?>" >Old Balance :</td>
                <td class="text-right"><?php echo number_format($sell_data->old_gold_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($sell_data->old_silver_fine, 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format($sell_data->old_amount, 2, '.', ''); ?></td>
            </tr>
            <tr>
                <td class="text-right" colspan="<?php echo $colspan; ?>" >Net Balance :</td>
                <td class="text-right"><?php echo number_format(($sell_data->old_gold_fine + $total_gold_fine), 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format(($sell_data->old_silver_fine + $total_silver_fine), 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format(($sell_data->old_amount + $total_amount), 2, '.', ''); ?></td>
            </tr>
            <?php if($ask_discount_in_sell_purchase == '1') { ?>
                <tr>
                    <td class="text-right" colspan="<?php echo $colspan; ?>" >Discount :</td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right">
                        <?php
                            echo number_format($sell_data->discount_amount, 2, '.', '');
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td class="text-right" colspan="<?php echo $colspan; ?>" >New Net Balance :</td>
                <td class="text-right"><?php echo number_format(($sell_data->old_gold_fine + $total_gold_fine), 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format(($sell_data->old_silver_fine + $total_silver_fine), 3, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format(($sell_data->old_amount + $total_amount - $sell_data->discount_amount), 2, '.', ''); ?></td>
            </tr>
        </table>
    </body>
</html>


