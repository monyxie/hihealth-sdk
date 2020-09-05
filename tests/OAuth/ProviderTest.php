<?php

namespace OAuth;

use Mockery;
use Monyxie\HiHealth\OAuth\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new Provider([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'mock_redirect_uri',
        ]);
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/oauth2/v2/token', $uri['path']);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/oauth2/v2/authorize', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getHeader')
            ->times(1)
            ->andReturn('application/json');
        $response->shouldReceive('getBody')
            ->times(1)
            ->andReturn('{"access_token":"mock_access_token","refresh_token":"mock_refresh_token","token_type":"bearer","expires_in":3600,"scope":"mock_scope"}');
        $response->shouldReceive('getStatusCode')
            ->times(1)
            ->andReturn(200);

        $client = Mockery::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 3600, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertNull($token->getResourceOwnerId(), 'HiHealth does not return user ID with access token. Expected null.');
    }
}
