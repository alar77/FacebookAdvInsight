<?php
error_reporting(E_ALL ^E_NOTICE);
require_once(__DIR__ . "/../../vendor/autoload.php");
use AMT\AdvApi\FacebookAds\FbManager;
use Dotenv\Dotenv;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Values\AdsInsightsLevelValues;
/*
 * _env è un file in formato chiave=valore che contiene i dati di configurazione. NON viene coindiviso su git
 * perché puo' contenere dati sensibili. Vedere _env.default per il formato
 */
(new Dotenv(__DIR__,'.env'))->load();
try {
    $manager=new FbManager(getenv('app_id'),getenv('app_secret'),getenv('access_token'),getenv('business_id'));
    $accountId=$manager->getAccountIdByName("Phil Pharma");
    $fields=[
        AdsInsightsFields::IMPRESSIONS,
        AdsInsightsFields::SPEND,
        AdsInsightsFields::CPP,
        AdsInsightsFields::ACCOUNT_NAME,
        AdsInsightsFields::ACCOUNT_ID,
        AdsInsightsFields::CAMPAIGN_NAME,
        AdsInsightsFields::CAMPAIGN_ID,
        AdsInsightsFields::ADSET_NAME,
        AdsInsightsFields::ADSET_ID,
        AdsInsightsFields::AD_NAME,
        AdsInsightsFields::AD_ID,
        AdsInsightsFields::CLICKS,
    ];
    $params = array(
        'level' =>AdsInsightsLevelValues::CAMPAIGN,
        'filtering' => [],
        'time_range' => ['since' => '2018-04-02','until' => '2018-11-14'],
    );
    print_r($manager->getInsights($accountId,$fields,$params));
}
catch(\Exception $e) {
    echo "Exception " ,$e->getMessage(),"\n";
    echo "\t",$e->getFile()," at ",$e->getLine(),"\n";
}