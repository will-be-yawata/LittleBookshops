<?php
namespace app\index\controller;
use app\index\model\Label;
use app\index\model\Order;
use app\index\model\Topsearch;
use think\Controller;

class Orderlist extends Controller{
    public function orderlist(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $this->assign('orderlist',(new Order())->orderlist_select_all());

        return view();
    }
}