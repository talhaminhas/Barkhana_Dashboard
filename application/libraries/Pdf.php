<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print_r('C:\\xampp\\htdocs\Barkhana\application\libraries\tcpdf\srcTcpdf.php');die;
require_once dirname(__FILE__) . 'C:\xampp\htdocs\Barkhana\application\libraries\tcpdf\srcTcpdf.php';
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}
/*Author:Tutsway.com */  
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */