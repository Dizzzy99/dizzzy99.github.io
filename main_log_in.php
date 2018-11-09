<?php
    session_start();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход</title>
</head>
<body>
<?php
if(!isset($_POST['button']) && !isset($_POST['btn_guest'])) {
    ?>
    <form action="main_log_in.php" method="post">
        <input type="text" name="nick" placeholder="Ваш ник..."><br>
        <input type="password" name="pass" placeholder="Ваш пароль..."><br>
        <input type="submit" name="button" value="Войти">
        <input type="submit" name="btn_guest" value="Войти как ГОСТЬ">
    </form>

    <?php
} else if(isset($_POST['button']) && !empty($_POST['nick']) && !empty($_POST['pass'])){
    require_once ("admin/param.php");
    $nick=myProtect($dbc,$_POST['nick']);
    $pass=myProtect($dbc,$_POST['pass']);
    $query="select id, nick, password, avatar from admins WHERE nick='$nick' AND password=SHA1('$pass')";
    //echo"<br>$query<br>";
    $rezult=mysqli_query($dbc,$query) or die("Ошибка Запроса БД");
    if(mysqli_num_rows($rezult)==1){
        $next=mysqli_fetch_array($rezult);
        $id=$next['id'];
        $user=$next['nick'];
        $ava=$next['avatar'];
        $_SESSION['userid']=$id;
        $_SESSION['usernick']=$user;
        $_SESSION['userava']=$ava;
        setcookie("userid", $id, time()+60*60*24*30*3);
        setcookie("usernick", $user,time()+60*60*24*30*3);
        setcookie("userava", $ava, time()+60*60*24*30*3);
        echo"<h2 align='center'><strong>Добро пожаловать!</strong></h2>";
        if(!empty($ava)) {
            echo"<div align='center'><table border='3'>
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
        }else {
            echo"<table border='3'>
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
    <td><img width='100px' src='images/nophoto.png'></td>
    </tr>
    </table></div>";
        }

       header("refresh:2; url=out.php?");
    } else {
        echo"У вас нет доступа!";
        header("refresh: 3; url=main_log_in.php");
    }
} elseif (isset($_POST['btn_guest'])){
    header("refresh:0;url=out_guest.php");
} else {
    header("refresh:0;url=main_log_in.php");
}

?>
</body>
</html>