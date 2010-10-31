<?php

class Registration extends Base_Controller{

    function __construct() {
        parent::__construct();
        $this->add_css('main');
    }

    function index() {
        $this->data['heading'] = 'Home Page';
        $this->render();
    }

    function edit() {
        $this->data['heading'] = 'Edit Page';
        $this->render();
    }
}