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
    <title>Категории</title>
</head>
<body>
<h3><strong>Добавтье категорию</strong></h3>
<?php
if (!isset($_POST['button']) && !isset($_POST['back'])) {
    ?>
    <form method="post" action="addcrime.php">
        <table border="3">
            <tr>
                <th>Категория</th>
                <th>Я не робот...</th>
                <th>Ваш ответ</th>
            </tr>
            <tr>
                <td><input type="text" name="name_c" placeholder="Введите категорию"></td>
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
} else if (isset($_POST['button']) && !empty($_POST['name_c']) && !empty($_POST['captcha'])) {
    $cap = $_POST['captcha'];
    if ($cap == $_SESSION['sum']) {
        $name_c = $_POST['name_c'];
        require_once("admin/param.php");
        $query = "insert into crimes(name_c) VALUES ('$name_c')";
        mysqli_query($dbc, $query) or die("Errro of query crimes");
        echo "Категория успешно добавлена!";
        header("refresh:2; url=out.php");
        mysqli_close($dbc);
    } else {
        header("refresh:0; url=addcrime.php");
    }
} elseif (isset($_POST['back'])) {
    header("refresh:0;url=out.php");
} else echo "Не достаточно данных.";
}else {
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>