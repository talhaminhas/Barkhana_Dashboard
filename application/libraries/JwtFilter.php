<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('vendor/autoload.php');

class JwtFilter {

    private $CI;

    public function __construct() 
    {
        $this->CI = &get_instance();
        $this->CI->load->library('jwtlib');
    }

    public function authenticate() 
    {
        $token = $this->CI->input->get_request_header('Authorization');

        if ($token) 
        {
            try {
                $decoded = $this->CI->jwtlib->decodeTokenLib($token);
                // Token is valid, proceed with the request
                if(!$decoded)
                {
                    // Token is invalid or expired, return an error response
                    http_response_code(401);
                    exit(json_encode(array('error' => 'Invalid token')));
                }

            } catch (Exception $e) {
                // Token is invalid or expired, return an error response
                $this->CI->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('error' => 'Invalid token')));
                exit;
            }
        } else 
        {
            // Token is missing, return an error response
            $this->CI->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(array('error' => 'Missing token')));
            exit;
        }
    }

    
}