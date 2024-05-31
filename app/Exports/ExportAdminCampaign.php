<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportAdminCampaign implements FromCollection
{
    use Exportable;
    
    protected $institutionId; 
    
    public function __construct($institutionId) 
    { 
        $this->institutionId = $institutionId; 
    }
    public function collection()
    {
        $campaignList = DB::select("SELECT c.campaign_id, i.institution_name, pt.program_type_name, c.campaign_name, ls.leadsource_name, 
                                            cs.course_name, cps.campaign_status_name 
                                            FROM campaigns c
                                            LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id 
                                            LEFT JOIN leadsource ls ON c.fk_lead_source_id = ls.leadsource_id
                                            LEFT JOIN courses cs ON c.fk_course_id = cs.course_id
                                            LEFT JOIN institution i ON i.institution_id = cs.fk_institution_id
                                            LEFT JOIN campaign_status cps ON c.fk_campaign_status_id = cps.campaign_status_id
                                            WHERE i.institution_id = ?", [$this->institutionId]);

        $campaignArray[] = array('Institution', 'Program Type', 'Campaign', 'Lead Source', 'Course', 'Status');
        foreach($campaignList as $camp){
        $campaignArray[] = array(
            'Institution' => $camp->institution_name,
            'Program Type' => $camp->program_type_name,
            'Campaign' => $camp->campaign_name,
            'Lead Source' => $camp->leadsource_name,
            'Course' => $camp->course_name,
            'Status' => $camp->campaign_status_name
            );
        }    
        return collect($campaignArray);
    }
}
