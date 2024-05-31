@extends('it-admin-shared.it-admin-master')

@section('it-adminContent')
<div class="col-lg-12 mb-4">
      <div class="nav-wrapper position-relative">
        <ul class="nav nav-pills nav-tabs p-1" role="tablist">
          @foreach($institutionList as $institute)
            <li class="nav-item">
              @if($institute->institution_name == "AAFT Online")
                  <a class="nav-link mb-0 px-0 py-2 mx-1 active" data-bs-toggle="tab" onclick="changeInstitution('{{ $institute->institution_name }}')" role="tab">              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @else
                  <a class="nav-link mb-0 px-0 py-2 mx-1" data-bs-toggle="tab" onclick="changeInstitution('{{ $institute->institution_name }}')" role="tab" >              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @endif
            </li>
          @endforeach          
        </ul>
      </div>
    </div>
<div class="row mt-4">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Campaigns</h5>                
                    </div>
                    <!-- <div class="col-lg-6 col-6 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                        <a class="btn btn-primary" id="createCampaignID" href="{{ url('int-campaign-download')}}">
                            <i class="fa fa-file-excel-o" style="font-size: small;">&nbsp; Export</i>                            
                        </a>                
                    </div>
                </div>                   -->
            </div>            
        </div>
        <div class="card-body px-1 pb-2">
            <div class="table-responsive">
            <table class="table table-striped mb-1" id="itAdminCampaignTable">
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
                      <th class="opacity-10">ACTION</th>
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
                          <button type="button" style="background-color: #1AD5984D; color: #119a6d; border:0px #1AD5984D;">{{ $campaign->campaign_status_name }}</button>
                        @elseif($campaign->campaign_status_name == "On Hold")
                          <button type="button" style="background-color: #FFC1074D; color: #ae8919; border:0px #FFC1074D;">{{ $campaign->campaign_status_name }}</button>  
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
                      <td style="padding-left: 15px;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="viewCampaign({{ $campaign->campaign_id }})"><span class="fa fa-eye" style="font-size: small;">&nbsp;View</span></button>                        
                        @if($campaign->camp_edit_id && $campaign->camp_edit_active == 1 && $campaign->camp_edit_request == 1 && $campaign->camp_edit_accept == 0 && $campaign->edit_comments == null) 
                          <button type="button" class="btn btn-sm btn-success" onclick="approveCampaign({{ $campaign->campaign_id }})"><span class="fa fa-thumbs-o-up" style="font-size: small;">&nbsp;Edit Approval</span></button>
                        @elseif($campaign->camp_param_check_id && !$campaign->camp_lead_request_id && ($campaign->camp_integrated == 0 || $campaign->lead_field ))
                            <button type="button" class="btn btn-sm btn-success" onclick="integrateCampaign({{ $campaign->campaign_id }})"><span class="fa fa-compress" style="font-size:small;">&nbsp;Integrate</span></button>
                        
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
<!-- View Campaign Modal -->
<div class="modal fade" id="viewCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <table class="table table-bordered" id="viewTableId">
            
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Approval Campaign Modal -->
<div class="modal fade" id="approvalCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Edit Campaign Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hdnCampId" name="hdnCampId" />
        <p id="descriptionId">Do you wish to approve the edit request?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="buttonId" title="Accept" onclick="acceptCampaign();">Accept</button>
        <button type="button" class="btn btn-danger" title="Reject" onclick="rejectCampaign();">Reject</button>
      </div>
    </div>
  </div>
</div>
<!-- Reject Comment Campaign Modal -->
<div class="modal fade" id="rejectCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Edit Campaign Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hidnCampId" name="hidnCampId" />
        <div class="row">
            <div class="col-md-3">
                <label for="rejectCampComment">Reason</label>
                <span class="text-danger">*</span>
            </div>
            <div class="col-md-9">
                <textarea class="form-control" name="rejectCampComment" id="rejectCampComment" cols="30" rows="5"></textarea>
                <span class="text-danger" id="rejectReasonValId"></span>
            </div>
        </div>
        <div class="row form-group mt-3">
            <div class="col-md-5">
              <button class="btn btn-primary" id="submit" title="submit" onclick="rejectCampaignComment();">Submit</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>                
        </div>
      </div>      
    </div>
  </div>
</div>

<!-- Integrate Campaign Modal -->
<div class="modal fade" id="integrateCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Integrate Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hdnCampaginId" name="hdnCampaignId" />
        <p id="descriptionId">Do you wish to integrate the campaign?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="buttonId" title="Accept" onclick="acceptIntegrateCampaign();">Accept</button>
        <button type="button" class="btn btn-danger" title="Reject" onclick="rejectIntegrateCampaign();">Reject</button>
      </div>
    </div>
  </div>
</div>
<!-- Reject Integrate Campaign Modal -->
<div class="modal fade" id="rejectIntegrateCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Campaign Integration Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hidnCampaignId" name="hidnCampaignId" />
        <div class="row">
            <div class="col-md-3">
                <label for="rejectCampaignComment">Reason</label>
                <span class="text-danger">*</span>
            </div>
            <div class="col-md-9">
                <textarea class="form-control" name="rejectCampaignComment" id="rejectCampComment" cols="30" rows="5"></textarea>
                <span class="text-danger" id="rejectIntegrateReasonValId"></span>
            </div>
        </div>
        <div class="row form-group mt-3">
            <div class="col-md-5">
              <button class="btn btn-primary" id="submitIntegrate" title="submit" onclick="rejIntegrateCampaignComment();">Submit</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>                
        </div>
      </div>      
    </div>
  </div>
</div>

<!-- Lead request Campaign Modal -->
<div class="modal fade" id="leadRequestCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Lead Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hdnLeadCampaginId" name="hdnLeadCampaginId" />
        <p id="descriptionId">Please share the test lead for the campaign?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="buttonId" title="Accept" onclick="submitLeadCampaign();">Submit</button>
        <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
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
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").addClass( "active bg-primary" );
          $("#it-adminLandingPageID").removeClass( "active bg-primary" );
          $("#it-adminHomeID").removeClass( "active bg-primary" );
          $("#it-adminCampaignFormID").removeClass("active bg-primary");
          $("#it-adminLandingPageID").removeClass("active bg-primary");
        $('#itAdminCampaignTable').dataTable();          
    });

    function viewCampaign(id) {
        $.ajax({
          type:'get',
          url: "/it-admin-view-campaign",
          data: {'campaignId' : id},
          success:function(data){
            if(data){
              $("#viewCampaignModal").modal('show');              
              var camp_Table_View = $("#viewTableId").empty();
              for(var i = 0; i < data.campaignDetails.length;i++){
                var camp_Append = "<tr>" +
                                    "<td><b>INSTITUTION</b></td>" + 
                                    "<td>" + data.campaignDetails[i].institution_name + "</td>" +
                                  "</tr>"+
                                  "<tr>" +
                                    "<td><b>PROGRAM TYPE</b></td>" +
                                    "<td>" + data.campaignDetails[i].program_type_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>COURSE</b></td>" + 
                                    "<td>" + data.campaignDetails[i].course_name + "</td>" + 
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN DATE</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_date + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>AGENCY</b></td>" +
                                    "<td>" + data.campaignDetails[i].agency_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>LEADSOURCE</b></td>" +
                                    "<td>" + data.campaignDetails[i].leadsource_name + "</td>" + 
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>PERSONA</b></td>" +
                                    "<td>" + data.campaignDetails[i].persona_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN PRICE</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_price_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>HEADLINE</b></td>" +
                                    "<td>" + data.campaignDetails[i].headline_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" + 
                                    "<td><b>TARGET LOCATION</b></td>" +
                                    "<td>" + data.campaignDetails[i].target_location_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" + 
                                    "<td><b>TARGET SEGMENT</b></td>" +
                                    "<td>" + data.campaignDetails[i].target_segment_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN SIZE</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_size_name + "</td>" +
                                  "<tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN VERSION</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_version_name + "</td>" +
                                  "<tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN TYPE</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_type_name + "</td>" +
                                  "<tr>" +
                                  "<tr>" +
                                    "<td><b>ADSET</b></td>" +
                                    "<td>" + data.campaignDetails[i].adset + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>ADNAME</b></td>" +
                                    "<td>" + data.campaignDetails[i].adname + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CREATIVE</b></td>" +
                                    "<td>" + data.campaignDetails[i].creative + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN LEADSOURCE</b></td>" +
                                    "<td>" + data.campaignDetails[i].camp_leadsource + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN STATUS</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_status_name + "</td>" + 
                                  "</tr>";
                camp_Table_View.append(camp_Append);
              }
            }
          }
      });
    }

    function approveCampaign(id) {
      $("#approvalCampaignModal").modal('show');
      $("#hdnCampId").val(id);
    }

    function rejectCampaign() {
      $("#approvalCampaignModal").modal('hide');
      $("#rejectCampaignModal").modal('show');
      $("#hidnCampId").val($("#hdnCampId").val());
      $("#rejectReasonValId").text('');
      $("#rejectCampComment").val('');
    }

    function acceptCampaign() {
      var campaignId = $("#hdnCampId").val();
      $.ajax({
          type:'get',
          url: "/edit-accept-campaign",
          data: {'campaignId' : campaignId, 'approval': 1, 'comment': ''},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign'}}"}, 2000);
            }
          }          
      });
    }

    function rejectCampaignComment() {      
      var campaignId = $("#hidnCampId").val();
      var comment = $("#rejectCampComment").val();
      $("#rejectReasonValId").text('');
      if(comment == "") {
        $("#rejectReasonValId").empty().text("Please enter the reason");
        $("#submit").submit(function (e) {        
          if($("#rejectReasonValId").text() != ""){
            e.preventDefault();
            return false;
          }
        });   
      }

      else {
        $("#rejectReasonValId").empty().text("");
        $.ajax({
            type:'get',
            url: "/edit-accept-campaign",
            data: {'campaignId' : campaignId, 'comment' : comment, 'approval': 0},
            success:function(data){            
              if(data != ""){              
                $("#rejectCampaignModal").modal('hide');
                $.notify(data, "success");
                setTimeout(() => { window.location.href="{{'it-admin-campaign'}}"}, 2000);
              }
            }          
        });
      }
    }

    function integrateCampaign(id){
      $("#integrateCampaignModal").modal('show');
      $("#hdnCampaginId").val(id);
    }

    function acceptIntegrateCampaign() {
      var campaignId = $("#hdnCampaginId").val();
      $.ajax({
          type:'get',
          url: "/it-admin-integrate-campaign",
          data: {'campaignId' : campaignId, 'approval': 1, 'comment': ''},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign'}}"}, 2000);
            }
          }          
      });
    }

    function rejectIntegrateCampaign() {
      $("#rejectIntegrateCampaignModal").modal('show');
      $("#hidnCampaignId").val($("#hdnCampaignId").val());
    }

    function rejIntegrateCampaignComment(){
      var campaignId = $("#hidnCampaignId").val();
      var rejectReason = $("#camp_integrated").val();
      if(rejectReason == ""){
        $("#rejectIntegrateReasonValId").text("Please enter the reason");
        $("#submitIntegrate").submit(function (e) {        
          if($("#rejectIntegrateReasonValId").text() != ""){
            e.preventDefault();
            return false;
          }
        });
      }
      else {
        $("#rejectIntegrateReasonValId").text("");
        $.ajax({
            type:'get',
            url: "/it-admin-integrate-campaign",
            data: {'campaignId' : campaignId, 'approval': 0, 'comment': ''},
            success:function(data){           
              if(data != ""){              
                $.notify(data, "success");              
                setTimeout(() => { window.location.href="{{'it-admin-campaign'}}"}, 2000);
              }
            }          
        });
      }
    }  
    
    function leadRequestCampaign(campId){
      $("#leadRequestCampaignModal").modal('show');
      $("#hdnLeadCampaginId").val(campId);
    }

    function submitLeadCampaign(){
      debugger;
      var campId = $("#hdnLeadCampaginId").val();
      $.ajax({
          type:'get',
          url: "/it-admin-lead-campaign",
          data: {'campaignId' : campId},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign'}}"}, 2000);
            }
          }          
      });
    }

    function changeInstitution(institution) {      
      $.ajax({
        type:'get',
        url: "/it-admin-change-camp-institution",
        data: {'institution' : institution},          
        success:function(data){
          var campBody = $("#itAdminCampaignTable").empty();         
          
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
                    "<th class='opacity-10'>ACTION</th>" +
                "</tr>" +
                "</thead><tbody>";
            campBody.append(campTheadItem);
            for(var i = 0; i < data.campaignList.length;i++){
              
              var campStatusItem = "";
              if(data.campaignList[i]['campaign_status_name'] == 'Active') {
                campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border: 0px #1AD5984D;'> " + data.campaignList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignList[i]['campaign_status_name'] == 'On Hold') {
                campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignList[i]['campaign_status_name'] == 'New') {
                campStatusItem = "<button type='button' style='background-color: #217EFD4D; color: #217EFD; border:0px #217EFD4D;'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
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
              
              if(data.campaignList[i]['camp_edit_id'] && data.campaignList[i]['camp_edit_active'] == 1 && data.campaignList[i]['camp_edit_request'] == 1 && data.campaignList[i]['camp_edit_accept'] == 0 && data.campaignList[i]['edit_comments'] == null) {
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-success' onclick='approveCampaign("+ data.campaignList[i]['campaign_id'] +")'><span class='fa fa-thumbs-o-up' style='font-size: small;'>&nbsp;Edit Approval</span></button>";
              }
              else if(data.campaignList[i]['camp_param_check_id'] && !data.campaignList[i]['camp_lead_request_id'] && (data.campaignList[i]['camp_integrated'] == 0 || data.campaignList[i]['lead_field'] )){
                campFormButtonItem ="<button type='button' class='btn btn-sm btn-success' onclick='integrateCampaign("+ data.campaignList[i]['campaign_id'] +")'><span class='fa fa-compress' style='font-size:small;'>&nbsp;Integrate</span></button>";
                        
              }
              var campBodyItem = "<tr>" +
                                  "<td style='padding-left: 20px;'><span class='text-primary'>"+ data.campaignList[i]['program_type_name'] +"</span></td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignList[i]['course_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignList[i]['leadsource_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignList[i]['agency_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignList[i]['campaign_name'] +"</td>" +                                  
                                  "<td style='padding-left: 20px;'>"+ campStatusItem + "</td>" +
                                  "<td style='padding-left: 20px;'>"+ campApprovalStatusItem + "</td>" +
                                  "<td style='padding-left: 20px;'>"+ campAcceptComment +"</td>" +
                                  "<td style='padding-left: 20px;'> <button class='btn btn-sm btn-primary' style='margin-right: 5px;' onclick='viewCampaign(" + data.campaignList[i]['campaign_id'] + ")'><span class='fa fa-eye' style='font-size: small;'>&nbsp;View</span></button>"+ 
                                    campFormButtonItem
                                    +"</td>" +
                                  "</tr>";
              campBody.append(campBodyItem);
            }
            campBody.append("</tbody>")
          }
          $('#itAdminCampaignTable').DataTable().destroy();
          $("#itAdminCampaignTable").dataTable();
        }
      });
    }

</script>
@endsection