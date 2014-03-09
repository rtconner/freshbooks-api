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

```php
$domain = 'your-subdomain'; // https://your-subdomain.freshbooks.com/
$token = '1234567890'; // your api token found in your account
Freshbooks\FreshBooksApi::init($domain, $token);
```

Example: list clients with an email of some@email.com

```php
// Method names are the same as found on the freshbooks API
$fb = new Freshbooks\FreshBooksApi('client.list');

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

If you're creating a recurring profile with multiple line items, it might look something like this:

```php
// Create a recurring profile with multiple line items
$fb = new Freshbooks\FreshBooksApi('recurring.create');
$fb->post(array(
    'recurring' => array(
        'client_id' => 41,
        'lines' => array(
            'line' => array(
                array(
                    'name' => 'A prod name',
                    'description' => 'The description',
                    'unit_cost' => 10,
                    'quantity' => 2
                ),
                array(
                    'name' => 'Another prod name',
                    'description' => 'The other description',
                    'unit_cost' => 20,
                    'quantity' => 1
                )
            )
        )
    )
));

var_dump($fb->getGeneratedXML()); // You can view what the XML looks like that we're about to send over the wire

$fb->request();

if($fb->success()) {
    $res = $fb->getResponse();
    var_dump($res['recurring_id']);
}
```

#### Credits

 - Jordan Boesch - http://boedesign.com/
 - Jason Reading - http://jasonreading.com
 - Robert Conner - http://smartersoftware.net
