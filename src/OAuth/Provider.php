<?php


namespace Monyxie\HiHealth\OAuth;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Monyxie\HiHealth\Environment\EnvironmentInterface;
use Monyxie\HiHealth\Environment\Production;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

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
        return "{$this->environment->getFoundationUrl()}?nsp_ts={$timestamp}&nsp_svc=huawei.oauth2.user.getTokenInfo&open_id=OPENID";
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
            throw new IdentityProviderException("Response status failure", $data['error'] ?? 0, $data ?? ($response->getBody() . ''));
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ResourceOwner($response);
    }

    /**
     * @param AccessToken $token
     * @return array|mixed
     * @throws IdentityProviderException
     */
    public function revokeAccess(AccessToken $token)
    {
        return $this->delUserConsentByApp($token);
    }

    /**
     * Requests resource owner details.
     *
     * @param AccessToken $token
     * @return mixed
     * @throws IdentityProviderException
     */
    protected function delUserConsentByApp(AccessToken $token)
    {
        $timestamp = time();
        $url =  "{$this->environment->getFoundationUrl()}?nsp_ts={$timestamp}&nsp_svc=huawei.oauth2.consent.delUserConsentByApp";

        $request = $this->getAuthenticatedRequest(self::METHOD_POST, $url, $token);

        $response = $this->getParsedResponse($request);

        if (false === is_array($response)) {
            throw new UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON.'
            );
        }

        return $response;
    }
}