<?php
namespace app\index\controller;

use app\index\model\User;
use think\Controller;

class Forget extends Controller{
    public function forget(){
        return view();
    }
    public function resetUserPassword(){
        $user_id = input('post.userId/s');
        $user_password = input('post.password/s');
        $User = new User();
        $res = $User->updata_password_user($user_id,$user_password);
        return $res;
    }
}