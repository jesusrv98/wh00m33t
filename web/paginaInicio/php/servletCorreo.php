<?php
    $to = "rodriguezvargasjesus@gmail.com";
    $subject = $_POST['asunto'];

    $message = "<h1>Nombre del cliente:".$_POST['nombre'].", correo del cliente:". $_POST['email']."</h1>";
    $message .= $_POST['mensaje'];

    $header = "From:www.whomeet.com \r\n";
    $header .= "Cc:afgh@somedomain.com \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html \r\n";

    $retval = mail($to, $subject, $message, $header);

    if( $retval == true) {
        $msg = "Mensaje enviado con éxito";
    }else{
        $msg = "No se pudo enviar el mensaje";
    }

?>
