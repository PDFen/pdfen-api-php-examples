# pdfen-api-php-examples
PHP examples how to link to the API of PDFen.com

Not a PHP developer? But you are a programmer? Then these PHP examples should still make it clear how to call the REST API of PDFen.com. 
However do not hesitate to contact us contact@pdfen.com if you have any questions, remarks or need help.

INSTALLATION
1. Download or clone the project

2. Put your credientials in /Config/Config.php

$this->aConnectInfo['username'] = 'set your PDFen.com user name (e-mail) here';

$this->aConnectInfo['password'] = 'set your PDFen.com password here';

3. Choose an example, e.g. How to convert MSG file to PDF

Example: Examples\convert-one-msg-file.php and adjust:

$aFileInfo['file_name_or_title'] = 'my test msg file'; /* change your title here */

$aFileInfo['extension'] = 'msg'; /* Set the extension */

$aFileInfo['source_path'] =  __DIR__ . '/ExampleFiles/pdfenoutlookimagepdf.msg'; /* Absolute path to your source file. */


4. Run the php program, e.g.:
http://localhost/pdfen-api-php-examples/Examples/convert-one-msg-file.php

5. If no errors occure, you can download the result through the url


PROJECT Content

/Config

/Config.php: Change the username and password

/Examples

Example php files how to convert or merge files

/ExampleFiles

Example dummy source files which you can use to be converted

/Core

Api.php: This file contains functions how to call the API of PDFen.com. Step by step you can see how to call the API.
 
