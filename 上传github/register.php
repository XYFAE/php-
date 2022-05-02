<?php
require_once 'dbconfig/mysql_PDO.php';
require_once 'dbconfig/mysql.php';
$conn = Connect();
header('Content-type:text/html; charset=utf-8');
session_start();

if (isset($_POST['reg'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rpassword = $_POST['rpassword'];
    $email = $_POST['email'];
    $phone = $_POST['phonenumber'];

    // 判断输入内容是否为空
    if (($username == '') || ($password == '') || ($rpassword == '') || ($phone == '')) {
        echo "<script>alert('用户名、密码、邮箱、手机号、不能为空,请重新注册')</script>";
        header('refresh:1;url=regi
        ster.html');
        exit;
    }
    //判断邮件是否合法&是否被注册
    if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $_POST['email'])) {
        $sql = "select * from users where email ='{$email}'";
        $exec = Execute($conn, $sql);
        if (mysqli_num_rows($exec) > 0) {
            echo "<script>alert('邮箱已被注册');</script>";
            header('refresh:1;url=register.html');
            exit;
        }
    } else {
        echo "<script>alert('邮箱格式不正确,请重新注册')</script>";
        header('refresh:1;url=register.html');
        exit;
    }

    //判断手机号是否合法&是否被注册
    if (preg_match("/^1[34578]\d{9}$/", $phone)) {
        $sql = "select * from users where phone='{$phone}'";
        $exec = Execute($conn, $sql);
        if (mysqli_num_rows($exec) > 0) {
            echo "<script>alert('手机号已被注册');</script>";
            header('refresh:1;url=register.html');
            exit;
        }
    } else {
        echo "<script>alert('手机号格式不正确,请重新注册')</script>";
        header('refresh:1;url=register.html');
        exit;
    }

    //验证两次密码是否一致
    if ($password != $rpassword) {
        echo "<script>alert('两次输入的密码不一致'</script>";
        header('refresh:1;url=register.html');
        exit;
    }
    //手机号验证
    function checkPhoneNumber($phone_number){
        //@2017-11-25 14:25:45 https://zhidao.baidu.com/question/1822455991691849548.html
        //中国联通号码：130、131、132、145（无线上网卡）、155、156、185（iPhone5上市后开放）、186、176（4G号段）、175（2015年9月10日正式启用，暂只对北京、上海和广东投放办理）,166,146
        //中国移动号码：134、135、136、137、138、139、147（无线上网卡）、148、150、151、152、157、158、159、178、182、183、184、187、188、198
        //中国电信号码：133、153、180、181、189、177、173、149、199
        $g = "/^1[34578]\d{9}$/";
        $g2 = "/^19[89]\d{8}$/";
        $g3 = "/^166\d{8}$/";
        if(preg_match($g, $phone_number)){
            return true;
        }else  if(preg_match($g2, $phone_number)){
            return true;
        }else if(preg_match($g3, $phone_number)){
            return true;
        }
 
        return false;
    }
    if(checkPhoneNumber($phone)!==true){
        echo "<script>alert('手机号码格式不正确,请重新注册')</script>";
        header('refresh:1;url=register.html');
    }

    // 判断用户名是否存在
    $sql = "select * from users where users='{$username}'";
    $exec = Execute($conn, $sql);
    $pass = mysqli_fetch_row($exec);
    if ($pass) {
        echo "<srcipt>alert('用户已存在')</script>";
        header('refresh:1;url=register.html');
        exit;
    }

    // 创建用户
    $password = md5($password);
    $sql = "insert into users(users,pass,email,phone) values('{$username}','{$password}','{$email}','{$phone}')";
    $exec = Execute($conn, $sql);
    if ($exec) {
        echo "<script>alert('注册成功')</script>";
        header('refresh:1;url=index.php');
        $_SESSION['username'] = $username;
        $_SESSION['islogin'] = 1;
        exit;
    }else{
        echo "?";
    }


}
