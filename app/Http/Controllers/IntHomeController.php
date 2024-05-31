<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\ExportCampaign;
use App\Campaigns;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Components\BaseComponent;
class IntHomeController extends Controller
{
    public function InternalIndex() 
    {
        if(session('username'))
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
            
            return view('int-marketing-shared.int-home', compact(['campaignList', 'landingPageList', 'institutionId', 'institutionList', 
                                                            'lpCamplabels', 'lpCampCount', 'labels', 'leadCount']));
        }
        else 
        {
            return view('user-login');
        }
    }

    public function InternalChangeInstitution(Request $req)
    {
        if(session('username'))
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

    public function InternalCampaign() 
    {
        if(session('username') != "")
        {
            $institutionName = "AAFT Online";
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignDetails("AAFT Online");
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            return view('int-marketing-shared.int-campaign', compact(['campaignList', 'instituteId', 'institutionList']));
        }
        else
        {
            return view('user-login');
        }
    }

    public function InternalChangeInstitutionCampaign(Request $req)
    {
        if(session('username') != "")
        {
            $institutionName = $req->institution;
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignList = BaseComponent::CampaignDetails($institutionName);
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            return response()->json(['campaignList' => $campaignList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    }

    public function ViewCampaign(Request $req)
    {
        $campaignId = $req->campaignId;
        $campaignDetails = BaseComponent::ViewCampaignDetails($campaignId);

        return response()->json(["campaignDetails" => $campaignDetails]);
    }

    public function DownloadCampaign()
    {
        $fileName = "campaign - " . date("Y-m-d") . ".xlsx";
        return Excel::download(new ExportCampaign(), $fileName);
    }

    public function AcceptCampaign(Request $req)
    {
        $campaignId = $req->campaignId;
        $comments = $req->comment;
        $msg = $req->approval == 1 ? "Campaign approved successfully." : "Campaign rejected successfully.";
        $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
        DB::table('campaign_accept')->where('fk_campaign_id', $campaignId)->update([
            'fk_campaign_id' => $campaignId, 
            'fk_user_id' => $userId->user_id, 
            'camp_accept' => $req->approval == 1 ? 1 : 0,
            'comments' => $comments,
            'updated_by' => session("username"),
            'updated_date' => now(), 
            'active' => 1]);
        
        return response()->json([$msg]);
    }

    public function InternalCampaignForm()
    {
        if(session('username') != "")
        {
            $institutionName = "AAFT Online";
            $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
            $campaignFormList = BaseComponent::CampaignFormDetails($institutionName);
            $instituteId = DB::table('institution')->select('institution_id')->where('institution_name', '=', $institutionName)->pluck('institution_id');
            return view('int-marketing-shared.int-campaign-form', ['campaignFormList' => $campaignFormList, 'instituteId' => $instituteId, 'institutionList' => $institutionList]);
        }
        else
        {
            return view('user-login');
        }
    } 

    public function InternalChangeCampFormInstitution(Request $req)
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

    public function AcceptCampaignForm(Request $req)
    {
        $campaignId = $req->campaignId;
        $comments = $req->comment;
        $msg = $req->approval == 1 ? "Campaign approved successfully." : "Campaign rejected successfully.";
        $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
        DB::table('campaign_form_accept')->where('fk_campaign_form_id', $campaignId)->update([
            'fk_user_id' => $userId->user_id, 
            'camp_form_accept' => $req->approval == 1 ? 1 : 0,
            'camp_form_comments' => $comments, 
            'updated_by' => session("username"),
            'updated_date' => now(), 
            'active' => 1]);
        
        return response()->json([$msg]);
    }

    public function InternalLandingPage()
    {
        $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
        $landingPageList = BaseComponent::ViewLandingPageList('AAFT Online'); 
        $institutionId = DB::table('institution')->where('institution_name', 'AAFT Online')->value('institution_id');
        return view('int-marketing-shared.int-landing-page', ['landingPageList' => $landingPageList, 'institutionId' => $institutionId, 'institutionList' => $institutionList]);
    }

    public function AcceptLpCampForm(Request $req)
    {
        $campaignId = $req->campaignId;
        $comments = $req->comment;
        $msg = $req->approval == 1 ? "Landing page campaign approved successfully." : "Landing page campaign rejected successfully.";
        $userId = DB::table('users')->select('user_id')->where('username', '=', session('username'))->first();
        DB::table('landing_page_campaign_accept')->where('fk_lp_camp_id', $campaignId)->update([
            'fk_user_id' => $userId->user_id, 
            'lp_accept' => $req->approval == 1 ? 1 : 0,
            'lp_comments' => $comments,
            'updated_by' => session("username"),
            'updated_date' => now(), 
            'active' => 1]);
        
        return response()->json([$msg]);
    }

    public function GetLandingPage($lpId)
    {
        $landingPageId = $lpId;
        
        $landingPageList = DB::select("SELECT landing_page_id, fk_course_id, title, description, assignee, assigner, fk_development_id, fk_issue_id, fk_priority_id, fk_lp_status_id, i.institution_id
                                        FROM landing_page lp 
                                        LEFT JOIN courses c ON c.course_id = lp.fk_course_id
										LEFT JOIN institution i ON i.institution_id = c.fk_institution_id
                                        WHERE landing_page_id = ?", [$landingPageId]);
        
        $landingPageCommentList = DB::select("SELECT lp_comment_id, lp_comment FROM landing_page_comments WHERE fk_landing_page_id = ?", [$landingPageId]);
        $landingFileList = DB::select("SELECT lp_file_id, file_name, file_path FROM landing_page_file WHERE fk_lp_id = ?", [$landingPageId]);
        
        $institutionList = DB::select("SELECT institution_id, institution_name FROM institution WHERE active = 1");
        $assigneeList = DB::select("SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS assignee FROM users u
                                    LEFT JOIN role r ON u.fk_role_id = r.role_id
                                    WHERE u.ACTIVE  = 1 AND (r.role_name = 'Internal Marketing' OR r.role_name = 'IT Admin' OR r.role_name = 'IT')");
        
        $developmentTypeList = DB::select("SELECT development_type_id, development_type_name FROM development_type WHERE active = 1");
        $issueList = DB::select("SELECT issue_id, issue_name FROM issue WHERE active = 1");
        $priorityList = DB::select("SELECT priority_id, priority_name FROM priority WHERE active = 1");
        $statusList = DB::select("SELECT lp_status_id, lp_status FROM landing_page_status WHERE active = 1");
        return view('int-marketing-shared.int-create-landing-page', compact(["institutionList", "landingPageList", "assigneeList",
                                 "developmentTypeList", "issueList", "priorityList", "statusList", "landingPageId", "landingPageCommentList", "landingFileList"]));
    }

    public function GetLandingPageCourses(Request $req)
    {
        $institutionId = $req->institutionId;
        $courseList = DB::select("SELECT course_id, course_name FROM courses WHERE fk_institution_id = ? AND active = 1", [$institutionId]);
        return response()->json(["courseList" => $courseList]);
    }

    public function StoreLandingPage(Request $req)
    {
       $institutionId = $req->landing_page_institution;
       $courseId = $req->landing_page_course;
       $title = $req->landing_page_title;
       $description = $req->description;
       $assignee = $req->assignee;
       $assigner = $req->assigner;
       $developmentType = $req->developmentType;
       $comments = $req->comments;
       $landingPageId = $req->landing_page_id;
       $issue = $req->issue == "" ? DB::table('issue')->where('issue_name', '=', "Task")->pluck('issue_id') : $req->issue;
       $priorityId = $req->priorityId == "" ? DB::table('priority')->where('priority_name', '=', 'Medium')->pluck('priority_id') : $req->priorityId;
       $status = $req->status == "" ? DB::table('landing_page_status')->where('lp_status', '=', "To Do")->pluck('lp_status_id') : $req->status;
       $fileName = "";
       
       try
        {
            if($landingPageId == 0 || $landingPageId == "")
            {         
                DB::table('landing_page')->insert(['fk_course_id' => $courseId, 'title' => $title, 'description' => $description, 'assignee' => $assignee,
                        'fk_development_id' => $developmentType, 'fk_issue_id' => $issue[0], 'fk_priority_id' => $priorityId[0], 'fk_lp_status_id' => $status[0], 'assigner' => $assigner, 'created_by' => session('username'), 
                        'updated_by' => session('username'), 'created_date' => now(), 'updated_date' => now(), 'active' => 1]);
                
                $lpId = DB::getPdo()->lastInsertId();
                if($comments != ""){
                    DB::table('landing_page_comments')->insert(['lp_comment' => $comments, 'fk_landing_page_id' => $lpId, 'created_by' => session("username"),
                                                        'updated_by' => session('username'), 'created_date' => now(), 'updated_date' => now(), 'active' => 1]);
                }

                if ($req->file('attach')) {
                    foreach ($req->file('attach') as $media) {
                        $imageName = $media->getClientOriginalName();
                        $destinationPath = 'public/uploads';
                        $file_Path = $destinationPath . '/' . $imageName;
                        $media->move($destinationPath, $imageName);
                        DB::table('landing_page_file')->insert(['file_name' => $imageName, 'file_path' => $file_Path, 'fk_lp_id' => $lpId, 'fk_user_id' => $assignee,
                                                                    'created_by' => session('username'), 'updated_by' => session('username'), 'created_date' => now(), 
                                                                    'updated_date' => now(), 'active' => 1]);
                            
                    }
                }
                
                return redirect()->route('intLandingPage')->with('message', 'Landing page created successfully.');                            
            }
            else 
            {
                DB::table('landing_page')->where('landing_page_id', $landingPageId)->update(['fk_course_id' => $courseId, 'title' => $title, 'description' => $description, 'assignee' => $assignee,
                        'fk_development_id' => $developmentType, 'fk_issue_id' => $issue[0], 'fk_priority_id' => $priorityId[0], 'fk_lp_status_id' => $status[0], 'assigner' => $assigner,
                        'updated_by' => session('username'), 'updated_date' => now()]);
                
                if($comments != ""){
                    DB::table('landing_page_comments')->where('fk_landing_page_id', $landingPageId)->update(['lp_comment' => $comments, 'updated_by' => session('username'), 'updated_date' => now()]);
                }

                if ($req->file('attach')) {
                    foreach ($req->file('attach') as $media) {
                        $imageName = $media->getClientOriginalName();
                        $destinationPath = 'public/uploads';
                        $file_Path = $destinationPath . '/' . $imageName;
                        $media->move($destinationPath, $imageName);
                        DB::table('landing_page_file')->where('fk_lp_id', $landingPageId)->update(['file_name' => $imageName, 'file_path' => $file_Path, 'updated_by' => session('username')]);
                    }
                }

                return redirect()->route('intLandingPage')->with('message', 'Landing page updated successfully.');                
            }
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', 'Error in creating a task');
        }
    } 
    
    public function IntLPChangeInstitution(Request $req)
    {
        $institution = $req->institution;
        $institutionList = DB::select("SELECT institution_id, institution_name, institution_code FROM institution WHERE active = 1");
        $landingPageList = BaseComponent::ViewLandingPageList($institution); 
        $institutionId = DB::table('institution')->where('institution_name', $institution)->value('institution_id');
        return response()->json(['landingPageList' => $landingPageList, 'institutionId' => $institutionId, 'institutionList' => $institutionList]);
    }
}
