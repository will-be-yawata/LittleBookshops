<?php
namespace app\index\model;

use think\Model;
use think\Session;

class User extends Model{
    public function select_user($name){
        $res = db("user")->where("user_id",$name)->find();
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
    public function updata_password_user($user_id,$password){
        $password = md5($password);
        $res = db('user')->where('user_id',$user_id)->setField('user_password',$password);
        return $res;
    }
    public function update_user($userId,$nickname,$consignee,$consigneePhone,$address){
        $res = db('user')->where('user_id',$userId)->setField([
            'user_nickname'=>$nickname,
            'user_name'=>$consignee,
            'user_phone'=>$consigneePhone,
            'user_address'=>$address,
            'user_updatetime'=> date("Y-m-d H:i:s")
        ]);
        return $res;
    }

    public function check_user_state(){
        if(Session::get('userInfo') == null){
            return false;
        }else{
            return true;
        }
    }
}