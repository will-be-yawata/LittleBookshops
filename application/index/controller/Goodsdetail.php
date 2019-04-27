<?php
namespace app\index\controller;
use app\index\model\API;
use app\index\model\Goods;
use app\index\model\Label;
use app\index\model\Topsearch;

use think\Controller;

class Goodsdetail extends Controller{
    public function goodsdetail(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $this->assign('bestsell',(new Goods())->select_top6());
        $data = input();
        $goods = (new Goods())->detail_select($data['goods_id']);
        $this->assign('detail',$goods);
        $this->assign('bookinfo',(new API())->isbn_getbookinfo($goods['goods_isbn']));

        return view();
    }

}