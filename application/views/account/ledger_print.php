<html>
    <head>
        <title>Ledger</title>
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
<!--
            table tr td {
                /*padding-left: 5px;
                padding-right: 5px;*/
                padding-top: 0px;
                padding-bottom: 2px;
                padding-left: 5px;
                padding-right: 5px;
            }
-->
            .border{
                border: 1px solid grey;
                border-radius: 25px !important;
            }
            .border-right{
                border-right: none;
            }
            .border-top{
                border-top: none;
            }
            .border-bottom{
                border-bottom: none;
            }
            .border-left{
                border-left: none;
            }            
            .line-hight {
                line-height: 3;
            }
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 5px;
                text-align: left;    
            }
        </style>
    </head>
    <body>
        <h2 align="center"><u>Ledger</u></h2>
        <h3 align="center">Name : <?php echo $account_name; ?><br />  From Date : <?php echo $from_date; ?>  To Date : <?php echo $to_date; ?></h3>
        <?php if(!empty($sell_array)){
            $opening_gold = (isset($opening->total_gold) && !empty($opening->total_gold))? $opening->total_gold : 0;
            $opening_silver = (isset($opening->total_silver) && !empty($opening->total_silver))? $opening->total_silver : 0;
            $opening_amount = (isset($opening->total_amount) && !empty($opening->total_amount))? $opening->total_amount : 0;
            ?>
                <table style="width:100%">
                    <tr>
                        <th width="5%" class="border-right border-left border-top">Type </th>
                        <th width="5%" class="border-right border-left border-top">Particulars</th>
                        <th width="5%" class="border-right border-left border-top">Gr.Wt.</th>
                        <th width="5%" class="border-right border-left border-top">Less</th>
                        <th width="5%" class="border-right border-left border-top">Net.Wt</th>
                        <th width="5%" class="border-right border-left border-top">Tunch</th>
                        <th width="5%" class="border-right border-left border-top">Rate</th>
                        <th width="10%" class="border-right border-left border-top">Gold</th>
                        <th width="10%" class="border-right border-left border-top">Silver</th>
                        <th width="10%" class="border-right border-left border-top">Amount</th>
                    </tr>
                    <tr>
                        <td class="border-right border-left border-bottom border-top"></td>
                        <td class="border-right border-left border-bottom border-top">OPENING BALANCE</td>
                        <td class="border-right border-left border-bottom border-top"></td>
                        <td class="border-right border-left border-bottom border-top"></td>
                        <td class="border-right border-left border-bottom border-top"></td>
                        <td class="border-right border-left border-bottom border-top"></td>
                        <td class="border-right border-left border-bottom border-top"></td>
                        <td class="border-right border-left border-bottom border-top text-right"><?php echo $opening_gold; ?></td>
                        <td class="border-right border-left border-bottom border-top text-right"><?php echo $opening_silver; ?></td>
                        <td class="border-right border-left border-bottom border-top text-right"><?php echo $opening_amount; ?></td>
                    </tr>
                </table>
                <?php 
                $i = 0;
                $len = count($sell_array);
                foreach($sell_array as $sell){ $gold_fine = 0;
                $silver_fine = 0; 
                $pay_rec_amount = 0;
                $metal_gold_fine = 0; 
                $metal_silver_fine = 0;
                $gold_bhav_fine = 0;
                $gold_amount = 0;
                $silver_bhav_fine = 0;
                $silver_amount = 0;
                $tran_gold_fine = 0;
                $tran_silver_fine = 0;
                $tran_amount = 0;
                $final_gold_fine = 0;
                $final_silver_fine = 0;
                $final_amount = 0; 
                $setting_rate = $this->crud->get_all_records('settings', '', '');
                $sell_items = $this->crud->get_sell_items($sell->sell_id);
                $payment_recipt = $this->crud->get_payment_receipt($sell->sell_id);
                $metal_pay = $this->crud->get_metal_payment_receipt($sell->sell_id);
                $setting_rate = $this->crud->get_all_records('settings', '', '');
                $gold_bhav = $this->crud->get_gold_bhav($sell->sell_id);
                $silver_bhav = $this->crud->get_silver_bhav($sell->sell_id);
                $transaction = $this->crud->get_transaction($sell->sell_id); ?>
                <?php if(!empty($sell_items) || !empty($payment_recipt) || !empty($metal_pay) || !empty($gold_bhav) || !empty($silver_bhav) || !empty($transaction)) { ?>
                    <table style="width:100%">
                    <?php if(!empty($sell_items)){ ?>
                        <?php foreach($sell_items as $sell_item){ ?>
                            <tr>
                                <td width="5%" class="border-right border-left border-bottom"><?php echo $sell_item->type_name; ?></td>
                                <td width="5%" class="border-right border-left border-bottom"><?php echo $sell_item->category_name.' - '.$sell_item->item_name; ?></td>
                                <td width="5%" class="border-right border-left border-bottom text-right"><?php echo number_format($sell_item->grwt, 3, '.', ''); ?></td>
                                <td width="5%" class="border-right border-left border-bottom text-right"><?php echo number_format($sell_item->less, 3, '.', ''); ?></td>
                                <td width="5%" class="border-right border-left border-bottom text-right"><?php echo number_format($sell_item->net_wt, 3, '.', ''); ?></td>
                                <td width="5%" class="border-right border-left border-bottom text-right"><?php echo number_format($sell_item->touch_id, 3, '.', ''); ?></td>
                                <td width="5%" class="border-right border-left border-bottom text-right"><?php echo (!empty($sell_item->gold_fine)) ? $setting_rate[0]->settings_value :  $setting_rate[1]->settings_value; ?></td>
                                <td width="10%" class="border-right border-left border-bottom text-right"><?php echo (!empty($sell_item->gold_fine) ? number_format($sell_item->gold_fine, 3, '.', '') : ''); ?></td>
                                <td width="10%" class="border-right border-left border-bottom text-right"><?php echo (!empty($sell_item->silver_fine) ? number_format($sell_item->silver_fine, 3, '.', '') : '');  ?></td>
                                <td width="10%" class="border-right border-left border-bottom text-right"></td>
                            </tr>
                            <?php $gold_fine += !empty($sell_item->gold_fine) ? $sell_item->gold_fine : 0;
                            $silver_fine += !empty($sell_item->silver_fine) ? $sell_item->silver_fine : 0;
                         } ?>
                    <?php } ?>

                    <?php if(!empty($payment_recipt)){ ?>
                        <?php foreach($payment_recipt as $pay_rec){ ?>
                            <tr>
                                    <td class="border-right border-left border-bottom"><?php echo 'Payment Receipt'; ?></td>
                                    <td class="border-right border-left border-bottom"><?php echo $pay_rec->pay_rec.' @ '.$pay_rec->cash_cheque; ?></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"></td>
                                    <td class="border-right border-left border-bottom text-right"><?php echo $pay_rec->amount; ?></td>
                            </tr>
                            <?php $pay_rec_amount += !empty($pay_rec->amount) ? $pay_rec->amount : 0;
                        } ?>
                    <?php } ?>

                    <?php if(!empty($metal_pay)){ ?>
                        <?php foreach($metal_pay as $metal){ ?>
                            <tr>
                                <td class="border-right border-left border-bottom"><?php echo 'Metal Issue Receive'; ?></td>
                                <td class="border-right border-left border-bottom"><?php echo $metal->pay_rec.' @ '.$metal->item_name; ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo number_format($metal->metal_grwt, 3, '.', ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo number_format($metal->less, 3, '.', ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo number_format($metal->ntwt, 3, '.', ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo number_format($metal->metal_tunch, 3, '.', ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($metal->gold_fine)) ? $setting_rate[0]->settings_value :  $setting_rate[1]->settings_value; ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($metal->gold_fine) ? number_format($metal->gold_fine, 3, '.', '') : ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($metal->silver_fine) ? number_format($metal->silver_fine, 3, '.', '') : '');  ?></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                            </tr>
                        <?php $metal_gold_fine += !empty($metal->gold_fine) ? $metal->gold_fine : 0;
                        $metal_silver_fine += !empty($metal->silver_fine) ? $metal->silver_fine : 0;
                        } ?>
                    <?php } ?>

                    <?php if(!empty($gold_bhav)){ ?>
                        <?php foreach($gold_bhav as $gold){ ?>
                            <tr>
                                <td class="border-right border-left border-bottom"><?php echo 'Gold Bhav'; ?></td>
                                <td class="border-right border-left border-bottom"><?php echo $gold->sell_purchase.' @ '.$gold->gold_rate; ?></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo $setting_rate[0]->settings_value; ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($gold->gold_weight) ? number_format($gold->gold_weight, 3, '.', '') : ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo $gold->gold_value; ?></td>
                            </tr>
                        <?php $gold_bhav_fine += !empty($gold->gold_weight) ? $gold->gold_weight : 0;
                                $gold_amount += !empty($gold->gold_value) ? $gold->gold_value : 0;
                        } ?>
                    <?php } ?>

                    <?php if(!empty($silver_bhav)){ ?>
                        <?php foreach($silver_bhav as $silver){ ?>
                            <tr>
                                <td class="border-right border-left border-bottom"><?php echo 'Silver Bhav'; ?></td>
                                <td class="border-right border-left border-bottom"><?php echo $silver->sell_purchase.' @ '.$silver->silver_rate; ?></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo $setting_rate[0]->settings_value; ?></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($silver->silver_weight) ? number_format($silver->silver_weight, 3, '.', '') : ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo $silver->silver_value ?></td>
                            </tr>
                            <?php $silver_bhav_fine += !empty($silver->silver_weight) ? $silver->silver_weight : 0;
                            $silver_amount += !empty($silver->silver_value) ? $silver->silver_value : 0;
                         } ?>
                    <?php } ?>

                    <?php if(!empty($transaction)){ ?>
                        <?php foreach($transaction as $trans){ ?>
                            <tr>
                                <td class="border-right border-left border-bottom"><?php echo 'Transaction'; ?></td>
                                <td class="border-right border-left border-bottom"><?php echo $trans->naam_jama.' @ '.$trans->account_name; ?></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo $setting_rate[0]->settings_value; ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($trans->gold_fine) ? number_format($trans->gold_fine, 3, '.', '') : ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo (!empty($trans->silver_fine) ? number_format($trans->silver_fine, 3, '.', '') : ''); ?></td>
                                <td class="border-right border-left border-bottom text-right"><?php echo $trans->amount ?></td>
                            </tr>
                        <?php $tran_gold_fine += !empty($trans->gold_fine) ? $trans->gold_fine : 0;
                        $tran_silver_fine += !empty($trans->silver_fine) ? $trans->silver_fine : 0;
                        $tran_amount += $trans->amount;
                        } 
                    } 
                    $total_gold_fine = $gold_fine + $metal_gold_fine + $gold_bhav_fine + $tran_gold_fine;
                    $total_silver_fine = $silver_fine + $metal_silver_fine + $silver_bhav_fine + $tran_silver_fine;
                    $total_amount = $pay_rec_amount + $gold_amount + $silver_amount + $tran_amount; ?>
                    <?php if(!empty($sell_items) || !empty($payment_recipt) || !empty($metal_pay) || !empty($gold_bhav) || !empty($silver_bhav) || !empty($transaction)) { ?>
                        <tr>
                            <th class="border-right border-left border-bottom">Sno-<?php echo $sell->sell_no; ?></th>
                            <th class="border-right border-left border-bottom"></th>
                            <th width="30%" colspan="5" class="border-right border-left border-bottom">Balance as on <?php echo date('d-m-Y', strtotime($sell->sell_date)); ?></th>
                            <th class="border-right border-left border-bottom text-right"><?php echo number_format($total_gold_fine, 3, '.', ''); ?></th>
                            <th class="border-right border-left border-bottom text-right"><?php echo number_format($total_silver_fine, 3, '.', ''); ?></th>
                            <th class="border-right border-left border-bottom text-right"><?php echo $total_amount; ?></th>
                        </tr>
                    <?php } ?>
                    </table>
                    <?php if ($i == $len - 1) { } else { ?>
                        <br /><br />
                    <?php } ?>
					
                <?php } ?>
                        <?php
                        $final_gold_fine = $final_gold_fine + $total_gold_fine;
                        $final_silver_fine = $final_silver_fine + $total_silver_fine;
                        $final_amount = $final_amount + $total_amount; ?>
			<?php $i++; } 
                        $closing_gold = $final_gold_fine + $opening_gold;
                        $closing_silver = $final_silver_fine + $opening_silver;
                        $closing_amount = $final_amount + $opening_amount;
                        ?>
                        <table style="width:100%">
                            <tr>
                                <td width="5%" class="border-right border-left border-bottom border-top"></td>
                                <td width="35%" colspan="6" class="border-right border-left border-bottom border-top">CLOSING BALANCE</td>
                                <td width="10%" class="border-right border-left border-bottom border-top text-right"><?php echo $closing_gold; ?></td>
                                <td width="10%" class="border-right border-left border-bottom border-top text-right"><?php echo $closing_silver; ?></td>
                                <td width="10%" class="border-right border-left border-bottom border-top text-right"><?php echo $closing_amount; ?></td>
                            </tr>
                        </table>
			<?php } else { ?>
                No Records Found!
            <?php } ?>
    </body>
</html>

