<?php
//mysqli("localhost", "root", "", "pbd");
//mysqli("sql11.freemysqlhosting.net", "sql11499637", "sxzsE2THwL", "sql11499637");
require_once $_SERVER['DOCUMENT_ROOT'] . "/conf/admin.php";
date_default_timezone_set('Europe/Madrid');
$myObj = new stdClass();

switch ($_POST['api']) {
    case "checkEmail":
        $email = sanitize($_POST['email']);
        checkEmail($email, $myObj);

        if (isset($myObj->success)) {
            checkCaptcha($email, sanitize($_POST['name']), sanitize($_POST['phone']), sanitize($_POST['password']), sanitize($_POST['captcha']), $myObj);
        }

        break;
    default:
        $myObj->error = "error en el switchcase";
        echo "error";
        break;
}
echo json_encode($myObj);
function sanitize($texto)
{
    return htmlentities(strip_tags($texto), ENT_QUOTES, 'UTF-8');
}

function checkEmail($email, $myObj)
{
    $email = strtolower(str_replace(" ", "", trim($email)));
    if ($email == "" || is_numeric($email)) {
        $myObj->error = "el email esta vacio o es un numero";
    }
    //
    $conn = new mysqli(DB_DIRECCION, DB_USUARIO, DB_PW, DB_BASEDATOS);
    $sql = "SELECT email FROM usuarios WHERE email='" . $email  . "' ;";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            $myObj->error = "Ya existe el email: " . $row['email'];
        }
    } else {
        $myObj->success = "email is OK";
        // echo "Error:<br>";
        // print_r($result);
        // echo "<br>" . $sql . "<br>";
    }
    $conn->close();
}

function checkCaptcha($email, $nombre, $phone, $password, $captcha, $myObj)
{

    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_V3_SECRET_KEY . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']
    );
    $response = json_decode($response);
    if ($response->success === false) {
        //Do something with error
    } else {
        if ($response->success == true && $response->score > 0.5) {
            insertUser($email, $nombre, $phone, $password, $myObj);
        } else if ($response->success == true && $response->score <= 0.5) {
            //Do something to denied access
            $myObj->error = "eres humano?";
        } else {
            $myObj->error = "NO eres humano?";
        }
    }
}

function insertUser($email, $nombre, $phone, $password, $myObj)
{
    $conn = new mysqli(DB_DIRECCION, DB_USUARIO, DB_PW, DB_BASEDATOS);
    $sql = "INSERT INTO usuarios_temp (email,nombre,phone,password) VALUES ('" . $email . "','" . $nombre . "'," . $phone . ",'" . md5($password) . "');";
    echo $sql;
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        // echo "insert table \"usuarios\"<br>";
        // echo $last_id;
        enviarmail($email);
    } else {
        // echo "Error: insert table \"usuarios\" " . $conn->error;
        $myObj->error = "Usuario NO guardado en la DB";
    }
    $conn->close();
}
function enviarmail($email)
{
    $usuario = new stdClass();
    $conn = new mysqli(DB_DIRECCION, DB_USUARIO, DB_PW, DB_BASEDATOS);
    $sql = "SELECT * FROM usuarios_temp WHERE email='" . $email . "' ORDER BY id DESC LIMIT 1;";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {

        while ($row = $result->fetch_assoc()) {
            $usuario->id = $row['id'];
            $usuario->email = $row['email'];
            $usuario->nombre = $row['nombre'];
            $usuario->phone = $row['phone'];
            $usuario->password = $row['password'];
            $usuario->reg_date = $row['reg_date'];
        }
        $conn->close();
        $xstring = $usuario->id . "-" . $usuario->email . "-" . $usuario->nombre . "-" . $usuario->phone . "-" . $usuario->password . "-" . $usuario->reg_date;
        $sha1 = sha1($xstring);
        echo $sha1;
        sendMail($usuario, $sha1);
    } else {
        $conn->close();
        echo "Error:<br>";
        print_r($result);
        echo "<br>" . $sql . "<br>";
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($usuario, $sha1)
{
    //MAIL
    $HostSMTP = 'smtp.gmail.com'; // Set the SMTP server to send through
    $ContrasenaDelCorreo = EMAIL_PW; // SMTP password
    $SendFromEMAIL = EMAIL_SMTP; // SMTP username
    $QuienLoEnviaNAME = 'moderator';
    $SendFromEMAILreply = 'onzepels@gmail.com';
    $QuienResponderNAME = 'moderator';
    $PortSMTP = 465; // TCP port to connect to
    //$PortSMTP = 587; // TCP port to connect to
    //
    $SentToEmail = $usuario->email;
    $Asunto = "ninguno";
    $BodyHTML = '<h1>hola x</h1> <br><a href="http://' . $_SERVER['HTTP_HOST'] . '/CURS/new_user.php?id=' . $usuario->id . '&clave=' . $sha1 . '"<b>' . $sha1 . '</b></a>';
    $BodyNOHTML = "hola que tal?";


    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function


    //Load Composer's autoloader
    require realpath($_SERVER["DOCUMENT_ROOT"]) . '/vendor/autoload.php';

    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;                      //Enable verbose debug output
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $HostSMTP;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $SendFromEMAIL;                     //SMTP username
        $mail->Password   = $ContrasenaDelCorreo;                               //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $PortSMTP;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($SendFromEMAIL, $QuienLoEnviaNAME);
        //$mail->addAddress($SentToEmail, 'Joe User');     //Add a recipient
        $mail->addAddress($SentToEmail);               //Name is optional
        $mail->addReplyTo($SendFromEMAIL, $QuienLoEnviaNAME);
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $Asunto;
        $mail->Body    = $BodyHTML;
        $mail->AltBody = $BodyNOHTML;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
