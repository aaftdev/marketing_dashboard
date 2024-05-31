@extends('ext-marketing.ext-master')

@section('content')
<style>
  table.dataTable {
    font-size:14px;
  }
</style>
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
                  <a class="nav-link mb-0 px-0 py-2 mx-1 active" data-bs-toggle="tab" onclick="changeCampFormInstitution('{{ $institute->institution_name }}')" role="tab">              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @else
                  <a class="nav-link mb-0 px-0 py-2 mx-1" data-bs-toggle="tab" onclick="changeCampFormInstitution('{{ $institute->institution_name }}')" role="tab" >              
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
                        <h5>Campaign Form</h5> 
                        <input type="hidden" id="hdnInstitutionId" value="{{ $instituteId[0] }}" />               
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                      <div class="dropdown float-lg-end pe-4">
                          <a class="btn btn-sm btn-primary" id="createCampaignFormID" onclick="createCampaignForm(0);" title="Create">
                              <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                          </a>                
                      </div>
                </div>                  
            </div>            
        </div>
        <div class="card-body px-1 pb-2">
            <div class="table-responsive">
            <table class="table align-items-center mb-1" id="campaignFormTable">
                <thead>
                <tr>                    
                    <th class="opacity-10">PROGRAM TYPE</th>
                    <th class="opacity-10">COURSE</th>
                    <th class="opacity-10">LEADSOURCE</th>
                    <th class="opacity-10">AGENCY</th>
                    <th class="opacity-10">CAMPAIGN FORM NAME</th>                                   
                    <th class="opacity-10">STATUS</th>
                    <th class="opacity-10">APPROVAL STATUS</th>
                    <th class="opacity-10">APPROVAL COMMENTS</th>                                    
                    <th class="opacity-10">ACTION</th>
                </tr>
                </thead>
                <tbody id="campaignFormBody">
                  @foreach($campaignFormList as $campaign)
                    <tr>                      
                      <td style="padding-left: 15px;"><span class="text-primary">{{ $campaign->program_type_name }}</span></td>
                      <td style="padding-left: 15px;">{{ $campaign->course_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->leadsource_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->agency_name }}</td>
                      <td style="padding-left: 15px;">{{ $campaign->campaign_form_name }}</td>
                                            
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
                          @if($campaign->camp_form_accept_id && $campaign->camp_form_accept == 1)
                              Yes 
                          @elseif($campaign->camp_form_accept_id && $campaign->camp_form_accept == 0 && $campaign->camp_form_comments != null)
                              No
                          @elseif($campaign->camp_form_accept_id && $campaign->camp_form_request == 1 && $campaign->camp_form_accept == 0) 
                              Approval Pending 
                          @endif
                      </td>
                      <td style="padding-left: 15px;">                        
                          {{ $campaign->camp_form_comments }}                         
                      </td> 
                                        
                      <td style="padding-left: 15px;">
                        <button class="btn btn-sm btn-primary" onclick="viewCampaignForm({{ $campaign->campaign_form_id }})"><span class="fa fa-eye" style="font-size: small;">&nbsp;View</span></button>
                        @if(($campaign->camp_form_accept_id && $campaign->camp_form_accept == 0 && $campaign->camp_form_accept_active == 1 && !is_null($campaign->camp_form_comments)) || ($campaign->camp_form_edit_request == 1 && $campaign->camp_form_edit_accept == 1 && $campaign->Edit_Active == 1))                        
                          <button type="button" class="btn btn-sm btn-primary" onclick="createCampaignForm({{ $campaign->campaign_form_id }})"><span class="fa fa-pencil" style="font-size: small;">&nbsp;Edit</span></button>
                        @elseif($campaign->camp_form_accept_id && $campaign->camp_form_accept == 1 && $campaign->camp_form_accept_active == 1 && !$campaign->camp_form_param_check_id)
                          <button type="button" class="btn btn-sm btn-info" onclick="parameterCheckCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-sliders" style="font-size: small; color:white;">&nbsp;Parameter</span></button>
                        @elseif($campaign->camp_form_param_check_id && !$campaign->lead_request_id && $campaign->form_integrated == 0)
                          <span>Campaign integration pending</span>
                        @elseif($campaign->lead_request_id && $campaign->camp_lead_request == 1 && $campaign->camp_lead_accept == 0 && $campaign->camp_form_lead_active == 1)
                          <button type="button" class="btn btn-sm btn-warning" onclick="leadRequestCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-users" style="font-size: small; color:white;">&nbsp;Lead Accept</span></button>
                        @elseif($campaign->camp_form_edit_id && $campaign->Edit_Active == 1 && $campaign->camp_form_edit_request == 0)
                          <button type="button" class="btn btn-sm btn-success" onclick="editRequestCampaign({{ $campaign->campaign_form_id }})"><span class="fa fa-pencil-square-o" style="font-size: small; color:white;">&nbsp;Edit Request</span></button>
                        @elseif($campaign->Edit_Active == 1 && $campaign->camp_form_edit_request == 1 && $campaign->camp_form_edit_accept == 0) 
                          <span>Edit approval pending</span>
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
<!-- Create Campaign Form Modal -->
<div class="modal fade" id="createCampaignFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="add-camp-form" id="add-camp-form" method="post" action="{{ url('store-camp-form') }}">
          @csrf
          <input type="hidden" id="hdnCampFormId" name="hdnCampFormId" />
          <div class="row form-group">
            <div class="col-md-3">
              <label class="form-label" for="campaign-institution">Institution</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaign-institution" class="form-control" id="campaign-institution" onchange="getCoursesForm();">                  
              </select>                
              <span id="institution-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="programType">Program Type</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="programType" class="form-control" id="programType">                  
              </select>                
              <span id="programType-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="courses">Courses</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="courses" class="form-control" id="courses">
                <option value="">--Select--</option>
              </select>                
              <span id="courses-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="marketingAgency">Marketing Agency</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="marketingAgency" class="form-control" id="marketingAgency">                  
              </select>                
              <span id="marketingAgency-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="leadSource">Lead Source</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="leadSource" class="form-control" id="leadSource">                  
              </select>                
              <span id="leadSource-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="campaign">Campaign</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaign" class="form-control" id="campaign">                  
              </select>                
              <span id="campaign-error" class="text-danger"></span>
            </div>
          </div>         
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="keyName">Key</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" name="keyName" id="keyName" class="form-control" onchange="checkKeyName();" />                
              <span id="keyName-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="campaignDate">Date</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="date" name="campaignDate" class="form-control" id="campaignDate" />                
              <span id="campaignDate-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2" id="campaignFormStatusDiv" style="display:none;">
            <div class="col-md-3">
              <label class="form-label" for="campaignFormStatusId">Status</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaignFormStatusId" class="form-control" id="campaignFormStatusId">                  
              </select>                
              <span id="campaignFormStatus-error" class="text-danger"></span>
            </div>
          </div>          
          <hr />
          <div class="row form-group mt-2" >
            <div class="col-md-5">              
              <button class="btn btn-sm btn-primary" id="generate" title="Generate" onclick="VerifyCamp();">Generate</button>
              <button data-bs-dismiss="modal" class="btn btn-sm btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Parameter Check Modal -->
<div class="modal fade" id="parameterCheckModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Parameter Check</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="parameter-check-form" name="parameter-check-form" method="post" action="{{ url('parameter-campaign-form') }}">
          @csrf
          <input type="hidden" name="campaignId" id="campaignId" />
          <div class="row form-group">
            <div class="col-md-4 text-end">
              <label class="form-label" for="published">Form published</label>
              <span class="text-danger">*</span>                                        
            </div>
            <div class="col-md-8">
              <input type="checkbox" id="published" name="published" />
              <br />              
              <span id="published-error-form" class="text-danger"></span>                
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4 text-end">
              <label class="form-label" for="course-campaign">Course integrated</label>
              <span class="text-danger">*</span>                            
            </div>
            <div class="col-md-8">
              <input type="checkbox" id="course-campaign" name="course-campaign" /> 
              <br />              
              <span id="course-campaign-error-form" class="text-danger"></span>
            </div>
          </div>
          <div class="row form mt-2">
            <div class="col-md-4 text-end">
              <label class="form-label" for="text-param">Parameter Add</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-8 text-start">
              <label for="yes" style="margin-right: 15px;">Yes</label>
              <input type="radio" class="paramCls" id="text-yes" name="text-param" value="1" onclick="showParameterDiv();" style="margin-right: 20px;">
              <label for="no" style="margin-right: 15px;">No</label>
              <input type="radio" class="paramCls" id="text-no" name="text-param" value="0" onclick="showParameterDiv();">
              <br />
              <span class="text-danger" id="text-param-error"></span>
            </div>            
          </div>            
          <div class="row form mt-2" style="display:none;" id="parameterDiv">
            <div class="col-md-4">
              <label class="form-label" for="parameterId">Enter parameter info</label>
              <span class="text-danger">*</span> 
            </div>
            <div class="col-md-8 ">
              <textarea name="parameterId" id="parameterId" cols="35" rows="5" class="form-control"></textarea>
              <span id="parameter-error" class="text-danger"></span>
            </div>
          </div>                    
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-sm btn-primary" type="submit" id="confirmParameterCheck" title="Confirm" onclick="VerifyParameterForm();">Confirm</button>
              <button data-bs-dismiss="modal" class="btn btn-sm btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Lead Acceptance Modal -->
<div class="modal fade" id="confirmLeadFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="leadTitleId">Lead Generation Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="confirm-lead-form" name="confirm-lead-form" action="{{ url('confirm-lead-camp-form') }}" method="post">
          @csrf
          <input type="hidden" id="hdnLeadCampaignId" name="hdnLeadCampaignId" />
          <div class="row form-group">
            <div class="col-md-4">
              <label class="form-label" for="leadEmailId">Email</label>              
            </div>
            <div class="col-md-8 ">
              <input type="text" name="leadEmailId" id="leadEmailId" class="form-control" />
              <span id="leadEmail-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label class="form-label" for="leadPhoneId">Phone Number</label>              
            </div>
            <div class="col-md-8 ">
              <input type="text" name="leadPhoneId" id="leadPhoneId" class="form-control" />
              <span id="leadPhone-error" class="text-danger"></span>
            </div>
          </div>
          <hr />
          <div class="row form-group mt-2" >
            <div class="col-md-5">              
              <button class="btn btn-sm btn-primary" id="confirmLeadbuttonId" title="Confirm Lead Generation" onclick="confirmLeadGeneration();">Confirm</button>
              <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </form>       
      </div>
        
    </div>
  </div>
</div>

<!-- Edit Request Modal -->
<div class="modal fade" id="editRequestFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Edit Campaign Form Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editCampaignId" name="editCampaignId" />
        <p id="descriptionId">Do you wish to send the request to edit the campaign form? </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-primary" id="buttonId" title="Request" onclick="confirmEditRequest();">Request</button>
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- View Campaign Check Modal -->
<div class="modal fade" id="viewCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Campaign Form Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" style="border: 1px solid black; " id="campaignTableDetails">
          
        </table>
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
        $("#extCampaignID").removeClass("active bg-primary bg-gradient");
        $("#extCampaignHomeID").removeClass("active bg-primary bg-gradient");
        $("#extCampaignFormID").addClass("active bg-primary bg-gradient");
        $('#campaignFormTable').dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaignForm(campFormId) {      
      $("#exampleModalLabel").html("Create New Campaign Form");
        $("#createCampaignFormModal").modal('show');
        $("#institution-error").html(''); 
        $("#programType-error").html(''); 
        $("#keyName-error").html('');
        $("#campaignDate-error").html('');
        $("#courses-error").html(''); 
        $("#marketingAgency-error").html('');
        $("#leadSource-error").html('');
        $("#keyName-error").html('');        
        $("#campaignsId-error").html('');
        $("#campaignDate").val('');
        $("#keyName").val('');
        $.ajax({
            type:'get',
            url: "/create-campaign-form", 
            data: {'institute' : $("#hdnInstitutionId").val(), 'campFormId': campFormId},        
            success:function(data){
                if(data){    
                  $("#hdnCampFormId").val(campFormId);            
                  var institutionId = $("#campaign-institution").empty();
                    institutionId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < Object.keys(data['institution']).length;i++){
                                              
                        if(data.institution[i]['institution_code'] == data.institutionCode){
                          var institution_item_el = '<option selected value="'+ data.institution[i]['institution_code']+'">'+ data.institution[i]['institution_name']+'</option>';
                        }
                        else {
                          var institution_item_el = '<option value="'+ data.institution[i]['institution_code']+'">'+ data.institution[i]['institution_name']+'</option>';
                        }
                        institutionId.append(institution_item_el);
                    }
                    
                    var programTypeId = $("#programType").empty();
                    programTypeId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.programType.length;i++){
                        
                        if(campFormId != 0 && data.programType[i]['program_type_id'] == data.campFormList[0]['fk_program_type_id']){
                          var programType_item_el = '<option selected value="'+ data.programType[i]['program_code'] +'">'+data.programType[i]['program_type_name']+'</option>';
                        } else {
                          var programType_item_el = '<option value="'+ data.programType[i]['program_code'] +'">'+data.programType[i]['program_type_name']+'</option>';
                        }
                        programTypeId.append(programType_item_el);
                    }

                    var marketingAgencyId = $("#marketingAgency").empty();
                    marketingAgencyId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.marketingAgency.length;i++){
                        if(campFormId != 0 && data.marketingAgency[i]['agency_id'] == data.campFormList[0]['fk_agency_id']){
                          var marketingAgency_item_el = '<option selected value="'+ data.marketingAgency[i]['agency_code'] +'">'+data.marketingAgency[i]['agency_name']+'</option>';
                        } else {
                          var marketingAgency_item_el = '<option value="'+ data.marketingAgency[i]['agency_code'] +'">'+data.marketingAgency[i]['agency_name']+'</option>';
                        }
                        marketingAgencyId.append(marketingAgency_item_el);
                    }

                    var leadSourceId = $("#leadSource").empty();
                    leadSourceId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.leadSource.length;i++){
                        if(campFormId != 0 && data.leadSource[i]['leadsource_id'] == data.campFormList[0]['fk_lead_source_id']){
                          var leadSource_item_el = '<option selected value="'+ data.leadSource[i]['leadsource_id'] +'">'+ data.leadSource[i]['leadsource_name']+'</option>';
                        }
                        else {
                          var leadSource_item_el = '<option value="'+ data.leadSource[i]['leadsource_id'] +'">'+ data.leadSource[i]['leadsource_name']+'</option>';
                        }
                        leadSourceId.append(leadSource_item_el);
                    }                   

                    var campaignId = $("#campaign").empty();
                    campaignId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i=0; i < data.campaignList.length;i++){
                        if(campFormId != 0 && data.campaignList[i]['campaign_id'] == data.campFormList[0]['fk_campaign_id']){
                          var campaign_item_el = '<option selected value="'+ data.campaignList[i]['campaign_id'] +'">'+ data.campaignList[i]['campaign_name']+'</option>';
                        } else {
                          var campaign_item_el = '<option value="'+ data.campaignList[i]['campaign_id'] +'">'+ data.campaignList[i]['campaign_name']+'</option>';
                        }
                        campaignId.append(campaign_item_el);  
                    }
                    
                    if(campFormId != 0){
                      
                      var courseId = $("#courses").empty();
                      courseId.append('<option selected="selected" value="">--Select--</option>');
                      for(var i = 0; i < data.courseList.length; i++){
                        if(data.campFormList[0]['fk_course_id'] == data.courseList[i]['course_id']){
                          var course_item_el = '<option selected value="'+ data.courseList[i]['course_code'] +'">'+ data.courseList[i]['course_name']+'</option>';
                        }
                        else {
                          var course_item_el = '<option value="'+ data.courseList[i]['course_code'] +'">'+ data.courseList[i]['course_name']+'</option>';
                        }
                        courseId.append(course_item_el);
                      }
                      $("#campaignDate").val(data.campFormList[0]['campaign_form_date']);
                      $("#exampleModalLabel").html("Edit Campaign Form");
                      $("#generate").html("Update");
                      $("#hdncampaignId").val(campaignId);
                      $("#campaignFormStatusDiv").show();
                      $("#keyName").val(data.campFormList[0]['form_key']);
                      var campaignStatusId = $("#campaignFormStatusId").empty();
                      campaignStatusId.append('<option selected="selected" value="">--Select--</option>');
                      for(var i = 0; i < data.campaignStatus.length; i++){
                        
                        if(data.campFormList[0]['fk_campaign_status_id'] == data.campaignStatus[i]['campaign_status_id']){
                          var campstatus_item_el = '<option selected value="'+ data.campaignStatus[i]['campaign_status_id'] +'">'+ data.campaignStatus[i]['campaign_status_name']+'</option>';
                        }
                        else {
                          var campstatus_item_el = '<option value="'+ data.campaignStatus[i]['campaign_status_id'] +'">'+ data.campaignStatus[i]['campaign_status_name']+'</option>';
                        }
                        campaignStatusId.append(campstatus_item_el);
                      }                      
                    }
                    else {
                      getCourses();
                    }
              }
                
            }
        });
    }

    function VerifyCamp() {      
      
      var institution = $("#campaign-institution").val();
      var programType = $("#programType").val();
      var marketingAgency = $("#marketingAgency").val();
      var leadSource = $("#leadSource").val();
      var keyName = $("#keyName").val();
      var courses = $("#courses").val();
      var campDate = $("#campaignDate").val();
      var campaignStatus = $("#campaignFormStatusId").val();
      var campaign = $("#campaign").val();

      if(institution == "" || institution == "undefined"){
        $("#institution-error").html("Please select an Institution");         
      }
      else {
        $("#institution-error").html(""); 
      }

      if(campaign == "" || campaign == "undefined"){
        $("#campaign-error").html("Please select a Campaign");
      }
      else {
        $("#campaign-error").html("");
      }

      if(programType == "" || programType == "undefined"){
        $("#programType-error").html("Please select a Program Type");        
      }
      else {
        $("#programType-error").html("");
      }
      if(marketingAgency == "" || marketingAgency == "undefined"){
        $("#marketingAgency-error").html("Please select a Marketing Agency");        
      }
      else {
        $("#marketingAgency-error").html("");
      }
      if(leadSource == "" || leadSource == "undefined"){
        $("#leadSource-error").html("Please select a Lead Source");        
      }
      else {
        $("#leadSource-error").html("");
      }
      
      if(courses == "" && institution == ""){
        $("#courses-error").html("Please select an institution first");        
      }
      else {
        $("#courses-error").html("");
      }
      
      if(courses == "" || courses == "undefined"){
        $("#courses-error").html("Please select a Course");        
      }
      else {
        $("#courses-error").html("");
      }
      
      if(campDate == "" || campDate == "undefined"){
        $("#campaignDate-error").html("Please select a Date");        
      }
      else {
        $("#campaignDate-error").html("");
      }      
      
      if(keyName == "" || keyName == "undefined"){
        $("#keyName-error").html("Please enter a Key");
      }
      else {
        $("#keyName-error").html("");
      }
      
      if(campaignStatus == "" || campaignStatus == "undefined"){
        $("#campaignFormStatus-error").html("Please select a Status");
      }
      else {
        $("#campaignFormStatus-error").html("");
      }
      
      $("#add-camp-form").submit(function (e) {
        if($("#institution-error").text() != "" || $("#programType-error").text() != "" || $("#campaignDate-error").text() != ""
        || $("#courses-error").text() != "" || $("#marketingAgency-error").text() != "" || $("#leadSource-error").text() != "" || $("#campaignFormStatus-error").text() != ""){
          e.preventDefault();
          return false;
        }
      }); 
          
    }

    function parameterCheckCampaign(campaignID) {
      
      $("#parameterCheckModal").modal('show');
      $("#campaignId").val(campaignID); 
      $("#published-error-form").text(''); 
      $("#course-campaign-error-form").text('');
      $("#published").prop('checked', false); 
      $("#course-campaign").prop('checked', false);
      $(".paramCls").prop('checked', false);
      $("#parameterId").val('');

    }

    function showParameterDiv(){    
      if($("input[name='text-param']:checked").val() == "1") {
        $("#parameterDiv").show();
      }
      else {
        $("#parameterDiv").hide();
      }
    }

    function VerifyParameterForm() {
      
      if($("#published").prop('checked') == false ){
        $("#published-error-form").text('Please select form is published');
      }
      else {
        $("#published-error-form").text('');
        $("#published").val(true);
      }
      
      if($("#course-campaign").prop('checked') == false ){
        $("#course-campaign-error-form").text('Please select course is integrated');
      }
      else {
        $("#course-campaign-error-form").text('');
        $("#course-campaign").val(true);
      }
      
      // if($(".paramCls").prop('checked'))
      // {
      //   $("#text-param-error").html("");
      // }
      // else {
      //   $("#text-param-error").html("Please select one of the parameters");
      // }

      // if($("#text-no").prop('checked')) {
      //   $("#text-param-error").html("");
      // }
      // else {
      //   $("#text-param-error").html("Please select one of the parameters");
      // }

      if($("input[name='text-param']:checked").val() == "1") {
        if($("#parameterId").val() == "") {
          $("#parameter-error").html("Please enter parameters");
        }
        else {
          $("#parameter-error").html("");
        }
      }
      
      $("#parameter-check-form").submit(function (e) { 
        if ($("#course-campaign-error-form").text() != "" || $("#published-error-form").text() != "" || $("#text-param-error").text() != "" || $("#parameter-error").text() != "") {
          e.preventDefault();
          return false;
        }
      });
    }

    function campNotify() {
      $("#successMesgID").hide();
    }

    function leadRequestCampaign(campaignId) {
      $("#confirmLeadFormModal").modal('show');
      $("#leadEmailId").val('');
      $("#leadPhoneId").val('');
      $("#leadPhone-error").html("");
      $("#hdnLeadCampaignId").val(campaignId);
    }

    function confirmLeadGeneration(){
      debugger;
      var campFormId = $("#hdnLeadCampaignId").val();
      var email = $("#leadEmailId").val();
      var phone = $("#leadPhoneId").val();
      if(email == "" && phone == ""){
        $("#leadPhone-error").html("Please enter email or phone number");
      }
      else {
        $("#leadPhone-error").html("");
      }

      $("#confirm-lead-form").submit(function (e) { 
        if ($("#leadPhone-error").text() != "") {
          e.preventDefault();
          return false;
        }
      });
    }

    function editRequestCampaign(campaignId) {
      $("#editRequestFormModal").modal('show');
      $("#editCampaignId").val(campaignId);          
    }

    function confirmEditRequest(campFormId) {
      var campaignFormId = $("#editCampaignId").val();      
      $.ajax({
          type:'get',
          url: "/confirm-edit-campaign-form",
          data: {'campaignFormId' : campaignFormId},
          success:function(data){
            if(data){
              $.notify(data, "success");
              $("#editRequestFormModal").modal('hide');
              setTimeout(() => {
                window.location.href="{{'campaignForm'}}";
              }, "2000");
              
            }
          }
      });
    }

    function changeCampFormInstitution(institution) {
      
      $.ajax({
        type:'get',
        url: "/ext-camp-form-change-institution",
        data: {'institution' : institution},          
        success:function(data){
          debugger;
          var campBody = $("#campaignFormTable").empty();         
          
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
                campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598;'> " + data.campaignFormList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignFormList[i]['campaign_status_name'] == 'On Hold') {
                campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107;'>" + data.campaignFormList[i]['campaign_status_name'] + "</button>";
              }
              else if (data.campaignFormList[i]['campaign_status_name'] == 'New') {
                campStatusItem = "<button type='button' style='background-color: #217EFD4D; color: #217EFD;'>" + data.campaignFormList[i]['campaign_status_name'] + "</button>";
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
              
              if((data.campaignFormList[i]['camp_form_accept_id'] && data.campaignFormList[i]['camp_form_accept'] == 0 && data.campaignFormList[i]['camp_form_accept_active'] == 1 && !is_null(data.campaignFormList[i]['camp_form_comments'])) || (data.campaignFormList[i]['camp_form_edit_request'] == 1 && data.campaignFormList[i]['camp_form_edit_accept'] == 1 && data.campaignFormList[i]['Edit_Active'] == 1)){                    
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-primary' onclick='createCampaignForm(" + data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-pencil' style='font-size: small;'>&nbsp;Edit</span></button>";
              }
              else if(data.campaignFormList[i]['camp_form_accept_id'] && data.campaignFormList[i]['camp_form_accept'] == 1 && data.campaignFormList[i]['camp_form_accept_active'] == 1 && !data.campaignFormList[i]['camp_form_param_check_id']) {
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-info' onclick='parameterCheckCampaign(" + data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-sliders' style='font-size: small; color:white;'>&nbsp;Parameter</span></button>";
              }
              else if(data.campaignFormList[i]['camp_form_param_check_id'] && !data.campaignFormList[i]['lead_request_id']){
                campFormButtonItem = "<span>Campaign integration pending</span>";
              }
              else if(data.campaignFormList[i]['lead_request_id'] && data.campaignFormList[i]['camp_lead_request'] == 1 && data.campaignFormList[i]['camp_lead_accept'] == 0 && data.campaignFormList[i]['camp_form_lead_active'] == 1){
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-warning' onclick='leadRequestCampaign(" + data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-users' style='font-size: small; color:white;'>&nbsp;Lead Accept</span></button>";
              }
              else if(data.campaignFormList[i]['camp_form_edit_id'] && data.campaignFormList[i]['Edit_Active'] == 1 && data.campaignFormList[i]['camp_form_edit_request'] == 0){
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-success' onclick='editRequestCampaign(" + data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-pencil-square-o' style='font-size: small; color:white;'>&nbsp;Edit Request</span></button>";
              }
              else if(data.campaignFormList[i]['Edit_Active'] == 1 && data.campaignFormList[i]['camp_form_edit_request'] == 1 && data.campaignFormList[i]['camp_form_edit_accept'] == 0){ 
                campFormButtonItem = "<span>Edit approval pending</span>";
              }
              var campBodyItem = "<tr>" +
                                  "<td style='padding-left: 20px;'><span class='text-primary'>"+ data.campaignFormList[i]['program_type_name'] +"</span></td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['course_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['leadsource_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['agency_name'] +"</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['campaign_form_name'] +"</td>" +                                  
                                  "<td style='padding-left: 20px;'>"+ campStatusItem + "</td>" +
                                  "<td style='padding-left: 20px;'>"+ campApprovalStatusItem + "</td>" +
                                  "<td style='padding-left: 20px;'>"+ data.campaignFormList[i]['camp_form_comments']  +"</td>" +
                                  "<td style='padding-left: 20px;'> <button class='btn btn-sm btn-primary' style='margin-right: 5px;' onclick='viewCampaignForm(" + data.campaignFormList[i]['campaign_form_id'] + ")'><span class='fa fa-eye' style='font-size: small;'>&nbsp;View</span></button>"+ 
                                    campFormButtonItem
                                    +"</td>" +
                                  "</tr>";
              campBody.append(campBodyItem);
            }
            campBody.append("</tbody>")
          }
          $('#campaignFormTable').DataTable().destroy();
          $("#campaignFormTable").dataTable();
        }
      });
    }

    function getCoursesForm() {
      var institutionCode = $("#campaign-institution").val();
      $.ajax({
        type:'get',
        url: "/courses-campaign/",
        data: {'institutionCode' : institutionCode},
        success:function(data){
          var coursesId = $("#courses").empty();
          coursesId.append('<option selected="selected" value="">--Select--</option>');
          if(data) {        
            for(var i = 0; i < data.courses.length;i++){
                var courses_item_el = '<option value="' + data.courses[i]['course_code']+'">'+ data.courses[i]['course_name']+'</option>';
                coursesId.append(courses_item_el);
            }

            var campaignId = $("#campaign").empty();
            campaignId.append('<option selected="selected" value="">--Select--</option>');
            for(var i=0; i < data.campaignList.length;i++){
              var campaign_item_el = '<option value="'+ data.campaignList[i]['campaign_id'] +'">'+ data.campaignList[i]['campaign_name']+'</option>';
              campaignId.append(campaign_item_el);  
            }
          }
        }
      });
    }

    function checkKeyName() {
      var campFormId = $("#hdnCampFormId").val();
      var keyName = $("#keyName").val();
      $.ajax({
          type:'get',
          url: "/camp-form-check-keyname",
          data: {'campFormId' : campFormId, 'keyName' : keyName},
          success:function(data){
            if(data[0][0]['count'] > 0){
              $("#keyName-error").html('Key name already exists');
            }
            else {
              $("#keyName-error").html('');
            }
          }
      });
    }

    function viewCampaignForm(campFormId) {
        $("#viewCampaignModal").modal('show');
        $.ajax({
          type:'get',
          url: "/ext-view-campaign-form",
          data: {'campFormId' : campFormId},
          success:function(data){
            if(data){
              
              var camp_Table_View = $("#campaignTableDetails").empty();
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
</script>
@endsection