<?php
namespace AMT\AdvApi\FacebookAds;
use AMT\AdvApi\AdsmanagerInterface;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\Business;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\BusinessFields;
use FacebookAds\Object\Values\AdsInsightsLevelValues;
use FacebookAds\Object\Values\AdsInsightsActionAttributionWindowsValues;
use FacebookAds\Logger\LoggerInterface;
use FacebookAds\Cursor;

class FbManager implements AdsmanagerInterface  {
    /** @var Api $api */
    private $api;
    /** @var Business $business */
    private $business;
    /** @var AdAccount[] $accounts */
    private $accounts=[];
    private $businessData;
    function __construct($appId,$appSecret,$authToken,$businessId=null) {
        Api::init($appId, $appSecret, $authToken);
        // The Api object is now available trough singleton
        $api = Api::instance();
        if (!is_null($businessId)) {
            $this->setBusinessId($businessId);
        }
    }
    public function setLogger(LoggerInterface $logger) {
        $this->api->setLogger($logger);

    }
    public function setBusinessId($businessId) {
        $this->business=new Business($businessId);
        $this->businessData = $this->business->getSelf([
            BusinessFields::NAME,
            BusinessFields::LINK,
            BusinessFields::PROFILE_PICTURE_URI,
        ]);
        /** @var Cursor $cursor */
        $cursor=$this->business->getClientAdAccounts([
            AdAccountFields::ACCOUNT_ID,
            AdAccountFields::ACCOUNT_STATUS,
            AdAccountFields::BUSINESS_NAME,
            AdAccountFields::NAME,
            AdAccountFields::USER_ROLE,
        ]);
        foreach ($cursor as $account) {
            $this->accounts[$account->{AdAccountFields::ACCOUNT_ID}]=$account;
        }
    }
    public function getAccountsIds() {
        return array_keys($this->accounts);
    }
    public function getAccounts() {
        return $this->accounts;
    }
    public function getAccountIdByName($name) {
        foreach ($this->accounts as $id=>$account) {
            if (!strcasecmp($name,$account->{AdAccountFields::BUSINESS_NAME})) return $id;
            if (!strcasecmp($name,$account->{AdAccountFields::NAME})) return $id;
        }
        $l=strlen($name);
        foreach ($this->accounts as $id=>$account) {
            if (!strncasecmp($name,$account->{AdAccountFields::BUSINESS_NAME},$l)) return $id;
            if (!strncasecmp($name,$account->{AdAccountFields::NAME},$l)) return $id;
        }
        return 0;
    }
    public function getInsights($accountId,array $fields=null,array $params=null) {
        $account=$this->accounts[$accountId];
        return $account->getInsights($fields,$params)->getResponse()->getContent();
    }

}

