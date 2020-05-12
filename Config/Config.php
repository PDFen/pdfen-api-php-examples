<?php


/* Before you start, adjust the following values in the __contsruct function
 *
 * $this->aConnectInfo['username']
 * $this->aConnectInfo['password']
 *
*/


class Config
{

    private $aConnectInfo = [];

    public function __contsruct()
    {
        $this->aConnectInfo['username'] = 'set your PDFen.com user name (e-mail) here'; /* First create an account on https://www.pdfen.com/register */
        $this->aConnectInfo['password'] = 'set your PDFen.com password here';

        $this->aConnectInfo['api_root_url'] = 'https://www.pdfen.com/api/v1/';
    }

    public function getConnectInfo() {
        return $this->aConnectInfo;
    }


}