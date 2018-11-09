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
    <title>Редактирование</title>
</head>
<body>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['name'])) {
    $id = $_GET['id'];
    $name = $_GET['name'];
    require_once("admin/param.php");
    $query_up = "select id, name, dr, info, crime_cat, term, photo from prisoner WHERE id=$id";
    $rezult_up = mysqli_query($dbc, $query_up) or die("Ошибка запроса редактирования");
    $next_up = mysqli_fetch_array($rezult_up);
    $name = $next_up['name'];
    $dr = $next_up['dr'];
    $info = $next_up['info'];
    $crime_cat = $next_up['crime_cat'];
    $term = $next_up['term'];
    $photo = $next_up['photo'];
    if (empty($photo)) {
        $photo = "nophoto.phg";
    }
    ?>
    <form method="post" action="edit.php" enctype="multipart/form-data">

        <h4>Внесите изменения</h4>
        <hr>
        <table border="2">
            <tr>
                <td><strong>Имя</strong></td>
                <td><strong>Дата регистрации</strong></td>
                <td><strong>Информация</strong></td>
                <td><strong>Категория</strong></td>
                <td><strong>Цена</strong></td>
                <td><strong>Фото</strong></td>
                <td><strong>Выбрать новое...</strong></td>
            </tr>
            <tr>
                <td><input type="text" name="name" value="<?= $name ?>"></td>
                <td><input type="date" name="dr" value="<?= $dr ?>"></td>
                <td><textarea name="info"><?= $info ?></textarea></td>
                <td><select name="crime_cat">
                        <?php
                        $query_cat = "select id, name_c from crimes";
                        $rezult_cat = mysqli_query($dbc, $query_cat) or die("Ошибка запроса категорий");
                        while ($nextc = mysqli_fetch_array($rezult_cat)) {
                            if ($crime_cat == $nextc['id']) {
                                echo " <option selected value='" . $nextc['id'] . "'>" . $nextc['name_c'] . "</option>";
                            } else {
                                echo " <option value='" . $nextc['id'] . "'>" . $nextc['name_c'] . "</option>";
                            }
                        }
                        ?>
                    </select></td>
                <td><input type="text" name="term" value="<?= $term ?>"></td>
                <td><img width="100px" src="images/<?= $photo ?>"></td>
                <td><input type="file" name="newphoto"></td>
                <td><input type="hidden" name="photo" value="<?= $photo ?>"></td>
        </table>
        <table border="4">
            <tr>
                <td><input type="submit" name="btn" value="Изменить"></td>
                <td><input type="submit" name="but_del" value="Удалить"></td>
                <td><input type="submit" name="back" value="Назад"></td>
            </tr>
            <input type="hidden" name="id" value="<?= $id ?>">
            </tr>
        </table>
    </form>
    <?php
} elseif (isset($_POST['btn']) && !empty($_POST['name']) && !empty(['dr']) && !empty($_POST['info']) && !empty($_POST['term']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $dr = $_POST['dr'];
    $info = $_POST['info'];
    $crime_cat = $_POST['crime_cat'];
    $term = $_POST['term'];
    require_once("admin/param.php");
    $photo = $_POST['photo'];
    if ($_FILES['newphoto']['error'] == 0) {
        if ($photo != "nophoto.png") {
            @unlink("images/$photo");
        }
        $fileNameTmp = $_FILES['newphoto']['tmp_name'];
        $fileName = $_FILES['newphoto']['name'];
        move_uploaded_file($fileNameTmp, "images/$fileName");
        $query = "update prisoner set name='$name', dr='$dr', info='$info', crime_cat='$crime_cat', term='$term', photo='$fileName' WHERE id=$id";
    } else {
        $query = "update prisoner set name='$name', dr='$dr', info='$info', crime_cat='$crime_cat', term='$term' WHERE id=$id";
    }
    mysqli_query($dbc, $query);
    echo "Данные успешно обновлены";
    mysqli_close($dbc);
    header("refresh:2;url=out.php");
} elseif (isset($_POST['but_del'])) {
    $id = $_POST['id'];
    require_once("admin/param.php");
    $query_d = "select photo from prisoner WHERE id=$id";
    $rezult_d = mysqli_query($dbc, $query_d) or die("Ошибка запроса удаления фото");
    $next_d = mysqli_fetch_array($rezult_d);
    $photo = $next_d['photo'];
    if (!empty($photo)) {
        @unlink("images/$photo");
    }
    $query_del = "delete from prisoner WHERE id=$id";
    mysqli_query($dbc, $query_del) or die("Ошибка запроса удаления");
    echo "<table border='2'><td>Удаление успешно</td></table>";
    mysqli_close($dbc);
    header("refresh:2;url=out.php");
} elseif (isset($_POST['back'])) {
    header("refresh:0; url=out.php");
} else {
    echo "<table border='2'><td>Ошибка изменения</td></table>";
}
}else{
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>