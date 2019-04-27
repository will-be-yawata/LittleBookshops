<?php
namespace app\admin\model;


class ActiveCss
{
    function shift($name){
        return "<script>window.onload=function (){document.getElementById('$name').className='active'}</script>";
    }
}