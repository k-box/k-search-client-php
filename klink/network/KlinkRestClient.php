<?php

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * RestClient adapter to mask the underlying library used.
 *
 * Standardizes the communication with a RESTFul endpoint. Exposes shortcut methods for common operations
 * Simple and uniform RESTful web service client.
 *
 *
 * @package    Klink
 * @subpackage Network
 * @since      0.1.0
 * @internal
 */

/**
 * Klink RestClient Class for communicating with a RESTful web service.
 *
 * This class is used to consistently make outgoing HTTP requests easy for developers
 * while still being compatible with the many PHP configurations under which
 * Klink Adpter runs.
 *
 * Remember to setup the default time zone (e.g., date_default_timezone_set('America/Los_Angeles');)
 *
 *
 * @package    Klink
 * @subpackage Network
 * @since      0.1.0
 * @internal
 */
final class KlinkRestClient implements IKlinkRestClient
{
    use LoggerAwareTrait;

    /**
     * The client version
     */
    const CLIENT_VERSION = '3.0.0';

    /**
     * The constant defining the json mime-type
     */
    const JSON_ENCODING = 'application/json';

    /**
     * The common part of the API URL to call
     */
    private $baseApiUrl = null;

    /**
     * the real transport layer used
     *
     * @var GuzzleHttp\Client
     */
    private $transport = null;

    /**
     * Holds the configuration for this instance
     *
     * @var array
     */
    private $config = null;

    /**
     * The mapper for transforming a json decoded response into a class
     *
     * @var JsonMapper
     */
    private $jm = null;

    /**
     * Description
     *
     * @param string              $baseApiUrl     (required) the starting part of the API url, must me not null or
     *                                            empty and a valid url
     * @param KLinkAuthentication $authentication (optional) the authentication information for this instance, use null
     *                                            if no authentication is required
     * @param array               $options        (optional) to be documented
     * @param LoggerInterface     $logger         The logger
     */
    function __construct($baseApiUrl,
        KlinkAuthentication $authentication = null,
        array $options = array(),
        LoggerInterface $logger = null
    )
    {
        KlinkHelpers::is_valid_url($baseApiUrl, 'baseApiUrl');

        $this->config = $this->getDefaultSettings();
        $this->config = array_merge($this->config, $options);

        $this->jm = new JsonMapper();
        $this->jm->bExceptionOnUndefinedProperty = true;

        $this->baseApiUrl = $baseApiUrl;

        $this->logger = $logger;

        $guzzle_config = [
            'base_uri' => $this->baseApiUrl,
            'allow_redirects' => true,
            'max' => $this->config['redirection'],
            'debug' => $this->config['debug'],
            'http_errors' => false, // disable throwing exceptions on an HTTP protocol errors (i.e., 4xx and 5xx responses)
            'headers' => [
                'User-Agent' => $this->config['user-agent'],
                'Accept' => self::JSON_ENCODING,
                'Content-Type' => self::JSON_ENCODING,
                'Accept-Version' => $this->config['api-version'],
            ],
            'verify' => false
        ];

        if (!is_null($authentication)) {
            $guzzle_config['auth'] = [
                $authentication->getUsername(),
                $authentication->getPassword()
            ];
            if ($authentication->getApiVersion()) {
                $guzzle_config['headers']['Accept-Version'] = $authentication->getApiVersion();
            }
        }

        $this->transport = new GuzzleHttp\Client($guzzle_config);
    }

    private function getDefaultSettings()
    {
        return [
            /**
             * Filter the timeout value for an HTTP request.
             *
             * @since 0.1.0
             *
             * @param int $timeout_value Time in seconds until a request times out.
             *                           Default 120.
             */
            'timeout' => 120,
            /**
             * Filter the number of redirects allowed during an HTTP request.
             *
             * @since 0.1.0
             *
             * @param int $redirect_count Number of redirects allowed. Fixed to 2, no more than two redirect are allowed
             */
            'redirection' => 2,
            /**
             * Filter the user agent value sent with an HTTP request.
             *
             * @since 0.1.0
             *
             * @param string $user_agent Boilerplate user agent string.
             */
            'user-agent' => 'Klink/' . self::CLIENT_VERSION,
            /**
             * Enable debug messaging.
             *
             * @since 0.1.0
             *
             * @param bool $debug    Whether to enable debug messages on the log.
             *                       Default false.
             */
            'debug' => false,


            'api-version' => KlinkCoreClient::DEFAULT_KCORE_API_VERSION,
        ];
    }


    /**
     * Make a GET request, expected return is an instance of $expected_return_type
     *
     * @param string              $url
     * @param array               $params
     * @param null|boolean|string $expected_return_type what class should the response return, null use a plain array,
     *                                                  false expects nothing, otherwise the class name with full
     *                                                  namespace
     *
     * @return mixed An instance of $expected_return_type, KlinkError in case of error
     */
    function get($url, $expected_return_type, array $params = [])
    {

        if (!self::_check_expected_return_type($expected_return_type)) {
            return new KlinkError(
                KlinkError::ERROR_CLASS_EXPECTED,
                KlinkHelpers::localize('The specified return type is not a class.'),
                KlinkError::ERRORCODE_CLASS_EXPECTED
            );
        }

        $response = $this->_doGet($url, $params);

        if (KlinkHelpers::is_error($response)) {
            return $response;
        }

        $class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

        return self::_deserialize_single($response->getBody(), $class);

    }

    /**
     * Make a GET request, expected return is an array of instances of $expected_return_type
     *
     * @param string              $url
     * @param array               $params
     * @param null|boolean|string $expected_return_type what class should the response return, null use a plain array,
     *                                                  false expects nothing, otherwise the class name with full
     *                                                  namespace
     *
     * @return mixed An instance of $expected_return_type, KlinkError in case of error
     */
    public function getCollection($url, $expected_return_type, array $params = [])
    {

        if (!self::_check_expected_return_type($expected_return_type)) {
            return new KlinkError(KlinkError::ERROR_CLASS_EXPECTED, KlinkHelpers::localize('The specified return type is not a class.'), KlinkError::ERRORCODE_CLASS_EXPECTED);
        }

        $response = $this->_doGet($url, $params);

        if (KlinkHelpers::is_error($response)) {
            return $response;
        }

        $class = is_object($expected_return_type) ? get_class($expected_return_type) : $expected_return_type;

        return self::_deserialize_array($response->getBody(), $class);

    }

    /**
     * Makes a POST request with the specified data using json content.
     *
     * Given the nature of the POST request a non-empty
     *
     * The expected return is a json response. The response will be automatically mapped to a specified class
     *
     * @param string       $url
     * @param array|object $data                the data that will be sent in the body of the request (json encoded)
     * @param string|class expected_return_type the class that represents the returned information from the server. If
     *                                          a class is provided must be an instance. If a string is provided the
     *                                          correspondent class must have a constructor with no arguments
     * @param array        $params
     *
     * @return object|KlinkError and instance of the class specified in $expected_return_type with the fields setup to
     *                           what is in the response
     */
    function post($url, $data, $expected_return_type, array $params = null)
    {

        if (!self::_check_expected_return_type($expected_return_type)) {
            return new KlinkError(KlinkError::ERROR_CLASS_EXPECTED, KlinkHelpers::localize('The specified return type is not a class.'), KlinkError::ERRORCODE_CLASS_EXPECTED);
        }

        $url = self::_construct_url($this->baseApiUrl, $url, $params);

        $encoder = new JsonStreamEncoder();

        $encoder->encode($data);

        $response = $this->transport->request('POST', $url, [
            'body' => $encoder->getJsonStream()
        ]);

        $encoder->closeJsonStream();

        if ($this->config['debug']) {

            if ($this->logger) {
                $this->logger->debug('POST {url}', array('url' => $url, 'status_code' => $response->getStatusCode(), 'response' => _get_headers_from($response)));
            }

        }

        //204 no content
        //201 created
        //202 accepted

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            return new KlinkError(KlinkError::ERROR_HTTP_REQUEST_FAILED, KlinkHelpers::localize($response->getReasonPhrase()), $response->getStatusCode());
        }

        if (!$this->_is_json_response($response)) {
            return new KlinkError(KlinkError::ERROR_HTTP_RESPONSE_FORMAT, KlinkHelpers::localize('Unsupported content encoding from the server.'), KlinkError::ERRORCODE_HTTP_RESPONSE_FORMAT);
        }

        $class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

        return self::_deserialize_single($response->getBody(), $class);

    }

    /**
     * Makes a PUT request
     *
     * @param string            $url
     * @param array|object      $data                 the data that will be sent in the body of the request (json
     *                                                encoded)
     * @param null|string|class $expected_return_type the class that represents the returned information from the
     *                                                server. If a class is provided must be an instance. If a string
     *                                                is provided the correspondent class must have a constructor with
     *                                                no arguments. If null is provided the response must return an
     *                                                empty body or an HTTP status 204 no-content otherwise an error is
     *                                                returned.
     * @param array             $params               (reserved for future use)
     *
     * @return boolean|object|KlinkError
     */
    function put($url, $data, $expected_return_type = null, array $params = null)
    {

        if (!is_null($expected_return_type) && !self::_check_expected_return_type($expected_return_type)) {
            return new KlinkError(KlinkError::ERROR_CLASS_EXPECTED, KlinkHelpers::localize('The specified return type is not a class.'), KlinkError::ERRORCODE_CLASS_EXPECTED);
        }

        $url = self::_construct_url($this->baseApiUrl, $url, $params);

        $encoder = new JsonStreamEncoder();

        $encoder->encode($data);

        $response = $this->transport->request('PUT', $url, [
            'body' => $encoder->getJsonStream()
        ]);

        $encoder->closeJsonStream();

        if ($this->config['debug']) {

            if ($this->logger) {
                $this->logger->debug('PUT {url}', array('url' => $url, 'status_code' => $response->getStatusCode(), 'response' => _get_headers_from($response)));
            }

        }

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            return new KlinkError(KlinkError::ERROR_HTTP_REQUEST_FAILED, KlinkHelpers::localize($response->getReasonPhrase()), $response->getStatusCode());
        }


        if (!is_null($expected_return_type)) {

            if (!$this->_is_json_response($response)) {
                return new KlinkError(KlinkError::ERROR_HTTP_RESPONSE_FORMAT, KlinkHelpers::localize('Unsupported content encoding from the server.'), KlinkError::ERRORCODE_HTTP_RESPONSE_FORMAT);
            }

            $class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

            return self::_deserialize_single($response->getBody(), $class);

        } else {
            return true;
        }

    }

    /**
     * Execute a Delete request
     *
     * @param string $url
     * @param array  $params (reserved for future version)
     *
     * @return boolean true if the response has been positive (response codes 200 or 204)
     */
    function delete($url, array $params = [])
    {

        $response = $this->transport->request('DELETE', $url, $params);

        if ($this->config['debug']) {

            if ($this->logger) {
                $this->logger->debug('DELETE {url}', array('url' => $url, 'status_code' => $response->getStatusCode(), 'response' => _get_headers_from($response)));
            }

        }

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
            return new KlinkError(KlinkError::ERROR_HTTP_REQUEST_FAILED, KlinkHelpers::localize($response->getReasonPhrase()), $response->getStatusCode());
        }

        return true;

    }

    /**
     * Compose an array of key-values into an url encoded string according to the RFC 1738 (PHP_QUERY_RFC1738)
     * specification
     *
     * @param array $array
     *
     * @return string the url-encoded version of the array keys and values according to the PHP_QUERY_RFC1738
     */
    private function _compose_get_parameters(array $array)
    {
        if (empty($array)) {
            return '';
        }

        return http_build_query($array, '', '&');

    }


    /**
     * Compose the url given tha base and the portions that needs to be attached
     *
     * @param string       $base the base part of the url, must be a valid url
     * @param string|array $rest
     *
     * @return string the full url
     */
    private function _construct_url($base, $rest, array $getParams = null)
    {
        KlinkHelpers::is_valid_url($base, 'starting part of the url');

        if (is_array($rest)) {

            $rest = array_filter($rest);

            $rest = implode('/', $rest);

        }

        if (!is_null($getParams) && !empty($getParams)) {
            $otherparams = array();

            foreach ($getParams as $key => $value) {

                if (strpos($rest, "{$key}") !== false) {

                    $rest = str_replace('{' . $key . '}', $value, $rest);

                } else {
                    $otherparams[$key] = $value;
                }

            }

            if (!empty($otherparams)) {
                $querystring = self::_compose_get_parameters($otherparams);

                $rest .= '?' . $querystring;
            }
        }

        $concat = (KlinkHelpers::string_ends_with($base, '/')) ? '' : '/';

        return $base . $concat . $rest;
    }

    private function _check_expected_return_type($return_type)
    {
        if ((!is_object($return_type) && !is_string($return_type)) || is_null($return_type)) {
            return false;
        }

        if (!is_object($return_type) && !@class_exists($return_type)) {
            return false;
        }

        return true;
    }


    private function _deserialize_single($body, $class)
    {

        try {

            $json = $body instanceof GuzzleHttp\Psr7\Stream ? $body->getContents() : (string)$body;

            $decoded = json_decode($json, false);

            if (is_null($decoded)) {

                $error_string = $this->get_last_json_error();

                if ($this->logger) {
                    $this->logger->error('JSON decode error: {error}', array('error' => $error_string, 'json' => $json));
                }

                return new KlinkError(KlinkError::ERROR_DESERIALIZATION_ERROR, $error_string, KlinkError::ERRORCODE_DESERIALIZATION_ERROR);
            }

            $deserialized = $this->jm->map($decoded, $class);

            return $deserialized;

        } catch (Exception $je) {

            if ($this->logger) {
                $this->logger->warning('JSON -> Class mapping error: {message}', array('message' => $je->getMessage(), 'exception' => $je));
            }

            return new KlinkError(KlinkError::ERROR_DESERIALIZATION_ERROR, $je->getMessage(), KlinkError::ERRORCODE_DESERIALIZATION_ERROR);

        }

    }

    private function _deserialize_array($body, $class)
    {

        try {

            $json = $body instanceof GuzzleHttp\Psr7\Stream ? $body->getContents() : (string)$body;

            $decoded = json_decode($json, false);

            if (is_null($decoded)) {

                $error_string = $this->get_last_json_error();

                if ($this->logger) {
                    $this->logger->error('JSON decode error: {error}', array('error' => $error_string, 'json' => $json));
                }

                return new KlinkError(KlinkError::ERROR_DESERIALIZATION_ERROR, $error_string, KlinkError::ERRORCODE_DESERIALIZATION_ERROR);
            }

            $deserialized = $this->jm->mapArray($decoded, new ArrayObject(), $class);

            return $deserialized->getArrayCopy();

        } catch (Exception $je) {

            if ($this->logger) {
                $this->logger->warning('JSON -> Class mapping error: {message}', array('message' => $je->getMessage(), 'exception' => $je));
            }

            return new KlinkError(KlinkError::ERROR_DESERIALIZATION_ERROR, $je->getMessage(), KlinkError::ERRORCODE_DESERIALIZATION_ERROR);

        }

    }


    private function _get_headers_from($response)
    {

        $h = [];

        foreach ($response->getHeaders() as $name => $values) {
            $h[] = $name . ': ' . implode(', ', $values);
        }

        return implode(PHP_EOL, $h);
    }


    private function _is_json_response($response)
    {

        $header = $response->getHeader('Content-Type');

        if (is_array($header)) {
            return in_array(self::JSON_ENCODING, $header);
        }

        return $header === self::JSON_ENCODING;

    }


    private function _doGet($url, $params)
    {

        $response = $this->transport->request('GET', $url, $params);

        if ($this->config['debug']) {

            if ($this->logger) {
                $this->logger->debug('GET {url}', array('url' => $url, 'status_code' => $response->getStatusCode(), 'response' => _get_headers_from($response)));
            }

        }

        if ($response->getStatusCode() !== 200) {
            return new KlinkError(KlinkError::ERROR_HTTP_REQUEST_FAILED, KlinkHelpers::localize($response->getReasonPhrase()), $response->getStatusCode());
        }

        if (!$this->_is_json_response($response)) {
            return new KlinkError(KlinkError::ERROR_HTTP_RESPONSE_FORMAT, KlinkHelpers::localize('Unsupported content encoding from the server.'), KlinkError::ERRORCODE_HTTP_RESPONSE_FORMAT);
        }

        return $response;
    }


    private function get_last_json_error()
    {
        $error_string = 'json deserialization error';

        if (function_exists('json_last_error')) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $error_string = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error_string = 'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error_string = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error_string = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $error_string = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $error_string = 'Unknown error';
                    break;
            }
        }

        return $error_string;
    }
}
