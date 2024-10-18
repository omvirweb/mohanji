<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
        <?php if($this->session->flashdata('error_message')){ ?>
            <div class="col-lg-12">
                <div class="alert alert-danger"><button data-dismiss="alert" class="close">&times;</button><?php echo $this->session->flashdata('error_message');?></div>
            </div>    
        <?php } elseif($this->session->flashdata('success_message')){ ?>
            <div class="col-lg-12">
                <div class="alert alert-success"><button data-dismiss="alert" class="close">&times;</button><?php echo $this->session->flashdata('success_message');?></div>
            </div>
        <?php } ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3><?php echo "Coming Soon"?></h3>
						<p>Cash</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
            <div class="col-lg-3 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 style="float: left; width: 25%;"><?php echo $gold_fine; ?></h3>
                        <div class="clearfix"></div>
                        <p>Fine Gold >> Gold</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="<?=$gold_stock_ledger_url?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow-gradient" style="background: #5f9ea0 !important;">
                    <div class="inner">
                        <h3 style="float: left; width: 25%;"><?php echo $silver_fine; ?></h3>
                        <div class="clearfix"></div>
                        <p>Fine Silver >> Silver</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="<?=$silver_stock_ledger_url?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
		</div>
		<!-- /.row -->		
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function(){
        Date.prototype.addDays = function(days) {
            this.setDate(this.getDate() + days);
            return this;
        };

        $(function() {
            var currentDate = new Date();
            var myDate = currentDate.addDays(2);
            $('#datepicker1').datepicker({ dateFormat: "dd-mm-yy"});
            $('#datepicker1').datepicker('setDate', myDate);
        });

        $(document).on('change', '#datepicker1', function(){
            var delivery_date = $(this).val();
            $.ajax({
                    url: "<?php echo base_url('auth/get_pending_orders'); ?>",
                    type: "POST",
                    data: {delivery_date : delivery_date},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $('#pending_orders').html(json.pending_orders);
                    }
                });
        });

        $(document).on('click', '#pending_oredr_click', function(){
            var delivery_date = $('#datepicker1').val();
            window.location.href = "<?php echo base_url('new_order/new_order_item_list'); ?>/"+delivery_date;
        });
    });
</script>