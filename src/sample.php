<?php

use Freshbooks\FreshBooksApi;

// Setup the login credentials
$domain = '';
$token = '';
FreshBooksApi::init($domain, $token);

/**********************************************
 * Fetch all clients by a specific id
 **********************************************/
$fb = new FreshBooksApi('client.list');
$fb->post(array(
    'email' => 'some@email.com'
));
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

/**********************************************
 * List invoices from a specific client
 **********************************************/
$fb = new FreshBooksApi('invoice.list');
$fb->post(array(
    'client_id' => 41
));
$fb->request();
if($fb->success())
{
    print_r($fb->getResponse());
}
else
{
    echo $fb->getError();
    print_r($fb->getResponse());
}

/**********************************************
 * Create a recurring profile with multiple line items
 **********************************************/
$fb = new FreshBooksApi('recurring.create');
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
//print_r($fb->getGeneratedXML());
$fb->request();
if($fb->success()) {

	$res = $fb->getResponse();
	$recurrng_id = $res['recurring_id'];
	// Do something with the recurring_id you were returned

} else {

	echo $fb->getError();
	print_r($fb->getResponse());

}