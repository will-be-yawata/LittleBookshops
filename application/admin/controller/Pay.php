<?php
namespace app\admin\controller;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();


class Pay
{
    public function pay(){
        echo (new ActiveCss())->shift("pay");
        return view();
    }
}