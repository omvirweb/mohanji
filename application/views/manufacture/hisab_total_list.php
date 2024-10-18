<?php $this->load->view('success_false_notify'); ?>
<?php $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type']; ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Hisab Total List
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
                                        <div class="filter">
                                            <div class="col-md-2">
                                                <label>Hisab Done From</label>
                                                <select name="hisab_done_from" id="hisab_done_from" class="form-control select2" >
                                                    <option value="">All</option>
                                                    <?php if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"worker_hisab")) { ?>
                                                        <option value="<?php echo HISAB_DONE_IS_MODULE_MIR; ?>">Manufacture Issue/Receive</option>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"worker_hisab_i_r_silver")) { ?>
                                                        <option value="<?php echo HISAB_DONE_IS_MODULE_MIR_SILVER; ?>">Manufacture I/R Silver</option>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"worker_hisab_handmade")) { ?>
                                                        <option value="<?php echo HISAB_DONE_IS_MODULE_MHM; ?>">Manufacture Hand Made</option>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID,"worker_hisab_machine_chain")) { ?>
                                                        <option value="<?php echo HISAB_DONE_IS_MODULE_MC; ?>">Manufacture Machine Chain</option>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(CASTING_MODULE_ID,"worker_hisab_casting")) { ?>
                                                        <option value="<?php echo HISAB_DONE_IS_MODULE_CASTING; ?>">Manufacture Casting</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        </div><div class="clearfix"></div><br />
                                        <table id="hisab_total_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="80px">Action</th>
                                                    <th>Module</th>
                                                    <th>Worker Name</th>
                                                    <th>Hisab Date</th>
                                                    <th>Fine</th>
                                                    <th>Total Fine Adjusted</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Total</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
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
<input type="hidden" id="is_module" value="-1" >
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Hisab Detail Table</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Action</th>
                            <th>Worker Name</th>
                            <th>Department Name</th>
                            <th>Date</th>
                            <th>Ref.</th>
                            <th>Balance Net.Wt</th>
                            <th>Balance fine</th>
                            <th>Complete?</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total : </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <div class="col-md-5">
                    <span class="text-danger">Click on Save to Remove Un-Checked Entry from Hisab.</span>
                </div>
                <div class="col-md-2 text-right">
                    <label for="fine">Hisab Fine <span class="required-sign">&nbsp;*</span>
                </div>
                <div class="col-md-2">
                    <input type="text" name="fine" id="fine" class="form-control num_only" value="0"></label>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" id="remove_hisab_entry">Save</button>
                    <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('#lott_complete').select2();
        $('.select2').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        initAjaxSelect2($("#worker_id"), "<?= base_url('app/worker_select2_source') ?>");
        var table = $('#hisab_total_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('manufacture/hisab_total_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.hisab_done_from = $('#hisab_done_from').val()
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                   "className": "text-nowrap",
                   "targets": [3],
                },
                {
                    "className": "dt-right",
                    "targets": [4,5],
                }
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                pageTotal4 = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 4 ).footer() ).html(
                    pageTotal4.toFixed(3)
                );
        
                pageTotal5 = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 5 ).footer() ).html(
                    pageTotal5.toFixed(3)
                );
            }
        });
        
        $(document).on('click', '#search', function (){
            $("#ajax-loader").show();
            table.draw();
        });
        
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('manufacture/hisab_detail_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.is_module = $('#is_module').val()
                    d.worker_hisab_id = $('#clicked_item_id').val()
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [5,6],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 5 ).footer() ).html(
                    pageTotal.toFixed(3)
                );
                pageTotal1 = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 6 ).footer() ).html(
                    pageTotal1.toFixed(3)
                );
            }
        });
        
        //jQuery('#item_table').wrap('<div class="dataTables_scroll" />');

        $(document).on('click', '.item_row', function () {
            $('#is_module').val($(this).attr('data-is_module'));
            $('#clicked_item_id').val($(this).attr('data-worker_hisab_id'));
            $('#fine').val($(this).attr('data-fine'));
            item_table.draw();
            $('#myModal').modal('show');
        });

        $(document).on("click", ".delete_wh", function () {
            if (confirm('Are you sure delete this records?')) {
                $('#ajax-loader').show();
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Worker Hisab Deleted Successfully!', true);
                        } else {
                            $('#ajax-loader').hide();
                            show_notify('Some error occurred! Please try again', false);
                            return false;
                        }
                    }
                });
            }
        });
        
        $(document).on('click', '#remove_hisab_entry', function() {
            if ($('input.uncheck_row:checked').length == 0) {
                show_notify("Please Select Atleast One Item", false);
                return false;
            }

            var worker_hisab_id = $('#clicked_item_id').val();
            var fine = $("#fine").val();
            if (fine == '' || fine == null) {
                $("#fine").focus();
                show_notify("Please Enter Fine!", false);
                return false;
            }
            if (fine < 0) {
                $("#fine").focus();
                show_notify("Please Enter Fine >= 0.", false);
                return false;
            }

            var checked_items = new Array();
            $.each(($('input.uncheck_row').not(':checked')), function () {
                checked_items.push($(this).val());
            });
            $.ajax({
                url: "<?php echo base_url('manufacture/remove_worker_hisab') ?>/",
                type: "POST",
                async:false,
                data: {checked_items: checked_items, worker_hisab_id: worker_hisab_id, fine: fine},
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('manufacture/hisab_total_list') ?>";
                    }
                    return false;
                }
            });
            return false;
        });
    });
</script>
