<?php
namespace OAuth\Grant;

class AlipayAuthorizationCode extends AuthorizationCode{

	public function prepareRequestParameters(array $defaults, array $options){
		$options['grant_type'] = $this->getName();
		$options['code'] = isset($options['code']) ? $options['code'] : $this->getCode();
		
		return $options;
	}
}
