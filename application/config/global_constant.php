<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	$config['site_info'] = array(
		"admin_name" => "jc@gcfrog.com",
		"site_name" => "https://rewwprintmail.com", 
		"email_smtp_host" => "smtp.gmail.com",
		"email_smtp_port" => "587",
		"smtp_email" => "logs@nettrackers.net",
		"smtp_password" => "bldpvudabejjrksd",
		"email_cc" => "partha.chowdhury@nettrackers.net, rohit@nettrackers.net, lauren@gcfrog.com, hsk@gcfrog.com, karen@gcfrog.com, denise@gcfrog.com, emma@gcfrog.com, brc@gcfrog.com, alan@gcfrog.com",
		"email_sub_admins" => "orders@gcfrog.com, jc@gcfrog.com, partha.chowdhury@nettrackers.net, rohit@nettrackers.net"
	);
	
	// Bootstrap Pagination Configuration
	$config['pagination']['per_page'] = PAGINATION_PER_PAGE;
	$config['pagination']["uri_segment"] = 3;
	$config['pagination']["num_links"] = 2;
	
	$config['pagination']['use_page_numbers'] = TRUE;
	$config['pagination']['page_query_string'] = TRUE;
	$config['pagination']['query_string_segment'] = 'page';
	$config['pagination']['reuse_query_string'] = TRUE;
	
	$config['pagination']['full_tag_open'] = '<ul class="pagination">';
	$config['pagination']['full_tag_close'] = '</ul>';

	$config['pagination']['first_link'] = '&laquo; First';
	$config['pagination']['first_tag_open'] = '<li class="prev page">';
	$config['pagination']['first_tag_close'] = '</li>';

	$config['pagination']['last_link'] = 'Last &raquo;';
	$config['pagination']['last_tag_open'] = '<li class="next page">';
	$config['pagination']['last_tag_close'] = '</li>';

	$config['pagination']['next_link'] = 'Next &rarr;';
	$config['pagination']['next_tag_open'] = '<li class="next page">';
	$config['pagination']['next_tag_close'] = '</li>';

	$config['pagination']['prev_link'] = '&larr; Previous';
	$config['pagination']['prev_tag_open'] = '<li class="prev page">';
	$config['pagination']['prev_tag_close'] = '</li>';

	$config['pagination']['cur_tag_open'] = '<li class="active"><a href="">';
	$config['cur_tag_close'] = '</a></li>';

	$config['pagination']['num_tag_open'] = '<li class="page">';
	$config['pagination']['num_tag_close'] = '</li>';

	$config['pagination']['anchor_class'] = 'follow_link';