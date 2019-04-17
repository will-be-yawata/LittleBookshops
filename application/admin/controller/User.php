<?php
namespace app\admin\controller;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();


class User
{
    public function user(){
        echo (new ActiveCss())->shift("user");
        return view();
    }
}