<?php

namespace CannyDain\APIClients\Google;

use CannyDain\APIClients\Google\Models\Analytics\AnalyticsQuery;
use CannyDain\APIClients\Google\Models\Analytics\AnalyticsQueryResponse;
use Exception;

class AnalyticsClient
{
    //https://developers.google.com/analytics/devguides/reporting/core/dimsmets#q=country&cats=visitor,session,trafficsources,adwords,goalconversions,platform,geonetwork,system,socialactivities,pagetracking,internalsearch,sitespeed,apptracking,eventtracking,ecommerce,socialinteractions,usertimings,exceptions,experiments,customvars,time
    const METRIC_VISITS = 'ga:visits';
    const METRIC_BOUNCES = 'ga:bounces';
    const METRIC_NEW_VISITS = 'ga:newVisits';

    const DIMENSION_BROWSER = 'ga:browser';
    const DIMENSION_COUNTRY = 'ga:country';

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

    public function getTotalVisitsBetweenDates($profileID, $startDate, $endDate)
    {
        return $this->query(new AnalyticsQuery($profileID, $startDate, $endDate, array(self::METRIC_VISITS)));
    }

    public function query(AnalyticsQuery $query)
    {
        $params = array
        (
            'dimensions' => implode(',', $query->getDimensions())
        );
        $start = $query->getStartDate();
        $end = $query->getEndDate();

        if (is_int($start))
            $start = date('Y-m-d', $start);
        if (is_int($end))
            $end = date('Y-m-d', $end);

        $results = $this->_analytics->data_ga->get('ga:'.$query->getProfileID(), $start, $end, implode(',', $query->getMetrics()), $params);


        return AnalyticsQueryResponse::ParseFromGAResponse($results);
    }

    public function dev()
    {
        $results = $this->query(new AnalyticsQuery
        (
            $this->getFirstProfileID(),
            null,
            null,
            array(self::METRIC_VISITS, self::METRIC_BOUNCES),
            array(self::DIMENSION_BROWSER, self::DIMENSION_COUNTRY)
        ));

        echo '<table>';
            echo '<tr>';
                echo '<th>Country/Browser</th>';
                echo '<th>Visits</th>';
                echo '<th>Bounces</th>';
            echo '</tr>';

        foreach ($results->getRows() as $row)
        {
            echo '<tr>';
                echo '<td>'.$row->getValueByHeader(self::DIMENSION_COUNTRY).'/'.$row->getValueByHeader(self::DIMENSION_BROWSER).'</td>';
                echo '<td>'.$row->getValueByHeader(self::METRIC_VISITS).'</td>';
                echo '<td>'.$row->getValueByHeader(self::METRIC_BOUNCES).'</td>';
            echo '</tr>';
        }
        echo '</table>';
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