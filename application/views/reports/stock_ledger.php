<?php $this->load->view('success_false_notify'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Stock Ledger <a href="<?= base_url('reports/stock_ledger/'.$item_stock_data->item_stock_id.'/'.$item_wise) ?>" class="btn btn-primary btn-xs" style="margin: 5px;" ><i class="fa fa-refresh"></i></a>
            <a href="<?= base_url('reports/stock_status') ?>" class="btn btn-primary pull-right btn-sm" style="margin: 5px;" >Stock Status</a>
        </h1>
    </section>
    <style> .dataTables_info { display: none; }</style>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <div class="col-md-2">
                                            <label>From Date</label>
                                            <input type="text" name="from_date" id="datepicker1" class="form-control" value="<?php echo date("d-m-Y");?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label>To Date</label>
                                            <input type="text" name="to_date" id="datepicker2" class="form-control" value="<?php echo date('d-m-Y');?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Department</label>
                                            <select class="form-control select2" id="department_id"></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Category</label>
                                            <select class="form-control select2" id="category_id">
                                                <option value="0"> All </option>
                                                <?php foreach ($category as $value) { ?>
                                                    <option value="<?= $value->category_id; ?>" <?php echo ($item_stock_data->category_id == $value->category_id) ? 'Selected' : ''; ?>><?= $value->category_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Item</label>
                                            <select id="item_id" class="form-control select2">
                                                <option value="0"> All </option>
                                                <?php foreach ($items as $value) { ?>
                                                    <option value="<?= $value->item_id; ?>" <?php echo ($item_stock_data->item_id == $value->item_id) ? 'Selected' : ''; ?>><?= $value->item_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Tunch</label>
                                            <select id="tunch" class="form-control select2">
                                                <option value="0"> All </option>
                                                <?php foreach ($carat as $value) { ?>
                                                    <option value="<?= $value->purity; ?>" <?php echo ($item_wise == '1' && $item_stock_data->tunch == $value->purity) ? 'Selected' : ''; ?>><?= $value->purity; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <label>Account</label>
                                            <select id="account_id" class="form-control select2"></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Type(Sort)</label>
                                            <select id="type_sort" class="form-control select2">
                                                <option value=""> All </option>
                                                <option value="P">P</option>
                                                <option value="S">S</option>
                                                <option value="E">E</option>
                                                <option value="M R">M R</option>
                                                <option value="M P">M P</option>
                                                <option value="F T">F T</option>
                                                <option value="T T">T T</option>
                                                <option value="MFI">MFI</option>
                                                <option value="MFR">MFR</option>
                                                <option value="MFIS">MFI S</option>
                                                <option value="MFRS">MFR S</option>
                                                <option value="MHMIFW">MHMIFW</option>
                                                <option value="MHMIS">MHMIS</option>
                                                <option value="MHMRFW">MHMRFW</option>
                                                <option value="MHMRS">MHMRS</option>
                                                <option value="CASTINGIFW">CASTINGIFW</option>
                                                <option value="CASTINGIS">CASTINGIS</option>
                                                <option value="CASTINGRFW">CASTINGRFW</option>
                                                <option value="CASTINGRS">CASTINGRS</option>
                                                <option value="MCHAINIFW">MCHAIN IFW</option>
                                                <option value="MCHAINIS">MCHAIN IS</option>
                                                <option value="MCHAINRFW">MCHAIN RFW</option>
                                                <option value="MCHAINRS">MCHAIN RS</option>
                                                <option value="O P">O P</option>
                                                <option value="O S">O S</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>RFID</label>
                                            <select name="rfid_filter" class="form-control select2" id="rfid_filter">
                                                <option value="0"> All </option>
                                                <option value="1"> Only RFID Stock </option>
                                                <option value="2"> Without RFID Stock </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>&nbsp;</label><br />
                                            <label><input type="checkbox" name="include_wastage" id="include_wastage" > Include Wastage *(Only for Balance Fine)</label>
                                        </div>
                                        <div class="col-md-1">
                                            <br/>
                                            <button name="search" id="search" class="btn btn-primary btn-sm pull-left" autocomplete="off"><span class="fa fa-search-plus"></span> Search</button>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="text-right text-danger pull-right"><br />If Tunch is Not from Master : Balance Will Not Come</label><br />
                                        </div>
                                        <div class="clearfix"></div><br/>
                                        <table class="table row-border table-bordered table-striped" style="width:100%" id="stock_ledger_table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Action</th>
                                                    <th rowspan="2"  width="50px" class="text-nowrap">Date</th>
                                                    <th rowspan="2">Created At</th>
                                                    <th rowspan="2">Particular</th>
                                                    <th rowspan="2">Type(Sort)</th>
                                                    <th rowspan="2">Gr.Wt.</th>
                                                    <th rowspan="2">Less</th>
                                                    <th rowspan="2">Net.Wt.</th>
                                                    <th rowspan="2">Tunch</th>
                                                    <th rowspan="2">Wastage</th>
                                                    <th rowspan="2">Fine</th>
                                                    <th colspan="3" class="text-center">Balance</th>
                                                </tr>
                                                <tr>
                                                    <th>Gross Wt</th>
                                                    <th>Net Wt</th>
                                                    <th>Fine</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
<!--                                            <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>-->
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
    var selected_rows = [];
    $(document).ready(function () {
        $('#ajax-loader').show();
        $('.select2').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
        <?php if (isset($department_id) && !empty($department_id)) { ?>
            setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $department_id) ?>");
        <?php } ?>
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_name_with_number_without_department_select2_source') ?>");
        
        table = $('#stock_ledger_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "searching": false,
            "paging": false,
            "order": [],
            "ordering":[1, "ASC"],
            "ajax": {
                "url": "<?php echo site_url('reports/stock_ledger_datatable') ?>",
                "type": "POST",
                beforeSend: function() {
                  $("#ajax-loader").show();
                },
                "data": function (d) {
                    d.from_date = $('#datepicker1').val();
                    d.to_date = $('#datepicker2').val();
                    d.department_id = $('#department_id').val();
                    d.category_id = $('#category_id').val();
                    d.item_id = $('#item_id').val();
                    d.tunch = $('#tunch').val();
                    d.account_id = $('#account_id').val();
                    d.type_sort = $('#type_sort').val();
                    d.rfid_filter = $('#rfid_filter').val();
                    d.include_wastage = $('input[name="include_wastage"]').prop('checked');
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                    "className": "dt-right",
                    "targets": [5,6,7,8,9,10,11,12,13],
                },
                {
                    "targets": [ 2 ],
                    "visible": false
                },
                {
                    "targets": [ 1 ],
                    "class": 'text-nowrap'
                },
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap'>" + data + "</div>";
                    },
                    targets: 1
                },
            ],
            "fnRowCallback": function (nRow, aData) {
                var api = this.api(), data;
                var $nRow = $(nRow);
                var date_text = '';
                if(aData[2] != ''){
                    date_text = aData[2].replace(/(<([^>]+)>)/ig,"");
                }
                var particular_text = '';
                if(aData[3] != ''){
                    particular_text = aData[3].replace(/(<([^>]+)>)/ig,"");
                }
                var type_text = '';
                if(aData[4] != ''){
                    type_text = aData[4].replace(/(<([^>]+)>)/ig,"");
                }
                var gr_wt_text = '';
                if(aData[5] != ''){
                    gr_wt_text = aData[5].replace(/(<([^>]+)>)/ig,"");
                }
                var row_unique_text = date_text + particular_text + type_text + gr_wt_text; 
                $nRow.attr("data-row_particular",row_unique_text);
                if(jQuery.inArray(row_unique_text,selected_rows) !== -1) {
                    $nRow.addClass('selected');
                }
                return nRow;
            },
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
//            totalGold = api
//                .column( 4 )
//                .data()
//                .reduce( function (a, b) {
//                    return intVal(a) + intVal(b);
//                }, 0 );
//            totalGrWt = api
//                .column( 5 )
//                .data()
//                .reduce( function (a, b) {
//                    return intVal(a) + intVal(b);
//                }, 0 );
//            
//            totalNetWt = api
//                .column( 6 )
//                .data()
//                .reduce( function (a, b) {
//                    return intVal(a) + intVal(b);
//                }, 0 );
//            totalSilver = api
//                .column( 9 )
//                .data()
//                .reduce( function (a, b) {
//                    return intVal(a) + intVal(b);
//                }, 0 );
// 
//            // Update footer
//            $( api.column( 4 ).footer() ).html(
//                totalGold.toFixed(3)
//            );
//            $( api.column( 5 ).footer() ).html(
//                totalGrWt.toFixed(3)
//            );
//            $( api.column( 6 ).footer() ).html(
//                totalNetWt.toFixed(3)
//            );
//            $( api.column( 9 ).footer() ).html(
//                totalSilver.toFixed(3)
//            );
        },
        });
    
        $('#stock_ledger_table tbody').on( 'click', 'tr', function () {
            if($(this).hasClass('selected') == false) {
                console.log($(this).attr('data-row_particular'));
                selected_rows.push($(this).attr('data-row_particular'));
            } else {
                remove_selected_rows(selected_rows,$(this).attr('data-row_particular'));
            }
            $(this).toggleClass('selected');
        } );
        
        $(document).on('change', '#department_id', function(){
            var department_id = $('#department_id').val();
            if(department_id == '' || department_id === null){
                $('#select2-department_id-container .select2-selection__placeholder').html(' All ');
            }
        });
        
        $('#select2-account_id-container .select2-selection__placeholder').html(' All ');
        $(document).on('change', '#account_id', function(){
            var account_id = $('#account_id').val();
            if(account_id == '' || account_id === null){
                $('#select2-account_id-container .select2-selection__placeholder').html(' All ');
            }
        });
        
        /*$(document).on('change', '#datepicker1, #datepicker2, #item_id, #tunch, #account_id, #type_sort, #include_wastage', function(){
            $('#ajax-loader').show();
            table.draw();
        });*/
        
        $(document).on('click', '#search', function(){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#category_id', function(){
            var category_id = $('#category_id').val();
            if(category_id != ' ' && category_id != null){
                $.ajax({
                    url:"<?php echo base_url('new_order/get_item_name'); ?>/" + category_id,
                    type:'GET',
                    data:'',
                    success: function(response){
                        var json = $.parseJSON(response);
                        var row_inc = 1;
                        var option = '';
                        var edit_item_id = <?php echo $item_stock_data->item_id ?>;
                        option = '<option value=""> All </option>'
                        $.each(json.items ,function(index, value){
                            if(edit_item_id == value.item_id){
                                option += '<option value="' + value.item_id + '" Selected>' + value.item_name + '</option>';
                            } else {
                                option += '<option value="' + value.item_id + '">' + value.item_name + '</option>';
                            }
                        });
                        $('#item_id').html(option);
                        row_inc++;
                    }
                });
            }
        }); 
        
        <?php if (isset($item_stock_data->category_id)) { ?>
            $('#category_id').change();
        <?php } ?>
    });
    
    function remove_selected_rows(array, value) {
        var i = 0;
        while (i < array.length) {
            if(array[i] === value) {
                array.splice(i, 1);
            } else {
                ++i;
            }
        }
        return array;
    }
</script>
