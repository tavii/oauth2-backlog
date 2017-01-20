<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2017/01/20
 */

namespace Tavii\OAuth2\Client\Provider\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException as BaseException;
use Psr\Http\Message\ResponseInterface;


class IdentityProviderException extends BaseException
{
    /**
     * Creates client exception from response.
     *
     * @param ResponseInterface $response
     * @param array             $data
     *
     * @return IdentityProviderException
     */
    public static function clientException(ResponseInterface $response, array $data)
    {
        return static::fromResponse(
            $response,
            isset($data['error']) && isset($data['error_description']) ? $data['error'].': '.$data['error_description'] : $response->getReasonPhrase()
        );
    }

    /**
     * Creates oauth exception from response.
     *
     * @param ResponseInterface $response
     * @param array             $data
     *
     * @return IdentityProviderException
     */
    public static function oauthException(ResponseInterface $response, array $data)
    {
        return static::fromResponse(
            $response,
            isset($data['error']) ? $data['error'] : $response->getReasonPhrase()
        );
    }

    /**
     * Creates identity exception from response.
     *
     * @param ResponseInterface $response
     * @param null              $message
     *
     * @return static
     */
    protected static function fromResponse(ResponseInterface $response, $message = null)
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }
}