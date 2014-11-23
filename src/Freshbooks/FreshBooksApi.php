<?php

namespace Freshbooks;

/**
 * A simple PHP API wrapper for the FreshBooks API.
 * All post vars can be found on the developer site: http://developers.freshbooks.com/
 * Stay up to date on Github: https://github.com/jboesch/FreshBooksRequest-PHP-API
 *
 * PHP version 5
 *
 * @author     Jordan Boesch <jordan@7shifts.com>
 * @license    Dual licensed under the MIT and GPL licenses.
 * @version    1.0
 */
class FreshBooksApi {

    /*
     * The domain you need when making a request
     */
    protected static $_domain = '';
    protected $domain = '';

    /*
     * The API token you need when making a request
     */
    protected static $_token = '';
    protected $token = '';

    /*
     * The API url we're hitting. {{ DOMAIN }} will get replaced with $domain
     * when you set FreshBooksApi::init($domain, $token)
     */
    protected $_api_url = 'https://{{ DOMAIN }}.freshbooks.com/api/2.1/xml-in';

    /*
     * Stores the current method we're using. Example:
     * new FreshBooksApi('client.create'), 'client.create' would be the method
     */
    protected $_method = '';

    /*
     * Any arguments to pass to the request
     */
    protected $_args = array();

    /*
     * Determines whether or not the request was successful
     */
    protected $_success = false;

    /*
     * Holds the error returned from our request
     */
    protected $_error = '';

    /*
     * Holds the response after our request
     */
    protected $_response = array();

    /*
     * Initialize the and store the domain/token for making requests
     *
     * @param string $domain The subdomain like 'yoursite'.freshbooks.com
     * @param string $token The token found in your account settings area
     * @return null
     */
    public static function init($domain, $token)
    {
        self::$_domain = $domain;
        self::$_token = $token;
    }

    /**
     * Initialize the and store the domain/token for making requests as init
     * does instead of doing what setMethod does
     *
     * @param string $domain The subdomain like 'yoursite'.freshbooks.com
     * @param string $token The token found in your account settings area
     */
    public function __construct($domain, $token)
    {
        $this->domain = $domain;
        $this->token = $token;
    }

    /*
     * Set the data/arguments we're about to request with
     *
     * @return null
     */
    public function post($data)
    {
        $this->_args = $data;
    }

    /*
     * Determine whether or not it was successful
     *
     * @return bool
     */
    public function success()
    {
        return $this->_success;
    }
    
    /*
     * Change the method name to allow for concurrent requests
     *
     * @param string $method The method name from the API, like 'client.update' etc
     * @return null
     */
    public function setMethod($method)
    {
        $this->_method = $method;
    }
    
    /*
     * Get the error (if there was one returned from the request)
     *
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /*
     * Get the response from the request we made
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /*
     * Get the generated XML to view. This is useful for debugging
     * to see what you're actually sending over the wire. Call this
     * after $fb->post() but before your make your $fb->request()
     *
     * @return array
     */
    public function getGeneratedXML()
    {

        $dom = new XmlDomConstruct('1.0', 'utf-8');
        $dom->fromMixed(array(
            'request' => $this->_args
        ));
        $post_data = $dom->saveXML();
        $post_data = str_replace('<request/>', '<request method="' . $this->_method . '" />', $post_data);
        $post_data = str_replace('<request>', '<request method="' . $this->_method . '">', $post_data);

        return $post_data;

    }

    /*
     * Send the request over the wire. Return result will be binary data if the FreshBooks response is
     * a PDF, array if it is a normal request.
     *
     * @return mixed
     */
    public function request()
    {
        if(!$this->domain || !$this->token)
        {
            throw new FreshBooksApiException(
                'You need to call new FreshBooksApi($domain, $token) or FreshBooksApi::init($domain, $token) with your domain and token.'
            );
        }

        $post_data = $this->getGeneratedXML();
        $url = str_replace('{{ DOMAIN }}', $this->domain, $this->_api_url);
        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 40s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // add POST fields
        curl_setopt($ch, CURLOPT_USERPWD, $this->token . ':X');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if(curl_errno($ch))
        {
            $this->_error = 'A cURL error occured: ' . curl_error($ch);
            return;
        }
        else
        {
            curl_close($ch);
        }

        // With the new invoice.getPDF request, we sometimes have non-XML come through here
        if (substr($result, 0, 4) == "%PDF")
        {
            // it's a PDF file
            $response = $result;
            $this->_success = true;
        }
        else
        {
            $response = json_decode(json_encode(simplexml_load_string($result)), true);
            $this->_success = ($response['@attributes']['status'] == 'ok');
        }

        $this->_response = $response;
        if(isset($response['error']))
        {
            $this->_error = $response['error'];
        }

    }

}
