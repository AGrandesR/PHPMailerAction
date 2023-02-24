# PHPMailerAction
*This package is an extension of Agrandesr/agile-router (v1.0+).*

This Custom Actions is a implementation of the [PHPMailer](https://github.com/PHPMailer/PHPMailer) package over Agile Router to send emails in a very easy way using the Custom Actions of Agile Router.

## Installation
First we need to require the package:
``` bash
composer require agrandesr/php-mailer-action
```
Next, we need to add to the Router before the run method.

``` php
require './vendor/autoload.php';

use Agrandesr\Router;

$router = new Router();

$router->addCustomAction('mail','App\\CustomActions\\PhpMailerAction');

$router->run();
```
Next you have to modify .env file with your mail server data:
``` .env
MAIL_HOST=domain.mail.example
MAIL_PORT=587
MAIL_USERNAME=no-reply@test.com
MAIL_PASSWORD=*******************
#MAIL_SECURITY=TLS
MAIL_SENDNAME=EmailTest
```

Now you can use the new action in your routes file.

``` json
{
    "mail":{
        "GET":{
            "execute":[
                {
                    "type":"mail",
                    "content":{
                        "addAddress":"example@test.com",
                        "addCC":"exampleCC@test.com",
                        "addBCC":"exampleBCC@test.com",
                        "addAttachment":[["src/file/image.png","ImageName.png"]],
                        "body":"src/template/mail.html",
                        "altBody":"No worries, was only a test"
                    }
                }
            ]
        }
    }
}
```
And that is all, you can create a endpoint to send a email very easy.

## Content parameters
Like you can see in the example, the action "PhpMailer" have the next parameters:
 - addAddress['required']:
 - addCC['optional']:
 - addBCC['optional']:
 - addAttachment['optional']:
 - body['optional']: You can add a source direction like "src/template.html" or write directly the html code.
 - altBody['optional']:
 - envFlag: You can add a flag to change the env variable key name to allow more than one mail server.

## ENV variables
You can have more than one mail server setted for one project using envFlag. The envFlag adds the value of the endFlag in the middle of your envFlag key. For example:
``` json
{
    "mail":{
        "GET":{
            "execute":[
                {
                    "type":"mail",
                    "content":{
                        "envFlag":"CALIFORNIA"
                        "addAddress":"california@test.com",
                        "body":"src/template/mail.html"
                    }
                },
                {
                    "type":"mail",
                    "content":{
                        "envFlag":"TEXAS"
                        "addAddress":"texas@test.com",
                        "body":"src/template/mail.html"
                    }
                }
            ]
        }
    }
}
```
For the last example you have to complete other env variables:
``` .env
MAIL_CALIFORNIA_HOST=domain.mail.example
MAIL_CALIFORNIA_PORT=587
MAIL_CALIFORNIA_USERNAME=no-reply@test.com
MAIL_CALIFORNIA_PASSWORD=*******************
MAIL_CALIFORNIA_SENDNAME=EmailTest

MAIL_TEXAS_HOST=domain.mail.example
MAIL_TEXAS_PORT=587
MAIL_TEXAS_USERNAME=no-reply@test.com
MAIL_TEXAS_PASSWORD=*******************
MAIL_TEXAS_SENDNAME=EmailTest
```