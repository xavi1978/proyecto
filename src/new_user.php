<?php
if (isset($_GET['id']) && isset($_GET['clave'])) {
    $id = $GET['id'];
    $clave = $GET['clave'];
    echo "id:" . $_GET['id'] . "<br>";
    echo "clave:" . $_GET['clave'] . "<br>";

    $conn = new mysqli(DB_DIRECCION, DB_USUARIO, DB_PW, DB_BASEDATOS);
    $sql = "SELECT * FROM usuarios_temp WHERE id='" . $id  . "' ;";
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
    }
    $xstring = $usuario->id . "-" . $usuario->email . "-" . $usuario->nombre . "-" . $usuario->phone . "-" . $usuario->password . "-" . $usuario->reg_date;
    $sha1 = sha1($xstring);

    if ($clave == $sha1) {
        insertUser($usuario);
    }
} else {
    echo "Error: <br>";
}
$conn->close();

function insertUser($user)
{
    $conn = new mysqli(DB_DIRECCION, DB_USUARIO, DB_PW, DB_BASEDATOS);
    $sql = "INSERT INTO usuarios (email,nombre,phone,password,reg_date) VALUES ('" . $user->email . "','" . $user->nombre . "'," . $user->phone . ",'" . $user->password . "','" . date("Y-m-d H:i:s") . "');";
    if ($conn->query($sql) === TRUE) {
        echo "<br>OK";
        $sql_a = "DELETE FROM usuarios_temp WHERE email='" . $user->email . "' || reg_date <= NOW() - INTERVAL 1 DAY;";
        $conn->query($sql_a);
        header('Location: login.html');
    } else {
        echo "<br>ERROR";
        //echo "Error: insert table \"usuarios\" " . $conn->error . " <br>" . $sql . "<br>";
    }
    $conn->close();
}
