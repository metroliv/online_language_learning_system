<?php



if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];


/* On Deploying In Live Server Kindly Change This To Your Given URL */
$url = $_SERVER['HTTP_HOST'] . '/views/confirm_password?token=';
$account_password_set = $_SERVER['HTTP_HOST'] . '/views/user_account_activation?token=';
$new_url = $_SERVER['HTTP_HOST'] . '/views/index';
$confirm_email_link = $_SERVER['HTTP_HOST'] . '/views/verify?email=';