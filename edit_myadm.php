<?php
session_start();
if(isset($_COOKIE['userid']) && isset($_COOKIE['usernick']) && isset($_COOKIE['userava'])){
    $_SESSION['userid']=$_COOKIE['userid'];
    $_SESSION['usernick']=$_COOKIE['usernick'];
    $_SESSION['userava']=$_COOKIE['userava'];
}
if(isset($_SESSION['userid']) && isset($_SESSION['usernick']) && isset($_SESSION['userava'])) {
    if($_SESSION['usernick']=="guest") {
        echo "<table border='4'><td>Гость не может изменять запись</td></table>";
        header("refresh:2; url=out.php");
    } else {
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Профиль</title>
</head>
<body>
<?php
require_once("admin/param.php");
$id = $_SESSION['userid'];
$nick = $_SESSION['usernick'];
if (!empty($_SESSION['userava'])) {
    $ava = $_SESSION['userava'];
} else {
    $ava = "nophoto.png";
}
$query_get = "select password from admins WHERE id=$id";
$rezult_get = mysqli_query($dbc, $query_get) or die("error query getter");
$next = mysqli_fetch_array($rezult_get);
$pass = $next['password'];
if (!isset($_POST['button']) && !isset($_POST['back'])) {

    ?>
    <form method="post" action="edit_myadm.php" enctype="multipart/form-data">
        <table border="2">
            <tr>
                <th>Никнейм</th>
                <th>Новый пароль</th>
                <th>Фото</th>
                <th>Новое...</th>
            </tr>
            <tr>
                <td><input type="text" name="nick" value="<?= $nick ?>"></td>
                <td><input type="password" name="passnew" placeholder="Новый пароль"></td>
                <td><img src="images/<?= $ava ?>" width="100px"></td>
                <td><input type="file" name="newava"></td>
            </tr>
            <input type="hidden" name="pass" value="<?= $pass ?>">
            <input type="hidden" name="ava" value="<?= $ava ?>">
            <input type="hidden" name="id" value="<?= $id ?>"><br>
        </table>
        <table border="5">
            <tr>
                <td><input type="submit" name="button" value="Обновить"></td>
                <td><input type="submit" name="back" value="Назад"></td>
            </tr>
        </table>
    </form>
    <?php
} elseif (isset($_POST['button']) && !empty($_POST['id']) && !empty($_POST['nick']) && !isset($_POST['back'])) {
    $id = $_POST['id'];
    $nick = myProtect($dbc, $_POST['nick']);
    $ava = $_POST['ava'];
    if (!empty($_POST['passnew'])) {
        $pass_tmp = myProtect($dbc, $_POST['passnew']);
        $query = "update admins set nick='$nick', password=SHA1('$pass_tmp')";
    } else {
        $pass = myProtect($dbc, $_POST['pass']);
        $query = "update admins set nick='$nick', password='$pass'";
    }
    if ($_FILES['newava']['error'] == 0) {
        if ($ava != "nophoto.png") {
            @unlink("images/$ava");
        }
        $filenametmp = $_FILES['newava']['tmp_name'];
        $filename = $_FILES['newava']['name'];
        move_uploaded_file($filenametmp, "images/$filename");
        $query .= ", avatar='$filename' WHERE id=$id";
    } else {
        $query .= " WHERE id=$id";
    }
    mysqli_query($dbc, $query) or die("Error query");
    if (isset($_COOKIE['userid'])) {
        setcookie("userid", "", time() - 60 * 60 * 24);
    }
    if (isset($_COOKIE['usernick'])) {
        setcookie("username", "", time() - 60 * 60 * 24);
    }
    if (isset($_COOKIE['userava'])) {
        setcookie("userava", "", time() - 60 * 60 * 24);
    }
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 60 * 60 * 24);
    }
    $_SESSION = array();
    session_destroy();
    echo "Операция выполнена успешно!";
    header("refresh:2; url=out.php");
} elseif (isset($_POST['back'])) {
    header("refresh:0; url=out.php");
} else {
    echo "Недостаточно данных";
    header("refresh:2; url=out.php");
}
}
} else {
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>