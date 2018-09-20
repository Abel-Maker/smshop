<?php
/**
 * Created by PhpStorm.
 * User: Abel
 * Date: 2018/9/5
 * Time: 11:27
 */

//连接数据库
$conn=mysqli_connect("localhost","root","###.",'shop');
//if (!$conn)       die('Could not connect: '.mysqli_error());
mysqli_query($conn,"set names utf8");

//当点击submit时处理
if(isset($_POST['submit'])){
    if ($_FILES["file"]["error"] > 0){//如果上传出错
        echo "Error: " . $_FILES["file"]["error"] . "<br />";
    }else{
        $commodity = "'".$_POST['commodity']."'";
        $in_price = "'".$_POST['in_price']."'";
        $out_price = "'".$_POST['out_price']."'";
        $cate = "'".$_POST['cate']."'";
        $size = "'".$_POST['Size']."'";
        $detail = "'".$_POST['detail']."'";
        $imageUrl= "'../../source/".$_FILES["file"]["name"]."'";// 存储在服务器的文件的临时副本的名称
        //echo "商品名称".$commodity."<br>商品价格".$price."<br>商品分类".$cate."<br>图片路径".$imageUrl;
        $sqlstr2="select * from shop.commodity where commodity =$commodity";
        $word2=mysqli_query($conn,$sqlstr2);
        $thread=mysqli_fetch_assoc($word2);
        //图片另存为自己的路径下
        if (file_exists("../../source/" . $_FILES["file"]["name"])&&isset($thread['commodity'])){
            //echo $_FILES["file"]["name"] . " already exists. ";
            echo "<script>alert($commodity+'already exists')</script>";
        }else{
            move_uploaded_file($_FILES["file"]["tmp_name"], "../../source/" . $_FILES["file"]["name"]);
            if (isset($imageUrl)&&isset($commodity)&&isset($in_price)&&isset($cate)&&isset($size)&&isset($detail)&&isset($out_price)){
                //存入数据库
                $sqlstr1="insert into shop.commodity(`commodity`,`cate`,`size`,`detail`,`in_price`,`out_price`,`imageUrl`) values($commodity,$cate,$size,$detail,$in_price,$out_price,$imageUrl)";
                //echo $sqlstr1;
                $word=mysqli_query($conn,$sqlstr1);
                //取出，显示
                $sqlstr2="select * from shop.commodity where commodity =$commodity";
                $word2=mysqli_query($conn,$sqlstr2);
                $thread=mysqli_fetch_assoc($word2);
                if(isset($thread)){
                    //header('content_type:'.$thread['type']);
                   // echo "<img src='".$thread['imageUrl']."'/>";
                    echo "<script>alert('保存成功！');window.location.href='../View/index.html';</script>";
/*                    echo '<div class="dialog">'
                            .'<div class="dialog-head">'
                                  .'<span class="dialog-close close rotate-hover"></span>'
                                  .'<strong>提示：</strong></div>'
                                  .'<div class="dialog-body">保存成功！</div><div class="dialog-foot">'
                              .'<a class="dialog-close button" href="../View/create.html">继续添加</a>'
		                        .'<a class="button bg-green" href="../View/index.html">查看商品</a></div></div>';*/
                    //echo '<html><link rel="stylesheet" href="../../Public/css/pintuer.css" type="text/css"><div class="dialog"><div class="dialog-head"><span class="dialog-close close rotate-hover"></span><strong>提示：</strong></div><div class="dialog-body">保存成功！</div><div class="dialog-foot"><a class="dialog-close button" href="../View/create.html">继续添加</a><a class="button bg-green" href="../View/index.html">查看商品</a></div></div></html>';
                }
            }
        }
    }
}
if(isset($_POST['save'])){
    //echo "OK";

        //判断是否有输入
    //if ((isset($_POST['commodity']))&&($in_price == '')&&($out_price == '')&&($cate == '')&&($size == '')&&($detail == '')){
    //if ((isset($_POST['commodity']))&&(isset($_POST['in_price']))&&(isset($_POST['out_price']))&&(isset($_POST['cate']))&&(isset($_POST['Size']))&&(isset($_POST['detail']))){

    if (($_POST['commodity'] =='')&&($_POST['in_price'] =='')&&($_POST['out_price'] =='')&&(isset($_POST['cate']))&&($_POST['Size'] == '')&&($_POST['detail'] == '')){
        //var_dump($_POST);
        echo "<script>alert('商品的信息未填写');window.location.href='../View/index.html';</script>";
        }else{
        $commodity_id =$_POST['commodity_id'];
        //print_r($_POST);
/*        echo "商品序号：".$commodity_id."<br>商品名称：".$commodity."<br>商品价格：".$in_price."<br>商品分类：".$cate."<br>售价：".$out_price."<br>型号：".$size.
            "<br>描述：".$detail;*/
        $sqlstr2="select * from shop.commodity where commodity_id =$commodity_id";
        $word2=mysqli_query($conn,$sqlstr2);
        $thread=mysqli_fetch_assoc($word2);
        if($thread['status']){
            //如果所有的相同，则不保存
            if (($thread['commodity'] == $_POST['commodity'])&&($thread['in_price'] == $_POST['in_price'])
                &&($thread['out_price'] == $_POST['out_price'])&&($thread['cate'] == $_POST['cate'])
                &&($thread['size'] == $_POST['Size'])&&($thread['detail'] == $_POST['detail'])){
                //echo $_FILES["file"]["name"] . " already exists. ";
                echo "<script>alert('商品的信息未修改');window.location.href='../View/edit.html';</script>";
            }else{
                $commodity = "'".$_POST['commodity']."'";
                $in_price = "'".$_POST['in_price']."'";
                $out_price = "'".$_POST['out_price']."'";
                $cate = "'".$_POST['cate']."'";
                $size = "'".$_POST['Size']."'";
                $detail = "'".$_POST['detail']."'";
                $imageUrl ="'".$thread['imageUrl']."'";
/*                echo "商品序号：".$commodity_id."<br>商品名称：".$commodity."<br>商品价格：".$in_price."<br>商品分类：".$cate."<br>售价：".$out_price."<br>型号：".$size.
            "<br>描述：".$detail;*/
                //move_uploaded_file($_FILES["file"]["tmp_name"], "../../source/" . $_FILES["file"]["name"]);
                if (isset($commodity)||isset($in_price)||isset($cate)||isset($size)||isset($detail)||isset($out_price)){
                    //存入数据库
                    //$updat="update shop.commodity(`commodity`,`cate`,`size`,`detail`,`in_price`,`out_price`,`imageUrl`) values($commodity,$cate,$size,$detail,$in_price,$out_price,$imageUrl)";
                    $update ="UPDATE shop.commodity SET `commodity`=$commodity,`imageUrl`=$imageUrl,`cate`=$cate,`size`=$size,
                                `in_price`=$in_price,`out_price`=$out_price,`detail`=$detail WHERE commodity_id=$commodity_id";
                    //echo "<br>".$update;
                    $updateRes=mysqli_query($conn,$update);
                    //取出，显示
                    $verify="select * from shop.commodity where commodity =$commodity";
                    $verifyRes=mysqli_query($conn,$verify);
                    $threadVer=mysqli_fetch_assoc($verifyRes);
                    if(isset($threadVer)){
                        echo "<script>alert('保存成功！');window.location.href='../View/index.html';</script>";
                        //;
                    }
                }
            }
        }
    }

}
if(isset($_POST['del'])){


    //echo "OOK".$_POST['commodity_id'];
    if ($_POST['commodity_id']){
        $commodity_id =$_POST['commodity_id'];
        //$commodity ='ni';
        $delete ="UPDATE shop.commodity SET `status`=0 WHERE commodity_id=$commodity_id";

        $deleteRes =mysqli_query($conn,$delete);

        $verify="select * from shop.commodity where commodity_id =$commodity_id";
        $verifyRes=mysqli_query($conn,$verify);
        $threadVer=mysqli_fetch_assoc($verifyRes);
        $commodity =$threadVer['commodity'];

        //echo $threadVer['status'];
        if(!$threadVer['status']){
            //echo $threadVer['status'];
            echo "<script>alert('商品删除成功！');window.location.href='../View/index.html';</script>";
            //;
        }
    }
}
