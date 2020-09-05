<?php


namespace Monyxie\HiHealth\OAuth;


use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * User constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getId()
    {
        return $this->attributes['open_id'] ?? null;
    }

    public function toArray()
    {
        return $this->attributes;
    }
}