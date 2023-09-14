<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtLib {
    private $key = 'ITS1234**_BarKhana'; 

    public function generateToken($data) {
                
				 $timezone = new DateTimeZone('Europe/London');
				 $dateTime = new DateTime('now',$timezone); 
				 
				 $iat = $dateTime->getTimestamp(); // current timestamp value
				 $dateTime->modify('+1 minutes');
				 $exp = $dateTime->getTimestamp();
				//$d = DateTime::createFromFormat('d-m-Y H:i:s', '22-09-2008 00:00:00');
				//$exp=$d->getTimestamp();
		
				$payload = array(
					"iss" => "IT Retail System Ltd",
					"aud" => "Barkhana",
					"sub" => $data,
					"iat" => $iat, //Time the JWT issued at
					"exp" => $exp, // Expiration time of token
					//"userName" => $data,
				);
				$token=JWT::encode($payload, $this->key, 'HS256');

				$expDateTime=new DateTime();
				$expDateTime->setTimestamp($exp);
				$expDateTime->setTimezone($timezone);
				$data=array(
					'token'=>$token,
					'expiry'=>$expDateTime->format('m/d/Y H:i:s')
				);
        return $data;//JWT::encode($payload, $this->key, 'HS256');
    }

    public function decodeToken($token) {
		try 
		{
			//$data= JWT::decode($token, $this->key, array('HS256'));
			$data= JWT::decode($token, new Key($this->key, 'HS256'));//,$headers = new stdClass());
			return $data;

		}catch (\Throwable $th) 
		{
			//throw $th;
			return $th->getMessage();
		}	
        
		
    }

    public function decodeTokenLib($token) {
		try 
		{
			//$data= JWT::decode($token, $this->key, array('HS256'));
			$data= JWT::decode($token, new Key($this->key, 'HS256'));//,$headers = new stdClass());
			return $data;

		}catch (\Throwable $th) 
		{
			throw $th;
		}	
        
		
    }

	public function isTokenExpired($token) 
	{
        
		$decoded = $this->decodeToken($token);

        if ($decoded->exp==null)
		{ 
			if($decoded == "Expired token") 
            {
				$payload['isExpired']=true;
				return $payload;
			}	

			return true;
        }

        $expiration = $decoded->exp ?? 0;

		$timezone = new DateTimeZone('Europe/London');
				 $dateTime = new DateTime('now',$timezone); 
				 $dateTime->setTimezone(new DateTimeZone('UTC'));
        $current_time = $dateTime->getTimestamp();

		$payload['data']=$decoded;
		$payload['isExpired']=$expiration < $current_time;

        return $payload;
    }
}
