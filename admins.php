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
    <title>Panel</title>
</head>
<body>
<?php
require_once("admin/param.php");
if (empty($_SESSION['userava'])) {
    $_SESSION['userava'] = "nophoto.png";
}
echo "
<table border='3'>
<tr>
<th>Статус:</th>
<th>Имя</th>
<th>Фото</th>
</tr>
";
echo "
<tr>
<td>Админитсратор</td>
<td>" . $_SESSION['usernick'] . "</td>
<td><img width='100px' src='images/" . $_SESSION['userava'] . "'></td>
</tr>
</table>";


if (!isset($_GET['back'])) {
    ?>
    <form method="get" action="admins.php">
        <table border="4">
            <tr>
                <td><input type="submit" name="back" value="Назад"></td>
            </tr>
        </table>
    </form>
    <?php
} elseif (isset($_GET['back'])) {
    header("refresh:0; url=out.php");
}
if (!isset($_GET['close']) && !isset($_GET['open'])) {
    ?>
    <form method='get' action='admins.php'>
        <table border='2'>
            <td>
                <input type='submit' name='open' value='Открыть список'>
            </td>
        </table>
    </form>
    <?php
}
$query = "select id, nick, password, avatar from admins";
$rezult = mysqli_query($dbc, $query);
if (!isset($_GET['open']) && isset($_GET['close'])) {
    ?>
    <form method='get' action='admins.php'>
        <table border='2'>
            <td>
                <input type='submit' name='open' value='Открыть список'>
            </td>
        </table>
    </form>
    <?php
} elseif (isset($_GET['open']) && !isset($_GET['close'])) {
    echo "<form method='get' action='admins.php'>
<table border='2'>
<td><input type='submit' name='close' value='Свернуть'></td>
</table>
</form>";
    echo "
<table border='1'>
<tr>
<th>Nо</th>
<th>Ник</th>
<th>Фото</th>
<th>Функции</th>
</tr>

";
    $num = 1;
    while ($next = mysqli_fetch_array($rezult)) {
        $id = $next['id'];
        $nick = $next['nick'];
        $ava = $next['avatar'];
        if (empty($ava)) {
            $ava = "nophoto.png";
        }
        echo "
    <tr>
    <td>$num</td>
    <td>$nick</td>
    <td><img src='images/$ava' width='150px'></td>
    <td><a href='del_adm.php?id=" . $id . "&nick=" . $nick . "'>Удалить $nick</a></td>
</tr>
    ";
        $num++;
    }
    echo "</table>";
}
}
} else {
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>