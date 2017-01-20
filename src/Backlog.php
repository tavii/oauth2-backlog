<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2017/01/20
 */

namespace Tavii\OAuth2\Client\Provider;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Tavii\OAuth2\Client\Provider\Exception\IdentityProviderException;

class Backlog extends AbstractProvider
{
    /**
     * @var string
     */
    private $team;

    /**
     * @var string
     */
    private $version = 'v2';

    /**
     * @var string
     */
    private $baseDomain = 'backlog.jp';

    /**
     * Backlog constructor.
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->team = $options['team'];
        unset($options['team']);
        parent::__construct($options, $collaborators);
    }


    public function getBaseAuthorizationUrl()
    {
        return $this->getTeamUrl().'/OAuth2AccessRequest.action';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getApiBaseUrl().'/oauth2/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getApiBaseUrl().'/users/myself';
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw IdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw IdentityProviderException::oauthException($response, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
    }


    protected function getApiBaseUrl()
    {
        return sprintf('https://%s/api/%s', $this->getTeamUrl(), $this->version);
    }

    protected function getTeamUrl()
    {
        return sprintf('https://%s.%s', $this->team, $this->baseDomain);

    }

}