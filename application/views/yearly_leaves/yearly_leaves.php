<?php  $this->load->view('success_false_notify');       ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Yearly Leaves
            <?php 
                $isView = $this->app_model->have_access_role(YEARLY_LEAVES_ID, "view");
                $isAdd = $this->app_model->have_access_role(YEARLY_LEAVES_ID, "add");
                if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';}
            ?>
        </h1>
    </section>
    <section class="content">
        <div class="clearfix">
            <div class="row">
                <?php if($isView) { ?>
                    <div style="margin: 15px;">
                        <div class="col-md-12">
                            <!-- Horizontal Form -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <div id="calendar"></div>
                                            <!--<img src="<?php echo base_url();?>assets/image/yearly_leaves.png" class="img-thumbnail" width="100%">-->
                                        </div>
                                    </div>
                                    <!--<button type="submit" class="btn btn-primary form_btn pull-right module_save_btn" style="margin: 5px;" <?php echo $btn_disable; ?>>Save</button>-->
                                </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
<div class="modal" id="event-modal" style="">
    <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">
                                    Event
                            </h4>
                    </div>
                    <div class="modal-body">
                            <input type="hidden" name="event-index" value="">
                            <form class="form-horizontal">
                                    <div class="form-group">
                                            <label for="min-date" class="col-sm-4 control-label">Name</label>
                                            <div class="col-sm-7">
                                                    <input name="event-name" type="text" class="form-control">
                                            </div>
                                    </div>
                                    <!--<div class="form-group">
                                            <label for="min-date" class="col-sm-4 control-label">Location</label>
                                            <div class="col-sm-7">
                                                    <input name="event-location" type="text" class="form-control">
                                            </div>
                                    </div>-->
                                    <div class="form-group">
                                            <!--<label for="min-date" class="col-sm-4 control-label">Dates</label>-->
                                            <div class="col-sm-7">
                                                    <div class="input-group input-daterange" data-provide="datepicker">
                                                            <input type="hidden" name="event-start-date" type="text" class="form-control" value="2012-04-05">
                                                            <!--<span class="input-group-addon">to</span>-->
                                                            <input type="hidden" name="event-end-date" type="text" class="form-control" value="2012-04-19">
                                                    </div>
                                            </div>
                                    </div>
                            </form>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="save-event">
                                    Save
                            </button>
                    </div>
            </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        function editEvent(event) {
            <?php if($isAdd) { ?>
            var dataSource = $('#calendar').data('calendar').getDataSource();
            var name = '';
            var id = '';
            for(var i in dataSource) {
                date1 = dataSource[i].startDate;
                date2 = event.startDate;
                if(date1.getTime() === date2.getTime()) {
                   name = dataSource[i].name;
                   id = dataSource[i].id;
                }
            }
            $('#event-modal input[name="event-index"]').val(id);
            $('#event-modal input[name="event-name"]').val(name);
            $('#event-modal input[name="event-start-date"]').datepicker('update', event ? event.startDate : '');
            $('#event-modal input[name="event-end-date"]').datepicker('update', event ? event.endDate : '');
            $('#event-modal').modal();
            <?php } ?>
        }

        function deleteEvent(event) {
            <?php if($isAdd) { ?>
            var dataSource = $('#calendar').data('calendar').getDataSource();
            if (confirm('Are you sure delete this records?')) {
                    $.ajax({
                        url: "<?= base_url('yearly_leaves/delete_yearly_leaves/') ?>"+ event.id,
                        type: "POST",
                        data: '',
                        success: function (response) {
                            var json = $.parseJSON(response);
                            if (json['error'] == 'Error') {
                                show_notify('Something Went Wrong!!.', false);
                            } else if (json['success'] == 'Deleted') {
                                for(var i in dataSource) {
                                    if(dataSource[i].id == event.id) {
                                        dataSource.splice(i, 1);
                                        break;
                                    }
                                }
                                $('#calendar').data('calendar').setDataSource(dataSource);
                                show_notify('Leave Deleted Successfully!', true);
                            }
                        }
                    });
            }
            <?php } ?>
        }

        function saveEvent() {
            <?php if($isAdd) { ?>
            var event = {
                id: $('#event-modal input[name="event-index"]').val(),
                name: $('#event-modal input[name="event-name"]').val(),
                startDate: $('#event-modal input[name="event-start-date"]').datepicker('getDate'),
                endDate: $('#event-modal input[name="event-end-date"]').datepicker('getDate'),
                from_db: '0'
            }

            var dataSource = $('#calendar').data('calendar').getDataSource();

            var dataSource_stringify = JSON.stringify(event);
            $.ajax({
                url: "<?= base_url('yearly_leaves/save_yearly_leaves') ?>",
                type: "POST",
                data: {dataSource: dataSource_stringify},
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        event.id = json['id'];
                        event.from_db = 1;
                        dataSource.push(event);

                        show_notify('Yearly Leave Information Added Successfully.', true);
                        $('#calendar').data('calendar').setDataSource(dataSource);
                        $('#event-modal').modal('hide');

                    }   else if (json['success'] == 'Updated') {
                        for(var i in dataSource) {
                            if(dataSource[i].id == event.id ) {
                                dataSource[i].name = event.name;
                                dataSource[i].startDate = event.startDate;
                                dataSource[i].endDate = event.endDate;
                            }
                        }
                        show_notify('Yearly Leave Information Updated Successfully.', true);
                        $('#calendar').data('calendar').setDataSource(dataSource);
                        $('#event-modal').modal('hide');
                    } else {
                        $('input[name="event-name"]').focus();
                        show_notify('Please Update Event Name.', false);
                    }
                },
            });
            <?php } ?>
        }

        $(function() {
            var currentYear = new Date().getFullYear();

            $('#calendar').calendar({ 
                enableContextMenu: true,
                enableRangeSelection: true,
                style:'background',
                selectRange: function(e) {
                    editEvent({ startDate: e.startDate, endDate: e.endDate });
                },
                mouseOnDay: function(e) {
                    if(e.events.length > 0) {
                        var content = '';

                        for(var i in e.events) {
                            content += '<div class="event-tooltip-content">'
                                            + '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div>'
                                        + '</div>';
                        }

                        $(e.element).popover({ 
                            trigger: 'manual',
                            container: 'body',
                            html:true,
                            content: content
                        });

                        $(e.element).popover('show');
                    }
                },
                mouseOutDay: function(e) {
                    if(e.events.length > 0) {
                        $(e.element).popover('hide');
                    }
                },
                dayContextMenu: function(e) {
                    $(e.element).popover('hide');
                },
                dataSource:  <?php echo $eventData; ?>                
            });

            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $('#save-event').click(function() {
                if($.trim($('input[name="event-index"]').val()) == ''){
                    if($.trim($('input[name="event-name"]').val()) == ''){
                        show_notify('Please Enter Event Name.', false);
                        $('input[name="event-name"]').focus();
                        return false;
                    }
                }
                var name_val = $.trim($('input[name="event-name"]').val());
                if(name_val == '') {
                    var event = {
                        id: $('#event-modal input[name="event-index"]').val(),
                        name: $('#event-modal input[name="event-name"]').val(),
                        startDate: $('#event-modal input[name="event-start-date"]').datepicker('getDate'),
                        endDate: $('#event-modal input[name="event-end-date"]').datepicker('getDate'),
                    }
                    deleteEvent(event);
                    $('#event-modal').modal('hide');
                } else {
                    saveEvent();
                }
                
            });

            $('input[name="event-name"]').keyup(function() {
                if($.trim($('input[name="event-index"]').val()) != ''){
                    var name_val = $.trim($('input[name="event-name"]').val());
                    if(name_val == '') {
                        $('#save-event').html('Delete');
                        $('#save-event').removeClass('btn-primary');
                        $('#save-event').addClass('btn-danger');
                    } else {
                        $('#save-event').addClass('btn-primary');
                        $('#save-event').removeClass('btn-danger');
                        $('#save-event').html('Save');
                    }
                } else {
                    $('#save-event').addClass('btn-primary');
                    $('#save-event').removeClass('btn-danger');
                    $('#save-event').html('Save');
                }
            });

            $('#event-modal').on('hidden.bs.modal', function () {
                $('#save-event').addClass('btn-primary');
                $('#save-event').removeClass('btn-danger');
                $('#save-event').html('Save');
            });
        });
    });
</script>