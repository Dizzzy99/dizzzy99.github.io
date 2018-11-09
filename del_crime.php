<?php
session_start();
if(isset($_COOKIE['userid']) && isset($_COOKIE['usernick']) && isset($_COOKIE['userava'])){
    $_SESSION['userid']=$_COOKIE['userid'];
    $_SESSION['usernick']=$_COOKIE['usernick'];
    $_SESSION['userava']=$_COOKIE['userava'];
}
if(isset($_SESSION['userid']) && isset($_SESSION['usernick']) && isset($_SESSION['userava'])) {

?>
    <!doctype html >
<html lang = "ru" >
<head >
    <meta charset = "UTF-8" >
    <meta name = "viewport"
          content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
    <meta http - equiv = "X-UA-Compatible" content = "ie=edge" >
    <title > Удаление категории </title >
</head >
<body >
<h3>Категории</h3>
<?php
require_once("admin/param.php");
if(!isset($_GET['id']) && !isset($_GET['back'])) {
    $query = "select id, name_c from crimes";
    $rezult = mysqli_query($dbc, $query) or die("Error query");
    echo "
    <table border='2'>
    <tr>
    <th>No</th>
    <th>Название</th>
    <th>Функция</th>
</tr>
";
    $num = 1;
    while ($next = mysqli_fetch_array($rezult)) {
        $id = $next['id'];
        $name_c = $next['name_c'];
        echo "
    <tr>
    <td>$num</td>
    <td>$name_c</td>
    <td><a href='del_crime.php?id=" . $id . "'>Удалить</a></td>
</tr>
";
        $num++;
    }
    echo "</table>
<form action='del_crime.php' method='get'>
<table border='3'><td>
<input type='submit' name='back' value='Назад'>
</td>
</table>
</form>
       ";
} elseif(isset($_GET['id']) && !isset($_GET['back'])){
    $id_del=$_GET['id'];
    $query_del="delete from crimes WHERE id=$id_del";
    mysqli_query($dbc,$query_del) or die("Error deleting");
    mysqli_close($dbc);
    echo"Успешно удалено!";
    header("refresh:2; url=del_crime.php");
} elseif(isset($_GET['back'])){
    header("location:out.php");
}else{
    echo"Ошибка передачи данных";
}

} else {
    echo"<table border='3'><td>Нет досутпа</td></table>";
}
?>
</body>
</html>