# pdfen-api-php-examples
PHP examples how to link to the API of PDFen.com

INSTALLATION
1. Download or clone the project

2. Put your credientials in /Config/Config.php
$this->aConnectInfo['username'] = 'set your PDFen.com user name (e-mail) here'; /* First create an account on https://www.pdfen.com/register */
$this->aConnectInfo['password'] = 'set your PDFen.com password here';

3. Choose an example, e.g. How to convert MSG file to PDF
Example: Examples\convert-one-msg-file.php
php -f onvert-one-msg-file.ph

OR through browser:
http://localhost/pdfen-api-php-examples/Examples/convert-one-msg-file.php

If no errors occure, you can download the result through the url

