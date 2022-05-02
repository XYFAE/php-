<?php
require_once 'dbconfig/mysql_PDO.php';
require_once 'dbconfig/mysql.php';
$conn=Connect();
header('Content-type:text/html; charset=utf-8');
session_start();

if(isset($_POST['login'])){

    $username = trim($_POST['username']); 
    $password = trim($_POST['password']);

    if(($username == '') || ($password == '')){

        echo "用户名或密码不能为空,3秒后跳转到登录页面,请重新登录";
        header('refresh:3;url=login.html');
        exit;
    }
    $password = md5($password);
    $sql ="select * from users where users = '{$username}' and pass = '{$password}'";
    $exec = Execute($conn,$sql);
    //$result = mysqli_fetch_array($exec);
    if (mysqli_num_rows($exec) !== 1) {
        //echo '<script>window.location.href="xyfaelogin.php?notifications=2&notifications_content=账号或密码错误"</script>';
        echo "<script>alert('账号或密码错误');</script>";
        header('refresh:0;url=login.html');
        exit;
    }

    $_SESSION['username'] = $username;
    $_SESSION['islogin'] = 1;
    echo "登录成功,3秒后跳转到个人中心,请稍等";
    header('refresh:3;url=index.php');
    exit;
    //$_SESSION['id'] = $result['id'];

    if ($_POST['remember'] == "yes") {
        setcookie('username', $username, time() + 7 * 24 * 60 * 60);
        setcookie('code', md5($username . md5($password)), time() + 7 * 24 * 60 * 60);
    }  else {
        // 没有勾选则删除Cookie
        setcookie('username', '', time() - 999);
        setcookie('code', '', time() - 999);
    } 
    

    header('location:index.php');

}