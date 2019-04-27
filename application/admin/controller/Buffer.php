<?php
namespace app\admin\controller;
use app\admin\model\Admin;
use think\Controller;
use think\Session;
use think\Cache;
use app\admin\model\ActiveCss;
Session::start();

class Buffer extends Controller
{
    public function buffer()
    {
        echo (new ActiveCss())->shift("buffer");
        $admin = new Admin();
        if (!$admin->check_login()) return view("Login/login");
        return view();
    }
    public function rm_redis(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  '',
        ];
        $redis = Cache::connect($p);
        if($redis->get('index_focus')){
            $redis->rm('index_focus');
        }
        if($redis->get('index_bestsell')){
            $redis->rm('index_bestsell');
        }
        if($redis->get('index_author')){
            $redis->rm('index_author');
        }
        if($redis->get('index_label')){
            $redis->rm('index_label');
        }
        return true;
    }
}
?>