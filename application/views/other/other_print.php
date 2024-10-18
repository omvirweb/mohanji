
<html>
    <head>
        <title>Other Sell/Purchase Print</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
                    <h5>Invoice</h5>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <h4><?php echo $company_name; ?></h4>
                    Contact : <?php echo $company_contact; ?><br>
                    <?php echo $company_address; ?>
                </td>
            </tr>
        </table>
        <table class="border">
            <tr>
                <td>Buyer :</td>
                <td><?php echo $other_data->account_name; ?></td>
                <td>Date :</td>
                <td><?php echo !empty($other_data->other_date) ? date('d-M-Y', strtotime($other_data->other_date)) : ''; ?></td>
            </tr>
            <tr>
                <td>Remark :</td>
                <td colspan="3"><?php echo $other_data->other_remark; ?></td>
            </tr>
        </table>
        <table class="border">
            <tr>
                <td width="40">Sr.No.</td>
                <td width="<?php if(PACKAGE_FOR != 'mohanji') { ?> 240 <?php } else { ?> 240 <?php } ?>">Category</td>
                <td class="text-right" width="100">Gr.Wt.</td>
                <td class="text-right" width="100">Rate</td>
                <td class="text-right" width="100">Amount</td>
            </tr>
            <?php 
                $row_inc = 1;
                $total_grwt = 0;
                $total_amount = 0;
            ?>
            <?php 
                if(!empty($other_items)){
                    foreach ($other_items as $other_item_row){
                        $total_grwt = $total_grwt + $other_item_row->grwt;
                        $total_amount = $total_amount + $other_item_row->amount;
            ?>
                        <tr>
                            <td><?php echo $row_inc; ?></td>
                            <td><?php echo $other_item_row->type_name . ' - ' . $other_item_row->category_name . ' - ' . $other_item_row->item_name; ?></td>
                            <td class="text-right"><?php echo $other_item_row->grwt; ?></td>
                            <td class="text-right"><?php echo $other_item_row->rate; ?></td>
                            <td class="text-right"><?php echo $other_item_row->amount; ?></td>
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
                <th></th>
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
                            <td></td>
                            <td class="text-right"><?php echo $pay_rec_data_row->amount; ?></td>
                        </tr>
            <?php
                        $row_inc++;
                    }
                }
            ?>
            <?php /* if($ask_discount_in_sell_purchase == '1') { ?>
            <tr>
                <?php if(PACKAGE_FOR != 'mohanji') { ?>
                    <td class="text-right" colspan="7" >Discount :</td>
                <?php } else { ?>
                    <td class="text-right" colspan="5" >Discount :</td>
                <?php } ?>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right">
                    <?php
                        echo number_format($other_data->discount_amount, 2, '.', '');
                        $total_amount = $total_amount - $other_data->discount_amount;
                    ?>
                </td>
            </tr>
            <?php } */ ?>
            <tr>
                <?php if(PACKAGE_FOR != 'mohanji') { ?>
                    <td class="text-right" colspan="4" >Bill Balance :</td>
                <?php } else { ?>
                    <td class="text-right" colspan="4" >Bill Balance :</td>
                <?php } ?>
                <td class="text-right"><?php echo number_format($total_amount, 2, '.', ''); ?></td>
            </tr>
            <tr>
                <?php if(PACKAGE_FOR != 'mohanji') { ?>
                    <td class="text-right" colspan="4" >Old Balance :</td>
                <?php } else { ?>
                    <td class="text-right" colspan="4" >Old Balance :</td>
                <?php } ?>
                <td class="text-right"><?php echo number_format($other_data->old_amount, 2, '.', ''); ?></td>
            </tr>
            <tr>
                <?php if(PACKAGE_FOR != 'mohanji') { ?>
                    <td class="text-right" colspan="4" >Net Balance :</td>
                <?php } else { ?>
                    <td class="text-right" colspan="4" >Net Balance :</td>
                <?php } ?>
                <td class="text-right"><?php echo number_format(($other_data->old_amount + $total_amount), 2, '.', ''); ?></td>
            </tr>
        </table>
    </body>
</html>


