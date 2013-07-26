<?php

namespace CannyDain\APIClients\Google;

use Exception;

class AnalyticsClient
{
    /**
     * @var GoogleClient
     */
    protected $_googleClient;

    /**
     * @var \Google_AnalyticsService
     */
    protected $_analytics;

    public function __construct(GoogleClient $client)
    {
        if (!$client->isAuthenticated())
            throw new Exception("Analytics requires authentication");

        $this->_googleClient = $client;
        $this->_analytics = new \Google_AnalyticsService($this->_googleClient->getBaseClient());
    }

    public function dev()
    {
        $profID = $this->getFirstProfileID();
        $results = $this->_analytics->data_ga->get('ga:' . $profID, '2013-07-26', '2013-07-26', 'ga:visits');

        $name = $results->getProfileInfo()->getProfileName();
        $rows = $results->getRows();
        $visits = $rows[0][0];

        echo $name.': '.$visits.' visits<br>';
    }

    public function getFirstProfileID()
    {
        $accounts = $this->_analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0)
        {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $webproperties = $this->_analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($webproperties->getItems()) > 0)
            {
                $items = $webproperties->getItems();
                $firstWebpropertyId = $items[0]->getId();

                $profiles = $this->_analytics->management_profiles->listManagementProfiles($firstAccountId, $firstWebpropertyId);

                if (count($profiles->getItems()) > 0)
                {
                    $items = $profiles->getItems();
                    return $items[0]->getId();

                }
                else
                {
                    throw new Exception('No profiles found for this user.');
                }
            }
            else
            {
                throw new Exception('No webproperties found for this user.');
            }
        }
        else
        {
            throw new Exception('No accounts found for this user.');
        }
    }
}