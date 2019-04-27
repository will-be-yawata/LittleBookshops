<?php
namespace app\index\model;

use think\Model;
use think\Session;

class Cart extends Model{
    public function cart_select(){
        $user = Session::get('userInfo');
        $car = db('car')->where('user_id',$user['user_id'])->where('goods_state',1)->select();
        $data = $car;
        foreach($car as $k=>$v){
            $goods = db('goods')->where('goods_id',$v['goods_id'])->find();
            $data[$k]['goods'] = $goods;
        }
        return $data;
    }
    public function cart_add($goods_id,$num){
        $goods = db('goods')->where('goods_id',$goods_id)->find();
        $sum = $goods['goods_current_price'] * $num;
        $user = Session::get('userInfo');
        $state = db('car')->where('user_id','=',$user['user_id'])->where('goods_id','=',$goods_id)->where('goods_state',1)->find();
        if($state){
            $num = $num+$state['goods_num'];
            $sum = $sum+$state['goods_sum'];
            $res = db('car')->where('user_id',$user['user_id'])->where('goods_id',$goods_id)->where('goods_state',1)
                ->update([
                    'goods_num'=>$num,
                    'goods_sum'=>$sum
                ]);
        }else{
            $res = db('car')->insert([
                'goods_id' => $goods_id,
                'goods_num' => $num,
                'goods_sum' => $sum,
                'user_id' => $user['user_id'],
                'goods_state'=>1
            ]);
        }
        return $res;
    }
    public function cart_count(){
        $user = Session::get('userInfo');
        $res = db('car')->where('user_id',$user['user_id'])->where('goods_state',1)->count();
        return $res;
    }
    public function cart_sum(){
        $user = Session::get('userInfo');
        $res = db('car')->where('user_id',$user['user_id'])->where('goods_state',1)->sum('goods_sum');
        return $res;
    }
    public function cart_goodsnum_add($car_id,$goods_id){
        $goods = db('goods')->where('goods_id',$goods_id)->find();
        $car = db('car')->where('car_id',$car_id)->find();
        $res = db('car')->where('car_id',$car_id)->update([
            'goods_num' => $car['goods_num']+1,
            'goods_sum' => $car['goods_sum']+$goods['goods_current_price']
        ]);
        return $res;
    }
    public function cart_goodsnum_minus($car_id,$goods_id){
        $goods = db('goods')->where('goods_id',$goods_id)->find();
        $car = db('car')->where('car_id',$car_id)->find();
        $res = db('car')->where('car_id',$car_id)->update([
            'goods_num' => $car['goods_num']-1,
            'goods_sum' => $car['goods_sum']-$goods['goods_current_price']
        ]);
        return $res;
    }
    public function cart_goodsnum_input($car_id,$goods_id,$num){
        $goods = db('goods')->where('goods_id',$goods_id)->find();
        $res = db('car')->where('car_id',$car_id)->update([
            'goods_num' => $num,
            'goods_sum' => $goods['goods_current_price']*$num
        ]);
        return $res;
    }
    public function cart_selectone($car_id){
        $res = db('car')->where('car_id',$car_id)->find();
        return $res;
    }
    public function cart_goods_delone($car_id){
        $res = db('car')->where('car_id',$car_id)->delete();
        return $res;
    }
    public function cart_check_sum($car_id){
        $res = db('car')->where('car_id','in',$car_id)->sum('goods_sum');
        return $res;
    }
    public function cart_check_del($car_id){
        $res = db('car')->where('car_id','in',$car_id)->delete();
        return $res;
    }
}