<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Tailor routes
Route::prefix('tailor')->group(function () {
    Route::get('login', [TailorController::class, 'showLoginForm'])->name('tailor.login');
    Route::post('login', [TailorController::class, 'login'])->name('tailor.login.submit');
    Route::get('register', [TailorController::class, 'showRegistrationForm'])->name('tailor.register');
    Route::post('register', [TailorController::class, 'register'])->name('tailor.register.submit');
    Route::get('/dashboard', [TailorController::class, 'dashboard'])->name('tailor.dashboard');
Route::post('logout', [TailorController::class, 'logout'])->name('tailor.logout');
});

Route::get('/tailor/customers', [TailorController::class, 'customerList'])->name('tailor.customer-list');
Route::get('/tailor/measurements', [TailorController::class, 'measurementList'])->name('tailor.measurement-list');
Route::get('/tailor/services', [TailorController::class, 'serviceList'])->name('tailor.service-index');
Route::get('/tailor/customer/{id}', [TailorController::class, 'viewCustomer'])->name('tailor.customer-details');
Route::delete('/tailor/customer/{tailorID}', [TailorController::class, 'destroyCustomer'])->name('tailor.customer.destroy');

// Display the profile edit form
Route::get('/tailor/profile-edit/{tailorID}', [TailorController::class, 'edit'])->name('tailor.profile-edit');

// Handle the profile edit form submission
Route::post('/tailor/profile-update/{tailorID}', [TailorController::class, 'update'])->name('tailor.profile.update');

// Customer routes
Route::prefix('customer')->group(function () {
    Route::get('login', [CustomerController::class, 'showLoginForm'])->name('customer.login');
    Route::post('login', [CustomerController::class, 'login'])->name('customer.login.submit');
    Route::get('register', [CustomerController::class, 'showRegistrationForm'])->name('customer.register');
    Route::post('register', [CustomerController::class, 'register'])->name('customer.register.submit');
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
});

// Customer Profile Edit and Update Routes
Route::get('/customer/profile-edit/{customerID}', [CustomerController::class, 'edit'])->name('customer.profile-edit');
Route::post('/customer/profile-update/{customerID}', [CustomerController::class, 'update'])->name('customer.profile.update');
Route::get('/customer/services', [CustomerController::class, 'CustomerserviceList'])->name('customer.service-index');
Route::get('/customer/measurements', [CustomerController::class, 'viewMeasurements'])->name('customer.measurement-index');

// Customer Logout Route
Route::post('logout', [CustomerController::class, 'logout'])->name('customer.logout');

// Tailor Routes
// Route for showing the service creation form
Route::get('/tailor/create', [ServiceController::class, 'createService'])->name('tailor.service-create');
Route::post('/tailor/services', [ServiceController::class, 'storeService'])->name('tailor.serviceStore');
Route::get('/tailor/{service}/edit', [ServiceController::class, 'edit'])->name('tailor.service-edit');
Route::put('/tailor/{service}', [ServiceController::class, 'update'])->name('tailor.update');
Route::delete('/tailor/{service}', [ServiceController::class, 'destroy'])->name('tailor.destroy');


Route::get('/tailor/measurement', [MeasurementController::class, 'createMeasurement'])->name('tailor.measurement-create');
Route::post('/tailor/measurement', [MeasurementController::class, 'storeMeasurement'])->name('tailor.store');
Route::get('/tailor/measurement/{measurementID}/edit', [TailorController::class, 'editMeasurement'])->name('tailor.measurement-edit');
Route::post('/tailor/measurement/{measurementID}/update', [TailorController::class, 'updateMeasurement'])->name('tailor.measurement-update');
Route::delete('/tailor/measurement/{measurementID}/delete', [TailorController::class, 'deleteMeasurement'])->name('tailor.measurement-delete');
Route::get('/tailor/measurement/{customerID}', [TailorController::class, 'viewMeasurementDetails'])->name('tailor.measurement-details');


Route::get('/tailor/appointments/calendar', [TailorController::class, 'calendar'])->name('tailor.appointment-calendar');
Route::get('/tailor/appointments', [TailorController::class, 'getAppointmentsByDate']);
Route::post('/tailor/appointments/store-appointment', [AppointmentController::class, 'appointmentStoreTailor'])->name('tailor.appointments.store');
Route::put('/tailor/appointments/{appointmentID}', [AppointmentController::class, 'updateAppointmentTailor'])->name('tailor.appointments.update');
Route::delete('/tailor/appointments/{appointmentID}', [TailorController::class, 'deleteAppointment'])->name('tailor.appointments.delete');
Route::post('/tailor/appointments', [AppointmentController::class, 'createAppointmentTailor'])->name('tailor.appointments.create');

Route::get('/customer/appointments', [AppointmentController::class, 'appointmentIndex'])->name('customer.appointment-index');
Route::get('/customer/appointments/create', [AppointmentController::class, 'appointmentCreate'])->name('customer.appointment-create');
Route::post('/customer/appointments/store', [AppointmentController::class, 'appointmentStore'])->name('customer.appointment-store');
Route::get('/customer/appointments/{appointmentID}/edit', [AppointmentController::class, 'appointmentEdit'])->name('customer.appointment-edit');
Route::post('/customer/appointments/{appointmentID}/update', [AppointmentController::class, 'appointmentUpdate'])->name('customer.appointment-update');
Route::post('/customer/appointments/{appointmentID}/delete', [AppointmentController::class, 'appointmentDelete'])->name('customer.appointment-delete');



Route::get('/customer/order/create', [OrderController::class, 'createOrderForm'])->name('customer.order-create');
Route::post('/customer/order-store', [OrderController::class, 'Orderstore'])->name('customer.order-store');
Route::post('/tailor/order/accept/{orderID}', [TailorController::class, 'acceptOrder'])->name('tailor.order-accept');
Route::post('/tailor/order/complete/{orderID}', [TailorController::class, 'completeOrder'])->name('orders.complete');
Route::get('/customer/order-history/{customerID?}', [CustomerController::class, 'viewOrderHistory'])->name('customer.order-history');
Route::get('/tailor/orders', [TailorController::class, 'pendingOrders'])->name('tailor.order-pending');
Route::get('/customer/order/{orderID}/receipt', [CustomerController::class, 'viewReceipt'])->name('customer.receipt');
Route::get('/tailor/order/{orderID}/details', [TailorController::class, 'viewOrderDetails'])->name('tailor.order-details');
Route::get('/order/receipt/download/{orderID}', [OrderController::class, 'downloadReceipt'])->name('customer.receipt-download');








