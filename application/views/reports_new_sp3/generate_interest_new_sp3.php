<div class="content-wrapper">   
    <section class="content-header">
        <h1> 
            <a href="<?= base_url('reports_new_sp3/interest') ?>" class="btn btn-primary btn-sm" style="margin: 5px;" ><i class="fa fa-arrow-left"></i> Back</a>
            Generate Interest
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
                                <form action="javascript:void(0);" method="post" id="store_interest_form">
                                    <div class="col-md-2">
                                        <label>Month : <?php echo $month; ?></label><br />
                                    </div>
                                    <div class="col-md-8">
                                        <label><a href="<?= base_url('master/setting') ?>" target="_blanck">Rate From Setting : </a></label> &nbsp;&nbsp;&nbsp;
                                        <label>Gold Rate : <?php echo $gold_rate; ?></label>&nbsp;&nbsp;&nbsp;
                                        <label>Silver Rate : <?php echo $silver_rate; ?></label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="month" id="month" value="<?php echo $month ?>">
                                        <input type="hidden" name="gold_rate" id="gold_rate" value="<?php echo $gold_rate ?>">
                                        <input type="hidden" name="silver_rate" id="silver_rate" value="<?php echo $silver_rate ?>">
                                        <button type="submit" name="store_interest" id="store_interest" class="btn btn-primary pull-right">Store All Account Interest [ Ctrl +S ]</button>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Account Name</th>
                                                    <th>Month Interest</th>
                                                    <th>Real Interest</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($interest_accounts)) {
                                                    foreach ($interest_accounts as $interest_account) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $interest_account['account_name'] ?>
                                                                <input type="hidden" name="account_id[]" value="<?php echo $interest_account['account_id'] ?>">
                                                            </td>
                                                            <td>
                                                                <?php echo $interest_account['month_interest'] ?>
                                                                <input type="hidden" name="interest_per[]" value="<?php echo $interest_account['interest_per'] ?>">
                                                                <input type="hidden" name="month_interest[]" value="<?php echo $interest_account['month_interest'] ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control num_only" name="real_interest[]" value="<?php echo $interest_account['month_interest'] ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var module_submit_flag = 0;
    $(document).ready(function () {

        $(document).bind("keydown", function (e) {
            if (e.ctrlKey && e.which == 83) {
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#store_interest_form").submit();
                    return false;
                }
            }
        });

        $(document).on('submit', '#store_interest_form', function () {
            var month = $('#month').val();
            if (month == '') {
                show_notify('Please Select Month!', false);
                $("#datepicker_month").focus();
                return false;
            }
            var gold_rate = $('#gold_rate').val();
            if (gold_rate == '' || gold_rate == 0) {
                show_notify('Please enter Gold Rate in Setting!', false);
                return false;
            }
            var silver_rate = $('#silver_rate').val();
            if (silver_rate == '' || silver_rate == 0) {
                show_notify('Please enter Silver Rate in Setting!', false);
                return false;
            }
            if (confirm('Are you sure, You want to Store All Account Monthly Interest?')) {
                $('#ajax-loader').show();
                var postData = new FormData(this);
                $.ajax({
                    url: "<?php echo base_url('reports/store_interest_in_journal'); ?>/",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: postData,
                    async: false,
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['success'] == 'Added') {
                            window.location.href = "<?php echo base_url('reports_new_sp3/interest') ?>";
                        } else {
                            show_notify('Not found Monthly Interest', true);
                        }
                        $('#ajax-loader').hide();
                        return false;
                    }
                });
            }
            module_submit_flag = 1;
            return false;
        });
    });
</script>


