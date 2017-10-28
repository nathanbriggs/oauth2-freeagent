<?php namespace Nathanbriggs\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Freeagent extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Client is in sandbox mode
     *
     * @var string
     */
    protected $isSandbox = false;

    /**
     * Creates and returns api base url base on client configuration.
     *
     * @return string
     */
    protected function getApiUrl()
    {
        return (bool) $this->isSandbox ? 'https://api.sandbox.freeagent.com' : 'https://api.freeagent.com';
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getApiUrl().'/v2/approve_app';
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getApiUrl().'/v2/token_endpoint';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token) // in FreeAgent, we'll query GET https://api.freeagent.com/v2/company  instead https://dev.freeagent.com/docs/company
    {
        return $this->getApiUrl().'/v2/comapny';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return string[]
     */
    protected function getDefaultScopes()
    {
        return [''];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode > 400) {
            throw new IdentityProviderException(
                $data['message'] ?: $response->getReasonPhrase(),
                $statusCode,
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param object $response
     * @param AccessToken $token
     * @return PaypalResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new FreeagentResourceOwner($response);
    }
}
