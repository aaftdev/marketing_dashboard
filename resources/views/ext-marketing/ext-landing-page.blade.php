@extends('ext-marketing.ext-master')

@section('content')

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
                <a class="nav-link mb-0 px-0 py-2 mx-1 active" data-bs-toggle="tab" onclick="changeLPInstitution('{{ $institute->institution_name }}')" role="tab">              
                <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                </a>
            @else
                <a class="nav-link mb-0 px-0 py-2 mx-1" data-bs-toggle="tab" onclick="changeLPInstitution('{{ $institute->institution_name }}')" role="tab" >              
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
                        <h5>Landing Page</h5> 
                        <input type="hidden" id="hdnInstituteId" value="{{ $institutionId }}" />               
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                      <div class="dropdown float-lg-end pe-4">
                                         
                      </div>
                </div>                  
            </div>            
        </div>
        <div class="card-body px-1 pb-2">
            <div class="table-responsive">
            <table class="table align-items-center mb-1" id="lpTable">
                <thead>
                <tr>            
                    <th class="opacity-10">PROGRAM TYPE</th>
                    <th class="opacity-10">COURSE</th>                            
                    <th class="opacity-10">URL</th>                            
                    <th class="opacity-10">STATUS</th>                    
                </tr>
                </thead>
                <tbody>
                  @foreach($landingPageList as $lp)
                    <tr>                      
                        <td style="padding-left: 15px;"><span class="text-primary">{{ $lp->program_type_name }}</span></td>
                        <td style="padding-left: 15px;">{{ $lp->course_name }}</td>                                    
                        <td class="text-wrap" style="padding-left: 15px;">{{ $lp->camp_url }}</td>                                                 
                        <td style="padding-left: 15px;">
                            @if($lp->active == 1)
                                <button type="button" style="background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;">Active</button>
                            @elseif($lp->active == 0)
                                <button type="button" style="background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;">Inactive</button>  
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
<!-- Create Campaign Modal -->
<div class="modal fade" id="createCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="add-lp-campaign" id="add-lp-campaign" method="post" action="{{ url('store-lp-campaign') }}">
          @csrf
          <input type="hidden" name="hdncampaignId" id="hdncampaignId" />
          <div class="row form-group">
            <div class="col-md-3">
              <label class="form-label" for="campaign-institution">Institution</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaign-institution" class="form-control" id="campaign-institution" onchange="getCourses('');">                  
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
              <label class="form-label" for="sourceType">Source Type</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="sourceType" class="form-control" id="sourceType">                  
              </select>                
              <span id="sourceType-error" class="text-danger"></span>
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
              <label class="form-label" for="lpUrl">URL</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" name="lpUrl" id="lpUrl" class="form-control" />                
              <span id="lpUrl-error" class="text-danger"></span>
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
          <div class="row form-group mt-2" id="campaignStatusDiv" style="display:none;">
            <div class="col-md-3">
              <label class="form-label" for="campaignStausId">Status</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaignStausId" class="form-control" id="campaignStausId">
              </select>                
              <span id="campaignStausId-error" class="text-danger"></span>
            </div>
          </div>
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="generate" title="Generate" onclick="VerifyCamp();">Generate</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
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
        <form id="parameter-check" name="parameter-check" method="post" action="{{ url('parameter-campaign') }}">
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
              <span id="published-error" class="text-danger"></span>                
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
              <span id="course-campaign-error" class="text-danger"></span>
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
              <button class="btn btn-sm btn-primary" type="submit" id="confirmParameterCheck" title="Confirm" onclick="VerifyParameter();">Confirm</button>
              <button data-bs-dismiss="modal" class="btn btn-sm btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- View Campaign Check Modal -->
<div class="modal fade" id="viewCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true" style="width:100%">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Campaign Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" style="border: 1px solid black; " id="campaignTableDetails">          
        </table>
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
        <input type="hidden" id="hdnLeadCampaignId" name="hdnLeadCampaignId" />
        <p>Do you wish to confirm the lead generation is initiated? </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="confirmLeadbuttonId" title="Confirm Lead Generation" onclick="confirmLeadGeneration();">Confirm</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Request Modal -->
<div class="modal fade" id="editRequestFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Edit Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editCampaignId" name="editCampaignId" />
        <p id="descriptionId">Do you wish to edit the campaign? </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="buttonId" title="Request" onclick="confirmEditRequest();">Request</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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
        $("#extCampaignID").removeClass( "active bg-primary bg-gradient" );
        $("#extCampaignHomeID").removeClass( "active bg-primary bg-gradient" );
        $("#extCampaignFormID").removeClass("active bg-primary bg-gradient");
        $("#extLandingID").addClass( "active bg-primary bg-gradient" );
        $('#lpTable').dataTable();          
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function changeLPInstitution(institutionName) {
        $.ajax({
            type:'get',
            url: "/ext-lp-change-institution",
            data: {'institution' : institutionName},          
            success:function(data){
              
              var campBody = $("#lpTable").empty();
              $("#hdnInstituteId").val(data.institutionId[0]);                
              if(data.landingPageList != "" && data.landingPageList != undefined){
                
                var campTheadItem = "<thead>" +
                "<tr>" +                    
                    "<th class='opacity-10'>PROGRAM TYPE</th>" +
                    "<th class='opacity-10'>COURSE</th>" +                    
                    "<th class='opacity-10'>URL</th>" +                    
                    "<th class='opacity-10'>STATUS</th>" +                    
                "</tr>" +
                "</thead><tbody>";
                campBody.append(campTheadItem);
                for(var i = 0; i < data.landingPageList.length;i++){
                  var campStatusItem = "";
                  if(data.landingPageList[i]['active'] == 1) {
                    campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598;'> " + "Active" + "</button>";
                  }
                  else if (data.landingPageList[i]['active'] == 0) {
                    campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107;'>" + "Inactive" + "</button>";
                  }                                

                  var campBodyItem = "<tr><td style='padding-left: 20px;'><span class='text-primary'>"+ data.landingPageList[i]['program_type_name'] +"</span></td>" +
                                     "<td style='padding-left: 20px;'>"+ data.landingPageList[i]['course_name'] +"</td>" +                                     
                                     "<td class='text-wrap' style='padding-left: 20px;'>"+ data.landingPageList[i]['camp_url'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ campStatusItem +"</td>" +
                                     "</tr>";
                  campBody.append(campBodyItem);
                }
                campBody.append("</tbody>")
              }
              $('#lpTable').DataTable().destroy();
              $("#lpTable").dataTable();
            }
        });
    }

    function createCampaign(campaignId) {  
      
      $("#exampleModalLabel").html("Create New Campaign ");
        $("#createCampaignModal").modal('show');
        $("#institution-error").html(''); 
        $("#programType-error").html(''); 
        $("#marketingAgency-error").html('');
        $("#sourceType-error").html('');
        $("#courses-error").html('');
        $("#campaignStausId-error").html('');
        $("#keyName-error").html('');
        $("#keyName").val('');
        $("#lpUrl-error").html('');
        $("#lpUrl").val('');
        $("#campaignDate").val('');        
        $.ajax({
            type:'get',
            url: "/create-landing-page-campaign", 
            data: {'institute' : $("#hdnInstituteId").val(), 'lpCampId': campaignId},            
            success:function(data){
                if(data){                                
                  
                  var institutionId = $("#campaign-institution").empty();
                    institutionId.append('<option selected="selected" value="">--Select--</option>');
                    var institutionCode = data.instituteCode;
                    for(var i = 0; i < Object.keys(data['institution']).length;i++){
                        
                        if(data.institution[i]['institution_code'] == institutionCode){
                          var institution_item_el = '<option selected value="'+data.institution[i]['institution_code']+'">'+data.institution[i]['institution_name']+'</option>';
                        }
                        else {
                          var institution_item_el = '<option value="'+data.institution[i]['institution_code']+'">'+data.institution[i]['institution_name']+'</option>';
                        }
                        institutionId.append(institution_item_el);
                    }                    

                    var programTypeId = $("#programType").empty();
                    programTypeId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.programType.length;i++){                        
                        if(campaignId != 0 && data.lpCampaignList[0]['program_code'] == data.programType[i]['program_code']){
                          var programType_item_el = '<option selected value="'+ data.programType[i]['program_code'] +'">'+data.programType[i]['program_type_name']+'</option>';
                        } 
                        else {
                          var programType_item_el = '<option value="'+ data.programType[i]['program_code'] +'">'+data.programType[i]['program_type_name']+'</option>';
                        }
                        programTypeId.append(programType_item_el);
                    }

                    var marketingAgencyId = $("#marketingAgency").empty();
                    marketingAgencyId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.marketingAgency.length;i++){
                        if(campaignId != 0 && data.lpCampaignList[0]['agency_code'] == data.marketingAgency[i]['agency_code']){
                          var marketingAgency_item_el = '<option selected value="'+ data.marketingAgency[i]['agency_code'] +'">'+data.marketingAgency[i]['agency_name']+'</option>';
                        }
                        else {
                          var marketingAgency_item_el = '<option value="'+ data.marketingAgency[i]['agency_code'] +'">'+data.marketingAgency[i]['agency_name']+'</option>';
                        }
                        marketingAgencyId.append(marketingAgency_item_el);
                    }
                                        
                    var sourceTypeId = $("#sourceType").empty();
                    sourceTypeId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.sourceType.length;i++){
                        if(campaignId != 0 && data.lpCampaignList[0]['source_type_id'] == data.sourceType[i]['source_type_id']){
                          var sourceType_item_el = '<option selected value="'+ data.sourceType[i]['source_type_id'] +'">'+ data.sourceType[i]['source_name']+'</option>';
                        }
                        else {
                          var sourceType_item_el = '<option value="'+ data.sourceType[i]['source_type_id'] +'">'+ data.sourceType[i]['source_name']+'</option>';
                        }
                        sourceTypeId.append(sourceType_item_el);
                    }
                    if(campaignId != 0) {
                      var courseId = $("#courses").empty();
                      $("#keyName").val(data.lpCampaignList[0]['key_name']);
                      $("#lpUrl").val(data.lpCampaignList[0]['camp_url']);
                      courseId.append('<option selected="selected" value="">--Select--</option>');
                      for(var i = 0; i < data.courseList.length; i++){
                        if(data.lpCampaignList[0]['course_code'] == data.courseList[i]['course_code']){
                          var course_item_el = '<option selected value="'+ data.courseList[i]['course_code'] +'">'+ data.courseList[i]['course_name']+'</option>';
                        }
                        else {
                          var course_item_el = '<option value="'+ data.courseList[i]['course_code'] +'">'+ data.courseList[i]['course_name']+'</option>';
                        }
                        courseId.append(course_item_el);
                      }
                      
                      if(data.lpCampaignList[0]['lp_campaign_id'] != 0){
                        $("#campaignStatusDiv").show();
                        var campaignStatusId = $("#campaignStausId").empty();
                        campaignStatusId.append('<option selected="selected" value="">--Select--</option>');
                        for(var i = 0; i < data.campaignStatusList.length; i++){
                          if(data.lpCampaignList[0]['campaign_status_id'] == data.campaignStatusList[i]['campaign_status_id']){
                            var campstatus_item_el = '<option selected value="'+ data.campaignStatusList[i]['campaign_status_id'] +'">'+ data.campaignStatusList[i]['campaign_status_name']+'</option>';
                          }
                          else {
                            var campstatus_item_el = '<option value="'+ data.campaignStatusList[i]['campaign_status_id'] +'">'+ data.campaignStatusList[i]['campaign_status_name']+'</option>';
                          }
                          campaignStatusId.append(campstatus_item_el);
                        }
                      }
                      $("#campaignDate").val(data.lpCampaignList[0]['lp_camp_date']);
                      $("#exampleModalLabel").html("Edit Campaign");
                      $("#generate").html("Update");
                      $("#hdncampaignId").val(campaignId);
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
      var sourceType = $("#sourceType").val();
      var courses = $("#courses").val();
      var campDate = $("#campaignDate").val();
      var keyName = $("#keyName").val();
      var campaignStatus = $("#campaignStausId").val();
      var campUrl = $("#lpUrl").val();
      if(institution == "" || institution == "undefined"){
        $("#institution-error").html("Please select an Institution");         
      }
      else {
        $("#institution-error").html(""); 
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

      if(sourceType == "" || sourceType == "undefined"){
        $("#sourceType-error").html("Please select a Source Type");
      }
      else {
        $("#sourceType-error").html("");
      }

      if(campUrl == "" || campUrl == "undefined"){
        $("#lpUrl-error").html("Please enter a URL");
      }
      else {
        $("#lpUrl-error").html("");
      }

      if(keyName == "" || keyName == "undefined"){
        $("#keyName-error").html("Please enter a Key Name");
      }
      else {
        $("#keyName-error").html("");
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
            
      if(campaignStausId == "" || campaignStausId == "undefined"){
        $("#campaignStausId-error").html("Please select a Status");        
      }
      else {
        $("#campaignStausId-error").html("");
      }
      
      $("#add-lp-campaign").submit(function (e) {        
        if($("#institution-error").text() != "" || $("#programType-error").text() != "" || $("#campaignDate-error").text() != "" || $("#sourceType-error").text() != "" || $("#lpUrl-error").text() != "" || $("#keyName-error").text() != ""
        || $("#courses-error").text() != "" || $("#marketingAgency-error").text() != "" || $("#campaignStausId-error").text() != ""){
          e.preventDefault();
          return false;
        }
      }); 
          
    }

    function checkKeyName() {
      var campFormId = $("#hdnCampFormId").val();
      var keyName = $("#keyName").val();
      $.ajax({
          type:'get',
          url: "/lp-camp-check-keyname",
          data: {'lpCampId' : campFormId, 'keyName' : keyName},
          success:function(data){
            
            if(data.lpCampCount[0]['count'] > 0){
              $("#keyName-error").html('Key name already exists');
            }
            else {
              $("#keyName-error").html('');
            }
          }
      });
    }

    function viewCampaign(campaignId) {
        $("#viewCampaignModal").modal('show');
        $.ajax({
          type:'get',
          url: "/ext-view-lp-campaign",
          data: {'lpCampId' : campaignId},
          success:function(data){
            if(data){              
              var camp_Table_View = $("#campaignTableDetails").empty();
              for(var i = 0; i < data.lpCampDetails.length;i++){
                var camp_Append = "<tr>" +
                                    "<td><b>INSTITUTION</b></td>" + 
                                    "<td>" + data.lpCampDetails[i].institution_name + "</td>" +
                                  "</tr>"+
                                  "<tr>" +
                                    "<td><b>PROGRAM TYPE</b></td>" +
                                    "<td>" + data.lpCampDetails[i].program_type_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>COURSE</b></td>" + 
                                    "<td>" + data.lpCampDetails[i].course_name + "</td>" + 
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN</b></td>" +
                                    "<td>" + data.lpCampDetails[i].camp_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN DATE</b></td>" +
                                    "<td>" + data.lpCampDetails[i].lp_camp_date + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>AGENCY</b></td>" +
                                    "<td>" + data.lpCampDetails[i].agency_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>Key Name</b></td>" +
                                    "<td>" + data.lpCampDetails[i].key_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>Source Name</b></td>" +
                                    "<td>" + data.lpCampDetails[i].source_name + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>URL</b></td>" +
                                    "<td class='text-wrap' style='width: 6rem;'>" + data.lpCampDetails[i].camp_url + "</td>" +
                                  "</tr>" +
                                  "<tr>" +
                                    "<td><b>CAMPAIGN STATUS</b></td>" +
                                    "<td>" + data.lpCampDetails[i].campaign_status_name + "</td>" + 
                                  "</tr>";
                camp_Table_View.append(camp_Append);
              }
            }
          }
        });
    }

    function leadRequestCampaign(campId) {
        $("#confirmLeadFormModal").modal('show');
        $("#hdnLeadCampaignId").val(campId);
    }

    function confirmLeadGeneration(){
      var campFormId = $("#hdnLeadCampaignId").val();
      $.ajax({
          type:'get',
          url: "/confirm-lead-lp-camp-form",
          data: {'lpCampFormId' : campFormId},
          success:function(data){
            if(data){
              $.notify(data, "success");
              $("#confirmLeadModal").modal('hide');
              setTimeout(() => {
                window.location.href="{{'landingPageForm'}}";
              }, "2000");
            }
          }
      });
    }  
    
    function editRequestCampaign(campId) {
        $("#editRequestFormModal").modal('show');
        $("#editCampaignId").val(campId);
    }

    function confirmEditRequest() {
      var campaignId = $("#editCampaignId").val();
      $.ajax({
          type:'get',
          url: "/confirm-edit-lp-campaign",
          data: {'lpCampId' : campaignId},
          success:function(data){
            if(data){
              $.notify(data, "success");
              $("#editRequestFormModal").modal('hide');
              setTimeout(() => {
                window.location.href="{{'landingPageForm'}}";
              }, "2000");
              
            }
          }
      });
    }

</script>

@endsection