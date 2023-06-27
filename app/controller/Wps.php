<?php
declare (strict_types=1);

namespace app\controller;

class Wps
{
	public function wps_index($ids = null)
	{
		$data = [
			'_w_appid' => 'SX20230625YMFFAN',
		];
		$sing = $this->getSign($data);//生成签名
	
		//type 值需要根据文档中文件的格式去定义  我只处理doc、docx所以固定了
		$url = 'https://o.wpsgo.com/office/p/' . 77 . '?_w_appid=' . $data['_w_appid'] . '&_w_signature=' . $sing;
		$params = [];
		$response = httpRequest($url, $params, 'GET');
		return $response;

	}
	
	/**
	 * 生成wps要的签名
	 */
	function getSign($data)
	{
		ksort($data); //按照key升序排列
		$stringA = '';
		foreach ($data as $key => $item) {
			$stringA .= $key . '=' . $item; // 整合新的参数数组
		}
		$key = 'mdIJwQPpkpnkejCwyyNGFxDBeTMhVgVV';
		$stringSignTemp = $stringA . '_w_secretkey=' . $key;//拼接$appKey
		$stringSignTemp = hash_hmac('sha1',$stringSignTemp,$key,true);
		$sign = base64_encode($stringSignTemp);
		return urlencode($sign);
	}
	//下面中info()和save()接口需要重定向分别是：/v1/3rd/file/info、/v1/3rd/file/save
	
	/**
	 * 获取文件信息
	 */
	public function info()
	{
		$header = request()->header();
		$id = $header['x-weboffice-file-id'];//文件id在你传给wps时它会返给你
		$templates = db('你的表')->where(['id' => $id])->find();
		//下面的格式 wps固定 具体的要看wps文档
		$data = [
			'file' => [
				'id' => $id, //文件id,字符串长度小于40
				'name' => $templates['name'], //文件名
				'version' => $templates['edition'], //当前版本号，位数小于11
				'size' => $templates['size'], //文件大小，单位为B
				'creator' => 'id0', //创建者id，字符串长度小于40
				'create_time' => time($templates['creattime']), //创建时间，时间戳，单位为秒
				'modifier' => 'id1000', //修改者id，字符串长度小于40
				'modify_time' => time(), //修改时间，时间戳，单位为秒
				'download_url' => $templates['url'], //文档下载地址
				'preview_pages' => 100,
				'user_acl' => [
					'rename' => 0, //重命名权限，1为打开该权限，0为关闭该权限，默认为0
					'history' => 0, //历史版本权限，1为打开该权限，0为关闭该权限,默认为1
					'copy' => 1, // 复制
					'export' => 1, // 导出PDF
					'print' => 1, // 打印
				],
				'watermark' => [
					'type' => 0, //水印类型， 0为无水印； 1为文字水印
					'value' => '', //文字水印的文字，当type为1时此字段必选
					'fillstyle' => 'rgba( 192, 192, 192, 0.6 )', //水印的透明度，非必选，有默认值
					'font' => 'bold 20px Serif', //水印的字体，非必选，有默认值
					'rotate' => -0.7853982, //水印的旋转度，非必选，有默认值
					'horizontal' => 50, //水印水平间距，非必选，有默认值
					'vertical' => 100 //水印垂直间距，非必选，有默认值
				]
			],
			'user' => [
				'id' => $templates['admin_id'], //用户id，长度小于40
				'name' => 'wps-' . $templates['admin_id'], //用户名称
				'permission' => 'write', //用户操作权限，write：可编辑，read：预览
				'avatar_url' => '' //用户头像地址
			]
		];
		return json_encode($data);
	}
	
	/**
	 * 保存文件
	 */
	public function save()
	{
		
		$menus = new  Obs;
		$header = request()->header();
		$id = $header['x-weboffice-file-id'];//$header['x-weboffice-file-id']
		$templates = db('你的表')->where(['id' => $id])->find();
		$url = $menus->uploads($_FILES);//文件上传接口
		$arr = array(
			'name' => $_FILES['file']['name'],
			'size' => $_FILES['file']['size'],
			'url' => $url,
			'edition' => $templates['edition'] + 1
		);
		db('你的表')->where(['id' => $id])->update($arr);
		
		$data = [
			'file' => [
				'id' => $id, //文件id，字符串长度小于40
				'name' => $_FILES['file']['name'], //文件名
				'version' => $templates['edition'] + 1, //当前版本号，位数小于11
				'size' => round($_FILES['file']['size'] / 8), //文件大小，单位是B
				'download_url' => $url //文件下载地址
			]
		];
		return json_encode($data);
	}
	
	
	
}
