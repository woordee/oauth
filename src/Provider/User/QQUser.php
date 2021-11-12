<?php
namespace OAuth\Provider\User;

use OAuth\Provider\ResourceOwner;

class QQUser extends ResourceOwner
{
	public function getId()
	{
		return $this->data['openid'];
	}
	
	public function getNickName()
	{
		return !empty($this->data['nickname']) ? $this->data['nickname'] : '';
	}
	
	public function getAvatar()
	{
		return $this->data['figureurl_qq_2']??'';
	}
}
