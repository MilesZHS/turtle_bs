<?php
//云之讯短信验证码相关配置

return [
		'accountsid'   =>  '8d1bcc27c400a9ca15044c73319cea0e',//填写在开发者控制台首页上的Account Sid
		'token'   =>  'cea8baf985da3cd5f53f6a33012c3ab1',//填写在开发者控制台首页上的Auth Token
		'appid'  =>  'ff7e4f2b3209424aa58846998311ef59',//应用的ID，可在开发者控制台内的短信产品下查看
		'templateid'  =>  '537928',//可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
		'identify_time' => 5,//超时时间（分钟）
];