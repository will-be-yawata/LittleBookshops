<?php
namespace app\admin\controller;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();


class Groups
{
    public function groups(){
        echo (new ActiveCss())->shift("groups");
        return view();
    }
}