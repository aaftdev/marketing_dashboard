<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExtHomeController;
use App\Http\Controllers\IntHomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ITAdminController;
use APP\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return view('user-login');
});

Route::get('first-login', function() {    
    return view('first-login');
});

Route::post('login-user', [AdminController::class, 'LoginUser']);
Route::get('logout', [AdminController::class, 'LogoutUser']);
Route::post('change-password', [AdminController::class, 'ChangePassword']);

//External Marketing Dashboard
Route::get('home', [ExtHomeController::class, 'Index']);

Route::get("campaignForm/{institutionName?}", [ExtHomeController::class, 'CampaignForm']);
Route::get('create-campaign-form', [ExtHomeController::class, 'CreateCampaignForm']);
Route::get('ext-camp-form-change-institution', [ExtHomeController::class, 'ChangeExtCampFormInstitution']);
Route::get('courses', [ExtHomeController::class, 'GetCourses']);
Route::get('courses-campaign', [ExtHomeController::class, 'GetCoursesCampaign']);

Route::post('store-camp-form', [ExtHomeController::class, 'StoreCampaignForm']);
Route::get('ext-view-campaign-form',[ExtHomeController::class, 'ExtViewCampaignForm']);
Route::post('parameter-campaign-form', [ExtHomeController::class, 'ParameterCampaignForm']);
Route::post('confirm-lead-camp-form', [ExtHomeController::class, 'ConfirmLeadCampForm']);
Route::get('confirm-edit-campaign-form', [ExtHomeController::class, 'EditCampaignFormRequest']);
Route::get('camp-form-check-keyname', [ExtHomeController::class, 'CheckCampaignFormKeyName']);
Route::get('ext-home-change-institution', [ExtHomeController::class, 'ChangeExtHomeInstitution']);

Route::get('ext-camp', [ExtHomeController::class, 'ExtCampaign']);
Route::get('create-campaign', [ExtHomeController::class, 'CreateCampaign']);
Route::post('store-campaign', [ExtHomeController::class, 'StoreCampaign']);
Route::get('ext-view-campaign', [ExtHomeController::class, 'ExtViewCampaign']);
Route::post('parameter-campaign', [ExtHomeController::class, 'ParameterCampaign']);
Route::get('confirm-lead-campaign', [ExtHomeController::class, 'ConfirmLeadCampaign']);
Route::get('confirm-edit-campaign', [ExtHomeController::class, 'ConfirmEditCampaign']);
Route::get('ext-camp-change-institution', [ExtHomeController::class, 'ChangeExtCampInstitution']);

Route::get('landingPageForm', [ExtHomeController::class, 'LandingPage']);
Route::get('ext-lp-change-institution', [ExtHomeController::class, 'ChangeExtLpInstitution']);
Route::get('create-landing-page-campaign', [ExtHomeController::class, 'CreateLandingPageCampaign']);
Route::get('lp-camp-check-keyname', [ExtHomeController::class, 'CheckLpCampKeyName']);
Route::post('store-lp-campaign', [ExtHomeController::class, 'StoreLpCampaign']);
Route::get('ext-view-lp-campaign', [ExtHomeController::class, 'ExtViewLpCampaign']);
Route::get('confirm-lead-lp-camp-form', [ExtHomeController::class, 'ConfirmLeadLpCampForm']);
Route::get('confirm-edit-lp-campaign', [ExtHomeController::class, 'ConfirmEditLpCampForm']);


//Internal Marketing Dashboard
Route::get('int-home', [IntHomeController::class, 'InternalIndex']);
Route::get('int-home-change-institution', [IntHomeController::class, 'InternalChangeInstitution']);
Route::get('int-landing-page', [IntHomeController::class, 'InternalLandingPage'])->name('intLandingPage');
Route::get('int-campaign', [IntHomeController::class, 'InternalCampaign']);
Route::get('int-camp-change-institution', [IntHomeController::class, 'InternalChangeInstitutionCampaign']);

Route::get('int-view-campaign', [IntHomeController::class, 'ViewCampaign']);
Route::get('int-campaign-download', [IntHomeController::class, 'DownloadCampaign']);
Route::get('int-create-landing-page/{lpId}', [IntHomeController::class, 'GetLandingPage']);
Route::get('get-courses', [IntHomeController::class, 'GetLandingPageCourses']);
Route::post('store-landing-page', [IntHomeController::class, 'StoreLandingPage']);
Route::get('int-lp-change-institution', [IntHomeController::class, 'IntLPChangeInstitution']);

Route::get('int-campaign-form', [IntHomeController::class, 'InternalCampaignForm']);
Route::get('accept-campaign-form', [IntHomeController::class, 'AcceptCampaignForm']);
Route::get('accept-lp-campaign-form', [IntHomeController::class, 'AcceptLpCampForm']);
Route::get('int-camp-form-change-institution', [IntHomeController::class, 'InternalChangeCampFormInstitution']);

//Admin Dashboard
Route::get('admin-institution', [AdminController::class, 'AdminInstitution']);
Route::get('admin-home', [AdminController::class, 'AdminHomeInstitution']);
Route::get('admin-campaign', [AdminController::class, 'AdminCampaignInstitution']);
Route::get('admin-campaign-list', [AdminController::class, 'AdminCampaignListInstitution']);
Route::get('admin-campaign-download', [AdminController::class, 'AdminCampaignDownload']);

//IT Admin Dashboard
Route::get('it-admin-home', [ITAdminController::class, 'ITAdminHome']);
Route::get('it-admin-home-change-institution', [ITAdminController::class, 'ITAdminChangeInstitution']);

Route::get('it-admin-campaign', [ITAdminController::class, 'ITAdminCampaign']);
Route::get('it-admin-campaign-form', [ITAdminController::class, 'ITAdminCampaignForm']);
Route::get('it-admin-integrate-campaign-form', [ITAdminController::class, 'ITAdminIntegrateCampaignForm']);
Route::get('it-admin-lead-campaign-form', [ITAdminController::class, 'ITAdminLeadCampaignForm']);
Route::get('it-admin-edit-campaign-form', [ITAdminController::class, 'ITAdminEditCampaignForm']);
Route::get('it-admin-lead-verify-campaign-form', [ITAdminController::class, 'ITAdminLeadVerifyCampaignForm']);
Route::get('it-admin-camp-form-change-institution', [ITAdminController::class, 'ITAdminChangeCampFormInstitution']);


Route::get('lead-request-campaign', [ITAdminController::class, 'ITAdminCampaignLeadRequest']);
Route::get('it-admin-view-campaign', [ITAdminController::class, 'ITAdminView']);
Route::get('edit-accept-campaign', [ITAdminController::class, 'ITAdminEditAccept']);
Route::get('it-admin-settings', [ITAdminController::class, 'ITAdminSettings']);
Route::get('it-admin-campaign-download', [ITAdminController::class, 'ITAdminCampaignDownload']);
Route::get('it-admin-users', [ITAdminController::class, 'ITAdminUsers']);
Route::get('it-admin-integrate-campaign', [ITAdminController::class, 'ITAdminIntegrateCampaign']);
Route::get('it-admin-lead-campaign', [ITAdminController::class, 'ITAdminLeadCampaign']);
Route::get('it-admin-change-camp-institution', [ITAdminController::class, 'ITAdminCampaignChangeInstitution']);


Route::get('it-admin-landing-page', [ITAdminController::class, 'ITAdminLandingPageList']);
Route::get('it-admin-create-landing-page', [ITAdminController::class, 'ITAdminCreateLandingPage']);
Route::post('it-admin-store-landing-page', [ITAdminController::class, 'ITAdminStoreLandingPage']);
Route::get('it-admin-delete-landing-page', [ITAdminController::class, 'ITAdminDeleteLandingPage']);


//Setting Role
Route::get('it-admin-role', [ITAdminController::class, 'ITAdminRole']);
Route::get('it-admin-role-edit', [ITAdminController::class, 'ITAdminGetRole']);
Route::post('it-admin-role-create', [ITAdminController::class, 'ITAdminCreateRole']);
Route::get('it-admin-role-delete', [ITAdminController::class, 'ITAdminDeleteRole']);

//Settings Agency
Route::get('it-admin-agency', [ITAdminController::class, 'ITAdminAgency']);
Route::get('it-admin-agency-edit', [ITAdminController::class, 'ITAdminGetAgency']);
Route::post('it-admin-agency-create', [ITAdminController::class, 'ITAdminCreateAgency']);
Route::get('it-admin-agency-delete', [ITAdminController::class, 'ITAdminDeleteAgency']);
Route::get('it-admin-agency-check', [ITAdminController::class, 'ITAdminAgencyCheck']);

//Settings Lead source
Route::get('it-admin-lead-source', [ITAdminController::class, 'ITAdminLeadSource']);
Route::get('it-admin-lead-source-edit', [ITAdminController::class, 'ITAdminGetLeadSource']);
Route::post('it-admin-lead-source-create', [ITAdminController::class, 'ITAdminCreateLeadSource']);
Route::get('it-admin-lead-source-delete', [ITAdminController::class, 'ITAdminDeleteLeadSource']);

//Settings Program Type
Route::get('it-admin-program-type', [ITAdminController::class, 'ITAdminProgramType']);
Route::get('it-admin-program-type-edit', [ITAdminController::class, 'ITAdminGetProgramType']);
Route::post('it-admin-program-type-create', [ITAdminController::class, 'ITAdminCreateProgramType']);
Route::get('it-admin-program-type-delete', [ITAdminController::class, 'ITAdminDeleteProgramType']);
Route::get('it-admin-program-type-check', [ITAdminController::class, 'ITAdminProgramTypeCheck']);

//Settings Persona
Route::get('it-admin-persona', [ITAdminController::class, 'ITAdminPersona']);
Route::get('it-admin-persona-edit', [ITAdminController::class, 'ITAdminGetPersona']);
Route::post('it-admin-persona-create', [ITAdminController::class, 'ITAdminCreatePersona']);
Route::get('it-admin-persona-delete', [ITAdminController::class, 'ITAdminDeletePersona']);
Route::get('it-admin-persona-check', [ITAdminController::class, 'ITAdminPersonaCheck']);

//Settings Campaign Price
Route::get('it-admin-campaign-price', [ITAdminController::class, 'ITAdminCampaignPrice']);
Route::get('it-admin-campaign-price-edit', [ITAdminController::class, 'ITAdminGetCampaignPrice']);
Route::post('it-admin-campaign-price-create', [ITAdminController::class, 'ITAdminCreateCampaignPrice']);
Route::get('it-admin-campaign-price-delete', [ITAdminController::class, 'ITAdminDeleteCampaignPrice']);
Route::get('it-admin-campaign-price-check', [ITAdminController::class, 'ITAdminCampaignPriceCheck']);

//Settings Headline
Route::get('it-admin-headline', [ITAdminController::class, 'ITAdminHeadline']);
Route::get('it-admin-headline-edit', [ITAdminController::class, 'ITAdminGetHeadline']);
Route::post('it-admin-headline-create', [ITAdminController::class, 'ITAdminCreateHeadline']);
Route::get('it-admin-headline-delete', [ITAdminController::class, 'ITAdminDeleteHeadline']);
Route::get('it-admin-headline-check', [ITAdminController::class, 'ITAdminHeadlineCheck']);

//Settings Target Location
Route::get('it-admin-target-location', [ITAdminController::class, 'ITAdminTargetLocation']);
Route::get('it-admin-target-location-edit', [ITAdminController::class, 'ITAdminGetTargetLocation']);
Route::post('it-admin-target-location-create', [ITAdminController::class, 'ITAdminCreateTargetLocation']);
Route::get('it-admin-target-location-delete', [ITAdminController::class, 'ITAdminDeleteTargetLocation']);
Route::get('it-admin-target-location-check', [ITAdminController::class, 'ITAdminTargetLocationCheck']);

//Settings Campaign Type
Route::get('it-admin-campaign-type', [ITAdminController::class, 'ITAdminCampaignType']);
Route::get('it-admin-campaign-type-edit', [ITAdminController::class, 'ITAdminGetCampaignType']);
Route::post('it-admin-campaign-type-create', [ITAdminController::class, 'ITAdminCreateCampaignType']);
Route::get('it-admin-campaign-type-delete', [ITAdminController::class, 'ITAdminDeleteCampaignType']);
Route::get('it-admin-campaign-type-check', [ITAdminController::class, 'ITAdminCampaignTypeCheck']);

//Settings Campaign Size
Route::get('it-admin-campaign-size', [ITAdminController::class, 'ITAdminCampaignSize']);
Route::get('it-admin-campaign-size-edit', [ITAdminController::class, 'ITAdminGetCampaignSize']);
Route::post('it-admin-campaign-size-create', [ITAdminController::class, 'ITAdminCreateCampaignSize']);
Route::get('it-admin-campaign-size-delete', [ITAdminController::class, 'ITAdminDeleteCampaignSize']);
Route::get('it-admin-campaign-size-check', [ITAdminController::class, 'ITAdminCampaignSizeCheck']);

//Settings Campaign Version
Route::get('it-admin-campaign-version', [ITAdminController::class, 'ITAdminCampaignVersion']);
Route::get('it-admin-campaign-version-edit', [ITAdminController::class, 'ITAdminGetCampaignVersion']);
Route::post('it-admin-campaign-version-create', [ITAdminController::class, 'ITAdminCreateCampaignVersion']);
Route::get('it-admin-campaign-version-delete', [ITAdminController::class, 'ITAdminDeleteCampaignVersion']);
Route::get('it-admin-campaign-version-check', [ITAdminController::class, 'ITAdminCampaignVersionCheck']);

//Settings Campaign Status
Route::get('it-admin-campaign-status', [ITAdminController::class, 'ITAdminCampaignStatus']);
Route::get('it-admin-campaign-status-edit', [ITAdminController::class, 'ITAdminGetCampaignStatus']);
Route::post('it-admin-campaign-status-create', [ITAdminController::class, 'ITAdminCreateCampaignStatus']);
Route::get('it-admin-campaign-status-delete', [ITAdminController::class, 'ITAdminDeleteCampaignStatus']);
Route::get('it-admin-campaign-status-check', [ITAdminController::class, 'ITAdminCampaignStatusCheck']);

//Settings Target Segment
Route::get('it-admin-target-segment', [ITAdminController::class, 'ITAdminTargetSegment']);
Route::get('it-admin-target-segment-edit', [ITAdminController::class, 'ITAdminGetTargetSegment']);
Route::post('it-admin-target-segment-create', [ITAdminController::class, 'ITAdminCreateTargetSegment']);
Route::get('it-admin-target-segment-delete', [ITAdminController::class, 'ITAdminDeleteTargetSegment']);
Route::get('it-admin-target-segment-check', [ITAdminController::class, 'ITAdminTargetSegmentCheck']);

//Settings Users
Route::get('it-admin-users', [ITAdminController::class, 'ITAdminUsers']);
Route::get('it-admin-users-edit', [ITAdminController::class, 'ITAdminGetUsers']);
Route::post('it-admin-users-create', [ITAdminController::class, 'ITAdminCreateUsers']);
Route::get('it-admin-users-delete', [ITAdminController::class, 'ITAdminDeleteUsers']);
