<?php
namespace app\index\controller;
use app\index\model\Label;
use app\index\model\Topsearch;
use think\Controller;

class Aboutus extends Controller{
    public function aboutus(){
        $this->assign('label',(new Label())->label());
        $this->assign('topsearch',(new Topsearch())->topsearch());
        return view();
    }
}