<?php
namespace app\index\controller;
use app\index\model\Label;
use app\index\model\Topsearch;
use think\Controller;

class Order extends Controller{
    public function order(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $order = input();
        $this->assign('orderinfo',(new \app\index\model\Order())->order_select($order['order_id']));
        return view();
    }
}