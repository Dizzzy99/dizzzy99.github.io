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
    <title>Новый товар</title>
</head>
<body>
<h3>Товар</h3>
<?php
require_once("admin/param.php");
if (!isset($_POST['button']) && !isset($_POST['back'])) {
    ?>
    <form action="addprisoner.php" method="post" enctype="multipart/form-data">
        <table border="2">
            <tr>
                <th>Имя</th>
                <th>Дата регистрации</th>
                <th>Информация</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Фото</th>
            </tr>
            <tr>
                <td><input type="text" name="name" placeholder="Введите название"></td>
                <td><input type="date" name="dr"></td>
                <td><textarea name="info" placeholder="Введите информацию о товаре"></textarea></td>
                <td><select name="crime_cat">
                        <?php
                        $query_crime = "select id, name_c from crimes ";
                        $rezult_crime = mysqli_query($dbc, $query_crime) or die("Error query crimes");
                        while ($next_crime = mysqli_fetch_array($rezult_crime)) {
                            echo "<option value='" . $next_crime['id'] . "'>" . $next_crime['name_c'] . "</option>";
                        }
                        ?>
                    </select></td>
                <td><input type="text" name="term" placeholder="Укажите цену"></td>
                <td><input type="file" name="photo"></td>
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
} elseif (isset($_POST['button']) && !empty($_POST['name']) && !empty($_POST['dr']) && !empty($_POST['info']) && !empty($_POST['term'])) {
    $name = $_POST['name'];
    $dr = $_POST['dr'];
    $info = $_POST['info'];
    $term = $_POST['term'];
    $crime_cat = $_POST['crime_cat'];
    if ($_FILES['photo']['error'] == 0) {
        $fileNameTmp = $_FILES['photo']['tmp_name'];
        $fileName = time() . $_FILES['photo']['name'];
        move_uploaded_file($fileNameTmp, "images/$fileName");
        $query = "insert into prisoner(name, dr, info, crime_cat, term, photo)
    VALUES ('$name', '$dr', '$info', '$crime_cat', '$term', '$fileName')";
    } else {
        $query = "insert into prisoner(name, dr, info, crime_cat, term) 
    VALUES ('$name', '$dr', '$info', '$crime_cat', '$term')";
    }
    mysqli_query($dbc, $query) or die("Ошибка запроса");
    echo "<br>Товар успешно добавлен<br>";
    header("refresh:2; url=out.php");
} elseif (isset($_POST['back'])) {
    header("refresh:0; url=out.php");
} else {
    echo "<br>Не достаточно данных<br>";
}
}else{
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>