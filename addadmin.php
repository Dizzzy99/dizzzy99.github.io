<?php
session_start();
if(isset($_COOKIE['userid']) && isset($_COOKIE['usernick']) && isset($_COOKIE['userava'])){
    $_SESSION['userid']=$_COOKIE['userid'];
    $_SESSION['usernick']=$_COOKIE['usernick'];
    $_SESSION['userava']=$_COOKIE['userava'];
}
if(isset($_SESSION['userid']) && isset($_SESSION['usernick']) && isset($_SESSION['userava'])) {

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Новый админ</title>
</head>
<body>
<h3>
    <underline>Добавте администратора</underline>
</h3>
<?php
require_once("admin/param.php");
if (!isset($_POST['button']) && !isset($_POST['back'])) {
    ?>
    <form method="post" action="addadmin.php" enctype="multipart/form-data">
        <table border="2">
            <tr>
                <th>Позывной</th>
                <th>Пароль</th>
                <th>Фото</th>
                <th>Я не робот...</th>
                <th>Ответ</th>
            </tr>
            <tr>
                <td><input type="text" name="nick" placeholder="Введите никнейм"></td>
                <td><input type="password" name="password" placeholder="Введите пароль"></td>
                <td><input type="file" name="avatar"></td>
                <td><img src="captcha.php"></td>
                <td><input type="text" name="captcha" placeholder="Введите ответ"></td>
            </tr>
        </table>
        <table border="4">
            <tr>
                <td><input type="submit" name="button" value="Добавить"></td>
                <td><input type="submit" name="back" value="Назад"></td>
            </tr>
        </table>
    </form>


    <?php
} else if (isset($_POST['button']) && !empty($_POST['nick']) && !empty($_POST['password']) && !empty($_POST['captcha'])) {
    $cap = $_POST['captcha'];
    if ($_SESSION['sum'] == $cap) {
        $nick = myProtect($dbc, $_POST['nick']);
        $original = true;
        /////////////////////////////
        $query_origin = "select nick from admins";
        $rezult_origin = mysqli_query($dbc, $query_origin);
        while ($next_origin = mysqli_fetch_array($rezult_origin)) {
            $oldnick = $next_origin['nick'];
            if ($nick == $oldnick) {
                $original = false;
                break;
            }
        }
        //////////////////
        if ($original == true) {

            $pass = myProtect($dbc, $_POST['password']);
            if ($_FILES['avatar']['error'] == 0) {
                $fileNameTmp = $_FILES['avatar']['tmp_name'];
                $fileName = time() . $_FILES['avatar']['name'];
                move_uploaded_file($fileNameTmp, "images/$fileName");
                $query = "insert into admins (nick, password, avatar) VALUES ('$nick', SHA1('$pass'), '$fileName')";
            } else {
                $query = "insert into admins (nick, password) VALUES ('$nick', SHA1('$pass'))";
            }
            mysqli_query($dbc, $query) or die("Ошибка запроса");
            echo "<br>Админ успешно добавлен!<br>";
            mysqli_close($dbc);
            header("refresh: 2; url=main_log_in.php");
        } else if ($original == false) {
            echo "<hr>Позывной занят<hr>";
            header("refresh:3; url=addadmin.php");
        }
    } else {
        header("refresh:0; url=addadmin.php");
    }
} elseif (isset($_POST['back'])) {
    header("refresh:0; url=out.php");
} else {
    echo "<hr>Недостаточно данных<hr>";
    header("refresh:2; url=addadmin.php");
}
} else {
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
    ?>
</body>
</html>