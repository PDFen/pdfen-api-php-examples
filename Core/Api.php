<?php

require_once __DIR__ . '/../Config/Config.php';

class Api
{
    private $aConnectInfo = [];

    public function __construct()
    {
        $oConfig = new Config();
        $this->aConnectInfo = $oConfig->getConnectInfo();

    }

    /**
     *
     * This function just send the request through the the api and returns the result.
     *
     * @param $method
     * @param $url
     * @param $data
     * @return bool|string
     */
    public function callAPI($method, $url, $data)
    {

        $ch = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',));
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',));
                if ($data) {
                    $url = sprintf("%s?%s", $url, $data);
                }
        }

        // OPTIONS:
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        /* Depending on the number of files or size of your files, you would like to increase or decrease this..or leave it comment out and let PDFen.com decide */
        /* curl_setopt($ch, CURLOPT_TIMEOUT, 300); */

        // EXECUTE:
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!$result && $httpCode !== 204) {
            echo $httpCode . '<br>';
            echo curl_error($ch) . '<br>';
            echo "Connection Failure";
            die();
        }
        curl_close($ch);
        return $result;
    }


    /**
     * Create a session based on username and password. This session can be used to perform all next requests
     *
     * POST: https://www.pdfen.com/api/v1/sessions
     *
     * @param $aConnectInfo ['api_root_url']
     * @return mixed
     */
    public function startSession()
    {

        $aOptions = array(
            "username" => $this->aConnectInfo['username'],
            "password" => $this->aConnectInfo['password'],
        );

        $response = $this->callAPI('POST', $this->aConnectInfo['api_root_url'] . 'sessions', json_encode($aOptions));
        $aSession = json_decode($response, true);

        if (!isset($aSession['session_id'])) {
            /* Something went wrong, check message */
            var_dump($aSession);
            die();
        }

        return $aSession;
    }


    /**
     * To add a new file in the session (file which you would like to convert. You first create the file object and after that add the content to the file.
     *
     * Step 1: POST: https://www.pdfen.com/api/v1/sessions/{session_id}/files
     * Step 2: PUT: https://www.pdfen.com/api/v1/sessions/{session_id}/files/{file_id}/data
     *
     * @param $aSession
     * @param $aFileInfo mixed
     * @return mixed
     */
    public function createNewFile($aSession, $aFileInfo = [])
    {

        $aOptions = array(
            "file_settings" => array
            (
                "title" => $aFileInfo['file_name_or_title'],
                "extension" => $aFileInfo['extension'],
            )
        );

        /* Step 1, create the file object in the session */
        $result = $this->callAPI('POST', $this->aConnectInfo['api_root_url'] . 'sessions/' . $aSession["session_id"] . '/files', json_encode($aOptions));
        $aFile = json_decode($result, true);
        if (!isset($aFile['file_id'])) {
            echo 'ERROR: Creating file object in session failed';
            /* Something went wrong, check message */
            var_dump($aFile);
            die();
        }

        unset($result);

        /* Step 2, add content to the file in the session */
        $data = file_get_contents($aFileInfo['source_path']);
        $result = $this->callAPI('PUT', $this->aConnectInfo['api_root_url'] . 'sessions/' . $aSession["session_id"] . '/files/' . $aFile['file_id'] . '/data', $data);
        /* Note $result is empty on success */

        if ( !empty($result)) {
             echo 'ERROR: Adding file content failed';
             var_dump($result);
             die();
        }

        return $aFile;
    }


    /**
     * Get all the set options from the session
     *
     * GET: https://www.pdfen.com/api/v1/sessions/{session_id}/options
     *
     * @param $aSession
     * @return mixed, all information about the options
     *
     */
    public function getOptions($aSession)
    {
        $result =$this->callAPI('GET', $this->aConnectInfo['api_root_url'] . 'sessions/' . $aSession["session_id"] . '/options', false);
        $aOptions = json_decode($result, true);

        if ( !is_array($aOptions) || !isset($aOptions['typeofaction'])) {
            echo 'ERROR: Options were not retreived';
            var_dump($aOptions);
            die();
        }

        return $aOptions;
    }

    /**
     *
     * Function to force batch as method.
     *
     * PUT: https://www.pdfen.com/api/v1/sessions/{session_id}/options
     *
     * @param $aSession
     * @param $aOptions
     * @return mixed, all set options
     */
    public function setBatch($aSession, $aOptions)
    {

        $aOptionsNew['typeofaction'] = 'batch';
        $aOptionsNew['template_id'] = $aOptions['template_id'];

        $result = $this->callAPI('PUT', $this->aConnectInfo['api_root_url'] . 'sessions/' . $aSession["session_id"] . '/options', json_encode($aOptionsNew));
        /* Note $result is empty on success */
        unset($result);
        $aOptionsResult = $this->getOptions($aSession);

        if ( !is_array($aOptionsResult) || !isset($aOptionsResult['typeofaction']) || $aOptionsResult['typeofaction'] != 'batch' ) {
            echo 'ERROR: Batch option was not set';
            var_dump($aOptionsResult);
            die();
        }

        return $aOptions;
    }

    /**
     *
     * Function to force merge as method.
     *
     * PUT: https://www.pdfen.com/api/v1/sessions/{session_id}/options
     *
     * @param $aSession
     * @param $aOptions
     * @return mixed, all set options
     */
    public function setMerge($aSession, $aOptions)
    {

        $aOptionsNew['typeofaction'] = 'merge';
        $aOptionsNew['template_id'] = $aOptions['template_id'];

        $result = $this->callAPI('PUT', $this->aConnectInfo['api_root_url'] . 'sessions/' . $aSession["session_id"] . '/options', json_encode($aOptionsNew));
        /* Note $result is empty on success */
        unset($result);
        $aOptionsResult = $this->getOptions($aSession);

        if ( !is_array($aOptionsResult) || !isset($aOptionsResult['typeofaction']) || $aOptionsResult['typeofaction'] != 'merge' ) {
            echo 'ERROR: Merge option was not set';
            var_dump($aOptionsResult);
            die();
        }

        return $aOptions;
    }

    /**
     * Start converting the file
     *
     * POST: https://www.pdfen.com/api/v1/sessions/{session_id}/processes
     *
     * @param $aSession
     * @return mixed, the result
     */
    public function startProcess($aSession)
    {

        /* In this example we set to call the converter and wait for it to finish */
        $aOptions = array(
            "process_settings" => array(
                "process_synchronous" => true,
                "immediate"           => true
            )
        );

        $result = $this->callAPI('POST', $this->aConnectInfo['api_root_url'] . 'sessions/' . $aSession["session_id"] . '/processes', json_encode($aOptions));
        $aProcess = json_decode($result, true);

        if ( !is_array($aProcess) || $aProcess['process_result']['status'] == 'ERROR' || empty($aProcess['process_result']['url']) ) {
            echo 'ERROR: Processing the file failed';
            var_dump($aProcess);
            die();
        }
        elseif  ( $aProcess['process_result']['status'] == 'INFO'  ) {
            /* Do something. Maybe conversion went well, but not all could be converted properly. Check the file */
        }
        elseif  ( $aProcess['process_result']['status'] == 'WARNING'  ) {
            /* Do something. Conversion went wrong with at least one file */
        }


        /* Status is OK */
        return $aProcess;
    }

}