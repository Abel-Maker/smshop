<?php
/**
 * Created by PhpStorm.
 * User: Abel
 * Date: 2018/9/6
 * Time: 10:52
 */
//设置页面内容时html ，编码格式为utf-8
header("Content-Type: application/json;charset=utf-8");
//HTML5-XHR2跨域
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Methods:POST,GET");

//判断如果是get请求，则进行搜索；如果是POST请求，则进行新建
//$_SERVER是一个超全局变量，在一个脚本的全部作用域中都可用，不用使用global关键字
//$_SERVER["REQUEST_METHOD"]返回访问页面使用的请求方法
if($_SERVER["REQUEST_METHOD"] == "GET"){
    search();
}elseif($_SERVER["REQUEST_METHOD"] == "POST"){
    create();
}
function search(){
    //数据库
    $link =mysqli_connect("localhost","root","###.","shop");
    mysqli_query($link,"set names utf8");

    //获取id
    //$commodity_id ="'".$_GET['commodity_id']."'";
    //echo

    //sql
    $sql="select * from shop.commodity WHERE status=1";
    //echo $sql;
    //获取id对应的参数
    $commodityRes = mysqli_query($link,$sql );
    $commodityNum =mysqli_num_rows($commodityRes);
    if ($commodityNum>0){
        $arr=array();
        for ($i=$commodityNum;$i>0;$i--){
            $commodity = mysqli_fetch_assoc($commodityRes);
            if($commodity['status']){
                $url ="'".$commodity['imageUrl']."'";
                $result = '{"success":true,"commodity_id":"'.$commodity['commodity_id'].'","commodity":"'.$commodity['commodity'].'","cate":"'.$commodity['cate'].'","in_price":"'.$commodity['in_price'].'","out_price":"'.$commodity['out_price'].'","detail":"'.$commodity['detail'].'","size":"'.$commodity['size'].'","imageUrl":"'.$commodity['imageUrl'].'","url":"'.$url.'"}';
            }
            $arr[]=$result;
        }

    }else{
        $result ='{"success":false,"msg":"当前没有商品!"}';
        $arr[]= $result;
    }
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
}
function create(){
    echo "CREATE";
}
