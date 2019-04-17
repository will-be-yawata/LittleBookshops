<?php
namespace app\index\controller;
use think\Session;

Session::start();

class Index
{
    public function index()
    {
        session("nextUrl","Index/index");
        //echo $_SESSION["nextUrl"];
        return view();
    }
}
