<?php   
require_once 'core/init.php'; 
require_once 'app/controller.php'; 
if($session_user->isLoggedIn()){ 
    Redirect::to(DNADMIN);
}else{

        
    if(Input::CheckInput('login_username','post','1')){
        $pageviewClass = new PageView();
        $page_type = 'Login';
        $page_item_ID = 1;

        $grab_info = '';

        $grab_info .= Input::get('login_username','post');
        $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                             'type'=>$page_type,
                             'grabbed_info'=>$grab_info));
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <?php include 'incl'._.'app_head_init_off'.P; ?>
        <link href="<?=DNADMIN?>/css/app_login_form.css" rel="stylesheet">
    </head>

    <body>
        <div class="wrapper">
            <header class="main-header">
                <?php ##include view_session_off_.'app_header'.P ?>
            </header>
            <div class="content-wrapper">
                <div class="app_guest_room" style="min-height: 95%;">
                    
                    <?php 
                        if(Input::checkInput('request','get','1')){
                            $post_request = Input::get('request','get');
                            switch($post_request){
                                case 'resetpassword':
                                    include 'views/login/login-resetpassword'.P;
                                    break; 
                                case 'forgotpassword':
                                    include 'views/login/login-forgotpassword'.P;
                                    break;   
                                default:
                                    include 'views/login/login-form'.P;
                                break;
                            }
                        }?>
                
                <br>
                </div>
                
                <div class="text-center" style="font-size: 12px;padding: 5px 10px 15px 10px; background: #fff; color: #888">
                <strong>Copyright &copy; <?=Dates::get('Y')?> <a href="#">Ray Globals</a>.</strong> All rights reserved.
                </div>
                
                
            </div>
        </div>
    </body>
    </html>

<?php

}?>

