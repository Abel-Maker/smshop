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
    $link =mysqli_connect("localhost","root","Abel555.","shop");
    mysqli_query($link,"set names utf8");

    //获取id
    $commodity_id ="'".$_GET['commodity_id']."'";
    //echo
    //sql
    $sql="select * from shop.commodity WHERE status=1 AND commodity_id=$commodity_id";
    //echo $sql;
    //获取id对应的参数
    $commodityRes = mysqli_query($link,$sql );
    $arr=array();
    //获取id对应的comment
        $commodity = mysqli_fetch_assoc($commodityRes);
        $url ="'".$commodity['imageUrl']."'";
        if($commodity['status']){
            // echo $url."\n";
            //$result = '{"totalItem":"'.$totalItem.'","pageSize":"'.$pageSize.'","totalPage":"'.$totalPage.'","username":"用户:'.$value.'","content":"留言内容:<br>'.$comment['content'].'"}';
            //$result .= '"username":"用户:'.$value.'","content":"留言内容:<br>'.$comment['content'].'"}';
            $result = '{"commodity_id":"'.$commodity['commodity_id'].'","commodity":"'.$commodity['commodity'].'","cate":"'.$commodity['cate'].'","in_price":"'.$commodity['in_price'].'","out_price":"'.$commodity['out_price'].'","detail":"'.$commodity['detail'].'","size":"'.$commodity['size'].'","imageUrl":"'.$commodity['imageUrl'].'","url":"'.$url.'"}';
            $arr[]=$result;
        }
    //echo $c;
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
}
function create(){
    echo "CREATE";
}