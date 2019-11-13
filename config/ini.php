<?php
error_reporting(-1);

/**
 * 
 * 
 * */
function env($key=''){


    $filename   =   get_env_file();


    if(!file_exists($filename)){
        //create_env_file($filename);
        die('Your environment file is not setup correctly, please configure environment file');
    }


    $data = file_get_contents($filename);
    $all_vars   =   explode(PHP_EOL,$data);
    //echo "<pre>";
    //die(print_r(array_filter($all_vars)));

    $all_vars   =   array_filter($all_vars);

    foreach($all_vars as $var=>$value){
        if($value==''){
            continue;
        }
        
        
        $single_var     =   explode('=',$value);
        
        /** If current row is not the requested row, skip  */
        if( $single_var[ 0 ] != $key){
            continue;
        }else{
            return $single_var[ 1 ];
        }



        if($single_var[ 1 ]==''){
            die('Value not available for #'.$single_var[ 0 ]);
        }
        
        /** Save requested value in array and return */
        $global_config[ $single_var[ 0 ] ]  =   $single_var[1];
        
    }
    return $global_config[$key];
}



function get_env_file()
{


    if(!$_SERVER['SERVER_NAME']){
        return ".ENV";
    }

    
    switch ($_SERVER['SERVER_NAME']) {
        
        case 'staging.mobi-hub.com':
            return ".ENV";
            break;
        
        default:
            return ".ENV";
            break;
    }

}