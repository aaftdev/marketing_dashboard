<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\ExportCampaign;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\BaseController;
use App\Http\Components\BaseComponent;
class ExtHomeController extends Controller
{
    public function Index()
    {
        if(session('username') != "")
        {
            $institutionList = DB::select("SELECT institution_id, institution_name FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignList("AAFT Online");
            $landingPageList = BaseComponent::ViewLandingPageList('AAFT Online');
            $activeCount = BaseComponent::CampaignCount("Active", "AAFT Online");
                
            $newCount = BaseComponent::CampaignCount("New", "AAFT Online");
            
            $onHoldCount = BaseComponent::CampaignCount("On Hold", "AAFT Online");

            $deleteCount = BaseComponent::CampaignCount("Delete", "AAFT Online");

            $lpStatusChart = BaseComponent::LandingPageStatusChart('AAFT Online');

            $lpAgencyChart = BaseComponent::LandingPageAgencyChart('AAFT Online');
            
            $lpCampLeadCollect = collect($lpStatusChart)->pluck('lpProgramCount', 'program_type_name');

            $labels = $lpCampLeadCollect->keys();
            $lpCampCount = $lpCampLeadCollect->values();

            $lpAgencyLeadCollect = collect($lpAgencyChart)->pluck('lpCourseCount', 'course_name');
            $lpLabels = $lpAgencyLeadCollect->keys();
            $lpAgencyCount = $lpAgencyLeadCollect->values();
                                                
            $institutionId = DB::table('institution')->select('institution_id')->where('institution_name', '=', 'AAFT Online')->pluck('institution_id');
            
            return view('ext-marketing.ext-home', compact(['campaignList', 'landingPageList', 'activeCount', 'newCount', 'onHoldCount', 'deleteCount', 'institutionId', 'institutionList', 
                                                            'labels', 'lpCampCount', 'lpLabels', 'lpAgencyCount']));
        }
        else
        {
            return view('user-login');
        }
    }

    public function ChangeExtHomeInstitution(Request $req)
    {
        if(session('username') != "")
        {
            $institution = $req->institution;
            //$institutionId = DB::table('institution')->where('institution_name', $institution)->value('institution_id');
            $campaignList = BaseComponent::CampaignList($institution);
            $landingPageList = BaseComponent::ViewLandingPageList($institution);
            $activeCount = BaseComponent::CampaignCount("Active", $institution);
                    
            $newCount = BaseComponent::CampaignCount("New", $institution);
            
            $onHoldCount = BaseComponent::CampaignCount("On Hold", $institution);

            $deleteCount = BaseComponent::CampaignCount("Delete", $institution);

            $lpStatusChart = BaseComponent::LandingPageStatusChart('AAFT Online');

            $lpAgencyChart = BaseComponent::LandingPageAgencyChart('AAFT Online');
            
            $lpCampLeadCollect = collect($lpStatusChart)->pluck('lpProgramCount', 'program_type_name');

            $labels = $lpCampLeadCollect->keys();
            $lpCampCount = $lpCampLeadCollect->values();

            $lpAgencyLeadCollect = collect($lpAgencyChart)->pluck('lpCourseCount', 'course_name');
            $lpLabels = $lpAgencyLeadCollect->keys();
            $lpAgencyCount = $lpAgencyLeadCollect->values();

            return response()->json(['campaignList' => $campaignList, 'activeCount' => $activeCount, 'newCount' => $newCount, 
                                     'onHoldCount' => $onHoldCount, 'deleteCount' => $deleteCount, 'landingPageList' => $landingPageList,
                                     'labels' => $labels, 'lpCampCount' => $lpCampCount, 'lpLabels' => $lpLabels, 'lpAgencyCount' => $lpAgencyCount]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function CampaignForm()
    {
        if(session('username') != "")
        {
            $institutionName = "AAFT Online";
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignFormList = BaseComponent::CampaignFormDetails($institutionName);
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            return view('ext-marketing.ext-campaignForm', ['campaignFormList' => $campaignFormList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ChangeExtCampFormInstitution(Request $req)
    {
        if(session('username') != "")
        {
            $institutionName = $req->institution;
            $institutionId = DB::table('institution')->where('institution_name', $institutionName)->value('institution_id');
            $campaignFormList = BaseComponent::CampaignFormDetails($institutionName);
            return response()->json(['campaignFormList' => $campaignFormList, 'institutionId' => $institutionId]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function CreateCampaignForm(Request $req) {
        if(session('username') != "")
        {
            $institution = DB::table('institution')->where('active', 1)->get();
            $institutionCode = DB::table('institution')->where('institution_id', '=', $req->institute)->pluck('institution_code');
            $programType = DB::table('program_type')->where('active', 1)->get();
            $marketingAgency = DB::table('agency')->where('active', 1)->get(); 
            $courseList = DB::table('courses')->where('fk_institution_id', '=', $req->institute)->get();
            $leadSource = DB::table('leadsource')->where('active', 1)->get();
            $campaignList = DB::select("SELECT c.campaign_id, c.campaign_name FROM campaigns c
                                        LEFT JOIN campaign_accept ca ON c.campaign_id = ca.fk_campaign_id
                                        LEFT JOIN campaign_lead_request clr ON c.campaign_id = clr.fk_campaign_id
                                        WHERE ca.camp_accept = 1 AND clr.camp_lead_accept = 1");
            $campFormList = "";
            $campaignStatus = "";
            if($req->campFormId != 0)
            {
                $campFormList = DB::table('campaign_form')->where('campaign_form_id', '=', $req->campFormId)->get();
                $campaignStatus = DB::table('campaign_status')->where('active', 1)->get();
            }
            return response()->json(['institution' => $institution, 'programType' => $programType, 'marketingAgency' => $marketingAgency, 'leadSource' => $leadSource,
                                    'courseList' => $courseList, 'institutionCode' => $institutionCode, 'campaignList' => $campaignList, 'campFormList' => $campFormList, 'campaignStatus'=>$campaignStatus]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function GetCourses(Request $req) {
        if(session('username') != "")
        {
            $institutionCode = $req->get('institutionCode');
            $institutionId = DB::table('institution')->where('institution_code', $institutionCode)->value('institution_id');
            $courses = DB::table('courses')->where('fk_institution_id', $institutionId)->get();
            return response()->json(['courses' => $courses]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function GetCoursesCampaign(Request $req)
    {
        if(session('username') != "")
        {
            $institutionCode = $req->get('institutionCode');
            $institutionId = DB::table('institution')->where('institution_code', $institutionCode)->value('institution_id');
            $courses = DB::table('courses')->where('fk_institution_id', $institutionId)->get();
            $campaignList = DB::select("SELECT c.campaign_id, c.campaign_name FROM campaigns c
                                        LEFT JOIN campaign_accept ca ON c.campaign_id = ca.fk_campaign_id
                                        LEFT JOIN campaign_lead_request clr ON c.campaign_id = clr.fk_campaign_id
                                        WHERE ca.camp_accept = 1 AND clr.camp_lead_accept = 1");
            return response()->json(['courses' => $courses, 'campaignList' => $campaignList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ExtViewCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            $campFormId = $req->campFormId;
            $campFormList = BaseComponent::ViewCampaignFormDetails($campFormId);
            
            return response()->json(['campaignDetails' => $campFormList]);
        }
        else
        {
            return view('user-login');
        }
    }
    
    public function excelCampaign($institution) {
              
        return Excel::download(new ExportCampaign(), 'campaigns.xlsx');
    }

    public function StoreCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            // $reqValidatedData = $req->validate([
            //     'campaign-institution' => 'required',
            //     'programType' => 'required',
            //     'marketingAgency' => 'required',
            //     'leadSource' => 'required',            
            //     'courses' => 'required',
            //     'campaignDate' => 'required',
            //     'campaign' => 'required',
            //     'keyName' => 'required',
            //     'campaignFormStatusId' => 'required'
            // ]);

            $institutionId = DB::table('institution')->where('institution_code', $req->input('campaign-institution'))->value('institution_id');
            $programTypeCode = DB::table('program_type')->where('program_code', $req->programType)->value('program_type_id');
            $agencyCode = DB::table('agency')->where('agency_code', $req->marketingAgency)->value('agency_id');
            $courseCode = DB::table('courses')->where('course_code', $req->courses)->value('course_id');

            $campaignStatusId = DB::table('campaign_status')->where('campaign_status_name', '=', 'New')->value('campaign_status_id');
            $keyName = $req->keyName;
            $userId = DB::table('users')->where('username', '=', session('username'))->value('user_id');
            $campaignFormName = $req->input('campaign-institution').''. $req->programType . '_' . $req->marketingAgency . '_' . $req->courses . '_' . $keyName;
            if($req->hdnCampFormId == 0) {
                DB::table('campaign_form')->insert([
                    'campaign_form_name' => $campaignFormName,
                    'form_key' => $keyName,
                    'fk_course_id' => $courseCode,
                    'fk_institution_id' => $institutionId,
                    'fk_program_type_id' => $programTypeCode,
                    'fk_agency_id' =>$agencyCode,
                    'fk_lead_source_id' => $req->leadSource,
                    'campaign_form_date' => $req->campaignDate,
                    'fk_user_id' => $userId,
                    'fk_campaign_status_id' => $campaignStatusId,
                    'fk_campaign_id' => $req->campaign,
                    'created_by' => session('username'),
                    'created_date' => now(),
                    'updated_by' => session('username'),
                    'updated_date' => now(),
                    'active' => 1
                ]);

                $campaignFormId = (int)DB::getPdo()->lastInsertId();
                
                DB::table('campaign_form_accept')->insert([            
                    'camp_form_request' => 1,
                    'fk_user_id' => $userId,
                    'fk_campaign_form_id' => $campaignFormId,            
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1,
                    'created_by' => session('username'),
                    'updated_by' => session('username')
                ]);
                
                $msg = "Campaign created successfully.";
            }
            else 
            {
                $campaignStatusId = DB::table('campaign_status')->where('campaign_status_id', '=', $req->campaignFormStatusId)->value('campaign_status_id');
                DB::table('campaign_form')->where('campaign_form_id', $req->hdnCampFormId)->update([
                    'campaign_form_name' => $campaignFormName,
                    'form_key' => $keyName,
                    'fk_course_id' => $courseCode,
                    'fk_institution_id' => $institutionId,
                    'fk_program_type_id' => $programTypeCode,
                    'fk_agency_id' => $agencyCode,
                    'fk_lead_source_id' => $req->leadSource,
                    'campaign_form_date' => $req->campaignDate,
                    'fk_campaign_id' => $req->campaign,
                    'fk_user_id' => $userId,
                    'fk_campaign_status_id' => $campaignStatusId,                
                    'updated_date' => now(),                
                    'updated_by' => session('username')
                ]);

                DB::table('campaign_form_edit')->where('fk_campaign_form_id', '=', $req->hdnCampFormId)->update([            
                    'camp_form_edit_accept' => 0,
                    'camp_form_edit_request' => 0,
                    'updated_date' => now(),
                    'updated_by' => session('username'),
                    'active' => 1    
                ]);

                DB::table('campaign_form_accept')->where('fk_campaign_form_id', $req->hdnCampFormId)->update([         
                    'camp_form_request' => 1,
                    'fk_user_id' => $userId,  
                    'fk_campaign_form_id' => $req->hdnCampFormId,                        
                    'updated_date' => now(),
                    'active' => 1,                
                    'updated_by' => session('username')
                ]);

                $msg = "Campaign updated successfully.";

                
            }

            return redirect()->back()->with('message', $msg);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ParameterCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            $published = $req->input('published') == "true" ? 1 : 0;
            $courseInterested = $req->input('course-campaign') == "true" ? 1 : 0;
            $textParam = $req->input('text-param');
            $paramComment = $req->input('parameterId');
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first(); 

            DB::table('campaign_form_parameter_check')->insert([
                'fk_camp_form_id' => $req->input('campaignId'),
                'is_course' => $courseInterested,
                'is_published' => $published,
                'is_lead_field' => $textParam,
                'lead_field' => $paramComment,
                'fk_user_id' => $userId -> user_id,
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1,
                'created_by' => session('username'),
                'updated_by' => session('username')
            ]);

            return redirect()->back()->with('message', ' Campaign parameter set successfully.');
        }
        else
        {
            return view('user-login');
        }
    }

    public function ConfirmLeadCampForm(Request $req) {
        if(session('username') != "")
        {
            $campaignId = $req->get('hdnLeadCampaignId');
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            $leadRequestId = DB::table('campaign_form_lead_request')->select('lead_request_id')->where('fk_campaign_form_id', $campaignId)->first();
            $campaignFormLeadId = DB::table('campaign_form_lead')->select('camp_form_lead_id')->where('fk_camp_form_lead_request_id', '=', $leadRequestId->lead_request_id)->pluck('camp_form_lead_id');
            if(!$campaignFormLeadId)
            {
                DB::table('campaign_form_lead')->insert([
                    'fk_camp_form_lead_request_id' => $leadRequestId->lead_request_id,
                    'fk_user_id' => $userId->user_id,
                    'lead_email' => $req->leadEmailId == "" ? "" : $req->leadEmailId,
                    'lead_phone' => $req->leadPhoneId == "" ? "" : $req->leadPhoneId,
                    'created_date' => now(),
                    'created_by' => session('username'),
                    'updated_by' => session('username'),
                    'updated_date' => now(),
                    'lead_verify' => 0,
                    'active' => 1
                ]);
            }
            else 
            {
                DB::table('campaign_form_lead')->where('camp_form_lead_id', $campaignFormLeadId)->update([
                    
                    'fk_user_id' => $userId->user_id,
                    'lead_email' => $req->leadEmailId == "" ? "" : $req->leadEmailId,
                    'lead_phone' => $req->leadPhoneId == "" ? "" : $req->leadPhoneId,
                    'updated_by' => session('username'),
                    'updated_date' => now(),
                    'active' => 1                    
                ]);
            }


            DB::table('campaign_form_lead_request')
                ->where('fk_campaign_form_id', $campaignId)
                ->update(['camp_lead_accept' => 1,                        
                          'updated_by' => session('username'),
                          'fk_user_id' =>$userId->user_id,
                          'updated_date' => now()]);
            
            $campaignStatus = DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', '=', 'Active')->first();            

            // DB::table('campaign_form')
            //     ->where('campaign_form_id', $campaignId)
            //     ->update(['fk_campaign_status_id' => $campaignStatus->campaign_status_id,
            //             'updated_by' => session('username'),
            //             'updated_date' => now()]);

            // DB::table('campaign_form_edit')->insert([
            //     'fk_campaign_form_id' => $campaignId,
            //     'fk_user_id' => $userId->user_id,
            //     'created_date' => now(),
            //     'updated_date' => now(),
            //     'active' => 1,
            //     'created_by' => session('username'),
            //     'updated_by' => session('username')
            // ]);

            return redirect()->back()->with('message', 'Test lead generated successfully.');
        }
        else
        {
            return view('user-login');
        }
    }
    
    public function EditCampaignFormRequest(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignFormId;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();

            DB::table('campaign_form_edit')->where('fk_campaign_form_id', '=', $campaignId)->update([
                'camp_form_edit_request' => 1,            
                'fk_user_id' => $userId->user_id,
                'updated_date' => now(),
                'updated_by' => session('username'),    
            ]);

            return response()->json(['Edit request sent successfully.']);
        }
        else
        {
            return view('user-login');
        }
    }

    public function CheckCampaignFormKeyName(Request $req)
    {
        if(session('username') != "")
        {
            $campaignKey = $req->get('keyName');
            $campaignFormId = $req->get('campaignFormId');
            $campaignFormCount = DB::select("SELECT COUNT(*) AS count FROM campaign_form cf
                                            WHERE cf.campaign_form_id <> ? AND cf.form_key = ?", [$campaignFormId, $campaignKey]); 
            
            return response()->json([$campaignFormCount]);
        }
        else
        {
            return view('user-login');
        }
    }

    //Campaign

    public function ExtCampaign()
    {
        if(session('username') != "")
        {
            $campaignList = BaseComponent::CampaignDetails("AAFT Online");
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', "AAFT Online")->pluck('institution_id');
            return view('ext-marketing.ext-camp', ['campaignList' => $campaignList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function CreateCampaign( Request $req )
    {
        if(session('username') != "")
        {
            $campaignId= $req->campaignId;
            $institution = DB::table('institution')->where('active', 1)->get();
            $institutionCode = DB::table('institution')->where('institution_id', '=', $req->institute)->pluck('institution_code');
            $programType = DB::table('program_type')->where('active', 1)->get();
            $marketingAgency = DB::table('agency')->where('active', 1)->get(); 
            $targetLocation = DB::table('target_location')->where('active', 1)->get();
            $persona = DB::table('persona')->where('active', 1)->get();
            $price = DB::table('campaign_price')->where('active', 1)->get();
            $headline = DB::table('headline')->where('active', 1)->get();
            $targetSegment = DB::table('target_segment')->where('active', 1)->get();
            $campaignType = DB::table('campaign_type')->where('active', 1)->get();
            $campaignSize = DB::table('campaign_size')->where('active', 1)->get();
            $leadSource = DB::table('leadsource')->where('active', 1)->get();
            $campaignVersion = DB::table('campaign_version')->where('active', 1)->get();
            $courseList = ""; 
            $campaignList = "";
            $campaignStatusList = "";
            if($campaignId != 0) 
            {
                $courseList = DB::table('courses')->where('active', 1)->get();
                $campaignList = DB::select("SELECT c.campaign_id, pt.program_code, cs.course_code, c.fk_leadsource_id, a.agency_code, c.campaign_name, tl.target_location_code, p.persona_code, cp.campaign_price_code, h.headline_code, 
                                                    c.campaign_date, ts.target_segment_code, ct.campaign_type_code, cps.campaign_size_code, cpv.campaign_version_code, cpst.campaign_status_id, ce.camp_edit_id                                   
                                            FROM campaigns c
                                            LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id
                                            LEFT JOIN courses cs ON cs.course_id = c.fk_course_id
                                            LEFT JOIN institution i ON cs.fk_institution_id = i.institution_id
                                            LEFT JOIN campaign_accept ca ON ca.fk_campaign_id = c.campaign_id                            
                                            LEFT JOIN agency a ON a.agency_id = c.fk_agency_id
                                            LEFT JOIN target_location tl ON c.fk_target_location_id = tl.target_location_id
                                            LEFT JOIN target_segment ts ON c.fk_target_segment_id = ts.target_segment_id
                                            LEFT JOIN persona p ON c.fk_persona_id = p.persona_id
                                            LEFT JOIN campaign_price cp ON cp.campaign_price_id = c.fk_campaign_price_id
                                            LEFT JOIN headline h ON h.headline_id = c.fk_headline_id
                                            LEFT JOIN campaign_type ct ON c.fk_campaign_type_id = ct.campaign_type_id
                                            LEFT JOIN campaign_size cps ON c.fk_campaign_size_id = cps.campaign_size_id
                                            LEFT JOIN campaign_version cpv ON c.fk_campaign_version_id = cpv.campaign_version_id
                                            LEFT JOIN campaign_status cpst ON c.fk_campaign_status_id = cpst.campaign_status_id
                                            LEFT JOIN campaign_edit ce ON c.campaign_id = ce.fk_campaign_id
                                            WHERE c.campaign_id = ?", [$campaignId]);
                
                $campaignStatusList = DB::select("SELECT campaign_status_id, campaign_status_name FROM campaign_status");
            }
            
            return response()->json(['institution' => $institution, 'programType' => $programType, 'marketingAgency' => $marketingAgency, 'leadSource' => $leadSource,
                'targetLocation' => $targetLocation, 'persona' => $persona, 'price' => $price, 'headline' => $headline, 'targetSegment' => $targetSegment, 'campaignType' => $campaignType,
                'campaignSize' => $campaignSize, 'campaignVersion' => $campaignVersion, 'instituteCode' => $institutionCode[0], 'campaignList' => $campaignList, 'courseList' => $courseList, 'campaignStatusList' => $campaignStatusList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function StoreCampaign(Request $req) {
        //Check Validation
        if(session('username') != "")
        {
            $reqValidatedData = $req->validate([
                'campaign-institution' => 'required',
                'programType' => 'required',
                'marketingAgency' => 'required',
                'leadSource' => 'required',
                'targetLocation' => 'required',
                'persona' => 'required',
                'price' => 'required',
                'courses' => 'required',
                'campaignDate' => 'required',
                'headline' => 'required',
                'targetSegment' => 'required',
                'campaignType' => 'required',
                'campaignSize' => 'required',
                'campaignVersion' => 'required'
            ]);

            $institution = DB::select('select institution_id, institution_name, institution_code from institution where institution_code = ?', [$reqValidatedData['campaign-institution']]);
            $programType = DB::select("select program_type_id, program_type_name, program_code from program_type where program_code = ?", [$reqValidatedData['programType']]);
            $agency = DB::select("select agency_id, agency_name, agency_code from agency where agency_code = ?", [$reqValidatedData['marketingAgency']]);
            $leadSource = DB::select("select leadsource_id, leadsource_name from leadsource where leadsource_id = ?", [$reqValidatedData['leadSource']]);
            $targetLocation = DB::select("select target_location_id, target_location_name, target_location_code from target_location where target_location_code = ?", [$reqValidatedData['targetLocation']]);
            $persona = DB::select("select persona_id, persona_name, persona_code from persona where persona_code = ?", [$reqValidatedData['persona']]);
            $price = DB::select("select campaign_price_id, campaign_price_name, campaign_price_code from campaign_price where campaign_price_code = ?", [$reqValidatedData['price']]);
            $courses = DB::select("select course_id, course_name, course_code from courses where course_code = ?", [$reqValidatedData['courses']]);
            $campDate= $req->input('campaignDate');
            $campaignMonth = Carbon::parse($campDate)->month;
            $campaignYear = Carbon::parse($campDate)->year;
            $campDay = Carbon::parse($campDate)->day;
            $headline = DB::select("select headline_id, headline_name, headline_code from headline where headline_code = ?", [$reqValidatedData['headline']]);
            $targetSegment = DB::select("select target_segment_id, target_segment_name, target_segment_code from target_segment where target_segment_code = ?", [$reqValidatedData['targetSegment']]);
            $campType = DB::select("select campaign_type_id, campaign_type_name, campaign_type_code from campaign_type where campaign_type_code = ?", [$reqValidatedData['campaignType']]);
            $campSize = DB::select("select campaign_size_id, campaign_size_name, campaign_size_code from campaign_size where campaign_size_code = ?", [$reqValidatedData['campaignSize']]);
            $campVersion = DB::select("select campaign_version_id, campaign_version_name, campaign_version_code from campaign_version where campaign_version_code = ?", [$reqValidatedData['campaignVersion']]);
            $campStatusId = DB::table('campaign_status')->where('campaign_status_name', '=', 'New')->pluck('campaign_status_id');

            foreach($institution as $inst){
                $institutionCode = $inst->institution_code;
                $institutionId = $inst->institution_id;
            }
            foreach($programType as $prog){
                $programTypeCode = $prog->program_code;
                $programTypeId = $prog->program_type_id;
            }
            foreach($agency as $ag){
                $agencyCode = $ag->agency_code;
                $agencyId = $ag->agency_id;
            }
            foreach($leadSource as $lead){
                $leadName = $lead->leadsource_name;
                $leadSourceId = $lead->leadsource_id;
            }
            foreach($targetLocation as $loc){
                $targetLocationCode = $loc->target_location_code;
                $targetLocationId = $loc->target_location_id;
            }
            foreach($persona as $per){
                $personaCode = $per->persona_code;
                $personaId = $per->persona_id;
            }
            foreach($price as $pr){
                $priceCode = $pr->campaign_price_code;
                $priceId = $pr->campaign_price_id;
            }
            foreach($courses as $cour){
                $courseCode = $cour->course_code;
                $courseId = $cour->course_id;
            }
            foreach($headline as $head){
                $headlineCode = $head->headline_code;
                $headlineId = $head->headline_id;
            }
            foreach($targetSegment as $seg){
                $targetSegmentCode = $seg->target_segment_code;
                $targetSegmentId = $seg->target_segment_id;
            }
            foreach($campType as $type){
                $campaignTypeCode = $type->campaign_type_code;
                $campaignTypeId = $type->campaign_type_id;
            }
            foreach($campSize as $camp)
            {
                $campaignSizeCode = $camp->campaign_size_code;
                $campaignSizeId = $camp->campaign_size_id;            
            }
            foreach($campVersion as $ver) {
                $campaignVersionCode = $ver->campaign_version_code;
                $campaignVersionId = $ver->campaign_version_id;
            }
            $campaignName = $institutionCode.''. $programTypeCode . '_' . $agencyCode . '_' . $courseCode . '_' . $campDay . '_' . $campaignMonth .'_'. $campaignYear;
            $campaignAdsetName = $campaignName . '_' . $personaCode . '_' . $targetLocationCode;
            $campaignAdName = $campaignAdsetName . '_' . $priceCode . '_' . $campaignTypeCode . '_' . $targetSegmentCode . '_' . $campaignVersionCode;
            $campaignCreative = $campaignName . '_' . $priceCode . '_' . $personaCode . '_' . $headlineCode . '_' . $campaignSizeCode . '_' . $campaignVersionCode;
            $leadSourceName = $institutionCode .''. $programTypeCode . '_' . $agencyCode . '_' . $courseCode . '_' . $leadName;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first(); 

            if($req->hdncampaignId == 0){
                DB::table('campaigns')->insert([
                    'campaign_name' => $campaignName,
                    'fk_institution_id' => $institutionId,
                    'fk_course_id' => $courseId,
                    'fk_program_type_id' => $programTypeId,
                    'fk_agency_id' => $agencyId,
                    'fk_leadsource_id' => $leadSourceId,
                    'fk_persona_id' => $personaId,
                    'fk_campaign_price_id' => $priceId,
                    'campaign_date' => $campDate,
                    'fk_headline_id' => $headlineId,
                    'fk_target_location_id' => $targetLocationId,
                    'fk_target_segment_id' => $targetSegmentId,
                    'fk_campaign_size_id' => $campaignSizeId,
                    'fk_campaign_version_id' => $campaignVersionId,
                    'fk_campaign_type_id' => $campaignTypeId,
                    'fk_user_id' => $userId->user_id,
                    'fk_campaign_status_id' => $campStatusId[0],
                    'adset' => $campaignAdsetName,
                    'adname' => $campaignAdName,
                    'creative' => $campaignCreative,
                    'leadsource_name' => $leadSourceName,
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1,
                    'created_by' => session('username'),
                    'updated_by' => session('username')
                ]);

                $campaign_Id = (int)DB::getPdo()->lastInsertId();
                
                DB::table('campaign_accept')->insert([            
                    'camp_request' => 1,
                    'fk_user_id' => $userId->user_id,
                    'fk_campaign_id' => $campaign_Id,            
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1,
                    'created_by' => session('username'),
                    'updated_by' => session('username')
                ]);
                
                $msg = "Campaign created successfully.";
            }
            else 
            {
                DB::table('campaigns')->where('campaign_id', $req->hdncampaignId)->update([
                    'campaign_name' => $campaignName,
                    'fk_institution_id' => $institutionId,
                    'fk_course_id' => $courseId,
                    'fk_program_type_id' => $programTypeId,
                    'fk_agency_id' => $agencyId,
                    'fk_leadsource_id' => $leadSourceId,
                    'fk_persona_id' => $personaId,
                    'fk_campaign_price_id' => $priceId,
                    'campaign_date' => $campDate,
                    'fk_headline_id' => $headlineId,
                    'fk_target_location_id' => $targetLocationId,
                    'fk_target_segment_id' => $targetSegmentId,
                    'fk_campaign_size_id' => $campaignSizeId,
                    'fk_campaign_version_id' => $campaignVersionId,
                    'fk_campaign_type_id' => $campaignTypeId,
                    'fk_user_id' => $userId->user_id,
                    'fk_campaign_status_id' => $campStatusId[0],
                    'adset' => $campaignAdsetName,
                    'adname' => $campaignAdName,
                    'creative' => $campaignCreative,
                    'leadsource_name' => $leadSourceName,                
                    'updated_date' => now(),                
                    'updated_by' => session('username')
                ]);

                DB::table('campaign_accept')->where('fk_campaign_id', $req->hdncampaignId)->update([         
                    'camp_request' => 1,
                    'fk_user_id' => $userId->user_id,                          
                    'updated_date' => now(),
                    'active' => 1,                
                    'updated_by' => session('username')
                ]);

                $msg = "Campaign updated successfully.";

                DB::table('campaign_edit')->where('fk_campaign_id', '=', $req->hdncampaignId)->update([
                    'updated_date' => now(),
                    'updated_by' => session('username'),
                    'active' => 0,
                ]);
            }
                                                
            return redirect()->back()->with('message', $msg);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ExtViewCampaign(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->input('campaignId');
            $campaignDetails = BaseComponent::ViewCampaignDetails($campaignId);
            return response()->json(['campaignDetails' => $campaignDetails]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ParameterCampaign(Request $req) 
    {
        if(session('username') != "")
        {
            $published = $req->input('published') == "true" ? 1 : 0;
            $courseInterested = $req->input('course-campaign') == "true" ? 1 : 0;
            $textParam = $req->input('text-param');
            $paramComment = $req->input('parameterId');
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first(); 

            DB::table('campaign_parameter_check')->insert([
                'fk_campaign_id' => $req->input('campaignId'),
                'course_parameter' => $courseInterested,
                'is_published' => $published,
                'is_lead_field_added' => $textParam,
                'lead_field' => $paramComment,
                'fk_user_id' => $userId -> user_id,
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1,
                'created_by' => session('username'),
                'updated_by' => session('username')
            ]);

            return redirect()->back()->with('message', ' Campaign parameter set successfully.');
        }
        else
        {
            return view('user-login');
        }
    }

    public function ConfirmLeadCampaign(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            $status= DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', '=', 'Active')->first();

            DB::table('campaign_lead_request')->where('fk_campaign_id', '=', $campaignId)->update([
                'camp_lead_accept' => 1,
                'updated_date' => now(),
                'updated_by' => session('username')
            ]);

            DB::table('campaigns')->where('campaign_id', '=', $campaignId)->update([
                'fk_campaign_status_id' => $status->campaign_status_id,
                'updated_date' => now(),
                'updated_by' => session('username')
            ]);

            DB::table('campaign_edit')->insert([
                'fk_campaign_id' => $campaignId,
                'fk_user_id' => $userId->user_id,
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1,
                'created_by' => session('username'),
                'updated_by' => session('username')
            ]);

            return response()->json(['Test lead generated successfully.']);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ConfirmEditCampaign(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();

            DB::table('campaign_edit')->where('fk_campaign_id', '=', $campaignId)->update([
                'camp_edit_request' => 1,            
                'fk_user_id' => $userId->user_id,
                'updated_date' => now(),
                'updated_by' => session('username'),    
            ]);

            return response()->json(['Edit request sent successfully.']);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ChangeExtCampInstitution(Request $req)
    {
        if(session('username') != "")
        {
            $institutionName = $req->institution;
            $institutionId = DB::table('institution')->where('institution_name', $institutionName)->value('institution_id');
            $campaignList = BaseComponent::CampaignDetails($institutionName);
            return response()->json(['campaignList' => $campaignList, 'institutionId' => $institutionId]);
        }
        else
        {
            return view('user-login');
        }
    }

    //Landing Page

    public function LandingPage()
    {
        $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
        $landingPageList = BaseComponent::ViewLandingPageList('AAFT Online'); 
        $institutionId = DB::table('institution')->where('institution_name', 'AAFT Online')->value('institution_id');
        return view('ext-marketing.ext-landing-page', ['landingPageList' => $landingPageList, 'institutionId' => $institutionId, 'institutionList' => $institutionList]);
    }

    public function ChangeExtLpInstitution(Request $req)
    {
        $institutionName = $req->institution;
        $institutionId = DB::table('institution')->where('institution_name', $institutionName)->value('institution_id');
        $lpList = BaseComponent::ViewLandingPageList($institutionName);
        return response()->json(['landingPageList' => $lpList, 'institutionId' => $institutionId]);
    }

    public function CreateLandingPageCampaign(Request $req)
    {
        $lpCampId = $req->lpCampId;
        $institution = DB::table('institution')->where('active', 1)->get();
        $institutionCode = DB::table('institution')->where('institution_id', '=', $req->institute)->pluck('institution_code');
        $programType = DB::table('program_type')->where('active', 1)->get();
        $marketingAgency = DB::table('agency')->where('active', 1)->get(); 
        $sourceType = DB::table('source_type')->where('active', 1)->get();
        
        $courseList = ""; 
        $lpCampaignList = "";
        $campaignStatusList = "";
        if($lpCampId !=0) {
            $campaignStatusList = DB::table('campaign_status')->where('active', 1)->get();
            $lpCampaignList = DB::select("SELECT lpc.lp_campaign_id, lpc.camp_name, lpc.key_name, lpc.lp_camp_date, pt.program_code, i.institution_name, c.course_code, a.agency_code, st.source_type_id, cs.campaign_status_id, lpc.camp_url
                                            FROM landing_page_campaign lpc
                                            LEFT JOIN institution i ON i.institution_id = lpc.fk_institution_id
                                            LEFT JOIN program_type pt ON pt.program_type_id = lpc.fk_program_type
                                            LEFT JOIN courses c ON lpc.fk_course_id = c.course_id
                                            LEFT JOIN agency a ON a.agency_id = lpc.fk_agency_id
                                            LEFT JOIN source_type st ON st.source_type_id = lpc.fk_source_type
                                            LEFT JOIN campaign_status cs ON cs.campaign_status_id = lpc.fk_campaign_status_id
                                            LEFT JOIN landing_page_campaign_lead_request lpr ON lpr.fk_lp_camp_id = lpc.lp_campaign_id
                                            LEFT JOIN landing_page_campaign_edit_request ler ON ler.fk_lp_camp_id = lpc.lp_campaign_id
                                            WHERE lpc.lp_campaign_id = ?", [$lpCampId]);
            $courseList = DB::table('courses')->where('active', 1)->get();
        }

        return response()->json(['institution' => $institution, 'programType' => $programType, 'marketingAgency' => $marketingAgency, 'instituteCode' => $institutionCode[0], 'sourceType' => $sourceType,
                                 'courseList' => $courseList, 'campaignStatusList' => $campaignStatusList, 'lpCampaignList' => $lpCampaignList]);
    }

    public function CheckLpCampKeyName(Request $req)
    {
        $keyName = $req->keyName;
        $lpCampId = $req->lpCampId;
        $lpCampCount = DB::select("SELECT COUNT(*) AS count FROM landing_page_campaign lpc
                                        WHERE lpc.lp_campaign_id <> ? AND lpc.key_name = ?", [$lpCampId, $keyName]); 
        
        return response()->json(['lpCampCount' => $lpCampCount]);
    }

    public function StoreLpCampaign(Request $req)
    {
        $lpCampId = $req->hdncampaignId;
        $keyName = $req->keyName;

        $institutionId = DB::table('institution')->where('institution_code', $req->input('campaign-institution'))->value('institution_id');
        $programTypeCode = DB::table('program_type')->where('program_code', $req->input('programType'))->value('program_type_id');
        $agencyCode = DB::table('agency')->where('agency_code', $req->input('marketingAgency'))->value('agency_id');
        $courseCode = DB::table('courses')->where('course_code', $req->input('courses'))->value('course_id');
        $campaignStatusId = DB::table('campaign_status')->where('campaign_status_name', '=', 'New')->value('campaign_status_id');
        $keyName = $req->input('keyName');
        $userId = DB::table('users')->where('username', '=', session('username'))->value('user_id');
        $campaignFormName = $req->input('campaign-institution').''. $req->input('programType') . '_' . $req->input('marketingAgency') . '_' . $req->input('courses') . '_' . $keyName;
        if($req->hdncampaignId == 0) {
            DB::table('landing_page_campaign')->insert([
                'camp_name' => $campaignFormName,
                'key_name' => $keyName,
                'fk_course_id' => $courseCode,
                'fk_institution_id' => $institutionId,
                'fk_program_type' => $programTypeCode,
                'fk_agency_id' =>$agencyCode,
                'fk_source_type' => $req->input('sourceType'),
                'fk_campaign_status_id' => $campaignStatusId,
                'fk_user_id' => $userId,
                'lp_camp_date' => $req->input('campaignDate'),
                'camp_url' => $req->input('lpUrl'),
                'created_by' => session('username'),
                'created_date' => now(),
                'updated_by' => session('username'),
                'updated_date' => now(),
                'active' => 1
            ]);

            $lpCampId = DB::getPdo()->lastInsertId();

            DB::table('landing_page_campaign_accept')->insert([
                'lp_request' => 1,
                'fk_lp_camp_id' => $lpCampId,
                'fk_user_id' => $userId,
                'created_date' => now(),
                'updated_date' => now(),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'active' => 1
            ]);

            $msg = "Campaign created successfully.";
        }
        else 
        {
            DB::table('landing_page_campaign')->where('lp_campaign_id', $req->hdncampaignId)->update([
                'camp_name' => $campaignFormName,
                'key_name' => $keyName,
                'fk_course_id' => $courseCode,
                'fk_institution_id' => $institutionId,
                'fk_program_type' => $programTypeCode,
                'fk_agency_id' =>$agencyCode,
                'fk_source_type' => $req->input('sourceType'),
                'fk_campaign_status_id' => $campaignStatusId,
                'fk_user_id' => $userId,
                'lp_camp_date' => $req->input('campaignDate'),
                'camp_url' => $req->input('lpUrl'),
                'updated_by' => session('username'),
                'updated_date' => now(),
                'active' => 1
            ]);

            DB::table('landing_page_campaign_edit_request')->where('fk_lp_camp_id', '=', $req->hdncampaignId)->update([            
                'edit_request' => 0,
                'edit_accept' => 0,
                'updated_date' => now(),
                'updated_by' => session('username'),
                'active' => 0    
            ]);

            $msg = "Campaign updated successfully.";            
        }

        return redirect()->back()->with('message', $msg);
    }

    public function ExtViewLpCampaign(Request $req)
    {
        $lpCampDetails = BaseComponent::ViewLandingPageCampDetails($req->lpCampId);
        return response()->json(['lpCampDetails' => $lpCampDetails]);
    }

    public function ConfirmLeadLpCampForm(Request $req)
    {
        $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
        $status= DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', '=', 'Active')->first();

        DB::table('landing_page_campaign_lead_request')->where('fk_lp_camp_id', '=', $req->lpCampFormId)->update([
            'lp_camp_accept' => 1,
            'updated_date' => now(),
            'updated_by' => session('username')
        ]);

        DB::table('landing_page_campaign')->where('lp_campaign_id', '=', $req->lpCampFormId)->update([
            'fk_campaign_status_id' => $status->campaign_status_id,
            'updated_date' => now(),
            'updated_by' => session('username')
        ]);

        DB::table('landing_page_campaign_edit_request')->insert([
            'fk_lp_camp_id' => $req->lpCampFormId,
            'fk_user_id' => $userId->user_id,
            'created_date' => now(),
            'updated_date' => now(),
            'active' => 1,
            'created_by' => session('username'),
            'updated_by' => session('username')
        ]);

        return response()->json(['Test lead generated successfully.']);
    }

    public function ConfirmEditLpCampForm(Request $req)
    {
        $campaignId = $req->lpCampId;
        $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();

        DB::table('landing_page_campaign_edit_request')->where('fk_lp_camp_id', '=', $campaignId)->update([
            'edit_request' => 1,            
            'fk_user_id' => $userId->user_id,
            'updated_date' => now(),
            'updated_by' => session('username'),    
        ]);

        return response()->json(['Edit request sent successfully.']);
    }
}
