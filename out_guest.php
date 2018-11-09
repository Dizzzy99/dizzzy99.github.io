<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Гость</title>
</head>
<body>
<?php

require_once ("admin/param.php");
if(empty($sort)){$sort="ASC";}
if(isset($_GET['sort'])){
    $sort=$_GET['sort'];
    switch ($sort){
        case "ASC" : $sort="DESC";break;
        case "DESC" : $sort="ASC";break;
        default: $sort="ASC";break;
    }
}
$query = "select id, name, dr, info, crime_cat, term, photo from prisoner";
$items=4;
$query_list="select id from prisoner";
////////////////////

?>
<form method="get" action="out_guest.php">
    <input type="text" name="search" placeholder="Введите...">
    <input type="submit" name="button" value="Поиск"><br>
    <?php
    if(!isset($_GET['back'])){
    ?>
    <input type="submit" name="back" value="Назад">
</form>
<?php

} elseif(isset($_GET['back'])){
    header("refresh:0; url=main_log_in.php");
}


if(isset($_GET['search'])){
    $search1=$_GET['search'];
    $search=str_replace(",", " ", $search1);
    $word=explode(" ", $search);
}
$final_w=array();
if(count($word)>0){
    foreach ($word as $tmp){
        if(!empty($tmp)){
            $final_w[]=$tmp;
        }
    }
}
$rezult_word=array();
if(count($final_w<0)){
    foreach ($final_w as $tmp){
        $rezult_word[]="name like '%".$tmp."%'";
    }
}
$where_rezult=implode(" or ", $rezult_word);
if(!empty($where_rezult))
{
    $query.=" WHERE $where_rezult";
    $query_list.=" WHERE $where_rezult";
}

/////////////////////
$rezult_list=mysqli_query($dbc,$query_list);
$rows_count=mysqli_num_rows($rezult_list);
$count_pages=ceil($rows_count/$items);
if(isset($_GET['page']) && !empty($_GET['page'])){
    $active_page=$_GET['page'];
} else {
    $active_page=1;
}
$skip=($active_page-1)*$items;

echo "<table border='1'>";
echo"
<tr>
<th><a href='out_guest.php?sort=".$sort."&search=".$search."&page=".$active_page."'>Название</a></th>
<th>Дата регистрации</th>
<th>Информация</th>
<th>Категория</th>
<th>Цена</th>
<th>Фото</th>
</tr>
";

$query.=" ORDER BY name $sort limit $skip, $items";

$rezult = mysqli_query($dbc, $query) or die("Errror query");
while ($next = mysqli_fetch_array($rezult)) {
    $name=$next['name'];
    $dr=myData($next['dr']);
    $info=$next['info'];
    $crime_cat=$next['crime_cat'];
    $query_c="select id, name_c from crimes WHERE id=$crime_cat";
    $rezult_c=mysqli_query($dbc, $query_c) or die("Error query cat");
    $next_c=mysqli_fetch_array($rezult_c);
    $crime_name=$next_c['name_c'];
    $term=$next['term'];
    $photo=$next['photo'];
    if(empty($photo)){
        $photo="nophoto.png";
    }
    echo"
    <tr>
<td>$name</td>
<td>$dr</td>
<td>$info</td>
<td>$crime_name</td>
<td>$term</td>
<td><img width='100px' src='images/$photo'></td>
</tr>
    ";
}
echo "</table>";
//////////////////////////
echo"<table border='1'><tr>";
if($active_page==1){
    echo"<td> << </td>";
} else {
    echo"<td><a href='out_guest.php?page=".($active_page-1)."&search=".$search."'> << </a></td>";
}
for($i=1;$i<=$count_pages;$i++){
    if($active_page==$i){
        echo"<td>$i</td>";
    } else {
        echo "<td><a href='out_guest.php?page=" . $i . "&search=".$search."'>$i</a></td>";
    }
}
if($active_page==$count_pages){
    echo"<td> >> </td>";
} else {
    echo"<td><a href='out_guest.php?page=".($active_page+1)."&search=".$search."'> >> </a></td>";
}
echo"</tr></table>";
?>
</body>
</html>