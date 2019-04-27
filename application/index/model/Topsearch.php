<?php
namespace app\index\model;
use think\Model;

class Topsearch extends Model{
    public function topsearch(){
        $res = db('top_search')->distinct ( true )->order('Rand()')->limit(6)->select();
        return $res;
    }
}