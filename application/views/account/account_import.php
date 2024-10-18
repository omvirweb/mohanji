<?php $this->load->view('success_false_notify'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Account Import
        </h1>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                
                <form id="product_import" class="" action="<?=base_url('account/account_import_csv') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title">
                                Account Import
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="import_file" class="control-label"></label>
                                        <input type="file" name="import_file" id="import_file" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="action" value="export"  class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
                
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
