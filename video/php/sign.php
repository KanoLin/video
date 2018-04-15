<?php
    //调用数据库配置文件
    include'config.php';
    //链接数据库
    $con=mysqli_connect($url,$user,$password,$dataBase);
    if (!$con) {
        $back['status']='1';
        $back['reason']='链接错误！' . mysqli_error();
        echo json_encode($back);
        die;
    }
    mysqli_set_charset($con,"utf8");

    //注册
    function signUp($username,$password,$email,$tel)
    {
        if (strlen($tel)>11){
            $back['status']='1';
            $back['reason']='手机号超过11位！';
            echo json_encode($back);
            return;
        }
        if (strlen($username)>20){
            $back['status']='1';
            $back['reason']='用户名超过20位！';
            echo json_encode($back);
            return;
        }
        if (strlen($email)>20){
            $back['status']='1';
            $back['reason']='邮箱超过20位！';
            echo json_encode($back);
            return;
        }
        global $con,$table;
        $stmt=mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt,"SELECT tel FROM `$table` WHERE tel=?");
        mysqli_stmt_bind_param($stmt,"s",$tel);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_fetch($stmt)){
            $back['status']='1';
            $back['reason']='手机号已被注册！';
            echo json_encode($back);
            return;
        }
        mysqli_stmt_prepare($stmt,"SELECT email FROM `$table` WHERE email=?");
        mysqli_stmt_bind_param($stmt,"s",$email);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_fetch($stmt)){
            $back['status']='1';
            $back['reason']='邮箱已被注册！';
            echo json_encode($back);
            return;
        }
        mysqli_stmt_prepare($stmt,"SELECT user FROM `$table` WHERE user=?");
        mysqli_stmt_bind_param($stmt,"s",$username);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_fetch($stmt)){
            $back['status']='1';
            $back['reason']='用户名已被注册！';
            echo json_encode($back);
            return;
        }
        mysqli_stmt_prepare($stmt,"INSERT INTO `$table`".
        "(user,pass,email,tel)".
        "VALUES".
        "(?,?,?,?)");
        $password=password_hash($password,PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt,"ssss",$username,$password,$email,$tel);  
        if (mysqli_stmt_execute($stmt)){
            $back['status']='0';
            echo json_encode($back);
        }
        else {
            $back['status']='1';
            $back['reason']='注册失败！';
            echo json_encode($back);
        }
    }
    
    //登陆
    function signIn($username,$password)
    {
        global $con,$table;
        $stmt=mysqli_stmt_init($con);
        mysqli_stmt_prepare($stmt,"SELECT pass FROM `$table` WHERE user=?");
        mysqli_stmt_bind_param($stmt,"s",$username);
        mysqli_stmt_bind_result($stmt,$pass);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_fetch($stmt)){
            if (password_verify($password,$pass)){
                $back['status']='0';
                echo json_encode($back);
            }
            else {
                $back['status']='1';
                $back['reason']='密码错误！';
                echo json_encode($back);
            }
        }
        else {
            $back['status']='1';
            $back['reason']='用户不存在！';
            echo json_encode($back);
        }
    }

    //判断动作
    if (isset($_POST['type'])){
        $username=isset($_POST['username'])?$_POST['username']:'';
        $password=isset($_POST['password'])?$_POST['password']:'';
        $email=isset($_POST['email'])?$_POST['email']:'';
        $tel=isset($_POST['tel'])?$_POST['tel']:'';
        switch ($_POST['type'])
        {
            case 'sign up':signUp($username,$password,$email,$tel);break;
            case 'sign in':signIn($username,$password);break;
        }
    }

    mysqli_close($con);
?>