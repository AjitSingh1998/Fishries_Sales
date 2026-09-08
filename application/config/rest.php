<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// base url of api 
//$config['request']['server'] = 'http://localhost/SIMRAN/rest_api/';
$config['request']['server'] = getenv('REST_API_SERVER') ?: '';
$config['request']['api_key'] = getenv('REST_API_KEY') ?: '';
$config['request']['api_name'] = 'X-API-KEY';
//$config['request']['http_user'] = 'username';
//$config['request']['http_pass'] = 'password';
//$config['request']['http_auth'] = 'basic';
//$config['request']['ssl_verify_peer'] = TRUE;
//$config['request']['ssl_cainfo'] = '/certs/cert.pem';
