<?php 
    if(PACKAGE_FOR == 'guru' || PACKAGE_FOR == 'mohanji'){
        $this->load->view('sell/add_guru');
    } else {
        $this->load->view('sell/add_'.PACKAGE_FOR);
    }
?>