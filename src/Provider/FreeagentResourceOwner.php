<?php namespace Nathanbriggs\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

// this is a FreeAgent API Company object -- https://dev.freeagent.com/docs/company

class FreeagentResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get user id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['subdomain'] ?:null;
    }

    /**
     * Get company name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['name'] ?: null;
    }

    /**
     * Get company email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['contact_email'] ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
