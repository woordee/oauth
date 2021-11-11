<?php
namespace OAuth\Provider\Channel;

use OAuth\AccessToken\AccessToken;
use OAuth\Provider\AbstractProvider;
use OAuth\Provider\User\QQUser;
use OAuth\Grant\QQAuthorizationCode;

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

	/**
	 *
	 * {@inheritdoc}
	 */
	protected function createAccessToken(array $response){
		$response['resource_owner_id'] = $response['uid'];
		return new AccessToken($response);
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
			'uid' => $accessToken->getUserId()];
	}

	public function getAccessTokenMethod(){
		return self::METHOD_GET;
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function createResourceOwner(array $response, AccessToken $token){
		return new QQUser($response);
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function checkResponse($response, $data){
		if(isset($response['error_code'])){
			throw new \Exception($response['error'], $response['error_code']);
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
		return '';
	}

	public function getUserScope(){
		return '';
	}
}
