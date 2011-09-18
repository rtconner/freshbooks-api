# FreshBooksRequest PHP API

This class is meant to give you the ability to quickly access the FreshBooks API.

## Usage

* You need to know the method names and params when you're creating a new FreshBooksRequest instance. See all here http://developers.freshbooks.com/
* The XML tag parameters you see on the freshbooks API page are the ones you pass to $fb->post() (as an array)
* Require the lib and setup with your credentials (domain and token)

```php
require('lib/FreshBooksRequest.php');

$domain = 'your-subdomain'; // https://your-subdomain.freshbooks.com/
$token = '1234567890'; // your api token found in your account
FreshBooks::init($domain, $token);
```
* Now let's say we want to list clients with an email of some@email.com

```php
/**********************************************
 * Fetch all clients by a specific id
 **********************************************/
// Method name found on the freshbooks API
$fb = new FreshBooksRequest('client.list');
// Any arguments you want to pass it
$fb->post(array(
    'email' => 'some@email.com'
));
// Make the request
$fb->request();
if($fb->success())
{
    echo 'successful! the full response is in an array below';
    print_r($fb->getResponse());
}
else
{
    echo $fb->getError();
    print_r($fb->getResponse());
}
```
* If you're creating a recurring profile with multiple line items, it might look something like this:

```php
/**********************************************
 * Create a recurring profile with multiple line items
 **********************************************/
$fb = new FreshBooksRequest('recurring.create');
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
// You can view what the XML looks like that we're about to send over the wire
//print_r($fb->getGeneratedXML());
$fb->request();
if($fb->success())
{
    $res = $fb->getResponse();
    $recurrng_id = $res['recurring_id'];
    // Do something with the recurring_id you were returned
}
```

## Change Log

### Changes in 1.0 (Sept 18, 2011)

* Launched!
