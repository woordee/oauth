<?php

namespace OAuth\Provider;

abstract class ResourceOwner
{
    /**
     * @var array 用户信息
     */
    protected $data;

    public function __construct(array $response)
    {
        $this->data = $response;
    }

    /**
     * 返回用户标识
     *
     * @return mixed
     */
    public abstract function getId();

    public function getUnionId()
    {
    	return isset($this->data['unionid']) ? $this->data['unionid'] : null;
    }
    
    /**
     * 返回用户信息数组
     *
     * @return array
     */
    public function toArray()
    {
        return [
        	'openid' => $this->getId(),
        	'nickname' => $this->getNickname(),
        	'avatar' => $this->getAvatar(),
        	'unionid' => $this->getUnionId()
        ];
    }
}
