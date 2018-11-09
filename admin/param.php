<?php
$dbc=mysqli_connect("localhost", "root", "", "prison") or die("Подключение к БД не удалось");
function myProtect($link, $data){
    return mysqli_real_escape_string($link,trim($data));
}
function myData($dat){
    $year= substr($dat, 0,4);
    $month= substr($dat, 5,2);
    $day=substr($dat, 8, 2);
    return $day."-".$month."-".$year;
}
?>