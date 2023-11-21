<?php
/**
 * authentik strategy for Opauth
 * based on https://developers.authentik.com/accounts/docs/OAuth2
 *
 * More information on Opauth: http://opauth.org
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.authentikStrategy
 * @license      MIT License
 */

/**
 * authentik strategy for Opauth
 * based on https://developers.authentik.com/accounts/docs/OAuth2
 *
 * @package			Opauth.authentik
 */
class authentikStrategy extends OpauthStrategy
{
    /**
     * Compulsory config keys, listed as unassociative arrays
     */
    public $expects = array('client_id', 'client_secret');

    /**
     * Optional config keys, without predefining any default values.
     */
    public $optionals = array('auth_endpoint', 'token_endpoint', 'user_info_endpoint',
        'redirect_uri', 'scope', 'state', 'access_type', 'approval_prompt');

    /**
     * Optional config keys with respective default values, listed as associative arrays
     * eg. array('scope' => 'email');
     */
    public $defaults = array(
        'redirect_uri' => '{complete_url_to_strategy}oauth2callback',
        'scope' => 'profile email',
        'auth_endpoint' => 'https://meu.authentik/application/o/authorize/',
        'token_endpoint' => 'https://meu.authentik/application/o/token/',
        'user_info_endpoint' => 'https://meu.authentik/application/o/userinfo/'
    );

    /**
     * Parameters that should not be sent to OAuth
     */
    public $configOnly = array(
        'auth_endpoint', 'token_endpoint', 'user_info_endpoint'
    );

    /**
     * Auth request
     */
    public function request()
    {
        $url    = $this->strategy['auth_endpoint'];
        $params = array(
            'client_id' => $this->strategy['client_id'],
            'redirect_uri' => $this->strategy['redirect_uri'],
            'response_type' => 'code',
            'scope' => $this->strategy['scope']
        );

        foreach ($this->optionals as $key) {
            if (!empty($this->strategy[$key]) && array_search($key,
                    $this->configOnly) === false) {
                $params[$key] = $this->strategy[$key];
            }
        }

        $this->clientGet($url, $params);
    }

    /**
     * Internal callback, after OAuth
     */
    public function oauth2callback()
    {
        if (array_key_exists('code', $_GET) && !empty($_GET['code'])) {
            $code     = $_GET['code'];
            $url      = $this->strategy['token_endpoint'];
            $params   = array(
                'code' => $code,
                'client_id' => $this->strategy['client_id'],
                'client_secret' => $this->strategy['client_secret'],
                'redirect_uri' => $this->strategy['redirect_uri'],
                'grant_type' => 'authorization_code'
            );
            $response = $this->serverPost($url, $params, null, $headers);

            $results = json_decode($response);

            if (!empty($results) && !empty($results->access_token)) {
                $userinfo = $this->userinfo($results->access_token);

                $this->auth = array(
                    'uid' => $userinfo['id'],
                    'info' => array(),
                    'credentials' => array(
                        'token' => $results->access_token,
                        'expires' => date('c', time() + $results->expires_in)
                    ),
                    'raw' => $userinfo
                );

                if (!empty($results->refresh_token)) {
                    $this->auth['credentials']['refresh_token'] = $results->refresh_token;
                }

                $this->mapProfile($userinfo, 'name', 'first_name');
                $this->mapProfile($userinfo, 'email', 'email');
                $this->mapProfile($userinfo, 'given_name', 'first_name');
                $this->mapProfile($userinfo, 'family_name', 'surname');
                $this->mapProfile($userinfo, 'picture', 'profile_picture_url');

                $this->callback();
            } else {
                $error = array(
                    'code' => 'access_token_error',
                    'message' => 'Failed when attempting to obtain access token',
                    'raw' => array(
                        'response' => $response,
                        'headers' => $headers
                    )
                );

                $this->errorCallback($error);
            }
        } else {
            $error = array(
                'code' => 'oauth2callback_error',
                'raw' => $_GET
            );

            $this->errorCallback($error);
        }
    }

    /**
     * Queries authentik API for user info
     *
     * @param string $access_token
     * @return array Parsed JSON results
     */
    private function userinfo($access_token)
    {
        $userinfo = $this->serverGet($this->strategy['user_info_endpoint'],
            array('access_token' => $access_token), null, $headers);
        if (!empty($userinfo)) {
            return $this->recursiveGetObjectVars(json_decode($userinfo));
        } else {
            $error = array(
                'code' => 'userinfo_error',
                'message' => 'Failed when attempting to query for user information',
                'raw' => array(
                    'response' => $userinfo,
                    'headers' => $headers
                )
            );

            $this->errorCallback($error);
        }
    }
}
