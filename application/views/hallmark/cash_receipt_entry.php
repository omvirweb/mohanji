<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="" method="post" id="save_order" novalidate enctype="multipart/form-data">
     
        <section class="content-header">
            <h1>
                Cash Receipt Entry           
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
                                            <div class="col-md-3">
                                                <label for="party">Party Name</label>
                                                <select name="party" id="party" class="form-control">
                                                    <option value="0">All</option>
                                                </select>
                                            </div>
                                            <table id="receipt_table" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Jr No</th>
                                                        <th>Taxable</th>
                                                        <th>CGST</th>
                                                        <th>SGST</th>
                                                        <th>Total</th>
                                                        <th>Adv.Rec</th>
                                                        <th>Total Rec</th>
                                                        <th>Balance</th>
                                                        <th>Total Pcs</th>
                                                        <th>Total Weight</th>
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
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
