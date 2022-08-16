<?php
include("config.php");

$sql = "SELECT * FROM `messages`";
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $dec_old=encrypt_decrypt_update('decrypt', $row['msg'],$previous);
            // echo $row['msg_id'];
            // echo "<br>";
            $enc_new = encrypt_decrypt_update('encrypt', $dec_old ,$new);
            $sql_update = mysqli_query($conn,"UPDATE messages SET msg = $enc_new WHERE msg_id = int($row['msg_id'])");
            
        } 
    }

?>