@extends('admin-shared.admin-master')

@section('adminContent')
<div class="row mt-4">
    @if(session()->has('message'))
      <div class="alert alert-success" id="successMesgID" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="display: none">
        {{ session()->get('message') }}
        <button type="button" onclick="campNotify();" class="btn-close" style="float: right;" aria-label="Close"></button>
      </div>
    @endif
    <div class="col-lg-12 mb-4">
      <div class="nav-wrapper position-relative">
        <ul class="nav nav-pills nav-tabs p-1" role="tablist">
          @foreach($institutionList as $institute)
            <li class="nav-item">
              @if($institute->institution_name == "AAFT Online")
                  <a class="nav-link mb-0 px-0 py-2 mx-1 active" data-bs-toggle="tab" onclick="changeCampInstitution('{{ $institute->institution_name }}')" role="tab">              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @else
                  <a class="nav-link mb-0 px-0 py-2 mx-1" data-bs-toggle="tab" onclick="changeCampInstitution('{{ $institute->institution_name }}')" role="tab" >              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @endif
            </li>
          @endforeach
        </ul>
      </div>
    </div>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4 ">
        <div class="card card-backgroundcolor">
            <div class="card-header pb-0 ">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Campaign</h5> 
                        <input type="hidden" id="hdnInstituteId" value="{{ $instituteId[0] }}" />               
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                      
                </div>                  
            </div>            
        </div>
        <div class="card-body px-1 pb-2">
            <div class="table-responsive">
            <table class="table align-items-center mb-1" id="campaignTable">
                <thead>
                <tr>                    
                    <th class="opacity-10">PROGRAM TYPE</th>
                    <th class="opacity-10">COURSE</th>
                    <th class="opacity-10">LEADSOURCE</th>
                    <th class="opacity-10">AGENCY</th>
                    <th class="opacity-10">CAMPAIGN NAME</th>                                       
                    <th class="opacity-10">STATUS</th>
                    <th class="opacity-10">APPROVAL STATUS</th>
                    <th class="opacity-10">APPROVAL COMMENTS</th>                                    
                    
                </tr>
                </thead>
                <tbody>
                  @foreach($campaignList as $campaign)
                    <tr>                      
                      <td style="padding-left: 15px;"><span class="text-primary">{{ $campaign->program_type_name }}</span></td>
                      <td style="padding-left: 15px;">{{ $campaign->course_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->leadsource_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->agency_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->campaign_name }}</td>                                         
                      <td style="padding-left: 15px;">
                        @if($campaign->campaign_status_name == "Active")
                          <button type="button" style="background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;">{{ $campaign->campaign_status_name }}</button>
                        @elseif($campaign->campaign_status_name == "On Hold")
                          <button type="button" style="background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;">{{ $campaign->campaign_status_name }}</button>  
                        @elseif($campaign->campaign_status_name == "New")
                          <button type="button" style="background-color: #217EFD4D; color: #217EFD; border:0px #217EFD4D;">{{ $campaign->campaign_status_name }}</button>
                        @endif
                      </td>                      
                      <td style="padding-left: 15px;">
                          @if($campaign->camp_accept_id && $campaign->camp_accept == 1 && $campaign->camp_accept_active == 1)
                              Yes 
                          @elseif($campaign->camp_accept == 0 && $campaign->camp_request == 1 && $campaign->comments != null)
                              No
                          @elseif($campaign->camp_accept == 0 && $campaign->camp_request == 1 && $campaign->comments == null)  
                              Approval Pending 
                          @endif
                      </td>
                      <td style="padding-left: 15px;">
                        @if($campaign->camp_accept_active == 1 && $campaign->camp_accept_id)                        
                          {{ $campaign->comments }}
                        @endif                         
                      </td>
                                     
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>        
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.tutorialjinni.com/notify/0.4.2/notify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.slim.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {        
        $("#adminCampaignID").addClass( "active bg-primary bg-gradient" );
          $("#adminLandingPageID").removeClass( "active bg-primary bg-gradient" );
          $("#adminCampaignFormID").removeClass("active bg-primary bg-gradient");
          $("#adminHomeID").removeClass("active bg-primary bg-gradient");
        $('#campaignTable').dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function changeCampInstitution(institution) {      
      $.ajax({
        type:'get',
        url: "/ext-camp-change-institution",
        data: {'institution' : institution},          
        success:function(data){
          var campBody = $("#campaignTable").empty();         
          
          if(data.campaignList != "" && data.campaignList != undefined){
            $("#hdnInstituteId").val(data.institutionId);            
            var campTheadItem = "<thead>" +
                "<tr>" +                    
                    "<th class='opacity-10'>PROGRAM TYPE</th>" + 
                    "<th class='opacity-10'>COURSE</th>" +
                    "<th class='opacity-10'>LEADSOURCE</th>" +
                    "<th class='opacity-10'>AGENCY</th>" +
                    "<th class='opacity-10'>CAMPAIGN NAME</th>" +                                   
                    "<th class='opacity-10'>STATUS</th>" +
                    "<th class='opacity-10'>APPROVAL STATUS</th>" +
                    "<th class='opacity-10'>APPROVAL COMMENTS</th>" +                                  
                    
                "</tr>" +
                "</thead><tbody>";
            campBody.append(campTheadItem);
            for(var i = 0; i < data.campaignList.length;i++){
              
              var campStatusItem = "";
              if(data.campaignList[i]['campaign_status_name'] == 'Active') {
                campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border: 0px #1AD5984D'> " + data.campaignList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignList[i]['campaign_status_name'] == 'On Hold') {
                campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border: 0px #FFC1074D'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignList[i]['campaign_status_name'] == 'New') {
                campStatusItem = "<button type='button' style='background-color: #217EFD4D; color: #217EFD; border: 0px #217EFD4D'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
              }
              
              var campApprovalStatusItem = "";
              if(data.campaignList[i]["camp_accept_id"] && data.campaignList[i]["camp_accept"] == 1){
                campApprovalStatusItem = "Yes"; 
              }
              else if(data.campaignList[i]["camp_accept_id"] && data.campaignList[i]["camp_accept"] == 0 && data.campaignList[i]["comments"] != null){
                campApprovalStatusItem = "No";
              }
              else if(data.campaignList[i]["camp_accept_id"] && data.campaignList[i]["camp_request"] == 1 && data.campaignList[i]["camp_accept"] == 0) { 
                campApprovalStatusItem = "Approval Pending"; 
              }

              var campFormButtonItem = "";
              var campAcceptComment = data.campaignList[i]['comments'] == null ? "" : data.campaignList[i]['comments'];
                            
              var campBodyItem = "<tr>" +
                                    "<td style='padding-left: 20px;'><span class='text-primary'>"+ data.campaignList[i]['program_type_name'] +"</span></td>" +
                                    "<td style='padding-left: 20px;'>"+ data.campaignList[i]['course_name'] +"</td>" +
                                    "<td style='padding-left: 20px;'>"+ data.campaignList[i]['leadsource_name'] +"</td>" +
                                    "<td style='padding-left: 20px;'>"+ data.campaignList[i]['agency_name'] +"</td>" +
                                    "<td style='padding-left: 20px;'>"+ data.campaignList[i]['campaign_name'] +"</td>" +                                  
                                    "<td style='padding-left: 20px;'>"+ campStatusItem + "</td>" +
                                    "<td style='padding-left: 20px;'>"+ campApprovalStatusItem + "</td>" +
                                    "<td style='padding-left: 20px;'>"+ campAcceptComment +"</td>" +                                  
                                  "</tr>";
              campBody.append(campBodyItem);
            }
            campBody.append("</tbody>")
          }
          $('#campaignTable').DataTable().destroy();
          $("#campaignTable").dataTable();
        }
      });
    }
</script>
@endsection