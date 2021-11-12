<?php
namespace OAuth\Provider\User;

use OAuth\Provider\ResourceOwner;

class AlipayUser extends ResourceOwner
{
    public function getId()
    {
        return $this->data['user_id'];
    }

    public function getNickName()
    {
        return !empty($this->data['nick_name']) ? $this->data['nick_name'] : '';
    }

    public function getAvatar()
    {
        return $this->data['avatar'];
    }
}
