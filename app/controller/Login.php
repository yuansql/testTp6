<?php
declare (strict_types=1);

namespace app\controller;

use app\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Login
{
	public function index()
	{
		return view('login');
	}
	
	public function login(Request $request)
	{
		$user_name = $request->param('user_name');
		$password = $request->param('password');
		
		if ($user_name == 'root' && $password == '123456') {
			$this->sendMsg();
			
		}else{
			die('失败');
		}
		die('ok');
	}
	
	public function sendMsg()
	{
		$queue_name = 'login_msg';
		$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'root', '123456', 'vhost');
		$channel = $connection->channel();
		
		$channel->queue_declare($queue_name, false, true, false, false);
		
//		$data = 'root login success-'.time();
		$data = random_int(0,11).'root login success-'.time();
		
		$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT]);
		$channel->basic_publish($msg, $exchange = '', $queue_name);
		
		$channel->close();
		$connection->close();
	}
}
