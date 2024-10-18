
<html>
    <head>
        <title>Order list Print</title>
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
                border: 0px solid black;
                border-collapse: collapse;
                font-weight: bolder;
                size: 10px;
            }
            th, td {
                text-align: left;
                line-height: 35px;
            }
            .no-border{
                border: none;
            }

        </style>
    </head>
    <body>

        <table>

            <tr>
                <td class="text-center" style="text-align: center;"><img src="<?php echo base_url(); ?><?= (isset($order_lot_item_data->image)) ? $order_lot_item_data->image : ''; ?>" alt="Image" class="text-center" style="width:auto; height:500px; margin: 0 auto; text-align: center;"/><br /></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Order No : <b><?php echo $order_lot_item_data->order_item_no; ?></b></td>
                <td>Pcs : <b><?php echo $order_lot_item_data->pcs; ?></b></td>
            </tr>
            <tr>
                <td>Order Date : <b><?php echo (isset($new_order_data->order_date))? date('d-m-Y', strtotime($new_order_data->order_date)) : ''; ?></b></td>
                <td>Size : <b><?php echo $order_lot_item_data->size; ?></b></td>
            </tr>
            <tr>
                <td>Design No :<b><?php echo $item_master_data->design_no; ?></b> </td>
                <td>Length : <b><?php echo $order_lot_item_data->length; ?></b></td>
            </tr>
            <tr>
                <td>Die No : <b><?php echo $item_master_data->die_no; ?></b></td>
                <td>Hook : <b><?php echo $order_lot_item_data->hook_style; ?></b></td>
            </tr>
            <tr>
                <td>Tunch : <b><?php echo $order_lot_item_data->tunch; ?></b></td>
                <td><b>Color :</b></td>
            </tr>
            <tr>
                <td>Weight : <b><?php echo $order_lot_item_data->weight; ?></b></td>
                <td>Delivery Date : <b><?php echo (isset($new_order_data->delivery_date))? date('d-m-Y', strtotime($new_order_data->delivery_date)) : ''; ?></b></td>
            </tr>
            <tr>
                <td>Remark : <b style="font-size: 25px; "><?php echo $order_lot_item_data->lot_remark; ?></b></td>
            </tr>
        </table>

    </body>
</html>


