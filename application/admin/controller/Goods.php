<?php
namespace app\admin\controller;
use app\admin\model\Admin;
use think\Controller;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();

class Goods extends Controller
{
    public function goods($type){//type:0->无、1->查、2->增、3->删、4->改
        echo (new ActiveCss())->shift("goods");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");


        if($type=1){

        }
        else if($type=2){

        }
        else if($type=3) {
            if (request()->isPost()) {
                $data = input('post.');
                $num=db("goods")->where("goods_id","in",$data["del_id"])->delete();
                echo "<script>alert('删除成功');</script>";
            }
        }
        else if($type=4){

        }

        $goods_list=db("goods")->select();
        $label_list=db("label")->select();
        if(count($goods_list)>0){
            for($i=0;$i<count($goods_list);$i++){
                $key=0;
                $goods_list[$i]["goods_label_list"]=explode(",",$goods_list[$i]["goods_label_list"]);
                $label=array();
                for($j=0;$j<count($goods_list[$i]["goods_label_list"]);$j++){
                    $tri=db("label")->where("label_id","=",$goods_list[$i]["goods_label_list"][$j])->select();
                    $sec=db("label")->where("label_id","=",$tri[0]["label_parent"])->select();
                    $pri=db("label")->where("label_id","=",$sec[0]["label_parent"])->select();
                    $label[$key++]=[$pri[0]["label_name"],$sec[0]["label_name"],$tri[0]["label_name"]];
                }
                $goods_list[$i]["label_list"]=$label;
            }
        }
        //dump($label_list);
        $goods_list = json_encode($goods_list);
        $label_list = json_encode($label_list);
        $this->assign("goods_list",$goods_list);
        $this->assign("label_list",$label_list);





        return view();
    }
}