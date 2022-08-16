<?php

function encrypt_decrypt($action, $string) {

    $output = false;

    $myfile = fopen("../date_new.txt", "r") or die("Unable to open file!");   
    
    $secret_key = fgets($myfile);;

    fclose($myfile);

    $encrypt_method = "AES-256-CBC";
        
    $secret_iv = 'This is my secret iv';

    $key = hash('sha256', $secret_key);
        
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } 
    else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

?>