<?php
namespace app\admin\controller;
use app\admin\model\Admin;
use think\Controller;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();

class Goods extends Controller
{
    public function goods(){
        echo (new ActiveCss())->shift("goods");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");
        $res=db("goods")
            ->alias("g")
            ->join("label l","l.label_id = g.goods_label")
            ->paginate(10);
        $page=$res->render();
        $this->assign("page",$page);
        $this->assign("list",$res);
        $this->assign("type","query");
        return view();
    }
    public function query(){
        echo (new ActiveCss())->shift("goods");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");

    //搜索框功能
        if(request()->isPost()){
            $data=input("post.");
            $res=db("goods")
                ->alias("g")
                ->join("label l","l.label_id = g.goods_label")
                ->where("goods_name","like","%".$data["query_text"]."%")
                ->paginate(10);
            $page=$res->render();
            $this->assign("page",$page);
            $this->assign("list",$res);
        }else{
            $res=db("goods")
                ->alias("g")
                ->join("label l","l.label_id = g.goods_label")
                ->paginate(10);
            $page=$res->render();
            $this->assign("page",$page);
            $this->assign("list",$res);
        }
        $this->assign("type","query");
        return view("Goods/goods");
    }
    public function add(){
        echo (new ActiveCss())->shift("goods");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");
        if (request()->isPost()) {
            $data = input('post.');
            if(isset($data["editorValue"])) unset($data["editorValue"]);
            //是否完善信息
            $isComplete=(isset($_FILES["goods_img"])&&isset($data["goods_name"])&&isset($data["goods_original_price"])&&isset($data["goods_current_price"])&&isset($data["goods_author"])&&isset($data["goods_press"])&&isset($data["goods_about_author"])&&isset($data["goods_about_book"])&&isset($data["goods_isbn"]))
                &&($_FILES["goods_img"]["name"]!=""&&$data["goods_name"]!=""&&$data["goods_original_price"]!=""&&$data["goods_current_price"]!=""&&$data["goods_author"]!=""&&$data["goods_press"]!=""&&$data["goods_about_author"]!=""&&$data["goods_about_book"]!=""&&$data["goods_isbn"]!="");
            if($isComplete){
                $extension=pathinfo($_FILES['goods_img']['name'],PATHINFO_EXTENSION);
                $this->upload($_FILES["goods_img"],$data["goods_isbn"]);
                //添加到数据库
                $temp=explode(",",$data["goods_label"]);
                $data["goods_label"]=$temp[1];
                $data["goods_parent_label"]=$temp[0];
                $data["goods_img"]=$data["goods_isbn"].".".$extension;
                $data["goods_createtime"]=$data["goods_updatatime"]=date("Y-m-d h:i:s");
                if(db("goods")->insert($data)){
                    echo "<script>alert('添加成功');</script>";
                }
            }else{
                echo "<script>alert('信息欠缺，请完善信息');window.history.back(-1); </script>";
                return;
            }
        }
        $label=db("label")->where("label_priority",3)->select();
        $this->assign("label",$label);
        $this->assign("type","add");
        return view("Goods/goods");
    }
    public function delete(){
        echo (new ActiveCss())->shift("goods");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");

        $res=db("goods")
            ->alias("g")
            ->join("label l","l.label_id = g.goods_label")
            ->paginate(4);
        $page=$res->render();
        $this->assign("page",$page);
        $this->assign("list",$res);
        $this->assign("type","delete");
        return view("Goods/goods");
    }
    public function delete_ajax(){
        if (request()->isPost()) {
            $id = input('post.goods_id');
            $img=db("goods")->where("goods_id",$id)->field("goods_img")->find();
            $img_path="static/admin/images/goods/";
            unlink($img_path.$img['goods_img']);
            $res=db("goods")->where("goods_id",$id)->delete();
            return $res;
        }
        return false;
    }
    public function update(){
        echo (new ActiveCss())->shift("goods");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");

        $res=db("goods")->paginate(4);
        $page=$res->render();
        $label=db("label")->where("label_priority","3")->select();
        $this->assign("page",$page);
        $this->assign("list",$res);
        $this->assign("label",$label);

        $this->assign("type","update");
        return view("Goods/goods");
    }
    public function update_ajax(){
        if (request()->isPost()) {
            $data=input("post.");
            $id=$data["goods_id"];

            $oldISBN=db("goods")->where("goods_id",$id)->field("goods_isbn isbn,goods_img img")->find();
            $img_path = "static/admin/images/goods/";
            if(isset($_FILES["goods_img"]["name"])&&$_FILES["goods_img"]["name"]!="") {
                //删除图片
                $img = db("goods")->where("goods_id", $id)->field("goods_img")->find();
                unlink($img_path . $img['goods_img']);

                //上传图片
                $extension = pathinfo($_FILES['goods_img']['name'], PATHINFO_EXTENSION);
                $this->upload($_FILES["goods_img"], $data["goods_isbn"]);
                $data["goods_img"]=$data["goods_isbn"].".".$extension;
            }else if($oldISBN["isbn"]!=$data["goods_isbn"]){
                $old_name=$oldISBN["img"];
                $new_name=$data["goods_isbn"].".".pathinfo($old_name, PATHINFO_EXTENSION);
                if (file_exists( $img_path.$old_name)){
                    if(rename($img_path.$old_name,$img_path.$new_name)) { //把原文件重新命名
                        $data["goods_img"]=$new_name;
                    }
                }
            }
            //补齐缺省项
            $temp=db("label")->where("label_id",$data["goods_label"])->field("label_parent")->find();
            $data["goods_parent_label"]=$temp["label_parent"];

            $data["goods_updatatime"]=date("Y-m-d h:i:s");
            //数据库更新
            $res=db("goods")->update($data);
            return $res;
        }
        return false;
    }
    private function upload($file,$name){
        $img_path="static/admin/images/goods/";
        if(!file_exists($img_path)) mkdir($img_path);
        $extension=pathinfo($file['name'],PATHINFO_EXTENSION);
        if(!($extension=="png"||$extension=="gif"||$extension=="jpg")) {
            echo "<script>alert('请上传png，gif，jpg类型的图片');window.history.back(-1);</script>";
            return false;
        }
        else{
            move_uploaded_file($file['tmp_name'],$img_path.$name.".".$extension);
            return true;
        }
    }
}