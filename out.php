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
    <title>Данные</title>
</head>
<body>
<?php
require_once("admin/param.php");
if(empty($_SESSION['userava'])){
    $_SESSION['userava']="nophoto.png";
}
echo"
<table border='3'>
<tr>
<th>Статус:</th>
<th>Имя</th>
<th>Фото</th>
</tr>
";
echo"
<tr>
<td>Администратор</td>
<td>".$_SESSION['usernick']."</td>
<td><img width='100px' src='images/".$_SESSION['userava']."'></td>
</tr>
</table>";
?>
<?php
$pass=false;
/////////////////////////////////////////
if(!isset($_GET['btn_crm_del']) && !isset($_GET['btn_prs']) && !isset($_GET['btn_adm']) && !isset($_GET['btn_crm']) && !isset($_GET['exit']) && !isset($_GET['edit']) && !isset($_GET['prof']) && !isset($_GET['admins'])) {

    echo "
    <form method='get' action='out.php'>
    <table border='5'>
    <tr>
    <td><input type='submit' name='prof' value='Мой профиль'></td>
    <td><input type='submit' name='admins' value='Администраторы'></td>
    <td><input type='submit' name='exit' value='Выход'></td>
    </tr>
    </table>
    <hr>
    <table border='4'>
    <tr> 
    <td><input type='submit' name='btn_prs' value='Новый товар'></td>
    <td><input type='submit' name='btn_adm' value='Добавить админа'></td>
    <td><input type='submit' name='btn_crm' value='Новая категория'></td>
    <td><input type='submit' name='btn_crm_del' value='Список категорий'></td>
    </tr>
    </table>
    <hr>
    </form>
";
    ?>
        <?php
}elseif(isset($_GET['btn_prs'])){
 header("refresh:0, url=addprisoner.php");
} elseif(isset($_GET['btn_adm'])){
    header("refresh:0, url=addadmin.php");
} elseif(isset($_GET['btn_crm'])){
    header("refresh:0, url=addcrime.php");
}elseif (isset($_GET['exit'])){
    header("refresh:0;url=exit.php");
} elseif (isset($_GET['prof'])){
    header("refresh:0; url=edit_myadm.php");
} elseif (isset($_GET['btn_crm_del'])){
  header("location:del_crime.php");
} elseif (isset($_GET['admins'])){
    $pass=true;
    $dostup=false;
    if(!isset($_GET['secure'])){
        ?>
        <form method="get" action="out.php"><table border="10">
            <tr>
                <td><input type="password" name="code" placeholder="Введите код доступа"></td>
            <td><input type="submit" name="secure" value="Подтвердить"></td>
                <input type="hidden" name="admins" value="admins">
            </tr>
            </table>
        </form>
        <?php
    } elseif(isset($_GET['secure']) && !empty($_GET['code']) && isset($_GET['admins'])){
        $code=$_GET['code'];
        if($code==8520){
            $dostup=true;
        } else{
            header("refresh:0; url=out.php");
        }
        if($dostup==true){
            header("refresh:0; url=admins.php");
        }
    }
}
///////////////////////////////
if($pass==false) {
    $query = "select id, name, dr, info, crime_cat, term, photo from prisoner";
    $items = 4;
    $query_list = "select id from prisoner";

    ?>
    <form method="get" action="out.php">
        <table border="2">
            <td><input type="text" name="search" placeholder="Введите..."></td>
            <td><input type="submit" name="button" value="Поиск"></td>
            </tr></table>
    </form>
    <?php
    if (isset($_GET['search'])) {
        $search1 = $_GET['search'];
        $search = str_replace(",", " ", $search1);
        $word = explode(" ", $search);
    }
    $final_w = array();
    if (count($word) > 0) {
        foreach ($word as $tmp) {
            if (!empty($tmp)) {
                $final_w[] = $tmp;
            }
        }
    }
    $rezult_word = array();
    if (count($final_w < 0)) {
        foreach ($final_w as $tmp) {
            $rezult_word[] = "name like '%" . $tmp . "%'";
        }
    }
    $where_rezult = implode(" or ", $rezult_word);
    if (!empty($where_rezult)) {
        $query .= " WHERE $where_rezult";
        $query_list .= " WHERE $where_rezult";
    }
    $rezult_list = mysqli_query($dbc, $query_list);
    $rows_count = mysqli_num_rows($rezult_list);
    $count_pages = ceil($rows_count / $items);
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $active_page = $_GET['page'];
    } else {
        $active_page = 1;
    }
    $skip = ($active_page - 1) * $items;
    if (empty($sort)) {
        $sort = "ASC";
    }
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        switch ($sort) {
            case "ASC":
                $sort = "DESC";
                break;
            case "DESC":
                $sort = "ASC";
                break;
            default:
                $sort = "ASC";
                break;
        }
    }
    $query .= " ORDER BY name $sort limit $skip, $items";

///////////////////////////////
    echo "<table border='1'>";
    echo "
<tr>
<th><a href='out.php?sort=" . $sort . "&search=" . $search . "&page=" . $active_page . "'>Имя</a></th>
<th>Дата регистрации</th>
<th>Информация</th>
<th>Категория</th>
<th>Цена</th>
<th>Фото</th>
<th>Изменить</th>
</tr>
";
    $rezult = mysqli_query($dbc, $query) or die("Error query");
    while ($next = mysqli_fetch_array($rezult)) {
        $id = $next['id'];
        $name = $next['name'];
        $dr = myData($next['dr']);
        $info = $next['info'];
        $crime_cat = $next['crime_cat'];
        $query_c = "select id, name_c from crimes WHERE id=$crime_cat";
        $rezult_c = mysqli_query($dbc, $query_c) or die("Error query cat");
        $next_c = mysqli_fetch_array($rezult_c);
        $crime_name = $next_c['name_c'];
        $term = $next['term'];
        $photo = $next['photo'];
        if (empty($photo)) {
            $photo = "nophoto.png";
        }
        echo "
    <tr>
<td>$name</td>
<td>$dr</td>
<td>$info</td>
<td>$crime_name</td>
<td>$term $</td>
<td><img width='100px' src='images/$photo'></td>
<td><a href='edit.php?id=" . $id . "&name=" . $name . "'>Редактировать запись</a></td>
</tr>
    ";
    }
    echo "</table>";
////////////////////
    echo "<table border='1'><tr>";
    if ($active_page == 1) {
        echo "<td> << </td>";
    } else {
        echo "<td><a href='out.php?page=" . ($active_page - 1) . "&search=" . $search . "'> << </a></td>";
    }
    for ($i = 1; $i <= $count_pages; $i++) {
        if ($active_page == $i) {
            echo "<td>$i</td>";
        } else {
            echo "<td><a href='out.php?page=" . $i . "&search=" . $search . "'>$i</a></td>";
        }
    }
    if ($active_page == $count_pages) {
        echo "<td> >> </td>";
    } else {
        echo "<td><a href='out.php?page=" . ($active_page + 1) . "&search=" . $search . "'> >> </a></td>";
    }
    echo "</tr></table>";
}

} else {
    echo"<table border='7'><td>Доступ закрыт!</td></table>";
    header("refresh:2; url=main_log_in.php");
}
?>
</body>
</html>
