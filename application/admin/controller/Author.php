<?php
/**
 * Created by PhpStorm.
 * User: 土豆
 * Date: 2019/4/24
 * Time: 22:51
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Admin;
use think\Session;
use app\admin\model\ActiveCss;
Session::start();

class Author extends Controller{
    public function author(){
        echo (new ActiveCss())->shift("author");
        $admin=new Admin();
        if(!$admin->check_login()) return view("Login/login");
        $author_list=db('author')->paginate(8);
        $this->assign('a_list',$author_list);
        return view();
    }
    public function add(){
        $data=[
            'author_name'=>$_POST['name'],
            'author_img'=>$_POST['img'],
            'author_about'=>$_POST['about']
        ];
        $res=Db::table('author')->insertGetId($data);
        return $this->redirect('admin/author/author');

    }
    public function update(){
        $a_id=$_POST['a_id'];
        $data=[
            'author_name'=>$_POST['name'],
            'author_img'=>$_POST['img'],
            'author_about'=>$_POST['about']
        ];
        Db::table('author')->where("author_id",$a_id)->update($data);
        return $this->redirect('admin/author/author');
    }
    public function del(){
        $a_id=$_GET['a_id'];
        Db::table('author')->where('author_id',$a_id)->delete();
        return $this->redirect('admin/author/author');
    }
}