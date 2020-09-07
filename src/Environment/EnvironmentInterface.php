<?php

namespace Monyxie\HiHealth\Environment;

interface EnvironmentInterface
{
    public function getAuthorizationUrl();

    public function getAccessTokenUrl();

    public function getFoundationUrl();

    public function getHealthUrl();
}