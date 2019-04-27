<?php
namespace app\index\controller;
use app\index\model\Label;
use app\index\model\Topsearch;
use app\index\model\Cart;
use think\Controller;

class Car extends Controller{
    public function car(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $this->assign('car',(new Cart())->cart_select());

        //var_dump((new Cart())->cart_select()[0]);
        return view();
    }
    public function car_add(){
        $goods_id = input('post.goods_id/s');
        $num = input('post.num/d');
        $res = (new Cart())->cart_add($goods_id,$num);
        return $res;
    }
    public function car_count(){
        $res = (new Cart())->cart_count();
        return $res;
    }
    public function car_sum(){
        $res = (new Cart())->cart_sum();
        return $res;
    }
    public function car_goodsnum_add(){
        $car_id = input('post.car_id/s');
        $goods_id = input('post.goods_id/s');
        $res = (new Cart())->cart_goodsnum_add($car_id,$goods_id);
        return $res;
    }
    public function car_goodsnum_minus(){
        $car_id = input('post.car_id/s');
        $goods_id = input('post.goods_id/s');
        $res = (new Cart())->cart_goodsnum_minus($car_id,$goods_id);
        return $res;
    }
    public function car_goodsnum_input(){
        $car_id = input('post.car_id/s');
        $goods_id = input('post.goods_id/s');
        $num = input('post.num/d');
        $res = (new Cart())->cart_goodsnum_input($car_id,$goods_id,$num);
        return $res;
    }
    public function car_selectone(){
        $car_id = input('post.car_id/s');
        $res = (new Cart())->cart_selectone($car_id);
        return $res;
    }
    public function car_delone(){
        $car_id = input('post.car_id/s');
        $res = (new Cart())->cart_goods_delone($car_id);
        return $res;
    }
    public function car_check_sum(){
        $car_id = input('post.car_id/a');
        $res = (new Cart())->cart_check_sum($car_id);
        return $res;
    }
    public function car_check_del(){
        $car_id = input('post.car_id/a');
        $res = (new Cart())->cart_check_del($car_id);
        return $res;
    }
}