<?php
/**
 * Created by PhpStorm.
 * User: Abel
 * Date: 2018/8/9
 * Time: 16:46
 */
//设置页面内容时html ，编码格式为utf-8
//header("Content-Type:text/plain;charset=utf-8");
header("Content-Type: application/json;charset=utf-8");
//HTML5-XHR2跨域
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Methods:POST,GET");
require "../service/sqlLogin.php";
session_start();
//判断如果是get请求，则进行搜索；如果是POST请求，则进行新建
//$_SERVER是一个超全局变量，在一个脚本的全部作用域中都可用，不用使用global关键字
//$_SERVER["REQUEST_METHOD"]返回访问页面使用的请求方法
if($_SERVER["REQUEST_METHOD"] == "GET"){
    login();
}elseif($_SERVER["REQUEST_METHOD"] == "POST"){
    register();
}
function login(){
    /*字符处理*/
    $username ="'".$_GET['username']."'";
    $pwd ="'".$_GET['passwd']."'";

    $user = new \App\sqlLogin($username);

    if($user->status){
        if((str_replace('\'','',$username) ===$user->username)&&(str_replace('\'','',$pwd) ===$user->passwd)){

            $_SESSION['username']=$username;
            $result='{
           "success":true,
           "msg":"'.$user->username.'登陆",
           "session":"'.$_SESSION['username'].'"
           }';
            //echo $_SESSION['username'];
        }else{
            $result='{
            "success":false,
            "msg":"账户或密码错误！"
        }';
        }
    }else{
        $result='{
            "success":false,
            "msg":"你的账户或密码错误！"
        }';
    }
    echo $result;
}
function register(){
  echo "Register";
}