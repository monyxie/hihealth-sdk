<?php


namespace Monyxie\HiHealth\OAuth;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Monyxie\HiHealth\Environment\EnvironmentInterface;
use Monyxie\HiHealth\Environment\Production;
use Psr\Http\Message\ResponseInterface;

class Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var EnvironmentInterface
     */
    private $environment;

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);
        $this->environment = new Production();
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->environment->getAuthorizationUrl();
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->environment->getAccessTokenUrl();
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $timestamp = time();
        return "{$this->environment->getTokenInfoUrl()}?nsp_ts={$timestamp}&nsp_svc=huawei.oauth2.user.getTokenInfo&open_id=OPENID";
    }

    /**
     * @return EnvironmentInterface
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environment;
    }

    /**
     * @param EnvironmentInterface $environment
     * @return Provider
     */
    public function setEnvironment(EnvironmentInterface $environment): Provider
    {
        $this->environment = $environment;
        return $this;
    }

    protected function getDefaultScopes()
    {
        return [Scopes::PROFILE_READONLY];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new IdentityProviderException("Response status failure", $data['error'] ?? 0, $response->getBody());
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ResourceOwner($response);
    }
}