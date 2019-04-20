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
    private static $test=0;
    private $data;
    private $total_count;
    private $total_page_num;
    private $size=2;//用户购物车页面每页显示数据量
    private $page_num=0;//当前页码
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
        $this->data=$res;
        $this->total_count=sizeof($this->data);
        $this->total_page_num=ceil($this->total_count/$this->size);

        return $res;
    }
    public function update($c_id,$data){
        //该函数用于修改某个用户的购物车信息
        //$data是数组，保存着修改后的数据，字段为:car_id,goods_id,goods_num,goods_sum,user_id,order_id


    }
    public function user_car($u_id,$page_num){
        if(!isset($page_num))$page_num=0;

        $this->page_num=$page_num;
        $row=db('user')->where('user_id',$u_id)->select();
        $name=$row[0]['user_name'];
        $user_car_data=$this->select($u_id);
        /**这里需要分页*/
        $user_car_data=array_slice($user_car_data,$page_num*$this->size,$this->size);

        $this->assign('list',$user_car_data);


        return view('user_car',['u_id'=>$u_id,'name'=>$name,'page_num'=>$page_num+1/*page_num是给用户看的，并不是真正的页面值*/,'real_page_num'=>$this->page_num]);
    }
    public function next($u_id){
        if($this->page_num<$this->total_page_num){
            echo "435";
            $this->page_num=$this->page_num+1;
//            return $this->user_car($u_id,$this->page_num);
        }
        else{
            echo $this->total_page_num;


        }

    }

}





