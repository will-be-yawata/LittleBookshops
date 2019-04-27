<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\User;
use app\index\model\API;

class Resign extends Controller{
    public function resign(){
        return view();
    }
    public function check_user_id(){
        $user_id = input('post.user_id/s');
        $res = new User();
        $res = $res->select_user($user_id);
        if($res){
             return true;
        }else{
            return false;
        }
        //return $user_id;
    }
    public function sendPhoneCode(){
        $phone = $_POST["phone"];
        $code = $_POST["code"];
        $api = new API();
        $res = $api->sendCode($phone,$code);
        //返回值为 0 即发送成功
        if($res){
            return false;
        }else{
            return true;
        }
    }
    public function insertUser(){
        $usedId = input('post.userId/s');
        $password = input('post.password/s');
        $nickname = input('post.nickname/s');
        $consignee = input('post.consignee/s');
        $consigneePhone = input('post.consigneePhone/s');
        $address = input('post.address/s');
        $User = new User();
        $insert = $User->insert_user($usedId,$password,$nickname,$consignee,$consigneePhone,$address);
//        if($insert){
//            return true;
//        }else{
//            return false;
//        }
        return $insert;
    }
}