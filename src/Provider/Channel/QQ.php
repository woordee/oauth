<?php
namespace OAuth\Provider\Channel;

use OAuth\AccessToken\AccessToken;
use OAuth\Provider\AbstractProvider;
use OAuth\Provider\User\QQUser;
use OAuth\Grant\QQAuthorizationCode;
use GuzzleHttp\Psr7\Request;

class QQ extends AbstractProvider{

	public function getBaseAuthorizationUrl(){
		return 'https://graph.qq.com/oauth2.0/authorize';
	}

	public function getAuthorizationParams(array $options = []){
		$data = [];
		$data['client_id'] = $this->clientId;
		$data['response_type'] = 'code';
		$data['redirect_uri'] = $this->redirectUri;
		$data = array_merge($data, $options);
		
		return $data;
	}

	public function getBaseAccessTokenUrl(array $params){
		$params['grant_type'] = 'authorization_code';
		return 'https://graph.qq.com/oauth2.0/token';
	}

	protected function getAccessTokenOptions($method, $options){
		$options['grant_type'] = 'authorization_code';
		
		return parent::getAccessTokenOptions($method, $options);
	}
	
	public function getOpenIdDetailUrl(){
		return 'https://graph.qq.com/oauth2.0/me';
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function getResourceOwnerDetailsUrl(AccessToken $token){
		return 'https://graph.qq.com/user/get_user_info';
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	protected function getResourceOwnerDetailParams(AccessToken $accessToken){
		return [
			'access_token' => $accessToken->getToken(),
			'oauth_consumer_key' => $this->clientId,
			'openid' => $accessToken->getUserId(),
			'fmt' => 'json'
		];
	}

	public function getAccessTokenMethod(){
		return self::METHOD_GET;
	}

	public function prepareAccessTokenResponse(array $response){
		$params = [
			'access_token' => $response['access_token'],
			'fmt' => 'json'
		];
		$method = $this->getAccessTokenMethod();
		$url = $this->getOpenIdDetailUrl();
		$options  = $this->getAccessTokenOptions($method, $params);
		$request = new Request($method, $url);
		$info = $this->getParsedResponse($request, $options);
		$response['resource_owner_id'] = $info['openid'];
		
		return $response;
	}
	/**
	 *
	 * {@inheritdoc}
	 */
	public function createResourceOwner(array $response, AccessToken $token){
		$response['openid'] = $token->getUserId();
		return new QQUser($response);
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function checkResponse($response, $data){
		if(isset($response['error'])){
			throw new \Exception($response['error_description'], $response['error']);
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function getAuthorizationGrant(string $code){
		return new QQAuthorizationCode(['code' => $code]);
	}

	public function getDefaultScopes(){
		return 'get_user_info';
	}

	public function getUserScope(){
		return '';
	}
}
