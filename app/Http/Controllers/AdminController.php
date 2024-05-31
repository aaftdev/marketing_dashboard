<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportAdminCampaign;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class AdminController extends Controller
{
    public function AdminInstitution()
    {
        if(session('username') != "")
        {
            $institution_List = DB::select('Select institution_id, institution_name FROM institution WHERE active = 1');
            return view('admin-shared.admin-institution', ['institution_List'=>$institution_List]);
        }
        else 
        {
            return view('user-login');
        }
    }

    private function GetCampaignList()
    {
        $campaignList = DB::select("SELECT c.campaign_id, i.institution_name, pt.program_type_name, c.campaign_name, ls.leadsource_name, 
                                    cs.course_name, cps.campaign_status_name 
                                    FROM campaigns c
                                    LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id 
                                    LEFT JOIN leadsource ls ON c.fk_lead_source_id = ls.leadsource_id
                                    LEFT JOIN courses cs ON c.fk_course_id = cs.course_id
                                    LEFT JOIN institution i ON i.institution_id = cs.fk_institution_id
                                    LEFT JOIN campaign_status cps ON c.fk_campaign_status_id = cps.campaign_status_id");
        return $campaignList;
    }

    public function AdminHomeInstitution(Request $req)
    {
        if(session('username') != "")
        {
            //session()->put('institutionId', $req->get('institutionId'));                
            $campaignList = DB::select("SELECT c.campaign_id, i.institution_name, pt.program_type_name, c.campaign_name, ls.leadsource_name, 
                                        cs.course_name, cps.campaign_status_name, c.active 
                                        FROM campaigns c
                                        LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id 
                                        LEFT JOIN leadsource ls ON c.fk_lead_source_id = ls.leadsource_id
                                        LEFT JOIN courses cs ON c.fk_course_id = cs.course_id
                                        LEFT JOIN institution i ON i.institution_id = cs.fk_institution_id
                                        LEFT JOIN campaign_status cps ON c.fk_campaign_status_id = cps.campaign_status_id
                                        WHERE c.active = 1 ORDER BY c.created_by DESC LIMIT 5");

            $campaignLeadCount = DB::select("SELECT l.leadsource_name as `Leadsource_Name`, COUNT(c.campaign_id) AS `Campaign_Count` FROM campaigns c
                                                LEFT JOIN leadsource l ON c.fk_lead_source_id = l.leadsource_id
                                                LEFT JOIN courses cs ON c.fk_course_id = cs.course_id
                                                LEFT JOIN institution i ON i.institution_id = cs.fk_institution_id
                                                WHERE c.active = 1                                            
                                                GROUP BY l.leadsource_id, l.leadsource_name");
            
            $campaignLeadCollect = collect($campaignLeadCount)->pluck('Campaign_Count', 'Leadsource_Name');
            
            $labels = $campaignLeadCollect->keys();
            $leadCount = $campaignLeadCollect->values();
            
            return view('admin-shared.admin-home', compact(['campaignList', 'labels', 'leadCount']));
        }
        else
        {
            return view('user-login');
        }
    }

    public function AdminCampaignInstitution()
    {
        if(session('username') != "")
        {
            $institutionList = DB::select('Select institution_id, institution_name FROM institution WHERE active = 1');
            return view('admin-shared.admin-campaign', compact(['institutionList']));
        }
        else
        {
            return view('user-login');
        }
    }

    public function AdminCampaignListInstitution(Request $req)
    {
        $institutionId = $req->get('institutionId');        
        $campaignList = DB::select("SELECT c.campaign_id, i.institution_name, pt.program_type_name, c.campaign_name, ls.leadsource_name, 
                                            cs.course_name, cps.campaign_status_name 
                                            FROM campaigns c
                                            LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id 
                                            LEFT JOIN leadsource ls ON c.fk_lead_source_id = ls.leadsource_id
                                            LEFT JOIN courses cs ON c.fk_course_id = cs.course_id
                                            LEFT JOIN institution i ON i.institution_id = cs.fk_institution_id
                                            LEFT JOIN campaign_status cps ON c.fk_campaign_status_id = cps.campaign_status_id
                                            WHERE i.institution_id = ?", [$institutionId]);
        
        return response()->json(['campaignList' => $campaignList]);
    }
    
    public function AdminCampaignDownload(Request $req)
    {        
        $fileName = "campaign - " . date("Y-m-d") . ".xlsx";
        return Excel::download(new ExportAdminCampaign($req->get('institutionId')), $fileName);
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
