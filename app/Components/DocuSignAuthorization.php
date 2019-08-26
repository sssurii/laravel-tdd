<?php

namespace App\Components;

use Firebase\JWT\JWT;
use Unirest\Request\Body;
use Unirest\Request;

class DocuSignAuthorization
{
    const TOKEN_EXPIRATION_IN_SECONDS = 3600; # 1 hour
    const TOKEN_REPLACEMENT_IN_SECONDS = 600; # 10 minutes

    private $config;
    private $exp = 3600;
    private $permission_scopes="signature%20impersonation";
    private $redirect_uri ="https://www.docusign.com";

    protected static $expires_in;
    protected static $access_token;
    protected static $expiresInTimestamp;
    protected static $accountID;
    protected static $base_uri;
    protected static $account;
    protected static $apiClient;

    public function __construct($client)
    {
        date_default_timezone_set('UTC');
        $clientId = env("DS_CLIENT_ID");
        if (!is_null($clientId) and !empty($clientId)) {
            $this->config = config('docusign');
        }
        self::$apiClient = $client;
    }

    public function checkToken()
    {
        if (is_null(self::$access_token)
           || (time() +  DocuSignAuthorization::TOKEN_REPLACEMENT_IN_SECONDS) > self::$expiresInTimestamp
        ) {
            $this->updateToken();
        }
    }

    private function updateToken()
    {
        $this->authToken = $this->configureJwtAuthorizationFlowByKey();
        self::$expiresInTimestamp = time() + self::$expires_in;

        // if (is_null(self::$account)) {
        //     self::$account = $this->getUserInfo();
        // }

        // self::$accountID = self::$account->{'account_id'};
        // self::$base_uri = self::$account->{'base_uri'}."/restapi";
        // $config = self::$apiClient->getConfig();
        // $config->setHost(self::$base_uri);
    }

    private function configureJwtAuthorizationFlowByKey()
    {
        $current_time = time();
        $aud = $this->authServerDomain();
        $iss = $this->config['DS_CLIENT_ID'];
        $_token = array(
            "iss" => $iss,
            "sub" => $this->config['DS_IMPERSONATED_USER_GUID'],
            "aud" => $aud,
            "scope" => $this->config['JWT_SCOPE'],
            "nbf" => $current_time,
            "exp" => $current_time + 60*1000
        );

        $private_key = $this->config['DS_PRIVATE_KEY'];
        $jwt  = JWT::encode($_token, $private_key, 'RS256');

        printf("Requesting an access token via the JWT flow...");
        $headers = array('Accept' => 'application/json');
        $data = array('grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer', 'assertion' => $jwt);
        $body = Body::form($data);
        $response = Request::post("https://{$aud}/oauth/token", $headers, $body);
        print_r($response);
        // Handle the response if it is an html page
        if (strpos($response->raw_body, '<html>') !== false) {
            throw new Exception("An error response was received!\n\n");
        }

        $json = $response->body;
        if (property_exists($json, 'error') && $json->{'error'} == 'consent_required') {
            $consent_url = "https://{$aud}/oauth/auth?response_type=code&scope={$this->permission_scopes}&client_id={$iss}&redirect_uri={$this->redirect_uri}";
            throw new Exception("\n\nC O N S E N T   R E Q U I R E D\n"
            ."Ask the user who will be impersonated to run the following url:\n"
            ."    {$consent_url}\n"
            ."It will ask the user to login and to approve access by your application.\n\n"
            ."Alternatively, an Administrator can use Organization Administration to\n"
            ."pre-approve one or more users.\n\n", 401);
        }

        if (property_exists($json, 'error') || !property_exists($json, 'access_token')) {
            throw new Exception("\n\nUnexpected error: {$json->{'error'}}\n\n");
        }

        printf("done. Continuing...");
        self::$access_token = $json->{'access_token'};
        self::$expires_in = $json->{'expires_in'};
        return $json;
        // $config = self::$apiClient->getConfig();
        // $config->setAccessToken(self::$access_token);
        // $config->addDefaultHeader('Authorization', "Bearer " . self::$access_token);
    }

    private function getUserInfo()
    {
        $aud = $this->authServerDomain();
        $access_token = self::$access_token;
        $user_info_url="https://{$aud}/oauth/userinfo";
        $headers = array('Accept' => 'application/json', 'Authorization' => "Bearer {$access_token}");
        $response = Request::get($user_info_url, $headers);

        $json = $response->body;
        $target = $this->config['DS_TARGET_ACCOUNT_ID'];
        $accounts = $json->{'accounts'};

        if (is_null($target) || empty($target) || $target === "FALSE") {
            foreach ($accounts as $acct) {
                if ($acct->{'is_default'} === true) {
                    return $acct;
                }
            }
        }

        # Looking for a specific user account
        foreach ($account as $acct) {
            if ($acct->{'account_id'} === $target) {
                return $acct;
            }
        }
        throw new Exception("\n\nUser does not have access to account {$target}\n\n");
    }

    private function authServerDomain()
    {
        $auth_server = $this->config['DS_AUTH_SERVER'];

        if (strpos($auth_server, 'https://') !== false) {
            $auth_server = substr($auth_server, 8);
        } else { # assuming http://blah
            $auth_server = substr($auth_server, 7);
        }
              return $auth_server;
    }
}
