<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';

// contact 
$route['contacts'] = 'Contact/contact';

// role
$route['all_roles'] = 'Allone/all_roles';
$route['country'] = 'Home/country';
$route['state'] = 'Home/state';
$route['city'] = 'Home/city';
$route['slaon_categories'] = 'Home/slaon_categories';

// Login and register
$route['login'] = 'Login/login';
$route['register'] = 'Login/register';
$route['forgot_password'] = 'Login/forgot_password';
$route['otp_verification'] = 'Login/otp_verification';
$route['change_password'] = 'Login/change_password';

// Profile
$route['profile'] = 'Profile/profile';
$route['update_profile'] = 'Profile/update_profile';
$route['update_profile_pic'] = 'Profile/update_profile_pic';

// salon list
$route['salon-list'] = 'Home/salon';
$route['salon'] = 'Salon/salon';
$route['popular-salon'] = 'Salon/popular_salon';
$route['favorite'] = 'Salon/favorite';
$route['get_favorite'] = 'Salon/get_favorite';
$route['time_slot'] = 'Salon/time_slot';
$route['search'] = 'Salon/search';
$route['salon_search'] = 'Salon/salon_search';
$route['cate_filter'] = 'Salon/cate_filter';

// booking 
$route['booking'] = 'Booking/booking';
$route['my_booking'] = 'Booking/my_booking';
$route['cancel_booking'] = 'Booking/cancel_booking';
$route['add_review'] = 'Review/add_review';

// Salon Seller API List
$route['salon_layer'] = 'API/salon_seller/Sellersalon/salon_layer';
$route['salon_register'] = 'API/salon_seller/Sellersalon/regsiter';
$route['salon_login'] = 'API/salon_seller/Sellersalon/login';
$route['deactive_account'] = 'API/salon_seller/Sellersalon/deactive_account';

// Salon Seller Profile API List
$route['get_profile'] = 'API/salon_seller/Profile/get_profile';
$route['update_seller_profile'] = 'API/salon_seller/Profile/update_seller_profile';
$route['get_bank_detail'] = 'API/salon_seller/Profile/get_bank_detail';
$route['update_bank_details'] = 'API/salon_seller/Profile/update_bank_details';
$route['update_password'] = 'API/salon_seller/Profile/update_password';
$route['update_status'] = 'API/salon_seller/Profile/update_status';
$route['salon_update_profile_pic'] = 'API/salon_seller/Profile/update_profile_pic';

// Salon Seller Dashboard API
$route['seller_dashboard'] = 'API/salon_seller/Sellersalon/seller_dashboard';
$route['get_general_setting'] = 'API/salon_seller/Sellersalon/get_general_setting';
$route['general_setting'] = 'API/salon_seller/Sellersalon/update_general_setting';
$route['get_services'] = 'API/salon_seller/Sellersalon/get_services';
$route['new_service'] = 'API/salon_seller/Sellersalon/add_service';
$route['update_service'] = 'API/salon_seller/Sellersalon/update_service';
$route['delete_service'] = 'API/salon_seller/Sellersalon/delete_service';
$route['get_descripation'] = 'API/salon_seller/Sellersalon/get_descripation';
$route['update_salon_descripation'] = 'API/salon_seller/Sellersalon/update_salon_descripation';
$route['get_inquiry_list'] = 'API/salon_seller/Sellersalon/get_inquiry_list';

//Bank API
$route['ifsc_code_checker'] = 'API/salon_seller/Bank/ifsc_code_checker';

//Earn Report API
$route['earn_report'] = 'API/salon_seller/Earn/earn_report';

$route['earn_report_filter'] = 'API/salon_seller/Earn/earn_report_filter';


//Booking API
$route['new_booking_list'] = 'API/salon_seller/Booking/new_booking_list';
$route['booking_list'] = 'API/salon_seller/Booking/booking_list';
$route['booking_accepted'] = 'API/salon_seller/Booking/booking_accepted';
$route['booking_completed'] = 'API/salon_seller/Booking/booking_completed';
$route['booking_rejected'] = 'API/salon_seller/Booking/booking_rejected';
$route['booking_cancel'] = 'API/salon_seller/Booking/booking_cancel';
$route['invoice'] = 'API/salon_seller/Booking/invoice';

$route['booking_list_filter'] = 'API/salon_seller/Booking/booking_list_filter';
$route['booking_completed_filter'] = 'API/salon_seller/Booking/booking_completed_filter';
$route['booking_rejected_filter'] = 'API/salon_seller/Booking/booking_rejected_filter';
$route['booking_cancel_filter'] = 'API/salon_seller/Booking/booking_cancel_filter';

// Customer Booking API
$route['booking_all_customer'] = 'API/salon_seller/Booking/booking_all_customer';
$route['booking_active_customer'] = 'API/salon_seller/Booking/booking_active_customer';
$route['booking_blocked_customer'] = 'API/salon_seller/Booking/booking_blocked_customer';
$route['booking_customer_change_status'] = 'API/salon_seller/Booking/booking_customer_change_status';
$route['change_booking_status'] = 'API/salon_seller/Booking/change_booking_status';
$route['view_booking'] = 'API/salon_seller/Booking/view_booking';

$route['change_booking_status_new'] = 'API/salon_seller/Booking/change_booking_status_new';

//Gallery API
$route['get_gallery_image'] = 'API/salon_seller/Image/get_gallery_image';
$route['delete_gallery_image'] = 'API/salon_seller/Image/delete_gallery_image';
$route['add_gallery_image'] = 'API/salon_seller/Image/add_gallery_image';

$route['add_banner_image'] = 'API/salon_seller/Image/add_banner_image';
$route['get_banner_image'] = 'API/salon_seller/Image/get_banner_image';
$route['delete_banner_image'] = 'API/salon_seller/Image/delete_banner_image';
$route['banner_change_status'] = 'API/salon_seller/Image/banner_change_status';

//Gallery API
$route['get_time_slot'] = 'API/salon_seller/Time/get_time_slot';
$route['update_time_slot'] = 'API/salon_seller/Time/update_time_slot';
$route['salon_time_slot'] = 'API/salon_seller/Time/salon_time_slot';

// Notification
$route['push_token'] = 'API/salon_seller/Notification/push_token';
$route['send_notification'] = 'API/salon_seller/Notification/send_notification';
$route['get_token'] = 'API/salon_seller/Notification/get_token';
$route['save_notification'] = 'API/salon_seller/Notification/save_notification';
$route['get_notification'] = 'API/salon_seller/Notification/get_notification';

$route['BookingCron'] = 'BookingCron/booking_cancels';

// Booking Payment
$route['payment_success'] = 'Payment/payment_success';
$route['payment_fail'] = 'Payment/payment_fail';

// Admin API'S

$route['admin_register'] = 'API/admin/Credentials/register';
$route['admin_login'] = 'API/admin/Credentials/login';
$route['admin_forgot_password'] = 'API/admin/Credentials/forgot_password';
$route['admin_change_password'] = 'API/admin/Credentials/change_password';


// Admin dashboard API
$route['dashboard'] = 'API/admin/Dashboard/dashboard';

// Admin partner list API
$route['new_partner_list'] = 'API/admin/Partners/new_partner_list';
$route['existing_partner_list'] = 'API/admin/Partners/existing_partner_list';
$route['block_partner_list'] = 'API/admin/Partners/block_partner_list';
$route['new_partner_view'] = 'API/admin/Partners/new_partner_view';
$route['reject_status_new_partner'] = 'API/admin/Partners/reject_status_new_partner';
$route['approve_status_new_partner'] = 'API/admin/Partners/approve_status_new_partner';
$route['upload_banner_image'] = 'API/admin/Partners/upload_banner_image';
$route['upload_gallery_image'] = 'API/admin/Partners/upload_gallery_image';
$route['remove_banner'] = 'API/admin/Partners/remove_banner';
$route['remove_gallery_image'] = 'API/admin/Partners/remove_gallery_image';

$route['existing_partner_view'] = 'API/admin/Partners/existing_partner_view';
$route['change_status'] = 'API/admin/Partners/change_status';

$route['block_partner_view'] = 'API/admin/Partners/block_partner_view';


// Admin Users list API
$route['new_users_list'] = 'API/admin/Users/new_users_list';
$route['existing_users_list'] = 'API/admin/Users/existing_users_list';
$route['new_users_view'] = 'API/admin/Users/new_users_view';
$route['existing_users_view'] = 'API/admin/Users/existing_users_view';
$route['change_user_status'] = 'API/admin/Users/change_user_status';

// Admin Users list API
$route['view_category'] = 'API/admin/Category/view_category';
$route['add_category'] = 'API/admin/Category/add_category';
$route['delete_category'] = 'API/admin/Category/delete_category';

// Admin Transaction list API
$route['transaction_list'] = 'API/admin/Transaction/transaction_list';

// Admin Queries List
$route['queries_list'] = 'API/admin/Queries/queries_list';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
