<?php
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

// keys();

function encrypt_decrypt_update($action, $string, $secret_key) {

    $output = false;

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


function keys(){
    include("config.php");
    $mynewfile = fopen("../date_new.txt", "r") or die("Unable to open file!");
    $previous = fgets($mynewfile);
    fclose($mynewfile);
    $myoldfile = fopen("../date_old.txt", "w") or die("Unable to open file!");
    fwrite($myoldfile, $previous);
    fclose($myoldfile);
    $new = random_str(16);
    $mynewfile = fopen("../date_new.txt", "w") or die("Unable to open file!");
    fwrite($mynewfile, $new);
    fclose($mynewfile);
    // $myoldfile = fopen("../date_old.txt", "r") or die("Unable to open file!");
    // $previous = fgets($myoldfile);
    // $mynewfile = fopen("../date_new.txt", "r") or die("Unable to open file!");
    // $new = fgets($mynewfile);

    $sql = "SELECT * FROM `messages`";
    $query = mysqli_query($conn, $sql);
    // echo mysqli_num_rows($query);
    // echo "<br>";
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            // echo $previous;
            // echo "<br>";
            $dec_old=encrypt_decrypt_update('decrypt', $row['msg'],$previous);
            // echo $dec_old;
            // echo"<br>";
            // echo $row['msg_id'];
            // echo"<br>";
            $enc_new=encrypt_decrypt_update('encrypt', $dec_old ,$new);
            // echo $enc_new;
            // echo"<br>";
            $sql_update = mysqli_query($conn,"UPDATE messages SET msg = '.$enc_new.' WHERE msg_id = '".$row['msg_id']."';");
            
        } 
    }
    fclose($myoldfile);
    fclose($mynewfile);
}

?>


<!-- $myfile = fopen("../date_new.txt", "r") or die("Unable to open file!");
echo fread($myfile,filesize("../date.txt"));
// echo "the key is: ".$key;
fclose($myfile);
?>


$mynewfile = fopen("../date_new.txt", "r") or die("Unable to open file!");
$previous = fgets($mynewfile);
fclose($mynewfile);
$myoldfile = fopen("../date_old.txt", "w") or die("Unable to open file!");
fwrite($myoldfile, $previous);
fclose($myoldfile);
$new = random_str(16);
$mynewfile = fopen("../date_new.txt", "w") or die("Unable to open file!");
fwrite($mynewfile, $new);
fclose($mynewfile); -->
