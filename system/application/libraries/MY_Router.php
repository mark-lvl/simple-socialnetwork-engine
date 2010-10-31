<?php

/**
* Router Extension to allow controllers in unlimited nesting of folders.
* Discussion thread at: http://codeigniter.com/forums/viewthread/56100/
* @author Peter Goodman
* @copyright Copyright 2007 Peter Goodman, all rights reserved.
*
* CMS capability by Adam Jackett www.darkhousemedia.com, 2009
*/
class MY_Router extends CI_Router {

    function _set_route_mapping() {
        parent::_set_route_mapping();

        // re-routed url
        if($this->rsegments != $this->segments) {
            array_unshift($this->rsegments, $this->directory);
        }
    }

    function _pluck_directory($segments) {
        $this->directory = '';

        foreach($segments as $segment) {
            $segment = trim($segment);
            if($segment != '') {
                if(is_dir(APPPATH .'controllers/'. $this->directory . $segment)) {
                    $this->directory .= array_shift($segments) .'/';
                } else {
                    break;
                }
            } else {
                array_shift($segments);
            }
        }

        // quick an easy forced reindexing
        $segments = array_values($segments);

        // put the entire directory path back into the segment as the first
        // item
        $dir = trim($this->directory, '/');
        if(!empty($dir)) {
            array_unshift($segments, $dir);
        }

        $this->segments = $segments;

        return $segments;
    }

    /*function _validate_request($segments) {
        return parent::_validate_request($this->_pluck_directory($segments));
    }*/

    function _validate_request($segments) {
        $segments = $this->_pluck_directory($segments);
        $found = TRUE;

        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }

        // Is the controller in a sub-folder?
        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {
            // Set the directory and remove it from the segment array
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
                {
                    //show_404($this->fetch_directory().$segments[0]);
                    $found = FALSE;
                }
            }
            else
            {
                $this->set_class($this->default_controller);
                $this->set_method('index');

                // Does the default controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
                {
                    $this->directory = '';
                    return array();
                }

            }

            //return $segments;
            if($found) return $segments;
        }

        // Can't find the requested controller...
        //show_404($segments[0]);
        $this->set_directory('');
        $this->set_class('main');
        $this->set_method('index');
        return array();
    }

}  