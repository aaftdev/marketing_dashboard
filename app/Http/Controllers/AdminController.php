<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportAdminCampaign;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Components\BaseComponent;
use Illuminate\Support\Facades\Password;


class AdminController extends Controller
{
    // public function AdminInstitution()
    // {
    //     if(session('username') != "")
    //     {
    //         $institution_List = DB::select('Select institution_id, institution_name FROM institution WHERE active = 1');
    //         return view('admin-shared.admin-institution', ['institution_List'=>$institution_List]);
    //     }
    //     else 
    //     {
    //         return view('user-login');
    //     }
    // }

    // private function GetCampaignList()
    // {
    //     $campaignList = DB::select("SELECT c.campaign_id, i.institution_name, pt.program_type_name, c.campaign_name, ls.leadsource_name, 
    //                                 cs.course_name, cps.campaign_status_name 
    //                                 FROM campaigns c
    //                                 LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id 
    //                                 LEFT JOIN leadsource ls ON c.fk_lead_source_id = ls.leadsource_id
    //                                 LEFT JOIN courses cs ON c.fk_course_id = cs.course_id
    //                                 LEFT JOIN institution i ON i.institution_id = cs.fk_institution_id
    //                                 LEFT JOIN campaign_status cps ON c.fk_campaign_status_id = cps.campaign_status_id");
    //     return $campaignList;
    // }

    public function AdminHomeInstitution(Request $req)
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
            
            return view('admin-shared.admin-home', compact(['campaignList', 'landingPageList', 'activeCount', 'newCount', 'onHoldCount', 'deleteCount', 'institutionId', 'institutionList', 
                                                            'labels', 'lpCampCount', 'lpLabels', 'lpAgencyCount']));
            
        }
        else
        {
            return view('user-login');
        }
    }

    // public function ChangeAdminHomeInstitution(Request $req)
    // {
    //     if(session('username') != "")
    //     {
    //         $institution = $req->institution;
    //         //$institutionId = DB::table('institution')->where('institution_name', $institution)->value('institution_id');
    //         $campaignList = BaseComponent::CampaignList($institution);
    //         $landingPageList = BaseComponent::ViewLandingPageList($institution);
    //         $activeCount = BaseComponent::CampaignCount("Active", $institution);
                    
    //         $newCount = BaseComponent::CampaignCount("New", $institution);
            
    //         $onHoldCount = BaseComponent::CampaignCount("On Hold", $institution);

    //         $deleteCount = BaseComponent::CampaignCount("Delete", $institution);

    //         $lpStatusChart = BaseComponent::LandingPageStatusChart('AAFT Online');

    //         $lpAgencyChart = BaseComponent::LandingPageAgencyChart('AAFT Online');
            
    //         $lpCampLeadCollect = collect($lpStatusChart)->pluck('lpProgramCount', 'program_type_name');

    //         $labels = $lpCampLeadCollect->keys();
    //         $lpCampCount = $lpCampLeadCollect->values();

    //         $lpAgencyLeadCollect = collect($lpAgencyChart)->pluck('lpCourseCount', 'course_name');
    //         $lpLabels = $lpAgencyLeadCollect->keys();
    //         $lpAgencyCount = $lpAgencyLeadCollect->values();

    //         return response()->json(['campaignList' => $campaignList, 'activeCount' => $activeCount, 'newCount' => $newCount, 
    //                                  'onHoldCount' => $onHoldCount, 'deleteCount' => $deleteCount, 'landingPageList' => $landingPageList,
    //                                  'labels' => $labels, 'lpCampCount' => $lpCampCount, 'lpLabels' => $lpLabels, 'lpAgencyCount' => $lpAgencyCount]);
    //     }
    //     else
    //     {
    //         return view('user-login');
    //     }
    // }

    public function AdminCampaignInstitution()
    {
        if(session('username') != "")
        {
            $campaignList = BaseComponent::CampaignDetails("AAFT Online");
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', "AAFT Online")->pluck('institution_id');
                        
            return view('admin-shared.admin-campaign', ['campaignList' => $campaignList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function AdminCampaignFormInstitution()
    {
        $institutionName = "AAFT Online";
        $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
        $campaignFormList = BaseComponent::CampaignFormDetails($institutionName);
        $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
        return view('admin-shared.admin-campaign-form', ['campaignFormList' => $campaignFormList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
    }
    
    public function AdminLandingPage()
    {        
        $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
        $landingPageList = BaseComponent::ViewLandingPageList('AAFT Online'); 
        $institutionId = DB::table('institution')->where('institution_name', 'AAFT Online')->value('institution_id');
        return view('admin-shared.admin-landing-page', ['landingPageList' => $landingPageList, 'institutionId' => $institutionId, 'institutionList' => $institutionList]);
    }

    public function LoginUser(Request $req)
    {
        $email = $req->input('loginEmail');
        $password = $req->input('loginPassword');
        $userList = DB::select("SELECT u.username, u.first_name, u.last_name, u.email,u.first_login, r.role_name FROM users u
                                        LEFT JOIN role r ON u.fk_role_id = r.role_id
                                        WHERE u.email = ? AND u.PASSWORD = ?", [$email, $password]);
        
        if(count($userList) == 0)
        {
            return redirect()->back()->with("loginMessage", "Invalid username or password.");
        }
        else 
        {
            foreach($userList as $user)
            {                
                session()->put('username', $user->username);
                session()->put('firstName', $user->first_name);
                session()->put('lastName', $user->last_name);
                session()->put('email', $user->email);
                session()->put('roleName', $user->role_name);

                //return $user;
                if($user->first_login == 1)
                {
                    return view('first-login');
                }
                 if($user->role_name == "Admin")
                {
                    return redirect()->action([AdminController::class, 'AdminHomeInstitution']);
                }
                else if($user->role_name == "IT Admin")
                {
                    return redirect()->action([ITAdminController::class, 'ITAdminHome']);
                }
                else if($user->role_name == "External Marketing")
                {
                    return redirect()->action([ExtHomeController::class, 'Index']);
                }
                else if($user->role_name == "Internal Marketing")
                {
                    return redirect()->action([IntHomeController::class, 'InternalIndex']);
                }
                else 
                {
                    return redirect()->action([PostController::class, 'index']);
                }
            }
        }
    }

    public function LogoutUser()
    {        
        session()->flush();
        return view('logged-off');
    }

    public function ChangePassword( Request $req)
    {
        $email = $req->input('loginEmail');
        $password = $req->input('newPassword');
        DB::table('users')->where('email', $email)->update(['password' => $password, 'first_login' => 0]);
        return view('user-login');
    }

    public function ForgotPassword(Request $req)
    {
        $email = $req->get('email');
        $status = Password::sendResetLink(
            $email
        );

        if($status == Password::RESET_LINK_SENT){

        }

    }

}
