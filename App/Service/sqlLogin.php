<?php
/**
 * Created by PhpStorm.
 * User: Abel
 * Date: 2018/5/21
 * Time: 13:47
 */
namespace App;
//数据库连接

//mysqli_query("");
class sqlLogin{
    //public $user_id;
    public $username;
    public $passwd;
    public $status;
    protected $data;
    protected $change =false;
    function __construct($username)
    {
        $link =mysqli_connect("localhost","root","###.","shop");
        mysqli_query($link,"set names utf8");
        $res = mysqli_query($link,"select * from shop.user WHERE username=$username");
        $this->data=$res->fetch_assoc();
        // $this->user_id=$this->data['user_id'];
        $this->username=$this->data['username'];
        $this->passwd=$this->data['pwd'];
        $this->status=$this->data['status'];
        //$this->reg_time=$this->data['reg_time'];
    }
    function __get($key)
    {
        if (isset($this->data[$key]))
        {
            return $this->data[$key];
        }
    }

    function __set($key, $value)
    {
        $this->data[$key] = $value;
        $this->change = true;
    }
    /*    function __destruct()
        {
            $link =mysqli_connect("localhost","root","Abel555.","test_sql");
            mysqli_query($link,"set names utf8");
            // TODO: Implement __destruct() method.
            foreach ($this->data as $k=>$v){
                $fields[] ="$k='{$v}'";
            }
            mysqli_query($link,"INSERT INTO user(id,username,age,tel,reg_time) values(DEFAULT ,'{$this->username}','{$this->age}','{$this->tel}','{$this->reg_time}')");
        }*/
}
