<?php
$config = array(
               'user_info' => array(
                                    array(
                                            'field' => 'first_name',
                                            'label' => 'lang:label_first_name',
                                            'rules' => 'trim|required|min_length[2]'
                                         ),
                                    array(
                                            'field' => 'last_name',
                                            'label' => 'lang:label_last_name',
                                            'rules' => 'trim|required|min_length[2]'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'lang:label_email',
                                            'rules' => 'trim|required|valid_email'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'lang:label_password',
                                            'rules' => 'trim|required|min_length[6]|max_length[12]|matches[repassword]'
                                         )
                                    ),
                  'login' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'lang:label_email',
                                            'rules' => 'trim|required|valid_email'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'lang:label_password',
                                            'rules' => 'trim|required|min_length[6]|max_length[12]'
                                         )
                                 )
               );

?>
