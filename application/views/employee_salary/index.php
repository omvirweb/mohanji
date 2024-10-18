<?php 
$this->load->view('success_false_notify');
$isAdd = $this->app_model->have_access_role(EMPLOYEE_SALARY_MODULE_ID, "add");
?>
<div class="content-wrapper" id="body-content">
    <section class="content-header">
        <h1>Employee Salary</h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php
                                $isView = $this->app_model->have_access_role(EMPLOYEE_SALARY_MODULE_ID, "view");
                                if($isView || $isAdd){
                            ?>
                            <form>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="account_id">Month<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" class="form-control" name="month" id="month">
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-2">
                                        <label for="account_id">Total Days<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" class="form-control" name="total_days" id="total_days" readonly>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-2">
                                        <label for="account_id">Holidays<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" class="form-control" name="holidays" id="holidays" readonly>
                                        <div class="clearfix"></div><br />
                                    </div>
                                    <div class="col-md-2">
                                        <label for="account_id">Working Days<span class="required-sign">&nbsp;*</span></label>
                                        <input type="text" class="form-control" name="working_days" id="working_days" readonly>
                                        <div class="clearfix"></div><br />
                                    </div>
                                </div>
                            </form>
                            <?php
                                }
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="javascript:void(0);" method="post" id="employee_salary_form">
                                        <div class="table-responsive" id="employee_html">

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
    $(document).ready(function(){

        $("#month").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            endDate: '-1m',
            minViewMode: "months"
        }).on('change', function(){
            var date = $(this).val();
            get_salary_data(date);
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#employee_salary_form").submit();
                return false;
            }
        });

        $(document).on('submit','#employee_salary_form',function(e){
            <?php if($isAdd){ ?>
                var form_data = new FormData(this);
                console.log(form_data);
                $.ajax({
                    url:"<?=base_url("employee_salary/save_salary")?>",
                    method:"post",
                    processData: false,
                    contentType: false,
                    data:form_data,
                    dataType:"json",
                    success:function(response){
                        console.log(response);
                        if(response.success){
                            show_notify(response.message,true);

                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }else{
                            show_notify(response.message,false);

                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }
                    }
                });
            <?php } ?>
        });

    });

    function get_salary_data(date)
    {
        $.ajax({
            url:"<?=base_url("employee_salary/get_salary_data")?>",
            method:"post",
            data:{
                date:date
            },
            dataType:"json",
            success:function(response){
                if(response.success){

                    $.each(response.data,function(index,value){
                        $('#'+index).val(value);
                    });

                    $('#employee_html').html(response.data.employee_html);

                }else{
                    show_notify(response.message,false);
                }
            }
        });
    }

</script>