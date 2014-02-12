<?php 

/**
 * InstagramAPI
 * PHP library to interact with the Instagram API.
 *
 * @author Dennis Pierce <github@stubenbaines>
 * @copyright 2014
 */

namespace Instagram;

use Instagram\Exception\InstagramException;

class Instagram {
    const VERSION = '0.1.0';
    protected $url = 'https://api.instagram.com';
    protected $key;
    protected $call;
    protected $method = 'GET';
    protected $getParams = array();

    function __construct($key) {
        if (!in_array('curl', get_loaded_extensions())) {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
        $this->key = $key;
    }

    /**
    * Converting parameters array to a single string with encoded values
    *
    * @param array $params Input parameters
    * @return string Single string with encoded values
    */
    protected function getParams(array $params) {
        $r = '';

        ksort($params);

        foreach ($params as $key => $value) {
            $r .= '&' . $key . '=' . rawurlencode($value);
        }

        unset($params, $key, $value);

        return trim($r, '&');
    }


    /**
     * Get to Instagram.
     *
     * @param string $endpoint Instagram endpoint.
     * @param array $getParams GET params we are sending.
     */
    public function get($endpoint, array $getParams = null) {
        $this->call = $endpoint;

        if ($getParams !== null && is_array($getParams)) {
            $this->getParams = $getParams;
        }


        return $this->sendRequest();
    }

    protected function processOutput($response) {
        return json_decode($response);
    }

    /**
     * Builds the url.
     */
    protected function getUrl() {
        $getParams = '';
        $getParams = $this->getParams($this->getParams);

        if (!empty($getParams)) {
            $getParams = '&' . $getParams;
        }

        return $this->url . $this->call . '?client_id=' . $this->key . $getParams;
    } 

    /**
     * Send a GET to Instagram.
     * TODO: Support POSTS.
     *
     * @throws Exception\InstagramException
     * @return string 
     */
    protected function sendRequest() {
        $url = $this->getUrl();

        $cOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );

        $c = curl_init();
        curl_setopt_array($c, $cOptions);
        $response = curl_exec($c);
        curl_close($c);
        unset($cOptions, $c);
//echo $response;
        return $this->processOutput($response);
    }
}

