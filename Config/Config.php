<?php

/* Adjust the following values in the contruct function
* username
 * password
 */


class Config
{

    private $aConnectInfo = [];

    public function __construct()
    {
        $this->aConnectInfo['username'] = 'set your PDFen.com user name (e-mail) here'; /* First create an account on https://www.pdfen.com/register */
        $this->aConnectInfo['password'] = 'set your PDFen.com password here';

        $this->aConnectInfo['api_root_url'] = 'https://www.pdfen.com/api/v1/';
    }

    public function getConnectInfo() {
        return $this->aConnectInfo;
    }


}