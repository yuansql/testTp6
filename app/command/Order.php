<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\facade\Db;

class Order extends Command
{
	protected function configure()
	{
		// 指令配置
		$this->setName('order_msg')
			->setDescription('the order_msg command');
	}
	
	protected function execute(Input $input, Output $output)
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
		
		//指定交换机类型为direct
		$channel->exchange_declare($exc_name, 'direct', false, true, false);
		
		//将队列名与交换器名进行绑定，并指定routing_key
		$channel->queue_bind($queue_name, $exc_name, $routing_key);
		
		$callback = function ($msg) use ($output) {
//            $output->writeln($msg->body);
			Db::table('goods_order')->where(['id' => $msg->body, 'is_pay' => 0])->delete();
			echo $msg->body;
			//确认消息已被消费，从生产队列中移除
			$msg->ack();
		};
		
		//设置消费成功后才能继续进行下一个消费
		$channel->basic_qos(null, 1, null);
		
		//开启消费no_ack=false,设置为手动应答
		$channel->basic_consume($queue_name, '', false, false, false, false, $callback);
		
		while ($channel->is_open()) {
			$channel->wait();
		}
		
		$channel->close();
		$connection->close();
	}
}
