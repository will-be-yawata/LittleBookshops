<?php
namespace app\index\model;

use think\Model;

class User extends Model{
    public function select_user($name){
        $res = db("user")->where("user_id",$name)->select();
        //$res = mysqli_fetch_row($res);
        return $res;
    }
    public function insert_user($userId,$password,$nickname,$consignee,$consigneePhone,$address){
        $password = md5($password);
        $res = db("user")->insert([
            'user_id' => $userId,
            'user_password' => $password,
            'user_nickname' => $nickname,
            'user_name' => $consignee,
            'user_phone' => $consigneePhone,
            'user_address' => $address,
            'user_createtime' => date("Y-m-d H:i:s")
        ]);

        return $res;
    }
}