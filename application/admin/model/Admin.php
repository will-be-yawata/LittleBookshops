<?php
namespace app\admin\model;
use think\Model;

class Admin extends Model{
    public function login($data){
        $admin = db('admin')->where('admin_name', $data['admin_name'])->find();
        if ($admin) {
            if ($admin['admin_password']===$data['admin_password']&&(strlen($admin['admin_password'])==strlen($data['admin_password']))) {
                session('admin_name', $admin['admin_name']);
                session('admin_id', $admin['admin_id']);
                return 1;//正确
            } else {
                return 2;//密码错误
            }
        } else {
            return 3;//没有此用户
        }
    }
    public function check_login(){
        if(isset($_SESSION['admin_name'])&&isset($_SESSION['admin_password'])) {
            $isLogin=$this->login(['admin_name'=>$_SESSION['admin_name'],'admin_password'=>$_SESSION['admin_password']]);
            if($isLogin==3){
                echo "<script>alert('用户名错误！');</script>";
                return false;
            }else if($isLogin==2){
                echo "<script>alert('密码错误！');</script>";
                return false;
            }else
                return true;
        }else{
            echo "<script>alert('请登录后再进行管理后台！');</script>";
            return false;
        }
    }
}