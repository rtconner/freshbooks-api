FreshBooks API
============

PHP wrapper for the FreshBooks API. Simplifies FreshBooks API XML structure into a PHP array strucure. You need to know the method names and params when you're creating a new FreshBooksApi instance. See all here http://developers.freshbooks.com/

#### Composer Install

    composer require rtconner/freshbooks-api dev-master
    
    .. or ..
    
    "require": {
        "rtconner/freshbooks-api": "dev-master"
    }


#### Sample Code

The XML tag parameters you see on the freshbooks API page are the ones you pass to $fb->post() (as an array)

Statically :
```php
$domain = 'your-subdomain'; // https://your-subdomain.freshbooks.com/
$token = '1234567890'; // your api token found in your account
Freshbooks\FreshBooksApi::init($domain, $token); 
```

Or using _construct and object instance:

```php
$domain = 'your-subdomain'; // https://your-subdomain.freshbooks.com/
$token = '1234567890'; // your api token found in your account
$fb = new Freshbooks\FreshBooksApi($domain, $token); 
```

Example: list clients with an email of some@email.com

```php
// Method names are the same as found on the freshbooks API
// Statically
$fb = new Freshbooks\FreshBooksApi('client.list');
// Or 
$fb->setMethod('client.list');

// For complete list of arguments see FreshBooks docs at http://developers.freshbooks.com
$fb->post(array(
    'email' => 'some@email.com'
));

$fb->request();

if($fb->success()) {
	echo 'successful! the full response is in an array below';
	var_dump($fb->getResponse());
} else {
	echo $fb->getError();
	var_dump($fb->getResponse());
}
```

#### Credits

 - Jordan Boesch - http://boedesign.com/
 - Jason Reading - http://jasonreading.com
 - Robert Conner - http://smartersoftware.net
