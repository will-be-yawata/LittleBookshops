<?php
namespace app\index\controller;

use app\index\model\Activity;
use app\index\model\Label;
use app\index\model\Topsearch;
use think\Controller;
use think\Session;
use app\index\model\User;

class Userinfo extends Controller{
    public function userinfo(){
        $User = new User();

        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        if($User->check_user_state()){
            $res = $User->select_user(Session::get("userInfo.user_id"));
            $this->assign('res' , $res);
            return view();
        }else{
            return view("Index/index");
        }
    }
    public function update_userinfo(){
        $userId = Session::get("userInfo.user_id");
        $nickname = input("post.nickname/s");
        $consignee = input("post.consignee/s");
        $consigneePhone = input("post.consigneePhone/s");
        $address = input("post.address/s");

        $User = new User();
        $res = $User->update_user($userId,$nickname,$consignee,$consigneePhone,$address);

        return $res;
    }
}