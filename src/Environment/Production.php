<?php


namespace Monyxie\HiHealth\Environment;


class Production implements EnvironmentInterface
{
    public function getAuthorizationUrl()
    {
        return 'https://oauth-login.cloud.huawei.com/oauth2/v2/authorize';
    }

    public function getAccessTokenUrl()
    {
        return 'https://oauth-login.cloud.huawei.com/oauth2/v2/token';
    }

    public function getTokenInfoUrl()
    {
        return 'https://oauth-api.cloud.huawei.com/rest.php';
    }

    public function getHealthUrl()
    {
        return 'https://healthopen.hicloud.com/rest.php';
    }
}