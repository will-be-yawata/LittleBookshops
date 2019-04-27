<?php
namespace app\index\model;

use think\Model;
use think\Session;

class Order extends Model{
    public function select_user(){
        $user = Session::get("userInfo");
        $res = db('user')->where('user_id',$user['user_id'])->find();
        return $res;
    }
    public function getorder_goods($carids){
        $data = array();
        $data["goods"] = array();
        $data["sum"] = db('car')->where('car_id',"in",$carids)->sum('goods_sum');
        $data["sum"] = number_format($data["sum"] ,2);
        $data["num"] = count($carids);
        $car = db('car')->where('car_id',"in",$carids)->select();
        foreach($car as $k=>$v){
            $goods = db('goods')->where('goods_id',$v['goods_id'])->find();
            $data['goods'][$k] = $goods;
            $data['goods'][$k]['goods_num'] = $v['goods_num'];
            $data['goods'][$k]['goods_sum'] = $v['goods_sum'];
        }
        return $data;
    }
    public function order_insert($consignee,$consigneePhone,$address,$carids){
        $user = Session::get("userInfo");
        $goods_num = count($carids);
        $order_sum = db('car')->where('car_id',"in",$carids)->sum('goods_sum');
        $order_courier_number = "3730810127133";
        $order_state = "完成";
        db('order')->insert([
            'goods_num' => $goods_num,
            'order_sum' => $order_sum,
            'user_id' => $user['user_id'],
            'order_gain_name' => $consignee,
            'order_gain_phone' => $consigneePhone,
            'order_gain_address' => $address,
            'order_courier_number' => $order_courier_number,
            'order_state' => $order_state,
            'order_createtime' => date("Y-m-d H:i:s"),
            'order_deliverytime' => date("Y-m-d H:i:s",strtotime('+2 hour 15 minutes 20 seconds')),
            'order_completetime' => date("Y-m-d H:i:s",strtotime('+7 day 5 hour 35 minutes 35 seconds'))
        ]);

        $order_id = db('order')->where('user_id',$user['user_id'])->order('order_id desc')->select();
        db('car')->where('car_id',"in",$carids)->update([
            'goods_state' => 2,
            'order_id' => $order_id[0]['order_id']
        ]);
        return $order_id[0];
    }
    public function  order_select($order_id){
        $data = db('order')->where('order_id',$order_id)->find();
        $car = db('car')->where('order_id',$order_id)->select();
        $data['goods'] = array();
        foreach($car as $k=>$v){
            $goods = db('goods')->where('goods_id',$v['goods_id'])->find();
            $data['goods'][$k] = $goods;
            $data['goods'][$k]['goods_num'] = $v['goods_num'];
            $data['goods'][$k]['goods_sum'] = $v['goods_sum'];
        }
        $data['exp'] = array();
        $data['exp'] = (new API())->exp_order($data['order_courier_number']);
        return $data;
    }
    public function orderlist_select_all(){
        $user = Session::get("userInfo");
        $order = db('order')->where('user_id',$user['user_id'])->select();
        $data = array();
        foreach($order as $key => $value){
            $data[$key] = $value;
            $car = db('car')->where('order_id',$value['order_id'])->select();
            $data[$key]['goods'] = array();
            foreach($car as $k=>$v){
                $goods = db('goods')->where('goods_id',$v['goods_id'])->find();
                $data[$key]['goods'][$k] = $goods;
                $data[$key]['goods'][$k]['goods_num'] = $v['goods_num'];
                $data[$key]['goods'][$k]['goods_sum'] = $v['goods_sum'];
            }
        }
        return $data;
    }
}