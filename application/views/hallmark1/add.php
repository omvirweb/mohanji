<?php $this->load->view('success_false_notify'); ?>
<style>
    .padding_sm{
        padding-left: 7px;
        padding-right: 7px;
    }
</style>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('hallmark1/save_entry') ?>" method="post" id="save_sell_purchase" novalidate enctype="multipart/form-data">
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                     <div class="col-md-1 padding_sm" style="background-color: #ffffff;">
                                        <label for="date">Date <span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" name="entry_date" id="datepicker2" tabindex="1" class="form-control input-datepicker" value="<?php  echo date('d-m-Y'); ?>" style="padding: 5px;"><br />
                                    </div>
                                    <div class="col-md-2 padding_sm">
                                        <label for="account_id">Account Name <span class="required-sign">&nbsp;*</span></label>
                                        <select name="account_id" id="account_id" class="form-control select2 input_class" tabindex="2" autofocus=""></select>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-2 padding_sm">
                                        <label for="account_id">Sample Name <span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" name="sample_name" id="sample_name" class="form-control input_class" autofocus="">
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-1 padding_sm">
                                        <label for="item_id">Item <span class="required-sign">&nbsp;*</span></label>
                                        <select name="item_id" id="item_id" class="form-control select2" tabindex="3" ></select>
                                        <div class="clearfix"></div><br />
                                    </div>


                                    <div class="col-md-2 padding_sm" style="background-color: #92E2BD;">
                                        <label for="gr_wt">G. Wt.</label>
                                        <input type="text" name="gr_wt" id="gr_wt" class="form-control num_only input_class" tabindex="4" value=""><br />
                                    </div>
                                    <div class="col-md-2 padding_sm" style="background-color: #92E2BD;">
                                        <label for="gr_wt">Chhijjat</label>
                                        <input type="text" name="chhijjat" id="chhijjat" class="form-control num_only input_class" tabindex="4" value="0.03"><br />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-1 padding_sm" style="background-color: #A3C9FF;">
                                        <label for="n_wt">N. Wt.</label>
                                        <input type="text" name="n_wt" id="n_wt" class="form-control num_only input_class" tabindex="4" value=""><br />
                                    </div>
                                    <div class="col-md-1 padding_sm" style="background-color: #A3C9FF;">
                                        <label for="Tounch">Tounch</label>
                                        <input type="text" name="tounch" id="tounch" class="form-control num_only input_class" tabindex="5" value=""><br />
                                    </div>
                                    <div class="col-md-1 padding_sm" style="background-color: #A3C9FF;">
                                        <label for="fine">Fine</label>
                                        <input type="text" name="fine" id="fine" class="form-control num_only input_class" tabindex="6" value=""><br />
                                    </div>
                                    <div class="col-md-1 padding_sm" style="background-color: #A3C9FF;">
                                        <label for="fine">Fine Diya</label>
                                        <input type="text" name="fine_diya" id="fine_diya" class="form-control num_only input_class" tabindex="6" value=""><br />
                                    </div>

                                    <div class="col-md-1 padding_sm" style="background-color: #FFC2B3;">
                                        <label for="payment_amount">Pcs</label>
                                        <input type="text" name="pcs" id="pcs" class="form-control num_only input_class" tabindex="7" value=""><br />
                                    </div>

                                    <div class="col-md-1 padding_sm" style="background-color: #FFC2B3;">
                                        <label for="payment_amount">Rate</label>
                                        <input type="text" name="rate" id="rate" class="form-control num_only input_class" tabindex="7" value=""><br />
                                    </div>

                                    <div class="col-md-2 padding_sm" style="background-color: #F8CB81;">
                                        <label for="payment_amount">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control num_only input_class" tabindex="7" value=""><br />
                                    </div>

                                    <div class="col-md-2 padding_sm" style="background-color: #D3C2FF;">
                                        <label for="payment_amount">Cash Received</label>
                                        <input type="text" name="cash_received" id="cash_received" class="form-control num_only input_class" tabindex="7" value=""><br />
                                    </div>

                                    <div class="col-md-1 padding_sm" style="background-color: #ffffff; width: 5%">
                                        <br/>
                                        <button type="submit" class="btn btn-primary btn-sm" ><b>Transfer</b></button>
                                    </div>

                                    <div class="col-md-1 padding_sm" style="background-color: #ffffff;">
                                        <br/>
                                        <button type="submit" class="btn btn-primary module_save_btn btn-sm" ><b>Save[Ctrl+S]</b></button>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    
<!--                                    <div class="col-sm-12">
                                        <table style="" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>SG</th>
                                                    <th>K</th>
                                                    <th>%</th>
                                                    <th>F</th>
                                                    <th>P. No. </th>
                                                    <th>GS</th>
                                                    <th>R</th>
                                                    <th>AR</th>
                                                    <th>SS</th>
                                                    <th>R</th>
                                                    <th>AR</th>
                                                    <th>CG</th>
                                                    <th>P.No./Nm</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list">
                                            </tbody>
                                        </table>
                                    </div>-->
                                    </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body table-responsive">
                                            <table id="lot_master_table1" class="table row-border table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 80px;">Action</th>
                                                        <th>Sr. No.</th>
                                                        <th>Date</th>
                                                        <th>Name</th>
                                                        <th>Sample Name</th>
                                                        <th>Item Name</th>
                                                        <th>G. Wt.</th>
                                                        <th>Chhijjat</th>
                                                        <th>N. Wt.</th>
                                                        <th>Tounch</th>
                                                        <th>Fine</th>
                                                        <th>Fine Diya</th>
                                                        <th>Pcs</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                        <th>Cash Received</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
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
                                                        <th></th>
                                                        <th></th>
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
        
    </form>
   
</div>

<script type="text/javascript">
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    
    $(document).ready(function () {
        
        $('#datepicker2').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: "today",
            maxDate: 0,
        })
            
        $('.select2').select2({width: '100%'});
        initAjaxSelect2($("#account_id"), "<?= base_url('app/party_name_with_number_select2_source/1') ?>");
        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        $("#account_id").select2('open');
        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/406') ?>");
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                $("#save_sell_purchase").submit();
                return false;
            }
            if(e.which == 112){
                e.preventDefault();
                $("#purchase_gr_wt").focus();
                return false;
            }
            if(e.which == 113){
                e.preventDefault();
                $("#payment_amount").focus();
                return false;
            }
            if(e.which == 114){
                e.preventDefault();
                $("#receipt_amount").focus();
                return false;
            }
            if(e.which == 115){
                e.preventDefault();
                $("#gold_bhav_gr_wt").focus();
                return false;
            }
            if(e.which == 116){
                e.preventDefault();
                $("#sell_gr_wt").focus();
                return false;
            }
        });
        
        $(document).on('keydown','#receipt_amount', function(e) {
            if (e.keyCode == 13) {
                $('#gold_bhav_type_id').select2('open');
            }
        });
        
        $('#gold_bhav_type_id').on("select2:close", function(e) { 
            $("#gold_bhav_gr_wt").focus();
        });
        
        $('#account_id').on("select2:close", function(e) { 
            $('#item_id').select2('open');
        });
        
        $('#item_id').on("select2:close", function(e) { 
            $("#purchase_gr_wt").focus();
        });
        
        $('body').on('keydown', 'input,select,.select2-search__field, textarea', function(e) {
            var self = $(this)
              , form = self.parents('form:eq(0)')
              , focusable
              , next
              , prev
              ;
            

            var id = $(this).attr('id');
            console.log(id);
            if (e.shiftKey) {
                if (e.keyCode == 13 && $(this).is("textarea") == false) {
                    focusable =   form.find('input,select,.select2-search__field,button,textarea').filter(':visible');
                    prev = focusable.eq(focusable.index(this)-1); 

                    if (prev.length) {
                       prev.focus();
                    } else {
                        form.submit();
                    }
                }
            } else if (e.keyCode == 13 && $(this).is("textarea") == false) {
                //focusable = form.find('input,a,select,.select2-search__field,button,textarea').filter(':visible');
                focusable = form.find('input,select,.select2-search__field,button,textarea').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                } else {
                    form.submit();
                }
                return false;
            }


        });
        
        $(document).on('submit', '#save_sell_purchase', function () {
            $(window).unbind('beforeunload');
            var datepicker2 = $("#datepicker2").val();
            if(datepicker2 == '' ){
                show_notify('Please Select Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account Name.', false);
                $("#account_id").select2('open');
                return false;
            }
            if ($.trim($("#item_id").val()) == '') {
                show_notify('Please Select Item.', false);
                $("#item_id").select2('open');
                return false;
            }
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('hallmark1/save_entry') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                async: false,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        show_notify('Tunch Testing Entry Added Successfully.', true);
                        $(".input_class").val('');
                        $("#sell_tunch").val('100');
                        $("#auto_fill_pending_wt"). prop("checked", false);
                        $("#account_id").val(null).trigger("change");
                        $("#item_id").val(null).trigger("change");
                        $("#gold_bhav_type_id").val('1').trigger("change");
                        setSelect2Value($("#item_id"), "<?= base_url('app/set_item_name_select2_val_by_id/406') ?>");
                        $("#account_id").select2('open');
                        table.draw();
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('tunch_testing/add/') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
        
        table = $('#lot_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
//            scroller: {
//                loadingIndicator: true
//            },
            "ajax": {
                "url": "<?php echo site_url('tunch_testing/tunch_testing_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.entry_date = $('#datepicker2').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                   "className": "dt-right",
                   "targets": [3,4,5,6,7,8,9,10,11,12,13,14],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                total_k = api.column( 3 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 3 ).footer() ).html(total_k.toFixed(3));
                
                total_f = api.column( 5 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 5 ).footer() ).html(total_f.toFixed(3));
                
                total_gs = api.column( 7 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 7 ).footer() ).html(total_gs.toFixed(3));
                
                total_ar = api.column( 9 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 9 ).footer() ).html(total_ar.toFixed(2));
                
                total_ss = api.column( 10 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 10 ).footer() ).html(total_ss.toFixed(3));
                
                total_ar2 = api.column( 12 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 12 ).footer() ).html(total_ar2.toFixed(2));
                
                total_cg = api.column( 13 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 13 ).footer() ).html(total_cg.toFixed(2));

            }
        });
        
        $(document).on("click", ".delete_tunch_testing", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=tunch_testing_id&table_name=tunch_testing',
                    success: function (data) {
                        table.draw();
                        show_notify('Tunch Testing Entry Deleted Successfully!', true);
                    }
                });
            }
        });
        
        $(document).on("change", "#datepicker2", function () {
            table.draw();
        });
        
        $(document).on("input", "#purchase_gr_wt, #purchase_tunch", function () {
            change_purchase_fine();
        });
        
        $(document).on("input", "#sell_gr_wt, #sell_tunch", function () {
            change_sell_fine();
        });
        
        $(document).on("change", "#auto_fill_pending_wt", function () {
            change_auto_fill_pending_wt();
        });
        
        $(document).on("input", "#purchase_fine", function () {
//            change_auto_fill_pending_wt();
        });
        
        $(document).on("input", "#gold_bhav_gr_wt, #gold_bhav_rate", function () {
            change_gold_bhav_amount();
//            change_auto_fill_pending_wt();
        });
        
    });
    
    function change_auto_fill_pending_wt() {
        if ($('#auto_fill_pending_wt').prop('checked')==true){
            var purchase_fine_auto = $('#purchase_fine').val() || 0;
            var gold_bhav_gr_wt_auto = $('#gold_bhav_gr_wt').val() || 0;
            var sell_gr_wt_auto = parseFloat(purchase_fine_auto).toFixed(3) - parseFloat(gold_bhav_gr_wt_auto).toFixed(3);
            $('#sell_gr_wt').val(parseFloat(sell_gr_wt_auto).toFixed(3));
            change_sell_fine();
        } else {
//            $('#sell_gr_wt').val('');
        }
    }
    
    function change_sell_fine(){
        var sell_gr_wt = $('#sell_gr_wt').val() || 0;
        var sell_tunch = $('#sell_tunch').val() || 0;
        var sell_fine = parseFloat(sell_gr_wt).toFixed(3) * parseFloat(sell_tunch).toFixed(3) / 100;
        $('#sell_fine').val(parseFloat(sell_fine).toFixed(3));
    }
    
    function change_purchase_fine(){
        var purchase_gr_wt = $('#purchase_gr_wt').val() || 0;
        var purchase_tunch = $('#purchase_tunch').val() || 0;
        var purchase_fine = parseFloat(purchase_gr_wt).toFixed(3) * parseFloat(purchase_tunch).toFixed(3) / 100;
        $('#purchase_fine').val(parseFloat(purchase_fine).toFixed(3));
//        change_auto_fill_pending_wt();
    }
    
    function change_gold_bhav_amount(){
        var gold_bhav_gr_wt = $('#gold_bhav_gr_wt').val() || 0;
        var gold_bhav_rate = $('#gold_bhav_rate').val() || 0;
        var gold_bhav_amount = parseFloat(gold_bhav_gr_wt).toFixed(3) * parseFloat(gold_bhav_rate).toFixed(2) / 10;
        $('#gold_bhav_amount').val(parseFloat(gold_bhav_amount).toFixed(2));
    }
</script>