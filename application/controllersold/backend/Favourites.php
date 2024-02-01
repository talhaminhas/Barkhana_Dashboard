<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Favourites Controller
 */
class Favourites extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'FAVOURITES' );
		///start allow module check
		$conds_mod['module_name'] = $this->router->fetch_class();
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;
		
		$logged_in_user = $this->ps_auth->get_user_info();

		$user_id = $logged_in_user->user_id;
		if(empty($this->User->has_permission( $module_id,$user_id )) && $logged_in_user->user_is_sys_admin!=1){
			return redirect( site_url('/admin/') );
		}
		///end check
	}

	function get_favourites($user_id = 0)
	{
		// check access
		$this->check_access( PUBLISH );
		
		// prepare data
		$category_data = array( 'status'=> 1 );
			
		// save data
		if ( $this->Category->save( $category_data, $category_id )) {
			echo true;
		} else {
			echo false;
		}
	}
	/**
	 * List down the registered users
	 */
	function index() {
		
		
		//no publish filter
		$conds['no_publish_filter'] = 1;

		// get rows count
		//$this->data['rows_count'] = $this->Favourite->count_all_by( $conds );
		$this->data['rows_count'] = $this->User->count_all_by( $conds );


		// get favourites
		$this->data['favourites'] = $this->Favourite->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();

		
	}
	function download_pdf() {
		// Load the TCPDF library
		//print_r(APPPATH . 'third_party\\tcpdf\\src\\Tcpdf.php');die;
		require_once(APPPATH . 'third_party/tcpdf/TCPDF.php'); // Adjust the path as needed
		
		// Create a new PDF instance
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
		// Set document information
		// $pdf->SetCreator('Your Name');
		// $pdf->SetAuthor('Your Name');
		// $pdf->SetTitle('Table Data PDF');
		// $pdf->SetSubject('Table Data');
		// $pdf->SetKeywords('PDF, Table Data, CodeIgniter');
	
		// // Add a page
		// $pdf->AddPage();
	
		// // Get the data you want to export (e.g., $purchasedproducts)
		// $data = $this->Favourite->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) ); // Replace with your data retrieval logic
	
		// // Define the table headers
		// $headers = array('No', 'Prd_name', 'Purchased_prd_code', 'Purchased_prd_price');
	
		// // Define an empty array for table data
		// $tableData = array();
	
		// // Add data rows to the table
		// foreach ($data as $row) {
		// 	$tableData[] = array($row->id, $row->name, $row->code, $row->unit_price);
		// }
	
		// // Create a table
		// $pdf->SetFillColor(240, 240, 240);
		// $pdf->SetFont('helvetica', 'B', 12);
		// $pdf->Write(0, 'Table Data', '', 0, 'C', true, 0, false, false, 0);
	
		// $pdf->SetFont('helvetica', '', 12);
		// $pdf->Ln(10);
		// $pdf->table($tableData, $headers);
	
		// // Close and output the PDF
		// $pdf->Output('table_data.pdf', 'D');
	}
	
}