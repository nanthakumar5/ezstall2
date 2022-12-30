<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Authentication\Login\Index');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);



/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//Api
$routes->group('api', function ($routes) {
	$routes->post('login', 'Api\Login\Index::index');
	$routes->post('register', 'Api\Register\Index::action');
    $routes->get('verification/(:any)', 'Api\Register\Index::verification/$1');
    $routes->post('forgotpassword', 'Api\Register\Index::forgotpassword');
	$routes->post('changepassword/(:any)/(:any)', 'Api\Register\Index::changepassword/$1/$2');

	//Home
	$routes->get('home', 'Api\Home\Index::index');

	$routes->post('upcomingevents', 'Api\Event\Index::upcomingevents');
	$routes->post('pastevents', 'Api\Event\Index::pastevents');
	$routes->post('viewallevents', 'Api\Event\Index::viewallevents');

    // Operator Lockunlock
    $routes->post('lockunlock', 'Api\Myaccount\Dashboard\Index::lockunlock');

	//Event
	$routes->get('eventlist', 'Api\Event\Index::listandsearch');
	$routes->get('eventdetail/(:num)', 'Api\Event\Index::detail/$1');
    $routes->post('checkincheckout', 'Api\Event\Index::checkincheckout');

	//Facility
	$routes->get('facilitylist', 'Api\Facility\Index::Index');
	$routes->get('facilitydetail/(:num)', 'Api\Facility\Index::detail/$1');

    //cart
    $routes->post('cart', 'Api\Cart\Index::index');
    $routes->post('stallcartinsert', 'Api\Cart\Index::stallcartinsert');
    $routes->post('stallcartdelete', 'Api\Cart\Index::stallcartdelete');

	//Dashboard Api's
	$routes->post('dashboard', 'Api\Myaccount\Dashboard\Index::index');
	$routes->post('navigationmenu', 'Api\Myaccount\Navigationmenu\Index::index');

    //Account Info
    $routes->post('account/view', 'Api\Myaccount\AccountInfo\Index::index');
    $routes->post('account/action', 'Api\Myaccount\AccountInfo\Index::action');

	//Event
	$routes->post('events', 'Api\Myaccount\Event\Index::index');
    $routes->get('events/view/(:num)', 'Api\Myaccount\Event\Index::view/$1');
	$routes->get('events/inventories/(:num)', 'Api\Myaccount\Event\Index::inventories/$1');
    $routes->post('events/delete', 'Api\Myaccount\Event\Index::delete');

	//Facility
	$routes->post('facility', 'Api\Myaccount\Facility\Index::index');
    $routes->get('facility/view/(:num)', 'Api\Myaccount\Facility\Index::view/$1');
	$routes->get('facility/inventories/(:num)', 'Api\Myaccount\Facility\Index::inventories/$1');
    $routes->post('facility/delete', 'Api\Myaccount\Facility\Index::delete');

    //Current Reservation
    $routes->post('list', 'Api\Myaccount\Currentreservation\Index::index');
    $routes->post('view', 'Api\Myaccount\Currentreservation\Index::view');
    $routes->post('paidunpaid', 'Api\Myaccount\Currentreservation\Index::paidunpaid'); 
    $routes->post('striperefunds', 'Api\Myaccount\Currentreservation\Index::striperefunds');
    $routes->post('cancelsubscription', 'Api\Myaccount\Currentreservation\Index::cancelsubscription');

    //Past Reservation
    $routes->post('pastlist', 'Api\Myaccount\Pastreservation\Index::index');
    $routes->post('pastview', 'Api\Myaccount\Pastreservation\Index::view');

    //Payment Info
    $routes->post('payment/list', 'Api\Myaccount\PaymentInfo\Index::index');
    $routes->post('payment/view', 'Api\Myaccount\PaymentInfo\Index::view');

    //Transaction
    $routes->post('transaction', 'Api\Myaccount\TransactionInfo\Index::index');

    //Subscription
    $routes->post('subscription', 'Api\Myaccount\Subscription\Index::index');

	//Stallmanager
	$routes->post('stallmanager', 'Api\Myaccount\Stallmanager\Index::index');
	$routes->post('addstallmanager', 'Api\Myaccount\Stallmanager\Index::add');
	$routes->post('editstallmanager', 'Api\Myaccount\Stallmanager\Index::edit');
	$routes->post('deletestallmanager', 'Api\Myaccount\Stallmanager\Index::delete');

	//Operator
	$routes->post('operator', 'Api\Myaccount\Operators\Index::index');
	$routes->post('addoperator', 'Api\Myaccount\Operators\Index::add');
	$routes->post('editoperator', 'Api\Myaccount\Operators\Index::edit');
	$routes->post('deleteoperator', 'Api\Myaccount\Operators\Index::delete');
    
    //Checkout
    $routes->post('checkout', 'Api\Checkout\Index::index'); 
    $routes->post('checkout/action', 'Api\Checkout\Index::action'); 
    $routes->post('stripepayment', 'Api\Stripe\Index::stripepayment');
    $routes->post('stripesecretkey', 'Api\Checkout\Index::stripesecretkey'); 

	//Homepage
	$routes->get('faq', 'Api\Faq\Index::index');
	$routes->get('aboutus', 'Api\Aboutus\Index::index');
	$routes->post('aboutus/view', 'Api\Aboutus\Index::view');
	$routes->get('contactus', 'Api\Contactus\Index::index');
	$routes->post('contactus/add', 'Api\Contactus\Index::add');
});

// Ajax
$routes->post('ajax/fileupload', 'Common\Ajax::fileupload');
$routes->post('ajax/ajaxoccupiedreservedblockunblock', 'Common\Ajax::ajaxoccupiedreservedblockunblock');
$routes->post('ajax/ajaxoccupied', 'Common\Ajax::ajaxoccupied');
$routes->post('ajax/ajaxreserved', 'Common\Ajax::ajaxreserved');
$routes->post('ajax/ajaxstripepayment', 'Common\Ajax::ajaxstripepayment');
$routes->post('ajax/ajaxproductquantity', 'Common\Ajax::ajaxproductquantity');
$routes->post('ajax/ajaxblockunblock', 'Common\Ajax::ajaxblockunblock');
$routes->post('ajax/importbarnstall', 'Common\Ajax::importbarnstall');
$routes->post('ajax/calendar', 'Common\Ajax::calendar');
$routes->post('ajax/barnstall1', 'Common\Ajax::barnstall1');
$routes->post('ajaxsearchevents', 'Common\Ajax::ajaxsearchevents');
$routes->post('ajaxsearchfacility', 'Common\Ajax::ajaxsearchfacility');

// Cron
$routes->get('cartremoval', 'Common\Cron::cartremoval');
$routes->get('bookingenddate', 'Common\Cron::bookingenddate');
$routes->get('bookingsubscriptionstall', 'Common\Cron::bookingsubscriptionstall');

// Validation
$routes->post('validation/emailvalidation', 'Common\Validation::emailvalidation');

//Site
$routes->match(['get', 'post'], '/', 'Site\Home\Index::index');
$routes->get('search', 'Site\Search\Index::index');
$routes->match(['get', 'post'], 'login', 'Site\Login\Index::index', ['filter' => 'siteauthentication1']);
$routes->match(['get', 'post'], 'register', 'Site\Register\Index::index', ['filter' => 'siteauthentication1']);
$routes->match(['get', 'post'], 'forgotpassword', 'Site\Forgotpassword\Index::index', ['filter' => 'siteauthentication1']);
$routes->match(['get', 'post'], 'changepassword/(:any)/(:any)', 'Site\Changepassword\Index::index/$1/$2', ['filter' => 'siteauthentication1']);
$routes->get('verification/(:any)', 'Site\Register\Index::verification/$1');
$routes->match(['get', 'post'], 'events', 'Site\Event\Index::lists');
$routes->match(['get', 'post'], 'events/latlong', 'Site\Event\Index::latlong');
$routes->match(['get', 'post'], 'events/detail/(:num)', 'Site\Event\Index::detail/$1');
$routes->get('event/pdf/(:any)', 'Site\Event\Index::downloadeventflyer/$1');
$routes->get('event/downloadstallmap/(:any)', 'Site\Event\Index::downloadstallmap/$1');
$routes->match(['get', 'post'], 'events/updatereservation/(:num)/(:num)', 'Site\Event\Index::updatereservation/$1/$2', ['filter' => 'siteauthentication2']);
$routes->match(['get', 'post'], 'facility', 'Site\Facility\Index::lists');
$routes->match(['get', 'post'], 'facility/detail/(:num)', 'Site\Facility\Index::detail/$1');
$routes->get('facility/download/(:any)', 'Site\Facility\Index::download/$1');
$routes->match(['get', 'post'], 'facility/updatereservation/(:num)/(:num)', 'Site\Facility\Index::updatereservation/$1/$2', ['filter' => 'siteauthentication2']);
$routes->get('aboutus', 'Site\Aboutus\Index::index');
$routes->get('aboutus/detail/(:num)', 'Site\Aboutus\Index::detail/$1');
$routes->get('faq', 'Site\Faq\Index::index');
$routes->get('banner', 'Site\Banner\Index::index');
$routes->get('contactus', 'Site\Contactus\Index::index');
$routes->get('termsandconditions', 'Site\Termsandconditions\Index::index');
$routes->get('privacypolicy', 'Site\Privacypolicy\Index::index');
$routes->match(['get', 'post'], 'checkout', 'Site\Checkout\Index::index', ['filter' => 'siteauthentication2']);
$routes->get('paymentsuccess', 'Site\Checkout\Index::success');
$routes->match(['get', 'post'], 'cart', 'Site\Cart\Index::action');
$routes->match(['get', 'post'], 'contactus', 'Site\Contactus\Index::index');
$routes->get('logout', 'Site\Logout\Index::index');

$routes->post('stripe/webhook', 'Common\Stripe::webhook');

$routes->group('myaccount', ['filter' => 'siteauthentication2'], function ($routes) {
    $routes->match(['get', 'post'], 'dashboard', 'Site\Myaccount\Dashboard\Index::index');
    $routes->match(['get', 'post'], 'updatedata', 'Site\Myaccount\Dashboard\Index::updatedata');
	
    $routes->match(['get', 'post'], 'account', 'Site\Myaccount\AccountInfo\Index::index');
    $routes->get('stripeconnect', 'Site\Myaccount\AccountInfo\Index::stripeconnect');
	
    $routes->match(['get', 'post'], 'events', 'Site\Myaccount\Event\Index::index');
    $routes->match(['get', 'post'], 'events/add', 'Site\Myaccount\Event\Index::eventsaction');
    $routes->match(['get', 'post'], 'events/edit/(:num)', 'Site\Myaccount\Event\Index::eventsaction/$1');
    $routes->match(['get', 'post'], 'facilityevents/add', 'Site\Myaccount\Event\Index::facilityaction');
    $routes->match(['get', 'post'], 'facilityevents/edit/(:num)', 'Site\Myaccount\Event\Index::facilityaction/$1');
    $routes->get('events/view/(:num)', 'Site\Myaccount\Event\Index::view/$1');
    $routes->get('events/inventories/(:num)', 'Site\Myaccount\Event\Index::inventories/$1');
    $routes->get('events/export/(:num)', 'Site\Myaccount\Event\Index::export/$1');
    $routes->get('events/eventreport/(:num)', 'Site\Myaccount\Event\Index::eventreport/$1');
    $routes->post('events/financialreport', 'Site\Myaccount\Event\Index::financialreport');

    $routes->match(['get', 'post'], 'facility', 'Site\Myaccount\Facility\Index::index');
    $routes->match(['get', 'post'], 'facility/add', 'Site\Myaccount\Facility\Index::action');
    $routes->match(['get', 'post'], 'facility/edit/(:num)', 'Site\Myaccount\Facility\Index::action/$1');
    $routes->get('facility/view/(:num)', 'Site\Myaccount\Facility\Index::view/$1');
    $routes->get('facility/inventories/(:num)', 'Site\Myaccount\Facility\Index::inventories/$1');
    $routes->get('facility/export/(:num)', 'Site\Myaccount\Facility\Index::export/$1');
    $routes->post('facility/financialreport', 'Site\Myaccount\Facility\Index::financialreport');

    $routes->get('calendar', 'Site\Myaccount\Calendar\Index::index');
	
    $routes->match(['get', 'post'], 'stallmanager', 'Site\Myaccount\Stallmanager\Index::index');
    $routes->match(['get', 'post'], 'stallmanager/add', 'Site\Myaccount\Stallmanager\Index::action');
    $routes->match(['get', 'post'], 'stallmanager/edit/(:num)', 'Site\Myaccount\Stallmanager\Index::action/$1');
	
    $routes->match(['get', 'post'], 'operators', 'Site\Myaccount\Operators\Index::index');
    $routes->match(['get', 'post'], 'operators/add', 'Site\Myaccount\Operators\Index::action');
    $routes->match(['get', 'post'], 'operators/edit/(:num)', 'Site\Myaccount\Operators\Index::action/$1');

    $routes->match(['get', 'post'], 'bookings', 'Site\Myaccount\Reservation\Index::index');
    $routes->match(['get', 'post'], 'stripe/(:any)', 'Site\Myaccount\Reservation\Index::index/$1');
    $routes->get('bookings/view/(:num)', 'Site\Myaccount\Reservation\Index::view/$1');
    $routes->post('bookings/searchbookeduser', 'Site\Myaccount\Reservation\Index::bookeduser');
    $routes->match(['get', 'post'], 'paidunpaid', 'Site\Myaccount\Reservation\Index::paidunpaid');
    $routes->post('bookings/cancelsubscription', 'Site\Myaccount\Reservation\Index::cancelsubscription');

    $routes->match(['get', 'post'], 'pastactivity', 'Site\Myaccount\PastActivity\Index::index');
    $routes->get('pastactivity/view/(:num)', 'Site\Myaccount\PastActivity\Index::view/$1');
	
    $routes->match(['get', 'post'], 'payments', 'Site\Myaccount\PaymentInfo\Index::index');
    $routes->get('payments/view/(:num)', 'Site\Myaccount\PaymentInfo\Index::view/$1');
	
    $routes->match(['get', 'post'], 'transactions', 'Site\Myaccount\TransactionInfo\Index::index');

    $routes->match(['get', 'post'], 'subscription', 'Site\Myaccount\Subscription\Index::index');
});

$routes->match(['get', 'post'], '/administrator', 'Admin\Login\Index::index', ['filter' => 'adminauthentication1']);
$routes->group('administrator', ['filter' => 'adminauthentication2'], function ($routes) {
    $routes->get('logout', 'Admin\Logout\Index::index');
    $routes->match(['get', 'post'], 'profile', 'Admin\Profile\Index::index');

    // Users
    $routes->match(['get', 'post'], 'users', 'Admin\Users\Index::index');
    $routes->match(['get', 'post'], 'users/action', 'Admin\Users\Index::action');
    $routes->get('users/action/(:num)', 'Admin\Users\Index::action/$1');
    $routes->post('users/DTusers', 'Admin\Users\Index::DTusers');
    $routes->post('users/import', 'Admin\Users\Index::import');
    $routes->get('users/sampleexport', 'Admin\Users\Index::sampleexport');

    // Event
    $routes->match(['get', 'post'], 'event', 'Admin\Event\Index::index');
    $routes->match(['get', 'post'], 'facilityevent/action', 'Admin\Event\Index::facilityeventaction');
    $routes->get('facilityevent/action/(:num)', 'Admin\Event\Index::facilityeventaction/$1');
    $routes->match(['get', 'post'], 'producerevent/action', 'Admin\Event\Index::producereventaction');
    $routes->get('producerevent/action/(:num)', 'Admin\Event\Index::producereventaction/$1');
    $routes->post('event/DTevent', 'Admin\Event\Index::DTevent');
    $routes->get('event/view/(:num)', 'Admin\Event\Index::view/$1');

    // Facility
    $routes->match(['get', 'post'], 'facility', 'Admin\Facility\Index::index');
    $routes->match(['get', 'post'], 'facility/action', 'Admin\Facility\Index::action');
    $routes->match(['get', 'post'], 'facility/action/(:num)', 'Admin\Facility\Index::action/$1');
    $routes->post('facility/DTfacility', 'Admin\Facility\Index::DTfacility');
    $routes->get('facility/view/(:num)', 'Admin\Facility\Index::view/$1');

    // Settings
    $routes->match(['get', 'post'], 'settings', 'Admin\Settings\Index::index');

    // Faq
    $routes->match(['get', 'post'], 'faq', 'Admin\Faq\Index::index');
    $routes->match(['get', 'post'], 'faq/action', 'Admin\Faq\Index::action');
    $routes->get('faq/action/(:num)', 'Admin\Faq\Index::action/$1');
    $routes->post('faq/DTfaq', 'Admin\Faq\Index::DTfaq');

    // Banner
    $routes->match(['get', 'post'], 'banner', 'Admin\Banner\Index::index');
    $routes->match(['get', 'post'], 'banner/action', 'Admin\Banner\Index::action');
    $routes->get('banner/action/(:num)', 'Admin\Banner\Index::action/$1');
    $routes->post('banner/DTbanner', 'Admin\Banner\Index::DTbanner');

    // Abouts Us
    $routes->match(['get', 'post'], 'aboutus', 'Admin\Aboutus\Index::index');
    $routes->match(['get', 'post'], 'aboutus/action', 'Admin\Aboutus\Index::action');
    $routes->get('aboutus/action/(:num)', 'Admin\Aboutus\Index::action/$1');
    $routes->post('aboutus/DTaboutus', 'Admin\Aboutus\Index::DTaboutus');

    // Terms and Conditions
    $routes->match(['get', 'post'], 'termsandconditions', 'Admin\Termsandconditions\Index::index');

    // Privacy Policy
    $routes->match(['get', 'post'], 'privacypolicy', 'Admin\Privacypolicy\Index::index');

    //Contactus
    $routes->match(['get', 'post'], 'contactus', 'Admin\Contactus\Index::index');
    $routes->match(['get', 'post'], 'contactus/DTcontactus', 'Admin\Contactus\Index::DTcontactus');

    // Plan
    $routes->match(['get', 'post'], 'plan', 'Admin\Plan\Index::index');
    $routes->match(['get', 'post'], 'plan/action', 'Admin\Plan\Index::action');
    $routes->get('plan/action/(:num)', 'Admin\Plan\Index::action/$1');
    $routes->post('plan/DTplan', 'Admin\Plan\Index::DTplan');

    //Payments
    $routes->get('payments', 'Admin\Payments\Index::index');
    $routes->post('payments/DTpayments', 'Admin\Payments\Index::DTpayments');
    $routes->get('payments/view/(:num)', 'Admin\Payments\Index::view/$1');

    // Stripe Payments
    $routes->match(['get', 'post'], 'stripepayments', 'Admin\StripePayments\Index::index');
    $routes->match(['get', 'post'], 'stripepayments/action', 'Admin\StripePayments\Index::action');
    $routes->post('stripepayments/DTstripepayments', 'Admin\StripePayments\Index::DTstripepayments');

    //Reservations
    $routes->match(['get', 'post'], 'reservations', 'Admin\Reservations\Index::index');
    $routes->post('reservations/DTreservations', 'Admin\Reservations\Index::DTreservations');
    $routes->get('reservations/view/(:num)', 'Admin\Reservations\Index::view/$1');

    //Comments
    $routes->match(['get', 'post'], 'comments/(:num)', 'Admin\Comments\Index::index/$1');
    $routes->match(['get', 'post'], 'comments/action', 'Admin\Comments\Index::action');
    $routes->get('comments/action/(:num)', 'Admin\Comments\Index::action/$1');

    //Newsletter
    $routes->get('newsletter', 'Admin\Newsletter\Index::index');
    $routes->post('newsletter/DTnewsletter', 'Admin\Newsletter\Index::DTnewsletter');

    // Report
    $routes->match(['get', 'post'], 'eventreport', 'Admin\Report\EventReport::index');

    // Financial Report
    $routes->match(['get', 'post'], 'financialreport', 'Admin\Report\FinancialReport::index');

    // Email Template
    $routes->match(['get', 'post'], 'emailtemplate', 'Admin\EmailTemplate\Index::index');
    $routes->match(['get', 'post'], 'emailtemplate/action/(:num)', 'Admin\EmailTemplate\Index::action/$1');
    $routes->post('emailtemplate/DTtemplates', 'Admin\EmailTemplate\Index::DTtemplates');

    // Tax
    $routes->match(['get', 'post'], 'tax', 'Admin\Tax\Index::index');
    $routes->match(['get', 'post'], 'tax/action', 'Admin\Tax\Index::action');
    $routes->get('tax/action/(:num)', 'Admin\Tax\Index::action/$1');
    $routes->post('tax/DTtax', 'Admin\Tax\Index::DTtax');

    // Settings
    $routes->match(['get', 'post'], 'settings', 'Admin\Settings\Index::index');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
