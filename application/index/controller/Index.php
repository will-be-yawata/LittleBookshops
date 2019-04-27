<?php
namespace app\index\controller;
use app\index\model\Activity;
use app\index\model\Goods;
use app\index\model\Label;
use app\index\model\Topsearch;
use think\Controller;
use think\Session;


class Index extends Controller
{
    public function index()
    {
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $this->assign('carousel',(new Activity())->activity());
        $this->assign('rushed',(new Goods())->select_top6());
        $this->assign('focus',(new Goods())->focus());
        $this->assign('bestsell',(new Goods())->bestsell());
        $this->assign('author',(new Goods())->author());

//        $aa = (new Goods())->author();
//        var_dump($aa);

        return view();
    }

    public function getout(){
        Session::set('userInfo',null);
        return true;
    }

    public function check_user_state(){
        if(Session::get('userInfo') == null){
            return false;
        }else{
            return true;
        }
    }
    public function rm_redis(){
        (new Goods())->rm_redis();
        return "11";
    }
}
