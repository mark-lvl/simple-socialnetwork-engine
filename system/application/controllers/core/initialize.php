<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class initialize extends Base_Controller {


    function Registration()
    {
        parent::__construct();
    }

    function index() 
    {
        $this->data['title'] = $this->data['lang']['title_register'];

        

        $this->render();
    }
}

?>