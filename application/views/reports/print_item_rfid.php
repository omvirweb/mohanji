
<html>
    <head>
        <title>RFID</title>
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
            table tr td {
                padding-top: 0px;
                padding-bottom: 2px;
                padding-left: 5px;
                padding-right: 5px;
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
            
        </style>
    </head>
    <body>

        <table style="font-size: 10px; font-family: Arial Rounded MT Bold;" class="border" width="100%">
        <tr>
            <td>
                <b>
                    GW : <?php echo number_format($item_stock_rfid->rfid_grwt,3,'.','');?><br />
                    Less : <?php echo number_format($item_stock_rfid->rfid_less,3,'.','');?><br />
                    Add : <?php echo number_format($item_stock_rfid->rfid_add,3,'.','');?><br />
                    <?php if(!empty($item_stock_rfid->rfid_charges)) {  echo 'Charges : '.$item_stock_rfid->rfid_charges; } ?>
                </b>
            </td>
            <td>
                <b>
                    NW : <?php echo number_format($item_stock_rfid->rfid_ntwt,3,'.','');?><br />
                    <?php if(!empty($item_stock_rfid->rfid_size)) {  echo 'Size : '.$item_stock_rfid->rfid_size.'<br />'; } ?>
                    <?php echo 'RFID ID : '.$item_stock_rfid->item_stock_rfid_id; ?><br />
                    <?php if($use_barcode == '1'){ ?><img alt="testing" src="<?= base_url().'/barcode.php';?>?codetype=Code128&size=20&text=<?php echo $item_stock_rfid->item_stock_rfid_id; ?>" style="height: 10px;"/><?php } ?>
                </b>
            </td>
        </tr>
    </table>
    </body>
</html>
