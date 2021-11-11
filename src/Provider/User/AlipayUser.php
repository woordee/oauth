<?php
namespace OAuth\Provider\User;

use OAuth\Provider\ResourceOwner;

class AlipayUser extends ResourceOwner
{
    public function getId()
    {
        return $this->data['user_id'];
    }

    public function getUserName()
    {
        return !empty($this->data['nick_name']) ? $this->data['nick_name'] : '';
    }

    public function getPicture()
    {
        return $this->data['avatar'];
    }
}
