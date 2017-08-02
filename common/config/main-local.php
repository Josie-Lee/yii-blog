<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=bdm30780697.my3w.com;dbname=bdm30780697_db',
            'username' => 'bdm30780697',
            'password' => '123456qq',
            'charset' => 'utf8',

        ],
            /*[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=blog',
            'username' => 'root',
            'password' => '1234',
            'charset' => 'utf8',
        ],*/
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
