<?php
namespace app\index\controller;

use app\index\model\Goods;
use app\index\model\Label;
use app\index\model\Topsearch;
use think\Controller;

class Goodslist extends Controller
{
    public function goodslist(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        $this->assign('bestsell',(new Goods())->select_top6());
        $data = input();
        if($data['type'] == 1){
            $this->assign('goodslist',(new Goods())->goodslist_label_select($data['info']));
        }else if($data['type'] == 2){
            $this->assign('goodslist',(new Goods())->goodslist_headersearch($data['info']));
        }


        return view();
    }
}