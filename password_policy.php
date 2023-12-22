<?php

function check_password_complexity($password){

    $upper_case = preg_match('@[A-Z]@', $password);
    $lower_case = preg_match('@[a-z]@', $password);
    $num    = preg_match('@[0-9]@', $password);
    $special = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password);

    if(!$upper_case || !$lower_case || !$num || !$special || strlen($password) < 8) {
        return false;
        
    }else{
        return true;
    }

}
?>