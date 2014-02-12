<?php
/*
          COPYRIGHT

Copyright 2007 Sergio Vaccaro <sergio@inservibile.org>

This file is part of JSON-RPC PHP.

JSON-RPC PHP is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

JSON-RPC PHP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JSON-RPC PHP; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * The object of this class are generic jsonRPC 1.0 clients
 * http://json-rpc.org/wiki/specification
 *
 * @author sergio <jsonrpcphp@inservibile.org>
 */
class jsonRPCClient {

  /**
   * Debug state
   *
   * @var boolean
   */
  private $debug;
  private $debug_output = NULL;

  /**
   * The server URL
   *
   * @var string
   */
  private $url;
  /**
   * The request id
   *
   * @var integer
   */
  private $id;

  /**
   * Takes the connection parameters
   *
   * @param string $url
   * @param boolean $debug
   */
  public function __construct($url, $debug = false) {
    $this->url = $url;
    $this->debug = $debug;	
    $this->debug_output = '';
    $this->id = rand(1, 100);
  }

  /**
   * Fetch debug information
   * @param none
   * @return array Debug data
   **/
  public function getDebugData() {
    if ($this->debug) return $this->debug_output;
    return false;
  }

  /**
   * Performs a jsonRCP request and gets the results as an array
   *
   * @param string $method
   * @param array $params
   * @return array
   */
  public function __call($method, $params) {
    if (!is_scalar($method)) throw new Exception('Method name has no scalar value');

    if (is_array($params)) {
      // no keys
      $params = array_values($params);
    } else {
      throw new Exception('Params must be given as array');
    }

    // prepares the request
    $request = array(
      'method' => $method,
      'params' => $params,
      'id' => $this->id
    );
    $request = json_encode($request);
    if ($this->debug) $this->debug_output[] = 'Request: '.$request;

    // performs the HTTP POST
    // extract information from URL for proper authentication
    $url = parse_url($this->url);
    $ch = curl_init($url['scheme'].'://'.$url['host'].':'.$url['port'].$url['path']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_USERPWD, $url['user'] . ":" . $url['pass']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    $response = curl_exec($ch);
    if ($this->debug) $this->debug_output[] = 'Response: ' . $response;
    $response = json_decode($response, true);
    $resultStatus = curl_getinfo($ch);
    if ($resultStatus['http_code'] != '200') {
      if ($resultStatus['http_code'] == '401') throw new Exception('RPC call did not return 200: Authentication failed');
      throw new Exception('RPC call did not return 200: HTTP error: ' . $resultStatus['http_code'] . ' - JSON Response: [' . @$response['error']['code'] . '] ' . @$response['error']['message']);
    }
    if (curl_errno($ch)) throw new Exception('RPC call failed: ' . curl_error($ch));
    curl_close($ch);

    // final checks and return
    if (!is_null($response['error']) || is_null($response)) throw new Exception('Response error or empty: ' . @$response['error']);
    if ($response['id'] != $this->id) throw new Exception('Incorrect response id (request id: ' . $this->id . ', response id: ' . $response['id'] . ')');
    return $response['result'];
  }
}
