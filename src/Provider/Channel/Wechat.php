<?php
namespace OAuth\Provider\Channel;

use OAuth\AccessToken\AccessToken;
use OAuth\Grant\WxAuthorizationCode;
use OAuth\Provider\AbstractProvider;
use OAuth\Provider\User\WechatUser;

/**
 * 微信登录
 */
class Wechat extends AbstractProvider{

	public function getBaseAuthorizationUrl(){
		return 'https://open.weixin.qq.com/connect/qrconnect';
	}

	public function getAuthorizationUrl(array $options = []){
		$url = parent::getAuthorizationUrl($options);
		
		return $url . '#wechat_redirect';
	}

	public function getAuthorizationParams(array $options = []){
		$data = [];
		$data['redirect_uri'] = $this->redirectUri;
		$data['appid'] = $this->clientId;
		$data['response_type'] = 'code';
		$data['scope'] = isset($options['scope']) ? $options['scope'] : $this->getDefaultScopes();
		$data = array_merge($data, $options);
		
		return $data;
	}

	public function getBaseAccessTokenUrl(array $params){
		return 'https://api.weixin.qq.com/sns/oauth2/access_token';
	}

	public function getResourceOwnerDetailsUrl(AccessToken $token){
		$url = 'https://api.weixin.qq.com/sns/userinfo';
		
		$params = [
			'access_token' => $token->getToken(), 
			'openid' => $token->openid, 
			'lang' => 'zh_CN'];
		
		return $url . '?' . http_build_query($params);
	}

	public function createResourceOwner(array $response, AccessToken $token){
		return new WechatUser($response);
	}

	public function getDefaultScopes(){
		return 'snsapi_login';
	}

	public function getUserScope(){
		return 'snsapi_userinfo';
	}

	public function checkResponse($response, $data){
	}

	public function getAuthorizationGrant(string $code){
		return new WxAuthorizationCode(['code' => $code]);
	}

	protected function getAccessTokenMethod(){
		return static::METHOD_GET;
	}

	public function prepareAccessTokenResponse(array $response){
		$response['refresh_token_expires_in'] = 86400 * 30;
		$response['resource_owner_id'] = $response['unionid'];
		
		return $response;
	}

	protected function getResourceOwnerDetailParams(AccessToken $accessToken){
		return [
			'access_token' => $accessToken->getToken(), 
			'openid' => $accessToken->openid
		];
	}
}
