<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportCampaign implements FromCollection
{
    use Exportable;
    
    public function collection()
    {
        $campaignList = DB::select("SELECT c.campaign_id, i.institution_name, pt.program_type_name, cs.course_name, c.campaign_name, c.adset_name, c.adname, c.creative, l.leadsource_name, DATE_FORMAT(c.campaign_date, '%d %M %Y') campaign_date, cps.campaign_status_name FROM campaigns c
                                    LEFT JOIN program_type pt ON c.fk_program_type_id = pt.program_type_id
                                    LEFT JOIN courses cs ON cs.course_id = c.fk_course_id
                                    LEFT JOIN institution i ON cs.fk_institution_id = i.institution_id
                                    LEFT JOIN leadsource l ON c.fk_lead_source_id = l.leadsource_id
                                    LEFT JOIN campaign_status cps ON c.fk_campaign_status_id = cps.campaign_status_id
                                    WHERE c.active = 1");

        $campaignArray[] = array('Institution', 'Program Type','Course', 'Campaign', 'Adset Name', 'Ad Name', 'Creative', 'Lead Source', 'Campagin Date', 'Status');
        foreach($campaignList as $camp){
            $campaignArray[] = array(
                'Institution' => $camp->institution_name,
                'Program Type' => $camp->program_type_name,
                'Course' => $camp->course_name,
                'Campaign' => $camp->campaign_name,
                'Adset Name' => $camp->adset_name,
                'Ad Name' => $camp->adname,
                'Creative' => $camp->creative,
                'Lead Source' => $camp->leadsource_name,
                'Campaign Date' => $camp->campaign_date,
                'Status' => $camp->campaign_status_name
            );
        }    
        return collect($campaignArray);
    }
}
