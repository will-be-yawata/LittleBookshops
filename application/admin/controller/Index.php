<?php
namespace app\admin\controller;
use app\admin\model\Admin;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();
class Index{
    public function index(){
        echo (new ActiveCss())->shift("index");
        $admin=new Admin();
        $data=array();
        if(request()->isPost()) {//登录情况
            $data = input('post.');
            if (isset($data["username"]) && isset($data["password"])) {
                session("admin_name", $data["username"]);
                session("admin_password", $data["password"]);
            }
        }
        if($admin->check_login()) return view();
        else return view("Login/login");
    }
}