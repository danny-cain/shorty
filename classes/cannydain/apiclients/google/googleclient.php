<?php

namespace CannyDain\APIClients\Google;

    require dirname(__FILE__).'/src/Google_Client.php';
    require dirname(__FILE__).'/src/contrib/Google_AnalyticsService.php';

class GoogleClient
{
    const SCOPE_ANALYTICS = 'https://www.googleapis.com/auth/analytics.readonly';

    protected $_sessionData = array();
    /**
     * @var \Google_Client
     */
    protected $_client;

    public function getBaseClient()
    {
        return $this->_client;
    }

    public function analytics()
    {
        static $analytics;

        if ($analytics == null)
        {
            $analytics = new AnalyticsClient($this);
        }

        return $analytics;
    }

    public function getAuthURL()
    {
        return $this->_client->createAuthUrl();
    }

    public function isAuthenticated()
    {
        return $this->_client->getAccessToken() != '';
    }

    public function connect($appName, $clientID, $clientSecret, $redirectURI, $devKey)
    {
        $this->_client = new \Google_Client();

        $this->_client->setApplicationName($appName);
        $this->_client->setClientId($clientID);
        $this->_client->setClientSecret($clientSecret);
        $this->_client->setRedirectUri($redirectURI);
        $this->_client->setDeveloperKey($devKey);
        $this->_client->setScopes(array(self::SCOPE_ANALYTICS));
        $this->_client->setUseObjects(true);
        
        if (isset($this->_sessionData['token']))
            $this->_client->setAccessToken($this->_sessionData['token']);

        if ($this->isCallback()) // callback from oAuth
        {
            $this->_client->authenticate();
            $this->_sessionData['token'] = $this->_client->getAccessToken();
        }
    }

    public function isCallback()
    {
        return isset($_GET['code']);
    }

    public function setSessionData($sessionData)
    {
        $this->_sessionData = $sessionData;
    }

    public function getSessionData()
    {
        return $this->_sessionData;
    }
}