<style>
.carousel-inner>.item>img {
    height: 600px !important;
}
.carousel-data{
    height: 600px !important;
    background-color: rgb(0, 0, 0,0.7);
    position: absolute;
    right: 0%;
    bottom: 0px;
    left: 65%;
    z-index: 10;
    padding-top: 0px;
    padding-bottom: 0px;
    color: #ffffff;
}
.inner_data{
    padding: 15px;
}
.inner_data>p{
    font-size: 18px;
}
#carouselButtons {
    margin-left: 50%;
    position: absolute;
    bottom: 5px;
}
</style>
    

<div class="content-wrapper" id="body-content">
    <?php if($this->applib->have_access_role(ORDER_SLIDER_MODULE_ID,"view")) { ?>
    <!-- Content Header (Page header) -->
    <div class="clearfix" style="margin-left: 5px; margin-right:5px;">
        <section class="content-header">
            <h1 style="color: green;float: left">
                Order
            </h1>
            <div class="col-md-2">
                <input type="text" name="order_no" id="order_no" class="form-control"  value="<?= ((isset($order_no)) ? $order_no : ''); ?>"><br />
            </div>
            
            <input type="button" id="view_order" class="btn btn-info btn-sm view_order" value="View" />
        </section>
        <br/>
            <?php
                $total_orders = count($orders); 
                if($total_orders == 0){ ?>
                    <center><h4>No orders to view!</h4></center>
                <?php }
                else if($total_orders == 1) { ?>
                    <?php // echo '<pre>'; print_r($orders); exit; ?>
                    <?php foreach($orders as $order){?>
                    <?php if(empty($order->order_image) && empty($order->item_image)){ ?>
                        <div class="">
                    <?php } else { ?>
                        <div class="carousel-inner">
                    <?php } ?>
                        <img src="<?php echo (empty($order->order_image) ? '/'.$order->item_image : base_url().''.$order->order_image )?>" alt="" style=" width:65%; height: 600px !important;">
                        <div class="carousel-data">
                            <div class="inner_data" >
                                    <p>Order No : <b><?php echo (isset($order->order_no) && !empty($order->order_no)) ? $order->order_no : '' ?></b></p>
                                    <p>Delivery Date : <b><?php echo (isset($order->delivery_date) && !empty($order->delivery_date)) ? date('d-m-Y', strtotime($order->delivery_date)) : '' ?></b></p>
                                    <p>Design No : <b><?php echo (isset($order->design_no) && !empty($order->design_no)) ? $order->design_no : '' ?></b></p>
                                    <p>Die No : <b><?php echo (isset($order->die_no) && !empty($order->die_no)) ? $order->die_no : '' ?></b></p>
                                    <p>Tunch : <b><?php echo (isset($order->purity) && !empty($order->purity)) ? $order->purity : '' ?></b></p>
                                    <p>Weight : <b><?php echo (isset($order->weight) && !empty($order->weight)) ? $order->weight : '' ?></b></p>
                                    <p>Pcs : <b><?php echo (isset($order->pcs) && !empty($order->pcs)) ? $order->pcs : '' ?></b></p>
                                    <p>Size : <b><?php echo (isset($order->size) && !empty($order->size)) ? $order->size : '' ?></b></p>
                                    <p>Length : <b><?php echo (isset($order->length) && !empty($order->length)) ? $order->length : '' ?></b></p>
                                    <p>Hook : <b><?php echo (isset($order->hook) && !empty($order->hook)) ? $order->hook : '' ?></b></p>
                                    <p>Remark : <b><?php echo (isset($order->remark) && !empty($order->remark)) ? $order->remark : '' ?></b></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php }
                else { ?>
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="2000">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    
                    <?php $i=0; foreach($orders as $order){?>
                        <div class="item <?php echo ($i == 1) ? 'active' : '' ?>">
                            <!--<img src="<?php echo '/'.$order->item_image ?>" alt="" style="width:65%;">-->
                            <img src="<?php echo (empty($order->order_image) ? '/'.$order->item_image : base_url().''.$order->order_image )?>" alt="" style="width:65%;">
                            <div class="carousel-data">
                                <div class="inner_data">
                                    <p>Order No : <b><?php echo (isset($order->order_no) && !empty($order->order_no)) ? $order->order_no : '' ?></b></p>
                                    <p>Delivery Date : <b><?php echo (isset($order->delivery_date) && !empty($order->delivery_date)) ? date('d-m-Y', strtotime($order->delivery_date)) : '' ?></b></p>
                                    <p>Design No : <b><?php echo (isset($order->design_no) && !empty($order->design_no)) ? $order->design_no : '' ?></b></p>
                                    <p>Die No : <b><?php echo (isset($order->die_no) && !empty($order->die_no)) ? $order->die_no : '' ?></b></p>
                                    <p>Tunch : <b><?php echo (isset($order->purity) && !empty($order->purity)) ? $order->purity : '' ?></b></p>
                                    <p>Weight : <b><?php echo (isset($order->weight) && !empty($order->weight)) ? $order->weight : '' ?></b></p>
                                    <p>Pcs : <b><?php echo (isset($order->pcs) && !empty($order->pcs)) ? $order->pcs : '' ?></b></p>
                                    <p>Size : <b><?php echo (isset($order->size) && !empty($order->size)) ? $order->size : '' ?></b></p>
                                    <p>Length : <b><?php echo (isset($order->length) && !empty($order->length)) ? $order->length : '' ?></b></p>
                                    <p>Hook : <b><?php echo (isset($order->hook) && !empty($order->hook)) ? $order->hook : '' ?></b></p>
                                    <p>Remark : <b><?php echo (isset($order->remark) && !empty($order->remark)) ? $order->remark : '' ?></b></p>
                                </div>
                            </div>
                        </div>
                    <?php $i++; } ?>
                    
                </div>
                
                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
                <div id="carouselButtons">
                    <button id="playButton" type="button" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-play"></span>
                     </button>
                    <button id="pauseButton" type="button" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-pause"></span>
                    </button>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
$('#playButton').click(function () {
    $('#myCarousel').carousel('cycle');
});
$('#pauseButton').click(function () {
    $('#myCarousel').carousel('pause');
});
$('#view_order').on('click', function(){
   var order_no = $('input[name=order_no]') .val();
   location.href = "<?= base_url('master/slider/') ?>"+order_no;
});
</script>