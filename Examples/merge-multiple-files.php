<?php

/* This example converts multiple files to one PDF */

require_once __DIR__ . '/../Core/Api.php';

$oApi = new Api();

/* 1. First you create a session based on your username and password (POST). */

$aSession = $oApi->startSession(); /* Username and password can be set in /Config/Config.php */

/* 2. Now we can create multiple file objects (files we would like to convert) in the session. */

/* 2a. Add the files */
$aOrdering = [];
$aFileInfo['file_name_or_title'] = 'my test msg file 1'; /* change your title here */
$aFileInfo['extension'] = 'msg'; /* Set the extension */
$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/pdfenoutlookimagepdf.msg'; /* Absolute path to your source file. */

$aResult = $oApi->createNewFile($aSession,$aFileInfo);
$aOrdering[] = $aResult['file_id'];

/* Collect files, so we can remove them at once at the end */
$aFiles[] = $aResult['file_id'];

/* Again a second one */
$aFileInfo['file_name_or_title'] = 'my test file 2'; /* change your title here */
$aFileInfo['extension'] = 'doc'; /* Set the extension */
$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/bijlage1.doc'; /* Absolute path to your source file. */

$aResult = $oApi->createNewFile($aSession,$aFileInfo);
$aOrdering[] = $aResult['file_id'];

/* Collect files, so we can remove them at once at the end */
$aFiles[] = $aResult['file_id'];

/* Again a third one */
$aFileInfo['file_name_or_title'] = 'my test file 3'; /* change your title here */
$aFileInfo['extension'] = 'jpg'; /* Set the extension */
$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/bijlage5.jpg'; /* Absolute path to your source file. */

$aResult = $oApi->createNewFile($aSession,$aFileInfo);
$aOrdering[] = $aResult['file_id'];

/* Collect files, so we can remove them at once at the end */
$aFiles[] = $aResult['file_id'];

/* Etc */

/* 2b. Update the ordering. See description in function setOrdering description */
$aResult = $oApi->setOrdering($aSession,$aOrdering);


/* 3. Now you can set the options. If you have set a default template in your account (www.pdfen.com). Then this step is not needed. */

/* 3a. First get the current options with the function getOptions */
$aOptions = $oApi->getOptions($aSession);

/* 3b. Lets force batch (instead of merge), which means only convert and do not merge. */
$aOptions = $oApi->setMerge($aSession,$aOptions);

/* 3c. Now set a title for the merged PDF file */
$aOptions = $oApi->setTitle($aSession,$aOptions,'My merged files');


/* 4. Now we are ready to convert the file (msg in this example), with the function startProcess. */
$aProcess = $oApi->startProcess($aSession);

/* 5. Remove all (uploaded files) */
$deleteResult = $oApi->deleteUploadedFiles($aSession,$aFiles);

/* Check all the information you can check by var_dump($aProcess) */

echo '<h2>Finished</h2>';
echo 'Converting and merging the files to one PDF finished successful<br/>';
echo '<a href="' .  $aProcess['process_result']['url'] . '" target="_blank" >Download result</a>';










