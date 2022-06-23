<?php
switch ($_POST['api']) {
    case "insertarItems":
        insertarItem($_POST['tema'], $_POST['titulo'], $_POST['item0'], $_POST['item1'], $_POST['item2'], $_POST['item3'], $_POST['item4'], $_POST['item5'], $_POST['item6'], $_POST['item7'], $_POST['item8'], $_POST['item9'],);

        break;
    default:

        echo "error";
        break;
}

// function insertarItem() {
//     $conn = new mysqli("sql4.freemysqlhosting.net", "sql4499513", "YJim2lFDE", "sql4499513");
//     $sql = "SELECT * FROM usuarios_temp WHERE id='" . $id . "' ;";
//     $result = $conn->query($sql);
// }

function insertarItem($tema, $titulo, $item0, $item1, $item2, $item3, $item4, $item5, $item6, $item7, $item8, $item9)
{
    $conn = new mysqli("localhost", "root", "", "pbd");
    $sql = "INSERT INTO listas (tema, titulo, item0, item1, item2, item3, item4, item5, item6, item7, item8, item9) VALUES ('" .  $tema . "','" . $titulo . "'," . $item0 .  ",'" .  $item1 . "','" .  $item2 . "','" .  $item3 . "','" .  $item4 . "','" .  $item5 . "','" .  $item6 . "','" .  $item7 . "','" .  $item8 . "','" . $item9 . "');";
    echo $sql;
    if ($conn->query($sql) === TRUE) {
        echo "Insert OK";
    } else {
        echo "<br>ERROR";
        //echo "Error: insert table \"usuarios\" " . $conn->error . " <br>" . $sql . "<br>";
    }
    $conn->close();
}
