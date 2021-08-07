<?php
require('./Captcha.php');

define('EVENTO_ID',48);

$captcha = new Captcha();

if ($captcha->checkCaptcha($_POST['h-captcha-response'])) {
    $inscripto_id = Cargar_base();

    if($inscripto_id){
    
        $data = array();

        if(send_mail()){
            echo "<fieldset>";
            echo "<div id='success_page'>";
            echo "<h3>E-mail Enviado con exito.</h3>";
            echo "<p>Muchas gracias por su inscripción - Si no ves el email en tu casilla, por favor revisa el spam</p>";
            echo "</div>";
            echo "</fieldset>";

            // $data['result'] = 'OK';
        }else{
            // $data['result'] = 'SEND_ERROR';
            echo "<fieldset>";
            echo "<div id='success_page'>";
            echo "<h3>Hubo un error en el envío del email. Por Favor vuelve a mandar tus datos.</h3>";
            echo "</div>";
            echo "</fieldset>";
        }
    
        die(json_encode($data));
    }

} else {
    echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h3>Captcha incorrecto. ¿Eres un robot?</h3>";
    echo "</div>";
    echo "</fieldset>";
}

    
   



/*

if(send_mail()){
    echo "";

    echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h3>E-mail Enviado con exito.</h3>";
    echo "<p>Gracias por su consulta. Nos comunicaremos a la brevedad</p>";
    echo "</div>";
    echo "</fieldset>";
}

*/
function send_mail(){


    $mail_to = 'pruebas <webmaster@moeventos.com.ar>';
    $field_from = 'forms.mediaorange@gmail.com';
    $subject = 'pruebas' . $_POST{'Cata'};

/*  $body_message = '<pre>: ';
    $body_message .=     print_r($_POST,true);
    $body_message .= '<pre/>';*/

    foreach ($_POST as $key => $value) {
        $body_message.= $key." = ". $value."\r\n";
    }


    $headers = 'From: '.$mail_to."\r\n";
    $mail_status = mail($field_from, $subject, $body_message, $headers);
    
    if($mail_status){
        return true;
    }

}


function Cargar_base(){
    // set post fields
    $post = [
        'evento_id' => EVENTO_ID,
        'nombre' => $_POST['name'].' '.$_POST['apellido'],
        'email' => $_POST['email'],
        'telefono' =>$_POST['tel'],
        'servicios' => '',
        'empresa' => $_POST['empresa'],
        'cargo' => $_POST['cargo'],
        'mensages' =>print_r($_POST,true),
    ];


    $ch = curl_init('https://eventos.mediaorange.com.ar/index.php/api/inscriptos/addnew');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($ch);
    $data = json_decode($response);
    return  $data->data->Inscriptos->id;
    curl_close($ch);
}

function cargar_reserva($inscripto_id,$reserva_id){
    // set post fields
    $post = [
        'evento_id' => EVENTO_ID,
        'user_id'=>$inscripto_id,
        'reserva_id'=>$reserva_id
    ];

    $ch = curl_init('https://eventos.mediaorange.com.ar/index.php/api/reservas/addReserva');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($ch);
    $data = json_decode($response);
       // print_r($data);
    curl_close($ch);
    return  $data;

}


?>