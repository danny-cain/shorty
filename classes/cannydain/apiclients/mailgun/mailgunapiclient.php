<?php

namespace CannyDain\APIClients\MailGun;

use CannyDain\Lib\Emailing\Models\Email;

class MailGunAPIClient
{
    protected $_apiKey = '';
    protected $_apiURL = 'https://api.mailgun.net/v2';
    protected $_domain = '';

    public function __construct($_apiKey = '', $_domain = '')
    {
        $this->_apiKey = $_apiKey;
        $this->_domain = $_domain;
    }

    public function setApiURL($apiURL)
    {
        $this->_apiURL = $apiURL;
    }

    public function getApiURL()
    {
        return $this->_apiURL;
    }

    public function setDomain($domain)
    {
        $this->_domain = $domain;
    }

    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    public function sendEmail(Email $email)
    {
        $text = $email->getPlainTextBody();
        if($text== '')
            $text = 'No Text Version';

        $params = array
        (
            'from' => $email->getSender()->getName().' <'.$email->getSender()->getEmail().'>',
            'subject' => $email->getSubject(),
            'html' => $email->getBody(),
            'text'=> $text,
        );

        foreach ($email->getAttachments() as $index => $attach)
            $params['attachment['.$index.']'] = '@'.$attach->getSourceFile();

        foreach($email->getRecipients() as $index => $recip)
            $params['to['.$index.']'] = $recip->getEmail();

        foreach ($email->getCarbonCopyRecipients() as $index => $recip)
            $params['cc['.$index.']'] = $recip->getEmail();

        foreach ($email->getBlindCarbonCopyRecipients() as $index => $recip)
            $params['bcc['.$index.']'] = $recip->getEmail();

        $curl = curl_init($this->_apiURL.'/'.$this->_domain.'/messages');
        curl_setopt($curl, CURLOPT_USERPWD, 'api:'.$this->_apiKey);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, array("Content-Type: multipart/form-data"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($curl);
        $data = json_decode($response, true);

        if ($data == null)
        {
            list($headers, $body) = explode("\r\n\r\n", $response);
            $data = json_decode($body,true);
        }
    }
}