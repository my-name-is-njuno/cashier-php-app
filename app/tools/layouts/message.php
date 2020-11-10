<?php
    if(get_sess('message_success')){
?>
        <div class="row justify-content-center">
            <div class="col-sm-12 justify-content-center">
                <div class="alert alert-success" role="alert">
                    <?php echo get_sess('message_success'); unset_sess('message_success');?>
                </div>
            </div>
        </div>
        
<?php
    } 
    if (get_sess('message_error')){
?>
        <div class="row  justify-content-center">
            <div class="col-sm-12 justify-content-center">
                <div class="alert alert-danger" role="alert">
                    <?php echo get_sess('message_error'); unset_sess('message_error');?>
                </div>
            </div>
        </div>
<?php
    }
    if (get_sess('message_info')){
?>
    <div class="row  justify-content-center">
        <div class="col-sm-12 justify-content-center">
            <div class="alert alert-primary" role="alert">
                <?php echo get_sess('message_info'); unset_sess('message_info');?>
            </div>
        </div>
    </div>
        <?php
    }


?>