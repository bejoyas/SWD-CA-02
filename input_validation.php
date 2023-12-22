<?php

function input_validation($input){

if (!preg_match("/^[a-zA-Z-0-9' ]*$/",$input)) {

    return(false);

}
else{

    return(true);
}

}?>