<?php
/**
 * Created by PhpStorm.
 * User: 土豆
 * Date: 2019/4/19
 * Time: 15:10
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;

session_start();
class Car extends Controller{
    public function Car(){//查询所有用户的数据库
        /**该函数用来显示所有的购物车数据库*/
        //根据不同的用户来分别显示购物车
        //先查询有购物车的用户
        $user_list=Db::table('car')->alias('c')->join('user u','c.user_id=u.user_id')->field('distinct u.user_id,u.user_nickname')->paginate(2);
        $this->assign('list',$user_list);




        return view();
    }
    public function select($u_id){
        //这个函数可以查询某个用户id对应的购物车数据和用户数据,还有商品数据
        $data_name="g.goods_id,c.goods_num,g.goods_name,u.user_id,u.user_nickname,c.car_id,c.goods_num,c.order_id";//这个是需要查询的数据字段名
        $sql="select $data_name from car c,user u,goods g where u.user_id=c.user_id and u.user_id=$u_id and c.goods_id=g.goods_id";
        $res=Db::table('car')->query($sql);
        return $res;
    }
    public function update($c_id,$data){
        //该函数用于修改某个用户的购物车信息
        //$data是数组，保存着修改后的数据，字段为:car_id,goods_id,goods_num,goods_sum,user_id,order_id


    }
    public function user_car($u_id){
        $row=db('user')->where('user_id',$u_id)->select();
        $name=$row[0]['user_name'];
        $user_car_data=$this->select($u_id);
//        dump($user_car_data);
        $this->assign('list',$user_car_data);
        $a=new Mypage("asd",2);
        dump($a->size);
        return view('user_car',['name'=>$name]);
    }

}
class Mypage{
    public $data;
    public $size;
    public function construct($data,$size){//data是数据(二维数组)，size是分页的大小
        $this->data=$data;
        $this->size=$size;
    }
    public function show(){

    }
}
