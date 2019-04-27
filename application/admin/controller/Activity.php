<?php
namespace app\admin\controller;
use app\admin\model\Admin;
use think\Session;
use think\Controller;
use app\admin\model\ActiveCss;
Session::start();
class Activity extends Controller{
    public function activity(){
        echo (new ActiveCss())->shift("activity");
        $admin=new Admin();
        if(request()->isPost()) {//登录页面进入此页面
            $adminInfo = input('post.');
            if (isset($adminInfo["username"]) && isset($adminInfo["password"])) {
                session("admin_name", $adminInfo["username"]);
                session("admin_password", $adminInfo["password"]);
            }
        }
        //登录判断
        if(!$admin->check_login()) return view("login/login");
        $activity=db('activity')->select();
        $activity = json_encode($activity);
        $this->assign('activity',$activity);
        return view();
    }
}