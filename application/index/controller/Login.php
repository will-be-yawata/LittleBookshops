<?php
namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Session;

class Login extends Controller{
    public function login(){
        return view();
    }
    public function check_user_id(){
        $userId = input('post.user_id/s');
        $User = new User();
        $res = $User->select_user($userId);
        //存在返回 1
        if($res){
            return true;
        }else{
            return false;
        }
        //return $res;
    }
    public function check_user_password(){
        $userId = input('post.user_id/s');
        $password = input('post.password/s');
        $password = md5($password);
        $User = new User();
        $res = $User->select_user($userId);
        if($res['user_password'] == $password){
           $data =[
               'user_id' => $res['user_id'],
               'user_nickname' => $res['user_nickname']
           ];
            //$data = json_encode($data);
            Session::set('userInfo' , $data);
            return true;
        }else{
            return false;
        }

    }
}