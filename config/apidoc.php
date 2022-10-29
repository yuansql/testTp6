<?php
return [
    // 文档标题
    'title'              => 'API接口文档',
    // 文档描述
    'desc'               => '',
    // 默认请求类型
    'default_method'=>'GET',
    // 允许跨域访问
    'allowCrossDomain'=>false,
    // 设置可选版本
	'apps' => [
		[
			'title'=>'后台管理',
			'path'=>'app\admin\controller',
			'folder'=>'admin',
			'groups'  => [
				['title'=>'基础模块','name'=>'base'],
				['title'=>'示例模块','name'=>'demo'],
				['title'=>'多级模块','name'=>'subMenu',
					'children'=>[
						['title'=>'多级v1','name'=>'subv1',],
						['title'=>'多级v2','name'=>'subv2'],
					]
				],
			],
			// 'controllers'=>[
			//     'app\admin\controller\BaseDemo',
			//     'app\admin\controller\CrudDemo',
			// ],
			// 'headers'=>[
			//     ['name'=>'token','type'=>'string','desc'=>'admin应用的全局请求头参数token'],
			// ],
			// 'parameters'=>[
			//     ['name'=>'abc','type'=>'string','desc'=>'admin应用的全局请求体参数abc'],
			// ],
		],
		[
			'title'=>'演示示例',
			'path'=>'app\demo\controller',
			'folder'=>'demo',
			'items'=>[
				['title'=>'V1.0','path'=>'app\demo\controller\v1','folder'=>'v1'],
				['title'=>'V2.0','path'=>'app\demo\controller\v2','folder'=>'v2']
			],
		
		],
	],
    // 自动生成url规则
    'auto_url' => [
        // 字母规则
        'letter_rule' => "lcfirst",
        // 多级路由分隔符
        'multistage_route_separator'  =>"."
    ],
    // 指定公共注释定义的文件地址
    'definitions'        => "app\common\controller\Definitions",
    // 缓存配置
    'cache'              => [
        // 是否开启缓存
        'enable' => false,
    ],
    // 权限认证配置
    'auth'               => [
        // 是否启用密码验证
        'enable'     => false,
        // 全局访问密码
        'password'   => "123456",
        // 密码加密盐
        'secret_key' => "apidoc#hg_code",
        // 有效期
        'expire' => 24*60*60
    ],
    // 统一的请求Header
    'headers'=>[],
    // 统一的请求参数Parameters
    'parameters'=>[],
    // 统一的请求响应体
    'responses'=>[
        ['name'=>'code','desc'=>'代码','type'=>'int'],
        ['name'=>'message','desc'=>'业务信息','type'=>'string'],
        ['name'=>'data','desc'=>'业务数据','main'=>true,'type'=>'object'],
    ],
    // md文档
    'docs'              => [],

];
