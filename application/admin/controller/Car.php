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
    public function Car(){
        /**该函数用来显示所有的购物车数据库*/
        $list=Db::table("car")->paginate(3);
        $this->assign('list',$list);

        return view();
    }

}
