<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');
Route::group('apidoc', function () {
	$controller_namespace = '\hg\apidoc\Controller@';
	Route::get('config'     , $controller_namespace . 'getConfig');
	Route::get('apiData'     , $controller_namespace . 'getApidoc');
	Route::get('mdMenus'     , $controller_namespace . 'getMdMenus');
	Route::get('mdDetail'     , $controller_namespace . 'getMdDetail');
	Route::post('verifyAuth'     , $controller_namespace . 'verifyAuth');
	Route::post('generator'     , $controller_namespace . 'createGenerator');
});
