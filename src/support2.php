<?php if($_SERVER["REQUEST_METHOD"] == "GET"){    
    $mail = htmlspecialchars($_GET['email']);
    $name = htmlspecialchars($_GET['name']);
    $message = htmlspecialchars($_GET['message']);

    $to = $mail;
    $sub = "Response To Querry";
    $msg = 'This is a message on response to your query.
    Dear '.$name.',
        Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.';
    $from = "kumarpankaj3404@gmail.com";
    $header = "From: $from\r\n";

    $check = mail($to,$sub,$msg,$header);
    if($check){
        echo "<script>alert('Mail Sent Successfully');</script>";
        header("Location: homePage.php");
    }
}
?>