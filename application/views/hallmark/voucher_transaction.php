<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="" method="post" id="save_order" novalidate enctype="multipart/form-data">
     
        <section class="content-header">
            <h1>
                Voucher Transaction             
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="receipt_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Party Name</th>
                                                        <th>Account Type</th>
                                                        <th>Reference</th>
                                                        <th>Voucher No</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <label for="opening_bal">Opening Bal</label>
                                            <input type="text" name="opening_bal" id="opening_bal" class="form-control">
                                            <br/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="receipt">Receipt</label>
                                            <input type="text" name="receipt" id="receipt" class="form-control">
                                            <br/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="payment">Payment</label>
                                            <input type="text" name="payment" id="payment" class="form-control">
                                            <br/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="net_bal">Net Bal</label>
                                            <input type="text" name="net_bal" id="net_bal" class="form-control">
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(document).ready(function () {     
        item_table = $('#receipt_table').DataTable({
            "serverSide": false,
            "paging": false,
            "ordering": false
        });   
    });    
</script>
