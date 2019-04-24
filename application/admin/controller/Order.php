<?php
/**
 * Created by PhpStorm.
 * User: 土豆
 * Date: 2019/4/21
 * Time: 15:33
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;

session_start();

class Order extends Controller{
    public function Order(){

        $user_list=db('order')->paginate(2);
        $this->assign('list',$user_list);


        return view('order/order');
    }
    public function update(){

        $o_id=$_POST['order_id'];
        $data=[
        "order_phone"=>$_POST['order_phone'],
        "order_courier_number"=>$_POST['courier_number']
        ];

        Db::table('order')->where('order_id',$o_id)->update($data);
        return $this->redirect('admin/order/order');
    }
    public function del(){
        $o_id=$_GET['order_id'];
        Db::table('order')->where('order_id',$o_id)->delete();
        return $this->redirect('admin/order/order');
    }

}