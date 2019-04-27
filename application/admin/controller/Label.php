<?php
namespace app\admin\controller;
use app\admin\model\Admin;
use think\Session;
use think\Controller;
use app\admin\model\ActiveCss;
Session::start();
class Label extends Controller{
    public function label($type){//type:0->无，1->查，2->增，3->删，4->改
        echo (new ActiveCss())->shift("label");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");
        if($type==1){//查询
            if(request()->isPost()){

            }
        }
        if($type==2){//添加
            if(request()->isPost()){
                $addData=input('post.');
                $data=array();
                if(count($addData)>0&&isset($addData["label"])){
                    for($i=0;$i<count($addData["label"]);$i++){
                        $data[$i]=explode(",",$addData["label"][$i]);
                    }
                    //  ①插入一级标签 查询id
                    //  ②修改二级parent   插入二级标签  查询id
                    //  ③修改三级parent 插入三级标签
                    $pri=array();$priID=array();
                    $sec=array();$secID=array();
                    $tri=array();$triID=array();
                    for($i=0;$i<count($data);$i++){//①
                        if($data[$i][1]=="1") {
                            $isInsert=db("label")->insert(["label_name"=>$data[$i][0],"label_parent"=>0,"label_priority"=>1]);//一级导航标签插入
                            $temp=db("label")->where("label_name","=",$data[$i][0])->where("label_priority","=",$data[$i][1])->find();
                            $data[$i]["label_id"]=$temp["label_id"];
                        }
                    }
                    for($i=0;$i<count($data);$i++){//②
                        if($data[$i][1]=="2"){
                            if($data[$i][2][0]=="a"){//将对应的parentId改回来
                                for($j=0;$j<count($data);$j++){
                                    if($data[$j][1]=="1"&&$data[$j][2]==$data[$i][2]){
                                        $data[$i]["label_parent"]=$data[$j]["label_id"];
                                    }
                                }
                            }
                            if(!isset($data[$i]["label_parent"])) $data[$i]["label_parent"]=$data[$i][2];
                            $isInsert=db("label")->insert(["label_name"=>$data[$i][0],"label_parent"=>$data[$i]["label_parent"],"label_priority"=>2]);//二级导航标签插入
                            $temp=db("label")->where("label_name","=",$data[$i][0])->where("label_priority","=",$data[$i][1])->find();
                            $data[$i]["label_id"]=$temp["label_id"];
                        }
                    }
                    for($i=0;$i<count($data);$i++){//③
                        if($data[$i][1]=="3"){
                            if($data[$i][2][0]=="a"){
                                for($j=0;$j<count($data);$j++){
                                    if($data[$j][1]=="2"&&$data[$j][3]==$data[$i][2]){
                                        $data[$i][2]=$data[$j]["label_id"];
                                    }
                                }
                            }
                            $isInsert=db("label")->insert(["label_name"=>$data[$i][0],"label_parent"=>$data[$i][2],"label_priority"=>3]);
                        }
                    }
                    echo "<script>alert('添加成功')</script>";
                }
            }
        }
        else if($type==3){//删除
            if(request()->isPost()){
                $data=input('post.');
//                dump($data);die;
                if(isset($data["triple"])){
                    db("label")->delete($data["triple"]);
                }
                if(isset($data["secondary"])){
                    db("label")->where("label_parent","in",$data["secondary"])
                    ->whereOr("label_id","in",$data["secondary"])
                    ->delete();
                }
                if(isset($data["primary"])){
                    db("label")->where("label_parent","in",$data["primary"])
                        ->whereOr("label_id","in",$data["primary"])
                        ->delete();
                }
                echo "<script>alert('删除成功');</script>";
            }
        }
        else if($type=4){//更改
            if(request()->isPost()){
                $updData=input("post.");
                $data=array();
                if(count($updData)>0&&isset($updData["upd"])) {
                    for ($i = 0; $i < count($updData["upd"]); $i++) {
                        $data[$i] = explode(",", $updData["upd"][$i]);
                        db("label")->where("label_id","=",$data[$i][1])->setField("label_name",$data[$i][0]);
                    }
                }
            }
        }
        $primary=db('label')->where('label_priority','=',1)->select();
        $secondary=db('label')->where('label_priority','=',2)->select();
        $triple=db('label')->where('label_priority','=',3)->select();
        $this->assign("primary",$primary);
        $this->assign("secondary",$secondary);
        $this->assign("triple",$triple);
        return view();
    }
}