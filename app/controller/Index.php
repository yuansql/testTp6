<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return '1111';
    }

    public function hello($name = 'ThinkPHP611')
    {
        return 'hello,' . $name;
    }
}
