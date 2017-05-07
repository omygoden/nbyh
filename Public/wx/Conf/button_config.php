<?php
$buy_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=index_info&refresh=1';
$jiazu_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=member&refresh=1';

$newmenu = '{
		 "button":[
			{	
			  "type":"view",
			  "name":"立刻购买",
			  "url":"http://yun.m3q.cn/index.php?g=App&m=Index&a=listsp"
			},{	
			  "type":"view",
			  "name":"我是代言人",
			  "url":"http://yun.m3q.cn/index.php?g=App&m=Index&a=member"
			},
		   {
			   "name":"我的助手",
			   
			    "sub_button":[
				{	
				   "type":"view",
				   "name":"代言人指南",
				   "url":"http://yun.m3q.cn/index.php?g=App&m=Index&a=wz&id=8"
				},
				{
				   "type":"click",
				   "name":"我的二维码",
				   "key":"GET_PIC"
				},
				{
				   "type":"view",
				   "name":"品牌介绍",
				   "url":"http://yun.m3q.cn/index.php?g=App&m=Index&a=wz&id=9"
				},
				{
				   "type":"view",
				   "name":"快递查询",
				   "url":"http://www.kuaidi100.com"
				},
				{
				   "type":"view",
				   "name":"联系客服",
				   "url":"http://yun.m3q.cn/index.php?g=App&m=Index&a=wz&id=10"
				}]
		   }]
		}';		
 
 
?>