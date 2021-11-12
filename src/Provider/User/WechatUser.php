<?php

namespace OAuth\Provider\User;

use OAuth\Provider\ResourceOwner;

class WechatUser extends ResourceOwner
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
        return $this->data['headimgurl']??'';
    }
}
