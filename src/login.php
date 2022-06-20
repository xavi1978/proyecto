<?php
date_default_timezone_set('Europe/Madrid');
$myObj = new stdClass();

switch ($_POST['api']) {
    case "checkEmail":
        checkCaptcha(sanitize($_POST['captcha']), $myObj);
        if (isset($myObj->success)) {
            loginUser(sanitize($_POST['email']), sanitize($_POST['password']), $myObj);
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

function checkCaptcha($captcha, $myObj)
{

    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_V3_SECRET_KEY . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']
    );
    $response = json_decode($response);
    if ($response->success === false) {
        //Do something with error
        $myObj->error = "no recaptcha.";
    } else {
        if ($response->success == true && $response->score > 0.5) {
            $myObj->success = "eres humano.";
        } else if ($response->success == true && $response->score <= 0.5) {
            $myObj->error = "eres humano?";
        } else {
            $myObj->error = "NO eres humano?";
        }
    }
}

function loginUser($email, $password, $myObj)
{
    $usuario = new stdClass();
    $conn = new mysqli(DB_DIRECCION, DB_USUARIO, DB_PW, DB_BASEDATOS);
    $sql = "SELECT nombre FROM usuarios WHERE email='" . $email . "' && '" . md5($password) . "';";
    // echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {

        while ($row = $result->fetch_assoc()) {
            $usuario->email = $email;
            $usuario->nombre = $row['nombre'];

            $usuario->token = md5(time() . "-" . $usuario->email);
            $sql_a = "UPDATE usuarios SET token='" . $usuario->token . "' WHERE email='" . $email . "' ;";
            $result = $conn->query($sql_a);

            $myObj->success = json_encode($usuario);
            // $myObj->error = null;
            break;
        }
    } else {
        echo "Error:user not found.";
    }
    $conn->close();
}
