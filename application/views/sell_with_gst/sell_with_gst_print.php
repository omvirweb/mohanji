<html>
    <head>
        <title>Sell/Purchase with GST Print</title>
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
            .border_none{
                border: none;
/*                border-radius: 25px !important;*/
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
/*            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                font-weight: bolder;
                size: 10px;
            }*/
            th, td {
                text-align: left;
                line-height: 25px;
            }
            .no-border{
                border: none;
            }
            .table1 tr td{
                background-color: #E5E5E5;
                padding: 15px 20px;
            }
            .lineitem_table thead tr th, .lineitem_table tfoot tr th{
                border-top: 2px solid #000000;
                border-bottom: 2px solid #000000;
                padding: 5px;
            }
            .lineitem_table tbody tr td{
                 line-height: 15px;
                 padding: 8px;
                 border-bottom: 2px solid #DFDFDF;
            }
            .footer_part_table tr td{
                line-height: 20px;
            }
        </style>
    </head>
    <body>
        <table class="no-border text-bold">
            <tr><td>TAX INVOICE</td></tr>
        </table>
        <br/>
        <table class="no-border text-bold" style="font-size: 34px;">
            <tr><td><?php echo $company_details->company_name; ?></td></tr>
        </table>
        <table class="no-border">
            <tr>
                <td><?php echo $company_details->company_address; ?>, <?php echo $city_name; ?>, PINCODE : <?php echo $company_details->company_postal_code; ?>, <?php echo $state_name; ?></td>
            </tr>
            <tr>
                <td><span class="text-bold">Mobile : </span><?php echo $company_details->company_mobile; ?> &nbsp; &nbsp; &nbsp; <span class="text-bold">GSTIN : </span> <?php echo $company_details->company_gst_no; ?></td>
            </tr>
        </table>
        <br/>
        <table class="no-border table1" style="border-top: 10px solid #000000;">
            <tr>
                <td align="left" class=""><span class="text-bold">Invoice Number </span><?php echo $sell_data->sell_no; ?></td>
                <td align="right" class=""><span class="text-bold">Invoice Date </span><?php echo date("d/m/Y",strtotime($sell_data->sell_date)); ?></td>
            </tr>
        </table>
        <table class="no-border" style="margin: 20px;">
            <tr class="">
                <td align="left" class="" style="width:30%;"><span class="text-bold">BILL TO</span></td>
                <td align="left" class="" style="width:40%;"></td>
                <td align="left" class="" style="width:30%;"><span class="text-bold">SHIP TO</span></td>
            </tr>
            <tr class="">
                <td align="left" class="" style="width:30%; line-height: 20px;" valign="top">
                    <span class="text-bold"><?php echo $account_data->account_name; ?></span><br/>
                    <?php echo $account_data->account_address; ?><br/>
                    <?php echo $account_city_name; ?>, PINCODE : <?php echo $account_data->account_postal_code; ?><br/>
                    GSTIN : <?php echo $account_data->account_gst_no; ?><br/>
                    Mobile NO. : <?php echo $account_data->account_mobile; ?><br/>
                    Place Of Supply : <?php echo $account_state_name; ?><br/>
                </td>
                <td align="left" class="" style="width:40%;">
                </td>
                <td align="left" class="" style="width:30%; line-height: 20px;" valign="top">
                    <span class="text-bold"><?php echo $sell_data->ship_to_name; ?></span><br/>
                    <?php echo $sell_data->ship_to_address; ?>
                </td>
            </tr>
        </table>
        <table class="lineitem_table no-border">
            <thead>
                <tr>
                    <th>ITEMS</th>
                    <th width="70">HSN</th>
                    <th class="text-right" width="100">QTY.</th>
                    <th class="text-right" width="100">RATE</th>
                    <th class="text-right" width="100">TAX</th>
                    <th class="text-right" width="100">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $total_grwt = 0;
                    $total_tax = 0;
                    $total_taxable_amount = 0;
                    $total_amount = 0;
                    if(!empty($sell_items_with_gst)){
                        foreach ($sell_items_with_gst as $sell_item_row){
                            $total_grwt = $total_grwt + $sell_item_row->grwt;
                            $total_tax = $total_tax + $sell_item_row->tax;
                            $taxable_amount = $sell_item_row->amount - $sell_item_row->tax;
                            $total_taxable_amount = $total_taxable_amount + $taxable_amount;
                            $total_amount = $total_amount + $sell_item_row->amount;
                ?>
                            <tr>
                                <td valign="top"><?php echo $sell_item_row->type_name . ' - ' . $sell_item_row->category_name . ' - ' . $sell_item_row->item_name; ?></td>
                                <td valign="top"><?php echo $sell_item_row->hsn_code; ?></td>
                                <td valign="top" class="text-right"><?php echo $sell_item_row->grwt; ?></td>
                                <td valign="top" class="text-right"><?php echo $sell_item_row->spi_rate; ?></td>
                                <td valign="top" class="text-right"><?php echo $sell_item_row->tax; ?><br><small style="color: #999999;">(<?php echo $sell_item_row->gst_rate; ?>%)</small></td>
                                <td valign="top" class="text-right"><?php echo $sell_item_row->amount; ?></td>
                            </tr>
                <?php
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                <tr><td style="padding: 50px;"></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr>
                    <th>SUB TOTAL</th>
                    <th class="text-right"></th>
                    <th class="text-right"><?php echo number_format($total_grwt, 3, '.', ''); ?></th>
                    <th></th>
                    <th class="text-right">₹<?php echo number_format($total_tax, 2, '.', ''); ?></th>
                    <th class="text-right">₹<?php echo number_format($total_amount, 2, '.', ''); ?></th>
                </tr>
            </tfoot>
        </table>
        <?php
            $company_state = substr($company_details->company_gst_no, 0, 2);
            $account_state = substr($account_data->account_gst_no, 0, 2);
            $grand_total = 0;
        ?>
        <table class="footer_part_table no-border">
            <tr>
                <td align="left" style="width:40%;" valign="top">
                    <span class="text-bold">BANK DETAILS</span>
                    <table class="no-border">
                        <tr>
                            <td>Name : </td>
                            <td><?php echo $account_data->bank_name; ?></td>
                        </tr>
                        <tr>
                            <td>IFSC Code : </td>
                            <td><?php echo $account_data->ifsc_code; ?></td>
                        </tr>
                        <tr>
                            <td>Account No. : </td>
                            <td><?php echo $account_data->bank_account_no; ?></td>
                        </tr>
                    </table>
                </td>
                <td align="left" class="" style="width:20%;">
                </td>
                <td align="left" style="width:40%;" valign="top">
                    <table class="no-border">
                        <tr>
                            <td class="text-right">TAXABLE AMOUNT </td>
                            <td class="text-right" width="120"><?php echo number_format($total_taxable_amount, 2, '.', ''); ?></td>
                        </tr>
                        <?php
                            $grand_total = $grand_total + $total_taxable_amount;
                            $grand_total = $grand_total + $total_tax;
                            $grand_total = $grand_total + $sell_data->tcs_amount;
                            $grand_total = number_format($grand_total, 2, '.', '');
                            if($company_state == $account_state){ 
                                $sc_gst = $total_tax / 2;
                        ?>
                            <tr>
                                <td class="text-right">SGST@1.5 </td>
                                <td class="text-right"><?php echo number_format($sc_gst, 2, '.', ''); ?></td>
                            </tr>
                            <tr>
                                <td class="text-right">CGST@1.5 </td>
                                <td class="text-right"><?php echo number_format($sc_gst, 2, '.', ''); ?></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td class="text-right">IGST@1.5 </td>
                                <td class="text-right"><?php echo number_format($total_tax, 2, '.', ''); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right" style="border-bottom: 2px solid #DFDFDF;">TCS@<?php echo $sell_data->tcs_per; ?></td>
                            <td class="text-right" style="border-bottom: 2px solid #DFDFDF;"><?php echo number_format($sell_data->tcs_amount, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right" style="border-bottom: 2px solid #DFDFDF;">GRAND TOTAL</th>
                            <th class="text-right" style="border-bottom: 2px solid #DFDFDF;"><?php echo $grand_total; ?></th>
                        </tr>
                        <?php /* <tr>
                            <td class="text-right" style="border-bottom: 2px solid #DFDFDF;">Received Amount</td>
                            <td class="text-right" style="border-bottom: 2px solid #DFDFDF;"><?php echo number_format(0, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right" style="border-bottom: 2px solid #DFDFDF;">Balance</th>
                            <th class="text-right" style="border-bottom: 2px solid #DFDFDF;"><?php echo number_format(0, 2, '.', ''); ?></th>
                        </tr> */ ?>
                        <tr>
                            <td colspan="2" class="text-right"><br>
                                <span class="text-bold">Invoice Amount (in words)</span><br>
                                <span><?php if($grand_total > 0){ echo money_to_word($grand_total); } else { $grand_total = abs($grand_total); echo 'Minus ' . money_to_word($grand_total); }; ?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
