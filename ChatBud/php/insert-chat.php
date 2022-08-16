<?php 


include('encryption_decryption.php');


    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        $message_id = mysqli_query($conn,"SELECT * FROM messages");
        if((int)$message_id->num_rows==0){
            $enc=encrypt_decrypt('encrypt', $message);
        }
        else{
            if((int)$message_id->num_rows%6!=0){
                $enc=encrypt_decrypt('encrypt', $message);
                
                // if(!empty( $message)){
                //     $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                //                                 VALUES ({$incoming_id}, {$outgoing_id}, '{$enc}')") or die();
                // }
            }
            else{
                $enc=encrypt_decrypt('encrypt', $message);
                $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$enc}')") or die();
                include('dates.php');
                keys();
                
            }
        }
        if(!empty( $message)){
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$enc}')") or die();
        }
    }else{
        header("location: ../login.php");
    }


    
?>