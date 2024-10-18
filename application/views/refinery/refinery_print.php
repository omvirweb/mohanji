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
                    color: #243061;
                }
                table{
                    border-spacing: 0;
                    color: #243061;
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
                    border-right: solid 0.5px #243061;
                }
                table.table-item-detail > thead > tr > th:first-child{
                    border-left: solid 0.5px #243061;
                }
                table.table-item-detail > tbody > tr > th:last-child,table.table-item-detail > tbody > tr > td:last-child{
                    border-right: solid 0.5px #243061;
                }
                table.table-item-detail > tbody > tr > th:first-child,table.table-item-detail > tbody > tr > td:first-child{
                    border-left: solid 0.5px #243061;
                }
                table.table-item-detail > thead > tr > th{
                    border-top: solid 0.5px #243061;
                    border-bottom: solid 0.5px #243061;
                }
                table.table-item-detail > tbody > tr > th{
                    border-top: solid 0.5px #243061;
                    border-bottom: solid 0.5px #243061;
                }
            }
            table.table-item-detail > thead > tr > th:last-child{
                border-right: solid 0.5px #243061;
            }
            table.table-item-detail > thead > tr > th:first-child{
                border-left: solid 0.5px #243061;
            }
            table.table-item-detail > tbody > tr > th:last-child,table.table-item-detail > tbody > tr > td:last-child{
                border-right: solid 0.5px #243061;
            }
            table.table-item-detail > tbody > tr > th:first-child,table.table-item-detail > tbody > tr > td:first-child{
                border-left: solid 0.5px #243061;
            }
            table.table-item-detail > thead > tr > th{
                border-top: solid 0.5px #243061;
                border-bottom: solid 0.5px #243061;
            }
            table.table-item-detail > tbody > tr > th{
                border-top: solid 0.5px #243061;
                border-bottom: solid 0.5px #243061;
            }
            table{
                border-spacing: 0;
                color: #243061;
            }
            table tr td {
                /*padding-left: 5px;
                padding-right: 5px;*/
                padding-top: 3px;
                padding-bottom: 2px;
                padding-left: 1px;
                padding-right: 1px;
                color: #243061;
            }
            .border{
                border: 1px solid grey;
/*                border-radius: 25px !important;*/
            }
            .border2{
                border: 2.3px solid grey;
/*                border-radius: 25px !important;*/
            }
            .border-right{
                border-right: none;
            }
            .border-left{
                border-left: none;
            }
            .line-hight {
                line-height: 2;
            }
            <?php if(isset($isimage)){ ?>
                table {
                    width:100%;
                }
            <?php } else { ?>
                table {
                    width:100%;
                    margin-left: 2%;
                    color: #243061;
                }
            <?php } ?>
            table, th, td {
                border: 1px solid #243061;
                border-collapse: collapse;
                font-weight: bolder;
                color: #243061;
                size: 10px;
            }
            th, td {
                text-align: left;
                line-height: 25px;
                color: #243061;
            }
            .no-border{
                border: none;
            }

        </style>
    </head>
    <body>
        <table class="border2" style="">
            <tr>
                <td class="no-border" style="line-height: 20px;">&nbsp; &nbsp;GST No. : <?php echo $company_data->company_gst_no; ?></td>
                <td class="no-border" style="line-height: 20px;" align="right">Cell No. : <?php echo $company_data->company_mobile; ?>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="line-height: 20px;">&nbsp; &nbsp;CIN : <?php echo $company_data->company_cin; ?></td>
                <td class="no-border" style="line-height: 20px;">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="line-height: 20px;">&nbsp; &nbsp;Regn. No. : <?php echo $company_data->company_reg_no; ?></td>
                <td class="no-border" style="line-height: 20px;">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" colspan="2" align="center" style="font-size:30px; font-weight: bold;"><?php echo $company_data->company_name; ?></td>
            </tr>
            <tr>
                <td class="no-border" colspan="2" align="center" style="font-size:12px; font-weight: bold;"><?php echo nl2br($company_data->company_address); ?></td>
            </tr>
        </table>
        <table class="border2" style="">
            <tr>
                <td class="no-border" style="line-height: 35px;">&nbsp; &nbsp;Invoice No. : <?php echo $entry_data->invoice_no; ?></td>
                <td class="no-border" style="line-height: 35px;" align="right">Date : <?php echo date('d-m-Y', strtotime($entry_data->entry_date)); ?>&nbsp; &nbsp;</td>
            </tr>
        </table>
        <table class="border2" style="">
            <tr>
                <td class="no-border" style="">&nbsp; &nbsp;Billing Details To : <?php echo $entry_data->account_name; ?></td>
            </tr>
            <tr>
                <td class="no-border" style="">&nbsp; &nbsp;Party's GST No. : <?php echo $entry_data->account_gst; ?></td>
            </tr>
            <tr>
                <td class="no-border" style="">&nbsp; &nbsp;Address : <?php echo nl2br($entry_data->account_address); ?></td>
            </tr>
        </table>
        <table class="border2" style="">
            <tr>
                <td class="no-border" colspan="6" style="">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" colspan="6" style="">&nbsp; &nbsp;Job Work Details Melting & Refines</td>
            </tr>
            <tr>
                <td class="no-border" style="width:12%" align='left'>&nbsp; &nbsp;Receiving</td>
                <td class="no-border" style="width:40%" align='left'>&nbsp; &nbsp;HSN & SAC Code No</td>
                <td class="border"    style="width:15%" align='center'><?php echo $entry_data->r_hsn_sac_code; ?></td>
                <td class="border"    style="width:15%" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="width:15%" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="width:3%" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Old & Scrap Gold Jewels Weight</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->r_old_jewels_weight, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Stones and Dust Weights Loss</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->r_stones_dust_weights_loss, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Before Melting Weight</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->r_before_melting_weight, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;After Melting Weight</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->r_after_melting_weight, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Testing Purity %</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->r_testing_purity_per, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Net Fine Gold Wt. 99.9%</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->r_net_fine_gold, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Delivery</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;HSN & SAC Code No</td>
                <td class="border"    style="" align='center' valign='middle'><?php echo $entry_data->d_hsn_sac_code; ?></td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Given Fine Gold Purity 99.90</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_given_fine_gold_purity, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Melting Charges Per Gram</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_melting_charges_weight, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_melting_charges_per_gram, 2, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_melting_charges_total, 2, '.',  ''); ?>&nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Refining Charges Per Gram</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_refining_charges_weight, 3, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_refining_charges_per_gram, 2, '.',  ''); ?>&nbsp;</td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->d_refining_charges_total, 2, '.',  ''); ?>&nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Total Amount</td>
                <td class="border"    style="" align='right' valign='middle'></td>
                <td class="border"    style="" align='right' valign='middle'></td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->sub_total, 2, '.',  ''); ?>&nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Job Work</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;GST</td>
                <td class="border"    style="" align='right' valign='middle'></td>
                <td class="border"    style="" align='right' valign='middle'></td>
                <td class="border"    style="" align='right' valign='middle'>&nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <?php if(!empty($entry_data->sgst)){ ?>
                <tr>
                    <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                    <td class="no-border" style="" align='left'>&nbsp; &nbsp;SGST @ <?php echo number_format($entry_data->sgst_per, 2, '.',  ''); ?>&nbsp;</td>
                    <td class="border"    style="" align='right' valign='middle'></td>
                    <td class="border"    style="" align='right' valign='middle'></td>
                    <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->sgst, 2, '.',  ''); ?>&nbsp;</td>
                    <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
                </tr>
            <?php } ?>
            <?php if(!empty($entry_data->cgst)){ ?>
                <tr>
                    <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                    <td class="no-border" style="" align='left'>&nbsp; &nbsp;CGST @ <?php echo number_format($entry_data->cgst_per, 2, '.',  ''); ?>&nbsp;</td>
                    <td class="border"    style="" align='right' valign='middle'></td>
                    <td class="border"    style="" align='right' valign='middle'></td>
                    <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->cgst, 2, '.',  ''); ?>&nbsp;</td>
                    <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
                </tr>
            <?php } ?>
            <?php if(!empty($entry_data->igst)){ ?>
                <tr>
                    <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                    <td class="no-border" style="" align='left'>&nbsp; &nbsp;IGST @ <?php echo number_format($entry_data->igst_per, 2, '.',  ''); ?>&nbsp;</td>
                    <td class="border"    style="" align='right' valign='middle'></td>
                    <td class="border"    style="" align='right' valign='middle'></td>
                    <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->igst, 2, '.',  ''); ?>&nbsp;</td>
                    <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
                </tr>
            <?php } ?>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Total Amount</td>
                <td class="border"    style="" align='right' valign='middle'></td>
                <td class="border"    style="" align='right' valign='middle'></td>
                <td class="border"    style="" align='right' valign='middle'><?php echo number_format($entry_data->total_amount, 2, '.',  ''); ?>&nbsp;</td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Payment Type, CASH</td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;RTGS-NEFT,IMPS,FUND Transfer,DD</td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;</td>
                <td class="no-border" style="" align='left'>&nbsp; &nbsp;Reference No.</td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border"    style="" align='right' valign='middle'></td>
                <td class="no-border" style="" align='right'>&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" colspan="6" style="">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" colspan="6" style="">&nbsp; &nbsp;</td>
            </tr>
            <tr>
                <td class="no-border" colspan="6" style="">&nbsp; &nbsp;</td>
            </tr>
        </table>
    </body>
</html>


