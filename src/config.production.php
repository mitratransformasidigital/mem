<?php

/**
 * PHPMaker 2021 configuration file (Production)
 */

return [
    "Databases" => [
        "DB" => ["id" => "DB", "type" => "MYSQL", "qs" => "`", "qe" => "`", "host" => "localhost", "port" => "3306", "user" => "root", "password" => "", "dbname" => "mitral"]
    ],
    "SMTP" => [
        "PHPMAILER_MAILER" => "smtp", // PHPMailer mailer
        "SERVER" => "mail.mitral.co.id", // SMTP server
        "SERVER_PORT" => 465, // SMTP server port
        "SECURE_OPTION" => "ssl",
        "SERVER_USERNAME" => "marketing@mitral.co.id", // SMTP server user name
        "SERVER_PASSWORD" => "M1tralB1s4777", // SMTP server password
    ],
    "JWT" => [
        "SECRET_KEY" => "OiF2yHZvlL4TYci9", // API Secret Key
        "ALGORITHM" => "HS512", // API Algorithm
        "AUTH_HEADER" => "X-Authorization", // API Auth Header (Note: The "Authorization" header is removed by IIS, use "X-Authorization" instead.)
        "NOT_BEFORE_TIME" => 0, // API access time before login
        "EXPIRE_TIME" => 600 // API expire time
    ]
];
