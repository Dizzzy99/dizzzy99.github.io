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
    <title>Удаление администратора</title>
</head>
<body>
<?php
if(!isset($_GET['del_yes'])  && !empty($_GET['nick'])) {
    ?>
    <form action="del_adm.php" method="get">
        <table border="2">
            <tr>
                <th><h4>Удалить </th>
                <th><?= $_GET['nick'] ?>?</th>
                </h3></tr>
            <tr>
                <td align="center"><input type="submit" name="del_yes" value="Да"></td>
                <td align="center"><input type="submit" name="del_no" value="Нет"></td>
                <input type="hidden" name="id" value="<?=$_GET['id']?>">
            </tr>
        </table>
    </form>
    <?php
} elseif (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['del_yes'])) {
        $id = $_GET['id'];
        require_once("admin/param.php");
        $query_d = "select avatar from admins WHERE id=$id";
        $rezult_d = mysqli_query($dbc, $query_d) or die("Ошибка запроса удаления фото");
        $next_d = mysqli_fetch_array($rezult_d);
        $ava = $next_d['avatar'];
        if (!empty($ava)) {
            @unlink("images/$ava");
        }
        $query_del = "delete from admins WHERE id=$id";
        mysqli_query($dbc, $query_del) or die("Ошибка запроса удаления");
        echo "<br>Удаление успешно<br>";
        mysqli_close($dbc);
        header("refresh:2;url=main_log_in.php");
    } else {
        echo "Запрос отклонён!";
    }
} else {
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>