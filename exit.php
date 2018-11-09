<?php
session_start();
?>
<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Выход</title>
</head>
<body>
<?php
if(!isset($_GET['button'])) {


    ?>
    <form action="exit.php" method="get">
        <table border="2">
            <tr>
                <th>Вы уверены что хотите выйти?</th>
            </tr>
        </table>
        <table border="2">
        <tr>
            <td><input checked type="radio" name="del" value="no">Нет</td>
            <td><input type="radio" name="del" value="yes">Да</td>
        </tr>
        </table><table border="4">
            <tr>
                <td><input type="submit" name="button" value="Подтвердить"></td>
            </tr></table>
    </form>
    <?php
} elseif (isset($_GET['button']) && $_GET['del'] == "yes") {

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
        header("refresh:0;url=out.php");

} else {
    header("refresh:0;url=out.php");
}
?>
</body>
</html>