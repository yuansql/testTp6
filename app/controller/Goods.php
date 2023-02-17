<?php
declare (strict_types=1);

namespace app\controller;

use think\facade\Db;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use think\facade\Log;

class Goods
{
	public function index()
	{
		$msg =11111;
		Log::write($msg);
		return '到达';
		return view('goods');
	}
	
	public function paySuccess()
	{
		$id = Db::table('goods_order')->insertGetId(['is_pay' => 1]);
		$this->sendMsg($id);
		return 1;
	}
	
	public function payFail()
	{
		$id = Db::table('goods_order')->insertGetId(['is_pay' => 0]);
		$this->sendMsg($id);
		return 1;
	}
	
	public function sendMsg($id)
	{
		//建立connction
		$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'root', '123456', 'demo');
		//Channel
		$channel = $connection->channel();
		//声明交换器
		$exc_name = 'delay_exc_order';
		//指定routing_key
		$routing_key = 'delay_route_order';
		//声明队列名称
		$queue_name = 'delay_queue_order';
		//设置延迟时间20s过期
		$ttl = 20000;
		
		//指定交换机类型为direct
		$channel->exchange_declare($exc_name, 'x-delayed-message', false, true, false);
		$args = new AMQPTable(['x-delayed-type' => 'direct']);
		
		$channel->queue_declare($queue_name, false, true, false, false, false, $args);
		
		//声明数据
		$data = $id;
		
		//绑定
		$channel->queue_bind($queue_name, $exc_name, $routing_key);
		
		//创建消息
		$arr = ['delivery_mode' => AMQPMEssage::DELIVERY_MODE_PERSISTENT, 'application_headers' => new AMQPTable(['x-delay' => $ttl])];
		
		$msg = new AMQPMessage($data, $arr);
		
		//发布消息
		//指定使用的routing_key
		$channel->basic_publish($msg, $exc_name, $routing_key);
		//关闭连接
		$channel->close();
		$connection->close();
	}
}
