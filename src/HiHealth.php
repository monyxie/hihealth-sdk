<?php


namespace Monyxie\HiHealth;


use Monyxie\HiHealth\Client\Client;
use Monyxie\HiHealth\Environment\EnvironmentInterface;
use Monyxie\HiHealth\Environment\Production;
use Monyxie\HiHealth\OAuth\Provider;

class HiHealth
{
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $clientSecret;
    /**
     * @var EnvironmentInterface|null
     */
    private $environment;
    /**
     * @var string
     */
    private $redirectUri;

    /**
     * HiHealthService constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->environment = new Production();
    }

    public function oauth(): Provider
    {
        $instance = new Provider([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'redirectUri' => $this->redirectUri,
        ]);
        $instance->setEnvironment($this->environment);
        return $instance;
    }

    public function client(string $accessToken): Client
    {
        $instance = new Client($accessToken);
        $instance->setEnvironment($this->environment);
        return $instance;
    }

    /**
     * @return EnvironmentInterface|null
     */
    public function getEnvironment(): ?EnvironmentInterface
    {
        return $this->environment;
    }

    /**
     * @param EnvironmentInterface|null $environment
     * @return HiHealth
     */
    public function setEnvironment(?EnvironmentInterface $environment): HiHealth
    {
        $this->environment = $environment;
        return $this;
    }
}