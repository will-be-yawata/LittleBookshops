<?php
namespace app\index\controller;
use app\index\model\Label;
use app\index\model\Order;
use app\index\model\Topsearch;
use think\Controller;
use think\Session;

class Getorder extends Controller{
    public function getorder(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $this->assign('order_userinfo',(new Order())->select_user());

        $carids = input('get.carids/s');
        $carids = explode("," , $carids);
        Session::set('carids',null);
        Session::set('carids',$carids);

        $this->assign('order_carinfo',(new Order())->getorder_goods($carids));
        return view();
    }
    public function order_insert(){
        $consignee = input('post.consignee/s');
        $consigneePhone = input('post.consigneePhone/s');
        $address = input('post.address/s');
        $carids = Session::get('carids');
        $order_id = (new Order())->order_insert($consignee,$consigneePhone,$address,$carids);
        return $order_id;
    }

}