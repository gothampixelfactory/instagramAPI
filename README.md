instagramAPI
============

PHP Library to make calls to the Instagram API

- Namespaced
- PHP 5.3

## Requirements ##

- PHP Version >= 5.3
- PHP cURL extension

## Installation ##


## Usage ##
$client_id = '87d4f4cdd36649f5aea49442e0fd088a';
$instagram = new Instagram($client_id);
$callParams = array(
    'count' => 500
);
$response = $instagram->get('/v1/users/3/media/recent/', $callParams);

