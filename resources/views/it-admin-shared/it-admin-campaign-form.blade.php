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
                        <h5>Campaign Form</h5>                
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
                  @foreach($campaignFormList as $campaign)
                    <tr>                      
                      <td style="padding-left: 15px;"><span class="text-primary">{{ $campaign->program_type_name }}</span></td>
                      <td style="padding-left: 15px;">{{ $campaign->course_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->leadsource_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->agency_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->campaign_form_name }}</td>                                         
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
                          @if($campaign->camp_form_accept_id && $campaign->camp_form_accept == 1 && $campaign->camp_form_accept_active == 1)
                              Yes 
                          @elseif($campaign->camp_form_accept == 0 && $campaign->camp_form_request == 1 && $campaign->camp_form_comments != null)
                              No
                          @elseif($campaign->camp_form_accept == 0 && $campaign->camp_form_request == 1 && $campaign->camp_form_comments == null)  
                              Approval Pending 
                          @endif
                      </td>
                      <td style="padding-left: 15px;">
                        @if($campaign->camp_form_accept_active == 1 && $campaign->camp_form_accept_id)                        
                          {{ $campaign->camp_form_comments }}
                        @endif                         
                      </td>                                     
                      <td style="padding-left: 15px;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="viewCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-eye" style="font-size: small;">&nbsp;View</span></button>                        
                        @if($campaign->camp_form_edit_id && $campaign->Edit_Active == 1 && $campaign->camp_form_edit_request == 1 && $campaign->camp_form_edit_accept == 0) 
                          <button type="button" class="btn btn-sm btn-success" onclick="approveCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-thumbs-o-up" style="font-size: small;">&nbsp;Edit Approve</span></button>
                        @elseif($campaign->camp_form_param_check_id && !$campaign->lead_request_id && ($campaign->form_integrated == 0 || $campaign->lead_field ))
                            <button type="button" class="btn btn-sm btn-success" onclick="integrateCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-compress" style="font-size:small;">&nbsp;Integrate</span></button>
                        @elseif($campaign->campaign_form_id && !$campaign->lead_request_id && $campaign->camp_form_param_check_id)
                            <button type="button" class="btn btn-sm btn-warning" onclick="leadRequestCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-users" style="font-size:small;">&nbsp;Lead Request</span></button>                        
                        @elseif($campaign->camp_form_lead_id && $campaign->campaign_form_lead_active == 1 && $campaign->lead_verify == 0)
                          <button type="button" class="btn btn-sm btn-warning" onclick="leadVerifyCampaign('{{ $campaign->camp_form_lead_id }}', '{{ $campaign->lead_email}}', '{{ $campaign->lead_phone }} ')"><span class="fa fa-users" style="font-size:small;">&nbsp;Lead Verify</span></button>
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
        <h5 class="modal-title" id="modalTitleId">Campaign Form</h5>
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
        <h5 class="modal-title" id="modalTitleId">Edit Campaign Form Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hdnCampId" name="hdnCampId" />
        <p id="descriptionId">Do you wish to approve edit request for campaing form?</p>
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

<!-- Lead Verify Campaign Modal -->
<div class="modal fade" id="leadVerifyCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Lead Verify</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hdnLeadId" name="hdnLeadId" />
        <p id="leadDescriptionId"></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="buttonId" title="Accept" onclick="acceptLead();">Accept</button>
        <button type="button" class="btn btn-danger" title="Reject" onclick="rejectLead();">Reject</button>
      </div>
    </div>
  </div>
</div>

<!-- Reject Lead Modal -->
<div class="modal fade" id="rejectLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Lead Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hidnLeadId" name="hidnLeadId" />
        <div class="row">
            <div class="col-md-3">
                <label for="rejectLeadComment">Reason</label>
                <span class="text-danger">*</span>
            </div>
            <div class="col-md-9">
                <textarea class="form-control" name="rejectLeadComment" id="rejectLeadComment" cols="30" rows="5"></textarea>
                <span class="text-danger" id="rejectLeadReasonValId"></span>
            </div>
        </div>
        <div class="row form-group mt-3">
            <div class="col-md-5">
              <button class="btn btn-primary" id="submitLead" title="submit" onclick="rejLeadComment();">Submit</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>                
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
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").removeClass( "active bg-primary" );
          $("#it-adminLandingPageID").removeClass( "active bg-primary" );
          $("#it-adminHomeID").removeClass( "active bg-primary" );
          $("#it-adminCampaignFormID").addClass("active bg-primary");
          $("#it-adminLandingPageID").removeClass("active bg-primary");
        $('#itAdminCampaignTable').dataTable();   
        $("#mainHeadingId").empty().html("Marketing Dashboard");       
    });

    function viewCampaign(id) {      
        $.ajax({
          type:'get',
          url: "/it-admin-view-camp-form",
          data: {'campaignId' : id},
          success:function(data){
            if(data){
              $("#viewCampaignModal").modal('show');              
              var camp_Table_View = $("#viewTableId").empty();
              debugger;
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
                                    "<td><b>CAMPAIGN FORM</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_form_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>Key Name</b></td>" +
                                    "<td>" + data.campaignDetails[i].form_key + "</td>" + 
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN DATE</b></td>" +
                                    "<td>" + data.campaignDetails[i].campaign_form_date + "</td>" +
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
          url: "/it-admin-edit-campaign-form",
          data: {'campaignId' : campaignId, 'approval': 1, 'comment': ''},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
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
            url: "/it-admin-edit-campaign-form",
            data: {'campaignId' : campaignId, 'comment' : comment, 'approval': 0},
            success:function(data){            
              if(data != ""){              
                $("#rejectCampaignModal").modal('hide');
                $.notify(data, "success");
                setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
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
          url: "/it-admin-integrate-campaign-form",
          data: {'campaignId' : campaignId, 'approval': 1, 'comment': ''},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
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
            url: "/it-admin-integrate-campaign-form",
            data: {'campaignId' : campaignId, 'approval': 0, 'comment': ''},
            success:function(data){           
              if(data != ""){              
                $.notify(data, "success");              
                setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
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
      
      var campId = $("#hdnLeadCampaginId").val();
      $.ajax({
          type:'get',
          url: "/it-admin-lead-campaign-form",
          data: {'campaignId' : campId},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
            }
          }          
      });
    }

    function leadVerifyCampaign(leadId, email, phone) {
      
      $("#leadVerifyCampaignModal").modal('show');
      $("#hdnLeadId").val(leadId);      
      $("#leadDescriptionId").text("Do confirm whether the lead email: '" + email + "' or phone number: '" + phone + "' is integrated in the CRM ?");
    }

    function acceptLead() {
      var leadId = $("#hdnLeadId").val();
      $.ajax({
          type:'get',
          url: "/it-admin-lead-verify-campaign-form",
          data: {'leadId' : leadId, 'approval': 1, 'comment': ''},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
            }
          }          
      });
    }

    function rejectLead() {
      $("#rejectLeadModal").modal('show');
      $("#hidnLeadId").val($("#hdnLeadId").val());
    }

    function rejLeadComment(){
      var leadId = $("#hidnLeadId").val();
      var rejectReason = $("#rejectLeadComment").val();
      if(rejectReason == ""){
        $("#rejectLeadReasonValId").text("Please enter the reason");
        $("#submitLead").submit(function (e) {        
          if($("#rejectLeadReasonValId").text() != ""){
            e.preventDefault();
            return false;
          }
        });
      }
      else {
        $("#rejectIntegrateReasonValId").text("");
        $.ajax({
            type:'get',
            url: "/it-admin-lead-verify-campaign-form",
            data: {'leadId' : leadId, 'approval': 0, 'comment': ''},
            success:function(data){           
              if(data != ""){              
                $.notify(data, "success");              
                setTimeout(() => { window.location.href="{{'it-admin-campaign-form'}}"}, 2000);
              }
            }          
        });
      }
    }
    
    function changeInstitution(institution) {
      
      $.ajax({
        type:'get',
        url: "/it-admin-camp-form-change-institution",
        data: {'institution' : institution},          
        success:function(data){          
          var campBody = $("#itAdminCampaignTable").empty();         
          
          if(data.campaignFormList != "" && data.campaignFormList != undefined){
            $("#hdnInstitutionId").val(data.institutionId);            
            var campTheadItem = "<thead>" +
                "<tr>" +                    
                    "<th class='opacity-10'>PROGRAM TYPE</th>" + 
                    "<th class='opacity-10'>COURSE</th>" +
                    "<th class='opacity-10'>LEADSOURCE</th>" +
                    "<th class='opacity-10'>AGENCY</th>" +
                    "<th class='opacity-10'>CAMPAIGN FORM NAME</th>" +                                   
                    "<th class='opacity-10'>STATUS</th>" +
                    "<th class='opacity-10'>APPROVAL STATUS</th>" +
                    "<th class='opacity-10'>APPROVAL COMMENTS</th>" +                                    
                    "<th class='opacity-10'>ACTION</th>" +
                "</tr>" +
                "</thead><tbody>";
            campBody.append(campTheadItem);
            for(var i = 0; i < data.campaignFormList.length;i++){
              
              var campStatusItem = "";
              if(data.campaignFormList[i]['campaign_status_name'] == 'Active') {
                campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;'> " + data.campaignFormList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignFormList[i]['campaign_status_name'] == 'On Hold') {
                campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;'>" + data.campaignFormList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignFormList[i]['campaign_status_name'] == 'New') {
                campStatusItem = "<button type='button' style='background-color: #217EFD4D; color: #217EFD; border:0px #217EFD4D;'>" + data.campaignFormList[i]['campaign_status_name'] + "</button>";
              }
              
              var campApprovalStatusItem = "";
              if(data.campaignFormList[i]["camp_form_accept_id"] && data.campaignFormList[i]["camp_form_accept"] == 1){
                campApprovalStatusItem = "Yes"; 
              }
              else if(data.campaignFormList[i]["camp_form_accept_id"] && data.campaignFormList[i]["camp_form_accept"] == 0 && data.campaignFormList[i]["camp_form_comments"] != null){
                campApprovalStatusItem = "No";
              }
              else if(data.campaignFormList[i]["camp_form_accept_id"] && data.campaignFormList[i]["camp_form_request"] == 1 && data.campaignFormList[i]["camp_form_accept"] == 0) { 
                campApprovalStatusItem = "Approval Pending"; 
              }

              var campFormButtonItem = "";
              
              if(data.campaignFormList[i]['camp_form_edit_id'] && data.campaignFormList[i]['Edit_Active'] == 1 && data.campaignFormList[i]['camp_form_edit_request'] == 1 && data.campaignFormList[i]['camp_form_edit_accept'] == 0){ 
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-success' onclick='approveCampaign("+ data.campaignFormList[i]['campaign_form_id'] +")'><span class='fa fa-thumbs-o-up' style='font-size: small;'>&nbsp;Edit Approve</span></button>";
              }          
              else if(data.campaignFormList[i]['camp_form_param_check_id'] && !data.campaignFormList[i]['lead_request_id'] && (data.campaignFormList[i]['form_integrated'] == 0 ||data.campaignFormList[i]['lead_field'] )){
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-success' onclick='integrateCampaign("+ data.campaignFormList[i]['campaign_form_id'] +")'><span class='fa fa-compress' style='font-size:small;'>&nbsp;Integrate</span></button>";
              }
              else if(data.campaignFormList[i]['campaign_form_id'] && !data.campaignFormList[i]['lead_request_id'] && data.campaignFormList[i]['camp_form_param_check_id']) {
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-warning' onclick='leadRequestCampaign("+ data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-users' style='font-size:small;'>&nbsp;Lead Request</span></button>";                        
              }
              else if(data.campaignFormList[i]['camp_form_lead_id'] && data.campaignFormList[i]['campaign_form_lead_active'] == 1 && data.campaignFormList[i]['lead_verify'] == 0){
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-warning' onclick='leadVerifyCampaign("+ data.campaignFormList[i]['camp_form_lead_id'] +", " + data.campaignFormList[i]['lead_email'] +"," + data.campaignFormList[i]['lead_phone'] + ")'><span class='fa fa-users' style='font-size:small;'>&nbsp;Lead Verify</span></button>";
              }

              var campFormComments = data.campaignFormList[i]['camp_form_comments'] == null ? "" : data.campaignFormList[i]['camp_form_comments'];
              var campBodyItem = "<tr>" +
                                  "<td style='padding-left: 20px;'><span class='text-primary'>"+ data.campaignFormList[i]['program_type_name'] +"</span></td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['course_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['leadsource_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['agency_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['campaign_form_name'] +"</td>" +                                  
                                  "<td style='padding-left: 20px;'>"+ campStatusItem + "</td>" +
                                  "<td style='padding-left: 20px;'>"+ campApprovalStatusItem + "</td>" +
                                  "<td style='padding-left: 20px;'>"+ campFormComments  +"</td>" +
                                  "<td style='padding-left: 20px;'> <button class='btn btn-sm btn-primary' style='margin-right: 5px;' onclick='viewCampaignForm(" + data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-eye' style='font-size: small;'>&nbsp;View</span></button>"+ 
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