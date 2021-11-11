<?php
namespace OAuth\Grant;

class WxAuthorizationCode extends AuthorizationCode
{
    public function prepareRequestParameters(array $defaults, array $options)
    {
        $options['appid'] = $defaults['client_id'];
        $options['secret'] = $defaults['client_secret'];
        $options['grant_type'] = $this->getName();
        $options['code'] = $this->getCode();

        return $options;
    }
}
