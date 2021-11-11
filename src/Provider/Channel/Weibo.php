<?php
namespace OAuth\Provider\Channel;

use OAuth\AccessToken\AccessToken;
use OAuth\Grant\WeiboAuthorizationCode;
use OAuth\Provider\AbstractProvider;
use OAuth\Provider\User\WeiboUser;

class Weibo extends AbstractProvider{

	public function getBaseAuthorizationUrl(){
		return 'https://api.weibo.com/oauth2/authorize';
	}

	public function getAuthorizationParams(array $options = []){
		$data = [];
		$data['client_id'] = $this->clientId;
		$data['response_type'] = 'code';
		$data['redirect_uri'] = $this->redirectUri;
		
		return $data;
	}

	public function getBaseAccessTokenUrl(array $params){
		$params['grant_type'] = 'authorization_code';
		return 'https://api.weibo.com/oauth2/access_token';
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
		return 'https://api.weibo.com/2/users/show.json';
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	protected function getResourceOwnerDetailParams(AccessToken $accessToken){
		return [
			'access_token' => $accessToken->getToken(), 
			'uid' => $accessToken->getUserId()
		];
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function createResourceOwner(array $response, AccessToken $token){
		return new WeiboUser($response);
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
		return new WeiboAuthorizationCode(['code' => $code]);
	}

	public function getDefaultScopes(){
		return '';
	}

	public function getUserScope(){
		return '';
	}
}
