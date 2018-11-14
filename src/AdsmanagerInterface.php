<?php
namespace AMT\AdvApi;

interface AdsmanagerInterface
{
    function __construct(string $id,string $secret,string $token,string $customer=null);
    function setBusinessId($id);
    function getAccountIdByName($name);
    function getAccounts();
    function getInsights($id,array $fields=null,array $options=null);
}

