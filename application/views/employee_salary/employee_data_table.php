<?php
$isAdd = $this->app_model->have_access_role(EMPLOYEE_SALARY_MODULE_ID, "add");
?>
<table class="table">
    <thead>
        <tr>
            <th width="10%">Employee</th>
            <th width="10%">Worked Days</th>
            <th width="10%">Monthly Salary</th>
            <th width="10%">Salary Calculated</th>
            <th width="5%">Give Salary</th>
            <th width="10%">Employlee Leaves</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($employee_salary_array)){
            foreach($employee_salary_array as $row){
                ?>
                <tr>
                    <td>
                        <?=$row['account_name']?>
                        <input type="hidden" name="month_year" value="<?=$month_year?>">
                        <input type="hidden" name="account_id[]" value="<?=$row['account_id']?>">
                        <input type="hidden" name="user_id[]" value="<?=$row['user_id']?>">
                        <input type="hidden" name="employee_salary_id[]" value="<?=$row['employee_salary_id']?>">
                    </td>
                    <td>
                        <?=$row['total_working_days']?>
                        <input type="hidden" name="worked_days[]" value="<?=$row['total_working_days']?>">
                    </td>
                    <td>
                        <?=$row['salary']?>
                        <input type="hidden" name="monthly_salary[]" value="<?=$row['salary']?>">
                    </td>
                    <td>
                        <?=$row['salary_calculated']?>
                        <input type="hidden" name="salary_calculated[]" value="<?=$row['salary_calculated']?>">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="give_salary[]" value="<?=$row['give_salary']?>">
                    </td>
                    <td>
                        <?=$row['leaves']?>
                        <input type="hidden" name="leaves[]" value="<?=$row['leaves']?>">
                    </td>
                </tr>
                <?php
            }
        }
        ?>

        <?php
            if($isAdd){
        ?>
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-sm pull-right salary_save_btn" autocomplete="off">Save  [ Ctrl +S ]</button>
            </div>
        <?php
            }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.salary_save_btn',function(){
            $("#ajax-loader").show();
        });
    });