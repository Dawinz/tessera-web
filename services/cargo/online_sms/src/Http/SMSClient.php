<?php

namespace Mulopwe\Orange\SMS\Http;

use Pimple\Container;
use Mulopwe\Orange\SMS\Http\Requests\AuthorizationRequest;

class SMSClient
{
    /**
     * Access token.
     *
     * @var string
     */

    
protected
 
$token;

    /**
     * Expires time.
     *
     * @var string
     */

    
protected
 
$expiresIn;

    /**
     * SMSCLient singleton instance.
     *
     * @var static
     */

    
protected
 
static
 
$instance;

    /**
     * Client ID.
     *
     * @var string
     */
    protected $clientID;

    /**
     * Client secret.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * Dependency injection container.
     *
     * @var Container
     */
    protected $container;

    /**
     * SMSClient constructor.
     *
     * @param string $clientID
     * @param string $clientSecret
     * @param Container $container
     */
    public function __construct(string $clientID, string $clientSecret, Container $container)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->container = $container;

        // Get the access token and expiration time from the Orange SMS API.
        $authorizationRequest = new AuthorizationRequest($clientID, $clientSecret);
        $response = $authorizationRequest->execute()->getBody();
        $authorizationResponse = json_decode($response, true);

        $this->container['access_token'] = $authorizationResponse['access_token'];
        $this->container['expires_in'] = $authorizationResponse['expires_in'];
    }

    /**
     * Set the access token.
     *
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->container['access_token'] = $token;

        return $this;
    }

    /**
     * Get the access token.
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->container['access_token'];
    }

    /**
     * Set the expires_in in seconds
     *
     * @param $expiresIn
     * @return $this
     */
    public function setTokenExpiresIn($expiresIn)
    {
        $this->container['expires_in'] = $expiresIn;

        return $this;
    }

    /**
     * Get the expire_in in seconds
     *
     * @return string
     */
    public function getTokenExpiresIn()
    {
        return $this->container['expires_in'];
    }

    /**
     * Refresh the access token.
     *
     * @return void
     */
    public function refreshToken()
    {
        $request = new AuthorizationRequest($this->clientID, $this->clientSecret);
        $response = $request->execute()->getBody();

        $this->container['access_token'] = json_decode($response, true)['access_token'];
        $this->container['expires_in'] = json_decode($response, true)['expires_in'];
    }

    /**
     * Refresh the access token automatically.
     *
     * @return void
     */
    public function refreshTokenAutomatically()
    {
        if (!$this->isAccessTokenValid()) {
            $this->refreshToken();
        }
    }

    /**
     * Check if the access token is valid.
     *
     * @return bool
     */
    public function isAccessTokenValid()
    {
        return isset($this->container['access_token']) && time() < $this->container['expires_in'];
    }




    /**
     * Configure the instance.
     *
     * @param array $options
     * @return $this
     */
    public function configure()
    {
        switch (count($options = func_get_args())) {
            case 0:
                break;

            case 1:
                $this->configureInstance($options[0]);
                break;

            case 2:
                $this->configureInstanceAssoc(
                    static::authorize($options[0], $options[1])
                );
                break;

            default:
                throw new \InvalidArgumentException('invalid argument count');
                break;
        }

        return $this;
    }

    /**
     * Configure instance using options.
     *
     * @param  mixed  $options
     * @return $this
     */
    protected function configureInstance($options)
    {
        if (is_string($options)) {
            $this->setToken($options)->setTokenExpiresIn(null);
        } elseif (is_array($options)) {
            $this->configureInstanceAssoc($options);
        }
    }

    /**
     * Configure instance using assoc array options.
     *
     * @param  array  $options
     * @return $this
     */
    protected function configureInstanceAssoc(array $options)
    {
        if (array_key_exists('access_token', $options)) {
            $this->setToken($options['access_token']);
        }

        if (array_key_exists('expires_in', $options)) {
            $this->setTokenExpiresIn($options['expires_in']);
        }

        return $this;
    }

    /**
     * Execute a request against the Api server
     *
     * @param SMSClientRequest $request
     * @param bool $decodeJson
     * @return array
     */
    public function executeRequest(SMSClientRequest $request, $decodeJson = true)
    {
        $options = $request->options();

        if (! isset($options['headers']["Authorization"])) {
            $options['headers']["Authorization"] = "Bearer ". $this->getToken();
        }

        $response = $request->execute($options)->getBody();

        return $decodeJson ? json_decode($response, true) : $response;
    }

    /**
     * Get the client access token
     *
     * @param $clientID
     * @param $clientSecret
     * @return array
     */
    public static function authorize($clientID, $clientSecret)
    {
        return json_decode(
            (new AuthorizationRequest($clientID, $clientSecret))->execute()->getBody(), true
        );
    }

    /**
     * Get the prepared singleton instance of the client.
     *
     * @return SMSClient
     */
    public static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = new static();
        }

        return static::$instance->configure(...func_get_args());
    }
}
