<?php

require_once __DIR__ . '/../Core/Api.php';

$oApi = new Api();

/* Note: when you convert multiple files in one action, you get a zip file as result with all converted files (PDF) */

/* 1. First you create a session based on your username and password (POST). */

$aSession = $oApi->startSession(); /* Username and password can be set in /Config/Config.php */


/* 2. Now we can create multiple file objects (files we would like to convert) in the session. */
$aFileInfo['file_name_or_title'] = 'my test msg file 1'; /* change your title here */
$aFileInfo['extension'] = 'msg'; /* Set the extension */
$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/pdfenoutlookimagepdf.msg'; /* Absolute path to your source file. */

$result = $oApi->createNewFile($aSession,$aFileInfo);

/* Again adding a second one */

$aFileInfo['file_name_or_title'] = 'my test file 2'; /* change your title here */
$aFileInfo['extension'] = 'doc'; /* Set the extension */
$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/bijlage1.doc'; /* Absolute path to your source file. */

$result = $oApi->createNewFile($aSession,$aFileInfo);

/* Etc */


/* 3. Now you can set the options. If you have set a default template in your account (www.pdfen.com). Then this step is not needed. */

/* 3a. First get the current options with the function getOptions */
$aOptions = $oApi->getOptions($aSession);

/* 3b. Lets force batch (instead of merge), which means only convert and do not merge. */
$aOptions = $oApi->setBatch($aSession,$aOptions);

/* 4. Now we are ready to convert the file (msg in this example), with the function startProcess. */
$aProcess = $oApi->startProcess($aSession);

/* Check all the information you can check by var_dump($aProcess) */

echo '<h2>Finished</h2>';
echo 'Converting the files finished successful<br/>';
echo '<a href="' .  $aProcess['process_result']['url'] . '" target="_blank" >Download result</a>  (zip file with PDF files) ';










