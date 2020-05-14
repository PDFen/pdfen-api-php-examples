<?php

/* Change the lines between 'START_CHANGE and END_CHANGE */

/* This example shows how to convert files to PDF. Just change the file you would like to convert. Check also ./ExampleFiles for example files */

require_once __DIR__ . '/../Core/Api.php';

$oApi = new Api();

/* 1. First you create a session based on your username and password (POST). */

$aSession = $oApi->startSession(); /* Username and password can be set in /Config/Config.php */

/* 2. Now we can create a file (file we would like to convert) in the session. */

/* START_CHANGE */
/* You can check all supported files by PDFen.con on this page: https://www.pdfen.com/faq/what-extensions-can-pdfen-convert-to-pdf */

$aFileInfo['file_name_or_title'] = 'my test file'; /* change your title here */

$aFileInfo['extension'] = 'doc'; /* Set the extension */
$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/bijlage1.doc'; /* Absolute path to your source file. */

/* OR */
//$aFileInfo['extension'] = 'ppt'; /* Set the extension */
//$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/bijlage2.ppt'; /* Absolute path to your source file. */

/* OR */
//$aFileInfo['extension'] = 'jpg'; /* Set the extension */
//$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/bijlage5.jpg'; /* Absolute path to your source file. */

/* ETC */

/* END_CHANGE */

$result = $oApi->createNewFile($aSession,$aFileInfo);

/* Note: you can repeat point 2 to add more than one file into the session. Files do NOT need to be the same format. Combinations of docx, doc, msg, eml, etc can be possible */


/* 3. Now you can set the options. If you have set a default template in your account (www.pdfen.com). Then this step is not needed. */

/* 3a. First get the current options with the function getOptions */
$aOptions = $oApi->getOptions($aSession);

/* 3b. Lets force batch (instead of merge), which means only convert and do not merge. */
$aOptions = $oApi->setBatch($aSession,$aOptions);

/* 4. Now we are ready to convert the file (msg in this example), with the function startProcess. */
$aProcess = $oApi->startProcess($aSession);

/* Check all the information you can check by var_dump($aProcess) */

echo '<h2>Finished</h2>';
echo 'Converting the file ' . $aFileInfo['file_name_or_title'] . ' finished successful<br/>';
echo '<a href="' .  $aProcess['process_result']['url'] . '" target="_blank" >Download result </a>';










