<?php


namespace Monyxie\HiHealth\Environment;


class Testing implements EnvironmentInterface
{
    public function getAuthorizationUrl()
    {
        return 'https://logintestlf.hwcloudtest.cn/oauth2/v2/authorize';
    }

    public function getAccessTokenUrl()
    {
        return 'https://logintestlf.hwcloudtest.cn/oauth2/v2/token';
    }

    public function getFoundationUrl()
    {
        return 'https://apitestlf.hwcloudtest.cn/rest.php';
    }

    public function getHealthUrl()
    {
        return 'http://hwlf.hwcloudtest.cn:10180/rest.php';
    }
}