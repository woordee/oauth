<?php
namespace OAuth\Provider\User;

use OAuth\Provider\ResourceOwner;

class WeiboUser extends ResourceOwner
{
    public function getId()
    {
        return $this->data['id'];
    }

    public function __get($field)
    {
        return $this->data[$field]??'';
    }
}
