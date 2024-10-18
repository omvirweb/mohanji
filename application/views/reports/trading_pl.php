<?php $this->load->view('success_false_notify'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Trading PL
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-2">
                            <label>From Date<span class="required-sign">&nbsp;*</span></label>
                            <input type="text" name="from_date" id="datepicker1" class="form-control" required value="<?php echo date("d-m-Y");?>">
                        </div>
                        <div class="col-md-2">
                            <label>To Date<span class="required-sign">&nbsp;*</span></label>
                            <input type="text" name="to_date" id="datepicker2" class="form-control" required value="<?php echo date('d-m-Y');?>">
                        </div>
                        <div class="col-md-2">
                            <br/>
                            <button name="" id="search" class="btn btn-primary btn-sm pull-left"><span class="fa fa-search-plus"></span> Search</button>
                        </div>
                        <div class="clearfix"></div><br />
                        <!---content start --->
                        <table id="trading_table" class="table row-border table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!---content end--->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        trading_table = $('#trading_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "bFilter": false,
            "bSort": false,
            "bInfo" : false,
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('reports/trading_pl_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [1,3],
            }]
        });
    
        $(document).on('click', '#search', function () {
            if ($.trim($("#datepicker1").val()) == '') {
                show_notify('Please Select From Date.', false);
                $("#datepicker1").focus();
                return false;
            }
            if ($.trim($("#datepicker2").val()) == '') {
                show_notify('Please Select To Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            $('#ajax-loader').show();
            trading_table.draw();
        });
        

    });
</script>
