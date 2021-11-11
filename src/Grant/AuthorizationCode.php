<?php
namespace OAuth\Grant;

abstract class AuthorizationCode
{

    /**
     * @var string $code auth code
     */
    protected $code;

    public function __construct($options = [])
    {
        $this->code = isset($options['code']) ? $options['code'] : '';
    }

    /**
     * @inheritdoc
     */
    protected function getName()
    {
        return 'authorization_code';
    }

    abstract public function prepareRequestParameters(array $defaults, array $options);

    /**
     * get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * set code
     *
     * @param string $code
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @inheritdoc
     */
    protected function checkRequiredParameters(array $options)
    {
        return true;
    }
}
