<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\ExportCampaign;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Http\Components\BaseComponent;

class ITAdminController extends Controller
{
    public function ITAdminHome()
    {
        if(session('username') != "")
        {
            $institutionList = DB::select("SELECT institution_id, institution_name FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignList("AAFT Online");
            $landingPageList = BaseComponent::ViewLandingPageList('AAFT Online');
            
            $campaignStatusChart = BaseComponent::CampaignStatusChart('AAFT Online');
            $lpStatusChart = BaseComponent::LandingPageStatusChart('AAFT Online');
            
            $campaignLeadCollect = collect($campaignStatusChart)->pluck('campaigncount', 'agency_name');
            $labels = $campaignLeadCollect->keys();
            $leadCount = $campaignLeadCollect->values();
            
            $lpCampLeadCollect = collect($lpStatusChart)->pluck('lpProgramCount', 'program_type_name');
            $lpCamplabels = $lpCampLeadCollect->keys();
            $lpCampCount = $lpCampLeadCollect->values();           
                                                
            $institutionId = DB::table('institution')->select('institution_id')->where('institution_name', '=', 'AAFT Online')->pluck('institution_id');

            return view('it-admin-shared.it-admin-home', compact(['campaignList', 'landingPageList', 'institutionId', 'institutionList', 
                                                                    'lpCamplabels', 'lpCampCount', 'labels', 'leadCount']));
        }
        else 
        {
            return view('user-login');
        }
    }

    public function ITAdminChangeInstitution(Request $req)
    {
        if(session('username') != "")
        {
            $institutionName = $req->institution;
            $institutionList = DB::select("SELECT institution_id, institution_name FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignList($institutionName);
            $landingPageList = BaseComponent::ViewLandingPageList($institutionName);
            
            $campaignStatusChart = BaseComponent::CampaignStatusChart($institutionName);
            $lpStatusChart = BaseComponent::LandingPageStatusChart($institutionName);
            
            $campaignLeadCollect = collect($campaignStatusChart)->pluck('campaigncount', 'agency_name');
            $labels = $campaignLeadCollect->keys();
            $leadCount = $campaignLeadCollect->values();
            
            $lpCampLeadCollect = collect($lpStatusChart)->pluck('lpProgramCount', 'program_type_name');
            $lpCamplabels = $lpCampLeadCollect->keys();
            $lpCampCount = $lpCampLeadCollect->values();           
                                                
            $institutionId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');

            return response()->json(['campaignList' => $campaignList, 'landingPageList' => $landingPageList, 'institutionId' => $institutionId, 'institutionList' => $institutionList, 
                                                                    'lpCamplabels' => $lpCamplabels, 'lpCampCount' => $lpCampCount, 'labels' => $labels, 'leadCount' => $leadCount]);
        }
        else 
        {
            return view('user-login');
        }
    }

    public function ITAdminCampaign()
    {
        if(session('username') != "")
        {
            $institutionName = "AAFT Online";
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignDetails("AAFT Online");
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            
            return view('it-admin-shared.it-admin-campaign', compact(['campaignList', 'instituteId', 'institutionList']));
        }
        else {
            return view('user-login');
        }
    }

    public function ITAdminCampaignChangeInstitution(Request $req)
    {
        if(session('username') != "")
        {
            $institutionName = $req->institution;
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignDetails($institutionName);
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            
            return response()->json(['campaignList'=>$campaignList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else {
            return view('user-login');
        }
    }

    public function ITAdminCampaignLeadRequest(Request $req)
    {
        $userId = DB::table('users')->where('username', '=', session('username'))->pluck('user_id');
               
        DB::table('campaign_lead_request')->insert([
            'fk_campaign_id' => $req->get('campaignId'),
            'fk_user_id' => $userId[0],
            'campaign_lead_request' => 1,
            'campagin_lead_request_date' => now(),
            'created_by' => session('username'),
            'updated_by' => session('username'),
            'created_date' => now(),
            'updated_date' => now(),
            'active' => 1   
        ]);

        return response()->json(["Lead request sent successfully."]);
    }

    public function ITAdminView(Request $req)
    {
        $campaignId = $req->campaignId;
        $campaignDetails = BaseComponent::ViewCampaignDetails($campaignId);
        
        return response()->json(['campaignDetails' => $campaignDetails]);
    }

    public function ITAdminEditAccept(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $approval = $req-> approval;
            $comment = $req->comment;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            DB::table('campaign_edit')->where('fk_campaign_id', $campaignId)->update([
                'camp_edit_accept' => $approval == 1 ? 1 : 0,
                'edit_comments' => $comment,
                'fk_user_id' => $userId->user_id,
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            return response()->json(["Edit request accepted successfully."]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCampaignDownload()
    {
        return Excel::download(new ExportCampaign(), 'campaigns'.now() . '.xlsx');
    }

    public function ITAdminSettings()
    {
        if(session('username') != "")
        {
            return view('it-admin-shared.it-admin-settings');
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminIntegrateCampaign(Request $req) 
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $comments = $req->comment;
            $msg = $req->approval == 1 ? "Campaign integrated successfully." : "Campaign not integrated.";
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            DB::table('campaign_parameter_check')->where('fk_campaign_id', $campaignId)->update([
                'camp_integrated' => $req->approval == 1 ? 1 : 0, 
                'fk_user_id' => $userId->user_id, 
                'lead_field' => $comments,
                'updated_by' => session("username"),
                'updated_date' => now(), 
                'active' => 1]);

            if($req->approval == 1)
            {           
                $campaignStatusId = DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', 'Active')->first();
                DB::table('campaigns')->where('campaign_id', $campaignId)->update([
                    'fk_campaign_status_id' => $campaignStatusId->campaign_status_id,
                    'updated_by' => session("username"),
                    'updated_date' => now()
                ]);

                DB::table('campaign_edit')->insert([
                    'fk_campaign_id' => $campaignId,
                    'fk_user_id' => $userId->user_id,
                    'created_date' => now(),
                    'updated_date' => now(),
                    'created_by' => session("username"),
                    'updated_by' => session("username"),
                    'active' => 1
                ]);
            }   
            
            return response()->json([$msg]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminLeadCampaign(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            DB::table('campaign_lead_request')->insert([
                'fk_campaign_id' => $campaignId,
                'fk_user_id' => $userId->user_id,
                'camp_lead_request'=> 1,
                'created_by'=> session("username"),
                'created_date' => now(),
                'updated_by' => session("username"),
                'updated_date' => now(),
                'active' => 1
            ]);

            return response()->json(["Lead request sent successfully."]);
        }
        else 
        {
            return view('user-login');
        }
    }

    //Campaign Form

    public function ITAdminCampaignForm()
    {
        if(session('username') != "")
        {
            $institutionName = "AAFT Online";
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignFormList = BaseComponent::CampaignFormDetails($institutionName);
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            return view('it-admin-shared.it-admin-campaign-form', ['campaignFormList' => $campaignFormList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminChangeCampFormInstitution(Request $req)
    {
        if(session('username') != "")
        {
            $institutionName = $req->institution;
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignFormList = BaseComponent::CampaignFormDetails($institutionName);
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            return response()->json(['campaignFormList' => $campaignFormList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminIntegrateCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $comments = $req->comment;
            $msg = $req->approval == 1 ? "Campaign form integrated successfully." : "Campaign form not integrated.";
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            DB::table('campaign_form_parameter_check')->where('fk_camp_form_id', $campaignId)->update([
                'form_integrated' => $req->approval == 1 ? 1 : 0, 
                'fk_user_id' => $userId->user_id, 
                'lead_field' => $comments,
                'updated_by' => session("username"),
                'updated_date' => now(), 
                'active' => 1]);
            
            // if($req->approval == 1)
            // {
            //     $campaignStatusId = DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', 'Active')->first();
            //     DB::table('campaign_form')->where('campaign_form_id', $campaignId)->update([
            //         'fk_campaign_status_id' => $campaignStatusId->campaign_status_id,
            //         'updated_by' => session("username"),
            //         'updated_date' => now()
            //     ]);

            //     DB::table('campaign_form_edit')->insert([
            //         'fk_campaign_form_id' => $campaignId,
            //         'fk_user_id' => $userId->user_id,
            //         'created_date' => now(),
            //         'updated_date' => now(),
            //         'created_by' => session("username"),
            //         'updated_by' => session("username"),
            //         'active' => 1
            //     ]);
            // }
            
            return response()->json([$msg]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminLeadCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            DB::table('campaign_form_lead_request')->insert([
                'fk_campaign_form_id' => $req->campaignId,
                'fk_user_id' => $userId->user_id,
                'camp_lead_request'=> 1,
                'created_by'=> session("username"),
                'created_date' => now(),
                'updated_by' => session("username"),
                'updated_date' => now(),
                'active' => 1
            ]);

            return response()->json(["Lead request sent successfully."]);
        }
        else
        {
            return view('user-login');
        }
    }
    
    public function ITAdminEditCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            $campaignId = $req->campaignId;
            $approval = $req-> approval;
            $msg = $req->approval == 1 ? "Edit request accepted successfully." : "Edit request not accepted.";
            $comment = $req->comment;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            DB::table('campaign_form_edit')->where('fk_campaign_form_id', $campaignId)->update([
                'camp_form_edit_accept' => $approval == 1 ? 1 : 0,
                'camp_form_edit_comment' => $comment,
                'fk_user_id' => $userId->user_id,
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            return response()->json([$msg]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminLeadVerifyCampaignForm(Request $req)
    {
        if(session('username') != "")
        {
            $leadId = $req->leadId;
            $approval = $req-> approval;
            $msg = $req->approval == 1 ? "Lead verified successfully." : "Lead not verified.";
            $comment = $req->comment;
            $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
            $campFormId = DB::table('campaign_form_lead')->select('fk_camp_form_lead_request_id')->where('camp_form_lead_id', $leadId)->first();
            DB::table('campaign_form_lead')->where('camp_form_lead_id', $leadId)->update([
                'lead_verify' => $approval == 1 ? 1 : 0,
                'lead_comment' => $comment,
                'fk_user_id' => $userId->user_id,
                'updated_by' => session('username'),
                'updated_date' => now(),
                'active' => $approval == 1 ? 1 : 0
            ]);

            if($req->approval == 0)
            {
                DB::table('campaign_form_lead_request')->where('lead_request_id', $campFormId->fk_camp_form_lead_request_id)->update([
                    'camp_lead_accept' => 0,
                    'updated_by' => session('username'),
                    'comments' => $comment,
                    'updated_date' => now()
                ]);
            }
            else 
            {
                $campFormId = DB::table('campaign_form_lead_request')->select('fk_campaign_form_id')->where('lead_request_id', $campFormId->fk_camp_form_lead_request_id)->pluck('fk_campaign_form_id');
                $campStatusId = DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', 'Active')->first();
                DB::table('campaign_form')->where('campaign_form_id', $campFormId)->update([
                    'fk_campaign_status_id' => $campStatusId->campaign_status_id,
                    'updated_by' => session('username'),
                    'updated_date' => now()
                ]);
            }

            return response()->json([$msg]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminViewCampForm(Request $req)
    {
        if(session('username') != "")
        {
            $campFormId = $req->campaignId;
            $campFormList = BaseComponent::ViewCampaignFormDetails($campFormId);
            
            return response()->json(['campaignDetails' => $campFormList]);
        }
        else
        {
            return view('user-login');
        }
    }

    // Landing Page

    public function ITAdminLandingPageList()
    {
        if(session('username') != "")
        {
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', 'AAFT Online')->pluck('institution_id');
            $landingPageList = BaseComponent::ViewLandingPageList('AAFT Online');
            return view('it-admin-shared.it-admin-landing-page', ['landingPageList' => $landingPageList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateLandingPage(Request $req)
    {
        if(session('username') != "")
        {
            $instituteId = $req->institute;
            $lpId = $req->lpCampId;
            $lpCampaignList = "";
            $campaignStatus = "";
            $institution = DB::table('institution')->where('active', 1)->get();
            $institutionCode = DB::table('institution')->where('institution_id', '=', $instituteId)->pluck('institution_code');
            $programType = DB::table('program_type')->where('active', 1)->get();
            // $marketingAgency = DB::table('agency')->where('active', 1)->get(); 
            $courseList = DB::table('courses')->where('fk_institution_id', '=', $req->institute)->get();            

            if($lpId != 0)
            {
                $lpCampaignList = BaseComponent::ViewLandingPageCampDetails($lpId);
                $campaignStatus = DB::table('campaign_status')->where('active', 1)->get();
            }
            
            return response()->json(['institution' => $institution, 'programType' => $programType, 'courseList' => $courseList, 
            'institutionCode' => $institutionCode, 'lpCampaignList' => $lpCampaignList, 'campaignStatusList'=>$campaignStatus]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminDeleteLandingPage(Request $req)
    {
        if(session('username') != "")
        {
            $lpCampId = $req->lpId;
            DB::table('landing_page')->where('lp_campaign_id', $lpCampId)->update(['active' => 0]);
            return response()->json(['Landing page deleted successfully.']);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminStoreLandingPage(Request $req)
    {
        if(session('username') != "")
        {
            $lpId = $req->hdnLPId;
            $msg = "";
            $institutionId = DB::table('institution')->where('institution_code', $req->input('campaign-institution'))->value('institution_id');
            $programTypeCode = DB::table('program_type')->where('program_code', $req->programType)->value('program_type_id');
            $agencyCode = DB::table('agency')->where('agency_code', $req->marketingAgency)->value('agency_id');
            $courseCode = DB::table('courses')->where('course_code', $req->courses)->value('course_id');
            $userId = DB::table('users')->where('username', '=', session('username'))->value('user_id');

            if($lpId == 0)
            {
                $campStatusId = DB::table('campaign_status')->select('campaign_status_id')->where('campaign_status_name', 'New')->first();
                DB::table('landing_page')->insert([
                    'fk_program_type' => $programTypeCode,
                    'fk_institution_id' => $institutionId,
                    'fk_course_id' => $courseCode,
                    
                    'fk_campaign_status_id' => $campStatusId->campaign_status_id,
                    'fk_user_id' => $userId,
                    'camp_url' => $req->lpUrl,
                    'created_by' => session('username'),
                    'updated_by' => session('username'),
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1
                ]);
                $msg = "Landing page created successfully.";
            }
            else
            {
                // $campaignStatusId = DB::table('campaign_status')->where('campaign_status_id', '=', $req->campaignFormStatusId)->value('campaign_status_id');
                DB::table('landing_page')->where('lp_campaign_id', $lpId)->update([
                    'fk_program_type' => $programTypeCode,
                    'fk_institution_id' => $institutionId,
                    'fk_course_id' => $courseCode,
                    
                    'fk_campaign_status_id' => $req->campaignStausId,
                    'fk_user_id' => $userId,
                    'camp_url' => $req->lpUrl,                    
                    'updated_by' => session('username'),                    
                    'updated_date' => now(),
                    'active' => 1
                ]);

                $msg = "Landing page updated successfully.";
            }

            return redirect()->back()->with('message', $msg);
        }
        else
        {
            return view('user-login');
        }
    }

    // Role Management

    public function ITAdminRole()
    {
        if(session('username') != "")
        {
            $roleList = DB::table('role')->get();
            return view('it-admin-shared.it-admin-role', ['roleList' => $roleList]);
        }
        else 
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateRole(Request $req)
    {
        $roleId = $req->input('hdnRoleId');
        $mesg = "";
        if($roleId == 0) 
        {
            DB::table('role')->insert([
                'role_name' => $req->input('roleName'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Role created successfully.";
        }
        else 
        {
            DB::table('role')->where('role_id', $roleId)->update([
                'role_name' => $req->input('roleName'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Role updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteRole(Request $req)
    {
        $roleId = $req->get('roleId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Role deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Role restored successfully.";
        }

        DB::table('role')->where('role_id', $roleId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetRole(Request $req)
    {
        $roleId = $req->get('roleId');
        $roleList =  DB::table('role')->where('role_id', $roleId)->get();
        return $roleList;
    }

    // End Role Management
    // Agency Management

    public function ITAdminAgency()
    {
        if(session('username') != "")
        {
            $agencyList = DB::table('agency')->get();
            return view('it-admin-shared.it-admin-agency', ['agencyList' => $agencyList]);
        }
        else 
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateAgency(Request $req)
    {
        $agencyId = $req->input('hdnAgencyId');
        $mesg = "";
        if($agencyId == 0) 
        {
            DB::table('agency')->insert([
                'agency_name' => $req->input('agencyName'),
                'agency_code' => $req->input('agencyCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Agency created successfully.";
        }
        else 
        {
            DB::table('agency')->where('agency_id', $agencyId)->update([
                'agency_name' => $req->input('agencyName'),
                'agency_code' => $req->input('agencyCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Agency updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteAgency(Request $req)
    {
        $agencyId = $req->get('agencyId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Agency deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Agency restored successfully.";
        }

        DB::table('agency')->where('agency_id', $agencyId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetAgency(Request $req)
    {
        $agencyId = $req->get('agencyId');
        $agencyList =  DB::table('agency')->where('agency_id', $agencyId)->get();
        return $agencyList;
    }

    public function ITAdminAgencyCheck(Request $req)
    {
        $mesg;
        $agencyCode = $req->get('agencyCode');
        $agencyId = $req->get('agencyId');
        if($agencyId != 0 )
        {
            $agencyList = DB::select("SELECT COUNT(*) AS count FROM agency a
                                      WHERE a.agency_id <> ? AND a.agency_code = ?", [$agencyId, $agencyCode]);
        }
        else 
        {
            $agencyList = DB::select("SELECT COUNT(*) AS count FROM agency a
                                      WHERE a.agency_code = ?", [$agencyCode]);
        }

        return response()->json($agencyList);        
    }

    //End Agency Management

    // Lead Source Management
    public function ITAdminLeadSource()
    {
        if(session('username') != "")
        {
            $leadsourceList = DB::table('leadsource')->get();
            return view('it-admin-shared.it-admin-lead-source', ['leadsourceList' => $leadsourceList]);
        }
        else 
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateLeadSource(Request $req)
    {
        $leadSourceId = $req->input('hdnLeadSourceId');
        $mesg = "";
        if($leadSourceId == 0) 
        {
            DB::table('leadsource')->insert([
                'leadsource_name' => $req->input('leadSourceName'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Lead source created successfully.";
        }
        else 
        {
            DB::table('leadsource')->where('leadsource_id', $leadSourceId)->update([
                'leadsource_name' => $req->input('leadSourceName'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Lead source updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteLeadSource(Request $req)
    {
        $leadSourceId = $req->get('leadSourceId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Lead source deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Lead source restored successfully.";
        }

        DB::table('leadsource')->where('leadsource_id', $leadSourceId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetLeadSource(Request $req)
    {
        $leadSourceId = $req->get('leadSourceId');
        $leadSourceList =  DB::table('leadsource')->where('leadsource_id', $leadSourceId)->get();
        return $leadSourceList;
    }

    //End Lead Source Management

    //Program type Management

    public function ITAdminProgramType()
    {
        if(session('username') != "")
        {
            $programTypeList = DB::table('program_type')->get();
            return view('it-admin-shared.it-admin-program-type', ['programTypeList' => $programTypeList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateProgramType(Request $req)
    {
        $programTypeId = $req->input('hdnProgramTypeId');
        $mesg = "";
        if($programTypeId == 0) 
        {
            DB::table('program_type')->insert([
                'program_type_name' => $req->input('programTypeName'),
                'program_code' => $req->input('programTypeCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Program type created successfully.";
        }
        else 
        {
            DB::table('program_type')->where('program_type_id', $programTypeId)->update([
                'program_type_name' => $req->input('programTypeName'),
                'program_code' => $req->input('programTypeCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Program type updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteProgramType(Request $req)
    {
        $programTypeId = $req->get('programTypeId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Program type deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Program type restored successfully.";
        }

        DB::table('program_type')->where('program_type_id', $programTypeId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetProgramType(Request $req)
    {
        $programTypeId = $req->get('programTypeId');
        $programTypeList =  DB::table('program_type')->where('program_type_id', $programTypeId)->get();
        return $programTypeList;
    }

    public function ITAdminProgramTypeCheck(Request $req)
    {
        $mesg;
        $programTypeCode = $req->get('programTypeCode');
        $programTypeId = $req->get('programTypeId');
        if($programTypeId != 0 )
        {
            $programTypeList = DB::select("SELECT COUNT(*) AS count FROM program_type p
                                      WHERE p.program_type_id <> ? AND p.program_code = ?", [$programTypeId, $programTypeCode]);
        }
        else 
        {
            $programTypeList = DB::select("SELECT COUNT(*) AS count FROM program_type p
                                      WHERE p.program_code = ?", [$programTypeCode]);
        }

        return response()->json($programTypeList);        
    }

    //End Lead Source Management

    //Persona Management

    public function ITAdminPersona()
    {
        if(session('username') != "")
        {
            $personaList = DB::table('persona')->get();
            return view('it-admin-shared.it-admin-persona', ['personaList' => $personaList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreatePersona(Request $req)
    {
        $personaId = $req->input('hdnPersonaId');
        $mesg = "";
        if($personaId == 0) 
        {
            DB::table('persona')->insert([
                'persona_name' => $req->input('personaName'),
                'persona_code' => $req->input('personaCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Persona created successfully.";
        }
        else 
        {
            DB::table('persona')->where('persona_id', $personaId)->update([
                'persona_name' => $req->input('personaName'),
                'persona_code' => $req->input('personaCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Persona updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeletePersona(Request $req)
    {
        $personaId = $req->get('personaId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Persona deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Persona restored successfully.";
        }

        DB::table('persona')->where('persona_id', $personaId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetPersona(Request $req)
    {
        $personaId = $req->get('personaId');
        $personaList =  DB::table('persona')->where('persona_id', $personaId)->get();
        return $personaList;
    }

    public function ITAdminPersonaCheck(Request $req)
    {
        $mesg;
        $personaCode = $req->get('personaCode');
        $personaId = $req->get('personaId');
        if($personaId != 0 )
        {
            $personaList = DB::select("SELECT COUNT(*) AS count FROM persona cp
                                      WHERE cp.persona_id <> ? AND cp.persona_code = ?", [$personaId, $personaCode]);
        }
        else 
        {
            $personaList = DB::select("SELECT COUNT(*) AS count FROM persona cp
                                      WHERE cp.persona_code = ?", [$personaCode]);
        }

        return response()->json($personaList);        
    }

    //End Persona Management

    //Campaign Price Management

    public function ITAdminCampaignPrice()
    {
        if(session('username') != "")
        {
            $campaignPriceList = DB::table('campaign_price')->get();
            return view('it-admin-shared.it-admin-campaign-price', ['campaignPriceList' => $campaignPriceList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateCampaignPrice(Request $req)
    {
        $campaignPriceId = $req->input('hdnCampaignPriceId');
        $mesg = "";
        if($campaignPriceId == 0) 
        {
            DB::table('campaign_price')->insert([
                'campaign_price_name' => $req->input('campaignPriceName'),
                'campaign_price_code' => $req->input('campaignPriceCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Campaign price created successfully.";
        }
        else 
        {
            DB::table('campaign_price')->where('campaign_price_id', $campaignPriceId)->update([
                'campaign_price_name' => $req->input('campaignPriceName'),
                'campaign_price_code' => $req->input('campaignPriceCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Campaign price updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteCampaignPrice(Request $req)
    {
        $campaignPriceId = $req->get('campaignPriceId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Campaign Price deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Campaign Price restored successfully.";
        }

        DB::table('campaign_price')->where('campaign_price_id', $campaignPriceId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetCampaignPrice(Request $req)
    {
        $campaignPriceId = $req->get('campaignPriceId');
        $campaignPriceList =  DB::table('campaign_price')->where('campaign_price_id', $campaignPriceId)->get();
        return $campaignPriceList;
    }

    public function ITAdminCampaignPriceCheck(Request $req)
    {
        $mesg;
        $campaignPriceCode = $req->get('campaignPriceCode');
        $campaignPriceId = $req->get('campaignPriceId');
        if($campaignPriceId != 0 )
        {
            $campaignPriceList = DB::select("SELECT COUNT(*) AS count FROM campaign_price cp
                                      WHERE cp.campaign_price_id <> ? AND cp.campaign_price_code = ?", [$campaignPriceId, $campaignPriceCode]);
        }
        else 
        {
            $campaignPriceList = DB::select("SELECT COUNT(*) AS count FROM campaign_price cp
                                      WHERE cp.campaign_price_code = ?", [$campaignPriceCode]);
        }

        return response()->json($campaignPriceList);        
    }

    //End Camapaign Price Management

    //Headline Management

    public function ITAdminHeadline()
    {
        if(session('username') != "")
        {
            $headlineList = DB::table('headline')->get();
            return view('it-admin-shared.it-admin-headline', ['headlineList' => $headlineList]);
        }
    }

    public function ITAdminCreateHeadline(Request $req)
    {
        $headlineId = $req->input('hdnHeadlineId');
        $mesg = "";
        if($headlineId == 0) 
        {
            DB::table('headline')->insert([
                'headline_name' => $req->input('headlineName'),
                'headline_code' => $req->input('headlineCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Headline created successfully.";
        }
        else 
        {
            DB::table('headline')->where('headline_id', $headlineId)->update([
                'headline_name' => $req->input('headlineName'),
                'headline_code' => $req->input('headlineCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Headline updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteHeadline(Request $req)
    {
        $headlineId = $req->get('headlineId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Headline deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Headline restored successfully.";
        }

        DB::table('headline')->where('headline_id', $headlineId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetHeadline(Request $req)
    {
        $headlineId = $req->get('headlineId');
        $headlineList =  DB::table('headline')->where('headline_id', $headlineId)->get();
        return $headlineList;
    }

    public function ITAdminHeadlineCheck(Request $req)
    {
        $mesg;
        $headlineCode = $req->get('headlineCode');
        $headlineId = $req->get('headlineId');
        if($headlineId != 0 )
        {
            $headlineList = DB::select("SELECT COUNT(*) AS count FROM headline h
                                      WHERE h.headline_id <> ? AND h.headline_code = ?", [$headlineId, $headlineCode]);
        }
        else 
        {
            $headlineList = DB::select("SELECT COUNT(*) AS count FROM headline h
                                      WHERE h.headline_code = ?", [$headlineCode]);
        }

        return response()->json($headlineList);        
    }

    //End Headline Management

    //Target Location Management

    public function ITAdminTargetLocation()
    {
        if(session('username') != "")
        {
            $targetLocationList = DB::table('target_location')->get();
            return view('it-admin-shared.it-admin-target-location', ['targetLocationList' => $targetLocationList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateTargetLocation(Request $req)
    {
        $targetLocationId = $req->input('hdnTargetLocationId');
        $mesg = "";
        if($targetLocationId == 0) 
        {
            DB::table('target_location')->insert([
                'target_location_name' => $req->input('targetLocationName'),
                'target_location_code' => $req->input('targetLocationCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Target location created successfully.";
        }
        else 
        {
            DB::table('target_location')->where('target_location_id', $targetLocationId)->update([
                'target_location_name' => $req->input('targetLocationName'),
                'target_location_code' => $req->input('targetLocationCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Target Location updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteTargetLocation(Request $req)
    {
        $targetLocationId = $req->get('targetLocationId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Target location deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Target location restored successfully.";
        }

        DB::table('target_location')->where('target_location_id', $targetLocationId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetTargetLocation(Request $req)
    {
        $targetLocationId = $req->get('targetLocationId');
        $targetLocationList =  DB::table('target_location')->where('target_location_id', $targetLocationId)->get();
        return $targetLocationList;
    }

    public function ITAdminTargetLocationCheck(Request $req)
    {
        $mesg;
        $targetLocationCode = $req->get('targetLocationCode');
        $targetLocationId = $req->get('targetLocationId');
        if($targetLocationId != 0 )
        {
            $targetLocationList = DB::select("SELECT COUNT(*) AS count FROM target_location t
                                      WHERE t.target_location_id <> ? AND t.target_location_code = ?", [$targetLocationId, $targetLocationCode]);
        }
        else 
        {
            $targetLocationList = DB::select("SELECT COUNT(*) AS count FROM target_location t
                                      WHERE t.target_location_code = ?", [$targetLocationCode]);
        }

        return response()->json($targetLocationList);        
    }

    //End Target Location Management

    //Campaign Type Management

    public function ITAdminCampaignType()
    {
        $campaignTypeList = DB::table('campaign_type')->get();
        return view('it-admin-shared.it-admin-campaign-type', ['campaignTypeList' => $campaignTypeList]);
    }

    public function ITAdminCreateCampaignType(Request $req)
    {
        $campaignTypeId = $req->input('hdnCampaignTypeId');
        $mesg = "";
        if($campaignTypeId == 0) 
        {
            DB::table('campaign_type')->insert([
                'campaign_type_name' => $req->input('campaignTypeName'),
                'campaign_type_code' => $req->input('campaignTypeCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Campaign Type created successfully.";
        }
        else 
        {
            DB::table('campaign_type')->where('campaign_type_id', $campaignTypeId)->update([
                'campaign_type_name' => $req->input('campaignTypeName'),
                'campaign_type_code' => $req->input('campaignTypeCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Campaign Type updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteCampaignType(Request $req)
    {
        $campaignTypeId = $req->get('campaignTypeId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Campaign type deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Campaign type restored successfully.";
        }

        DB::table('campaign_type')->where('campaign_type_id', $campaignTypeId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetCampaignType(Request $req)
    {
        $campaignTypeId = $req->get('campaignTypeId');
        $campaignTypeList =  DB::table('campaign_type')->where('campaign_type_id', $campaignTypeId)->get();
        return $campaignTypeList;
    }

    public function ITAdminCampaignTypeCheck(Request $req)
    {
        $mesg;
        $campaignTypeCode = $req->get('campaignTypeCode');
        $campaignTypeId = $req->get('campaignTypeId');
        if($campaignTypeId != 0 )
        {
            $campaignTypeList = DB::select("SELECT COUNT(*) AS count FROM campaign_type ct
                                      WHERE ct.campaign_type_id <> ? AND ct.campaign_type_code = ?", [$campaignTypeId, $campaignTypeCode]);
        }
        else 
        {
            $campaignTypeList = DB::select("SELECT COUNT(*) AS count FROM campaign_type ct
                                      WHERE ct.campaign_type_code = ?", [$campaignTypeCode]);
        }

        return response()->json($campaignTypeList);        
    }

    //End Campaign Type Management

    //Campaign Size Management

    public function ITAdminCampaignSize()
    {
        if(session('username') != "")
        {
            $campaignSizeList = DB::table('campaign_size')->get();
            return view('it-admin-shared.it-admin-campaign-size', ['campaignSizeList' => $campaignSizeList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateCampaignSize(Request $req)
    {
        $campaignSizeId = $req->input('hdnCampaignSizeId');
        $mesg = "";
        if($campaignSizeId == 0) 
        {
            DB::table('campaign_size')->insert([
                'campaign_size_name' => $req->input('campaignSizeName'),
                'campaign_size_code' => $req->input('campaignSizeCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Campaign size created successfully.";
        }
        else 
        {
            DB::table('campaign_size')->where('campaign_size_id', $campaignSizeId)->update([
                'campaign_size_name' => $req->input('campaignSizeName'),
                'campaign_size_code' => $req->input('campaignSizeCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Campaign size updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteCampaignSize(Request $req)
    {
        $campaignSizeId = $req->get('campaignSizeId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Campaign size deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Campaign size restored successfully.";
        }

        DB::table('campaign_size')->where('campaign_size_id', $campaignSizeId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetCampaignSize(Request $req)
    {
        $campaignSizeId = $req->get('campaignSizeId');
        $campaignSizeList =  DB::table('campaign_size')->where('campaign_size_id', $campaignSizeId)->get();
        return $campaignSizeList;
    }

    public function ITAdminCampaignSizeCheck(Request $req)
    {
        $mesg;
        $campaignSizeCode = $req->get('campaignSizeCode');
        $campaignSizeId = $req->get('campaignSizeId');
        if($campaignSizeId != 0 )
        {
            $campaignSizeList = DB::select("SELECT COUNT(*) AS count FROM campaign_size ct
                                      WHERE ct.campaign_size_id <> ? AND ct.campaign_size_code = ?", [$campaignSizeId, $campaignSizeCode]);
        }
        else 
        {
            $campaignSizeList = DB::select("SELECT COUNT(*) AS count FROM campaign_size ct
                                      WHERE ct.campaign_size_code = ?", [$campaignSizeCode]);
        }

        return response()->json($campaignSizeList);        
    }

    //End Campaign Size Management

    //Campaign Version Management

    public function ITAdminCampaignVersion()
    {
        if(session('username') != "")
        {
            $campaignVersionList = DB::table('campaign_version')->get();
            return view('it-admin-shared.it-admin-campaign-version', ['campaignVersionList' => $campaignVersionList]);
        }
        else 
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateCampaignVersion(Request $req)
    {
        $campaignVersionId = $req->input('hdnCampaignVersionId');
        $mesg = "";
        if($campaignVersionId == 0) 
        {
            DB::table('campaign_version')->insert([
                'campaign_version_name' => $req->input('campaignVersionName'),
                'campaign_version_code' => $req->input('campaignVersionCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Campaign version created successfully.";
        }
        else 
        {
            DB::table('campaign_version')->where('campaign_version_id', $campaignVersionId)->update([
                'campaign_version_name' => $req->input('campaignVersionName'),
                'campaign_version_code' => $req->input('campaignVersionCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Campaign version updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteCampaignVersion(Request $req)
    {
        $campaignVersionId = $req->get('campaignVersionId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Campaign version deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Campaign version restored successfully.";
        }

        DB::table('campaign_version')->where('campaign_version_id', $campaignVersionId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetCampaignVersion(Request $req)
    {
        $campaignVersionId = $req->get('campaignVersionId');
        $campaignVersionList =  DB::table('campaign_Version')->where('campaign_version_id', $campaignVersionId)->get();
        return $campaignVersionList;
    }

    public function ITAdminCampaignVersionCheck(Request $req)
    {
        $mesg;
        $campaignVersionCode = $req->get('campaignVersionCode');
        $campaignVersionId = $req->get('campaignVersionId');
        if($campaignVersionId != 0 )
        {
            $campaignVersionList = DB::select("SELECT COUNT(*) AS count FROM campaign_version ct
                                      WHERE ct.campaign_version_id <> ? AND ct.campaign_version_code = ?", [$campaignVersionId, $campaignVersionCode]);
        }
        else 
        {
            $campaignVersionList = DB::select("SELECT COUNT(*) AS count FROM campaign_version ct
                                      WHERE ct.campaign_version_code = ?", [$campaignVersionCode]);
        }

        return response()->json($campaignVersionList);        
    }

    //End Campaign Version Management

    //Campaign Status Management

    public function ITAdminCampaignStatus()
    {
        if(session('username') != "")
        {
            $campaignStatusList = DB::table('campaign_status')->get();
            return view('it-admin-shared.it-admin-campaign-status', ['campaignStatusList' => $campaignStatusList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateCampaignStatus(Request $req)
    {
        $campaignStatusId = $req->input('hdnCampaignStatusId');
        $mesg = "";
        if($campaignStatusId == 0) 
        {
            DB::table('campaign_status')->insert([
                'campaign_status_name' => $req->input('campaignStatusName'),                
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Campaign status created successfully.";
        }
        else 
        {
            DB::table('campaign_status')->where('campaign_status_id', $campaignStatusId)->update([
                'campaign_status_name' => $req->input('campaignStatusName'),                
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Campaign status updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteCampaignStatus(Request $req)
    {
        $campaignStatusId = $req->get('campaignStatusId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Campaign status deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Campaign status restored successfully.";
        }

        DB::table('campaign_status')->where('campaign_status_id', $campaignStatusId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetCampaignStatus(Request $req)
    {
        $campaignStatusId = $req->get('campaignStatusId');
        $campaignStatusList =  DB::table('campaign_Status')->where('campaign_status_id', $campaignStatusId)->get();
        return $campaignStatusList;
    }

    //End Campaign Status Management

    //Target Segment Management

    public function ITAdminTargetSegment()
    {        
        if(session('username') != "")
        {
            $targetSegmentList = DB::table('target_segment')->get();
            return view('it-admin-shared.it-admin-target-segment', ['targetSegmentList' => $targetSegmentList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateTargetSegment(Request $req)
    {
        $targetSegmentId = $req->input('hdnTargetSegmentId');
        $mesg = "";
        if($targetSegmentId == 0) 
        {
            DB::table('target_segment')->insert([
                'target_segment_name' => $req->input('targetSegmentName'),
                'target_segment_code' => $req->input('targetSegmentCode'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "Target segment created successfully.";
        }
        else 
        {
            DB::table('target_segment')->where('target_segment_id', $targetSegmentId)->update([
                'target_segment_name' => $req->input('targetSegmentName'),
                'target_segment_code' => $req->input('targetSegmentCode'),
                'updated_by' => session('username'),
                'updated_date' => now()
            ]);

            $mesg = "Target segment updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminDeleteTargetSegment(Request $req)
    {
        $targetSegmentId = $req->get('targetSegmentId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "Target segment deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "Target segment restored successfully.";
        }

        DB::table('target_segment')->where('target_segment_id', $targetSegmentId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    public function ITAdminGetTargetSegment(Request $req)
    {
        $targetSegmentId = $req->get('targetSegmentId');
        $targetSegmentList =  DB::table('target_segment')->where('target_segment_id', $targetSegmentId)->get();
        return $targetSegmentList;
    }

    public function ITAdminTargetSegmentCheck(Request $req)
    {
        $mesg;
        $targetSegmentCode = $req->get('targetSegmentCode');
        $targetSegmentId = $req->get('targetSegmentId');
        if($targetSegmentId != 0 )
        {
            $targetSegmentList = DB::select("SELECT COUNT(*) AS count FROM target_segment ct
                                      WHERE ct.target_segment_id <> ? AND ct.target_segment_code = ?", [$targetSegmentId, $targetSegmentCode]);
        }
        else 
        {
            $targetSegmentList = DB::select("SELECT COUNT(*) AS count FROM target_segment ct
                                      WHERE ct.target_segment_code = ?", [$targetSegmentCode]);
        }

        return response()->json($targetSegmentList);        
    }

    //End Target Segment Management

    //User Registration Management
    public function ITAdminUsers()
    {
        if(session('username') != "")
        {
            $userRegistrationList = DB::select("SELECT u.user_id, CONCAT(u.first_name, ' ',u.last_name) AS name, u.email, u.username, r.role_name, u.active 
                                                FROM users u
                                                LEFT JOIN role r ON u.fk_role_id = r.role_id");
            $roleList = DB::table('role')->get();
            return view('it-admin-shared.it-admin-users', ['userRegistrationList' => $userRegistrationList, 'roleList' => $roleList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ITAdminCreateUsers(Request $req)
    {
        $userId = $req->input('hdnUserId');
        $mesg = "";
        //$hashPassword = Hash::make($req->input('password'));
        if($userId == 0) 
        {
            DB::table('users')->insert([
                'first_name' => $req->input('firstName'),
                'last_name' => $req->input('lastName'),
                'email' => $req->input('email'),
                'username' => $req->input('username'),
                'password' => $req->input('password'),
                'first_login' => 1,
                'fk_role_id' => $req->input('role'),
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);
            $mesg = "User created successfully.";
        }
        else 
        {
            DB::table('users')->where('user_id', $userId)->update([
                'first_name' => $req->input('firstName'),
                'last_name' => $req->input('lastName'),
                'email' => $req->input('email'),
                'username' => $req->input('username'),
                'fk_role_id' => $req->input('role'),
                'updated_by' => session('username'),
                'updated_date' => now(),
                'active' => 1
            ]);

            $mesg = "User updated successfully.";
        }

        return redirect()->back()->with('message', $mesg);
    }

    public function ITAdminGetUsers(Request $req)
    {
        $userId = $req->get('userId');
        $userList =  DB::table('users')->where('user_id', $userId)->get();
        return $userList;
    }

    public function ITAdminDeleteUsers(Request $req)
    {
        $userId = $req->get('userId');
        $active = 0;
        $mesg = "";
        if($req->get('identification') == 0) {
            $active = 0;
            $mesg = "User deleted successfully.";
        }
        else {
            $active = 1;
            $mesg = "User restored successfully.";
        }

        DB::table('users')->where('user_id', $userId)->update([
            'active' => $active,
            'updated_by' => session('username'),
            'updated_date' => now()
        ]);

        return response()->json([$mesg]);
    }

    //End User Registration Management

}
