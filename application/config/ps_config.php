<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Home Page Config */
/* 1) if both frontend and backend folder exist */
// $config['homepage'] = "home";
// $config['reset_url'] = "reset";

/* 2) if only backend folder */
$config['homepage'] = "admin";
$config['reset_url'] = "reset_email";

/** Themes File Names */
$config['themes'] = array( 'default', 'green', 'blue', 'orange', 'blue-grey' );
$config['dashboard_setting'] = 1;
$config['pending_color']  = "#f23939";
$config['confirm_color']  = "#219427";
$config['complete_color'] = "#3d39f2";
$config['cancel_color']   = "#60605b";


/** System Email */
$config['version_no'] = "2.7";


/** Validation */
$config['client_side_validation'] = true;
$config['ajax_request_checking'] = true;

/** Comments */
$config['comments_display_limit'] = 3;
$config['news_display_limit'] = 6;
$config['fav_display_limit'] = 3;
$config['like_display_limit'] = 3;
$config['record_not_found'] = '10001##Sorry';
$config['record_no_pagination'] = '10002##Sorry, no more record in the system.';
$config['waiting_for_approval_label'] = "Waiting For Approval";
$config['reject_label'] = "Reject";

/** FrontEnd Template Path */
$config['fe_view_path'] = 'frontend';
$config['fe_url'] = '';

/** Backend Teamplate Path */
$config['be_view_path'] = 'backend';
$config['be_url'] = 'admin';

/** Uploads Folder Path */
$config['upload_path'] = 'uploads/';
$config['upload_thumbnail_path'] = 'uploads/thumbnail/';
$config['image_type'] = 'jpg|jpeg|png|JPEG|JPG|PNG|ico|csv';

/** Pagination */
$config['pagination']['per_page'] = 100;
$config['pagination']['num_links'] = 5;
$config['pagination']['uri_segment'] = 4;
$config['pagination']['attributes'] = array('class' => 'page-link');
$config['pagination']['full_tag_open'] =  '<ul class="pagination">';
$config['pagination']['full_tag_close'] = '</ul>';
$config['pagination']['num_tag_open'] = '<li class="page-item">';
$config['pagination']['num_tag_close'] = '</li>';
$config['pagination']['first_link'] = '&laquo;';
$config['pagination']['first_tag_open'] = '<li class="page-item">';
$config['pagination']['first_tag_close'] = '</li>';
$config['pagination']['last_link'] = '&raquo;';
$config['pagination']['last_tag_open'] = '<li class="page-item">';
$config['pagination']['last_tag_close'] = '</li>';
$config['pagination']['next_link'] = '&raquo;';
$config['pagination']['next_tag_open'] = '<li class="page-item">';
$config['pagination']['next_tag_close'] = '</li>';
$config['pagination']['prev_link'] = '&laquo;';
$config['pagination']['prev_tag_open'] = '<li class="page-item">';
$config['pagination']['prev_tag_close'] = '</li>';
$config['pagination']['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
$config['pagination']['cur_tag_close'] = '</a></li>';

$config['TIMEZONE'] = 'date_default_timezone_set';