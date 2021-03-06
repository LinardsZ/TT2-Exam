<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\MessagesController;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\AdministrationController;
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
//landing page
Route::get('/', [SearchController::class, 'index']);

//route for search result request
Route::get('/search', [SearchController::class, 'show'])->name('search.result');

//registration routes
Route::get('/register', [UsersController::class, 'create'])->middleware('guest')->name('register');
Route::post('/users', [UsersController::class, 'store'])->middleware('guest')->name('create.user');

//login and auth routes
Route::get('/login', [UsersController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login/auth', [UsersController::class, 'authenticate'])->middleware('guest')->name('auth.user');
Route::post('/logout', [UsersController::class, 'logout'])->middleware('auth')->name('logout');

//show profile page
Route::post('/profile', [UsersController::class, 'index'])->middleware('auth')->name('profile');

//edit and update user profile
Route::get('/profile/edit', [UsersController::class, 'edit'])->middleware('auth')->name('edit.profile');
Route::post('/profile/update', [UsersController::class, 'update'])->middleware('auth')->name('set.profile');

//add, edit, update and delete experience entries
Route::post('/experience/add', [ExperienceController::class, 'store'])->middleware('auth')->name('experience');
Route::get('/experience/edit/{id}', [ExperienceController::class, 'edit'])->middleware('auth');
Route::post('/experience/update', [ExperienceController::class, 'update'])->middleware('auth')->name('set.exp');
Route::delete('/experience/delete/{id}', [ExperienceController::class, 'destroy'])->middleware('auth');

//add, edit, update and delete education entries
Route::post('/education/add', [EducationController::class, 'store'])->middleware('auth')->name('education');
Route::get('/education/edit/{id}', [EducationController::class, 'edit'])->middleware('auth');
Route::post('/education/update', [EducationController::class, 'update'])->middleware('auth')->name('set.edu');
Route::delete('/education/delete/{id}', [EducationController::class, 'destroy'])->middleware('auth');

//show form for adding and storing a company
Route::get('/company/add', [CompanyController::class, 'index'])->middleware('auth')->name('add.company');
Route::post('/company/add', [CompanyController::class, 'store'])->middleware('auth')->name('store.company');

//edit and update company details
Route::get('company/edit', [CompanyController::class, 'edit'])->middleware('auth')->name('edit.company');
Route::post('company/update', [CompanyController::class, 'update'])->middleware('auth')->name('set.company');

//show detailed information for a single listing
Route::get('/listing/{id}', [JobOfferController::class, 'show']);

//add, edit, update and delete job offers
Route::post('/offer/add', [JobOfferController::class, 'store'])->middleware('auth')->name('add.offer');
Route::get('/offer/edit/{id}', [JobOfferController::class, 'edit'])->middleware('auth')->name('edit.offer');
Route::post('/offer/set', [JobOfferController::class, 'update'])->middleware('auth')->name('set.offer');
Route::delete('/offer/delete/{id}', [JobOfferController::class, 'destroy'])->middleware('auth')->name('delete.offer');

//routes for administrative managing
Route::get('manage', [AdministrationController::class, 'index'])->middleware('auth')->name('show.admin');

Route::get('/view/user/{id}', [AdministrationController::class, 'show'])->middleware('auth')->name('admin.showuser');
Route::get('/view/user/edit/{id}', [AdministrationController::class, 'edit'])->middleware('auth')->name('admin.edituser');
Route::post('/view/user/update', [AdministrationController::class, 'update'])->middleware('auth')->name('admin.updateuser');
Route::post('/view/user/experience/new', [AdministrationController::class, 'newExperience'])->middleware('auth')->name('admin.newexp');
Route::post('/view/user/education/new', [AdministrationController::class, 'newEducation'])->middleware('auth')->name('admin.newedu');
Route::get('/view/user/delete/{id}', [AdministrationController::class, 'destroy'])->middleware('auth')->name('admin.deleteuser');

Route::post('/view/company/offer/add', [AdministrationController::class, 'storeOffer'])->middleware('auth')->name('admin.addoffer');
Route::get('/view/company/{id}', [AdministrationController::class, 'showCompany'])->middleware('auth')->name('admin.showcompany');
Route::get('/view/company/edit/{id}', [AdministrationController::class, 'editCompany'])->middleware('auth')->name('admin.editcompany');
Route::post('/view/company/update', [AdministrationController::class, 'updateCompany'])->middleware('auth')->name('admin.updatecompany');
Route::get('/view/company/delete/{id}', [AdministrationController::class, 'destroyCompany'])->middleware('auth')->name('admin.deletecompany');

Route::get('/view/picture/delete/{userid}', [AdministrationController::class, 'destroyPicture'])->middleware('auth')->name('admin.deletepicture');
Route::get('/view/companypicture/delete/{cid}', [AdministrationController::class, 'destroyCompanyPicture'])->middleware('auth')->name('admin.deletecompanypicture');


// store a new message in database
Route::post('/message', [MessagesController::class, 'store'])->middleware('auth')->name('msg.store');

// view all msgs for a specific user/company
Route::get('/conversations/{id}', [MessagesController::class, 'index'])->middleware('auth')->name('show.msgs');

// view conversation between two users
Route::post('/conversations/view', [MessagesController::class, 'show'])->middleware('auth')->name('view.conversation');

//visit someone else's profile
Route::get('visit/profile/{id}', [UsersController::class, 'visit'])->name('visit.profile');
