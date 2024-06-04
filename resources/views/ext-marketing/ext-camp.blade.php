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
                      <div class="dropdown float-lg-end pe-4">
                          <a class="btn btn-sm btn-primary" id="createCampaignID" onclick="createCampaign(0);" title="Create">
                              <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                          </a>                
                      </div>
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
                                     
                      <td style="padding-left: 15px;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="viewCampaign({{ $campaign->campaign_id }})"><i class="fa fa-eye" style="font-size: small;">&nbsp;View</i></button>
                        @if(($campaign->camp_accept_id && $campaign->camp_accept == 0 && $campaign->camp_accept_active == 1 && !is_null($campaign->comments)) || ($campaign->camp_edit_request == 1 && $campaign->camp_edit_accept == 1 && $campaign->camp_edit_active == 1))                        
                          <button type="button" class="btn btn-sm btn-primary" onclick="createCampaign({{ $campaign->campaign_id }})"><i class="fa fa-pencil" style="font-size: small;">&nbsp;Edit</i></button>
                        @elseif($campaign->camp_accept_id && $campaign->camp_accept == 1 && $campaign->camp_accept_active == 1 && !$campaign->camp_param_check_id)
                          <button type="button" class="btn btn-sm btn-info" onclick="parameterCampaign({{ $campaign->campaign_id }})"><i class="fa fa-sliders" style="font-size: small; color:white;">&nbsp;Parameter</i></button>
                        @elseif($campaign->camp_param_check_id && !$campaign->camp_lead_request_id && ($campaign->camp_integrated == 0 || $campaign->lead_field ))
                          <span>Integration pending</span>                        
                        @elseif($campaign->camp_edit_id && $campaign->camp_edit_active == 1 && $campaign->camp_edit_request == 0)
                          <button type="button" class="btn btn-sm btn-success" onclick="editRequestCampaign({{ $campaign->campaign_id }})"><i class="fa fa-pencil-square-o" style="font-size: small; color:white;">&nbsp;Edit Request</i></button>
                        @elseif($campaign->camp_edit_active == 1 && $campaign->camp_edit_request == 1 && $campaign->camp_edit_accept == 0) 
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

<!-- Create Campaign Modal -->
<!-- <div class="modal fade" id="createCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="add-camp" id="add-camp" method="post" action="{{ url('store-camp') }}">
          @csrf
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
              <label class="form-label" for="keyName">Key</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" name="keyName" id="keyName" class="form-control" />                
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
</div> -->

<!-- Create Campaign Modal -->
<div class="modal fade" id="createCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="add-campaign" id="add-campaign" method="post" action="{{ url('store-campaign') }}">
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
              <label class="form-label" for="targetLocation">Target Location</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="targetLocation" class="form-control" id="targetLocation">                  
              </select>                
              <span id="targetLocation-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="persona">Persona</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="persona" class="form-control" id="persona">                  
              </select>                
              <span id="persona-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="price">Price</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="price" class="form-control" id="price">                  
              </select>                
              <span id="price-error" class="text-danger"></span>
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
              <label class="form-label" for="campaignDate">Date</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="date" name="campaignDate" class="form-control" id="campaignDate" />                
              <span id="campaignDate-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="headline">Headline</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="headline" class="form-control" id="headline">                  
              </select>                
              <span id="headline-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="targetSegment">Target Segment</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="targetSegment" class="form-control" id="targetSegment">
              </select>                
              <span id="targetSegment-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="campaignType">Type</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaignType" class="form-control" id="campaignType">                  
              </select>                
              <span id="campaignType-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="campaignSize">Size</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaignSize" class="form-control" id="campaignSize">
              </select>                
              <span id="campaignSize-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-3">
              <label class="form-label" for="campaignVersion">Version</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="campaignVersion" class="form-control" id="campaignVersion">
              </select>                
              <span id="campaignVersion-error" class="text-danger"></span>
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
              <button class="btn btn-primary" id="generate" title="Generate" onclick="VerifyCampaign();">Generate</button>
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
<div class="modal fade" id="viewCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
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
        $("#extCampaignID").addClass("active bg-primary bg-gradient");
        $("#extCampaignHomeID").removeClass("active bg-primary bg-gradient");
        $("#extCampaignFormID").removeClass("active bg-primary bg-gradient");
        $('#campaignTable').dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaign(campaignId) {  
      
      $("#exampleModalLabel").html("Create New Campaign");
        $("#createCampaignModal").modal('show');
        $("#institution-error").html(''); 
        $("#programType-error").html(''); 
        $("#campaignVersion-error").html('');
        $("#campaignSize-error").html('');
        $("#campaignType-error").html('');
        $("#targetSegment-error").html('');
        $("#headline-error").html('');
        $("#campaignDate-error").html('');
        $("#courses-error").html(''); 
        $("#marketingAgency-error").html('');
        $("#leadSource-error").html('');
        $("#targetLocation-error").html('');
        $("#persona-error").html('');
        $("#price-error").html('');
        $("#campaignsId-error").html('');
        $("#campaignDate").val('');
        $.ajax({
            type:'get',
            url: "/create-campaign", 
            data: {'institute' : $("#hdnInstituteId").val(), 'campaignId': campaignId},            
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
                        if(campaignId != 0 && data.campaignList[0]['program_code'] == data.programType[i]['program_code']){
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
                        if(campaignId != 0 && data.campaignList[0]['agency_code'] == data.marketingAgency[i]['agency_code']){
                          var marketingAgency_item_el = '<option selected value="'+ data.marketingAgency[i]['agency_code'] +'">'+data.marketingAgency[i]['agency_name']+'</option>';
                        }
                        else {
                          var marketingAgency_item_el = '<option value="'+ data.marketingAgency[i]['agency_code'] +'">'+data.marketingAgency[i]['agency_name']+'</option>';
                        }
                        marketingAgencyId.append(marketingAgency_item_el);
                    }

                    var leadSourceId = $("#leadSource").empty();
                    leadSourceId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.leadSource.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['fk_leadsource_id'] == data.leadSource[i]['leadsource_id']){
                          var leadSource_item_el = '<option selected value="'+ data.leadSource[i]['leadsource_id'] +'">'+ data.leadSource[i]['leadsource_name']+'</option>';
                        }
                        else {
                          var leadSource_item_el = '<option value="'+ data.leadSource[i]['leadsource_id'] +'">'+ data.leadSource[i]['leadsource_name']+'</option>';
                        }
                        leadSourceId.append(leadSource_item_el);
                    }

                    var targetLocationId = $("#targetLocation").empty();
                    targetLocationId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.targetLocation.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['target_location_code'] == data.targetLocation[i]['target_location_code']){
                          var targetLocation_item_el = '<option selected value="'+ data.targetLocation[i]['target_location_code'] +'">'+ data.targetLocation[i]['target_location_name']+'</option>';
                        }
                        else{
                          var targetLocation_item_el = '<option value="'+ data.targetLocation[i]['target_location_code'] +'">'+ data.targetLocation[i]['target_location_name']+'</option>';
                        }
                        targetLocationId.append(targetLocation_item_el);
                    }

                    var personaId = $("#persona").empty();
                    personaId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.persona.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['persona_code'] == data.persona[i]['persona_code']){
                          var persona_item_el = '<option selected value="'+ data.persona[i]['persona_code'] +'">'+ data.persona[i]['persona_name']+'</option>';
                        }
                        else {
                          var persona_item_el = '<option value="'+ data.persona[i]['persona_code'] +'">'+ data.persona[i]['persona_name']+'</option>';
                        }
                        personaId.append(persona_item_el);
                    }

                    var priceId = $("#price").empty();
                    priceId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.price.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['campaign_price_code'] == data.price[i]['campaign_price_code']){
                          var price_item_el = '<option selected value="'+ data.price[i]['campaign_price_code'] +'">'+ data.price[i]['campaign_price_name']+'</option>';
                        }
                        else {                    
                          var price_item_el = '<option value="'+ data.price[i]['campaign_price_code'] +'">'+ data.price[i]['campaign_price_name']+'</option>';
                        }
                        priceId.append(price_item_el);
                    }

                    var headlineId = $("#headline").empty();
                    headlineId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.headline.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['headline_code'] == data.headline[i]['headline_code']){
                          var headline_item_el = '<option selected value="'+ data.headline[i]['headline_code'] +'">'+ data.headline[i]['headline_name']+'</option>';
                        }
                        else {
                          var headline_item_el = '<option value="'+ data.headline[i]['headline_code'] +'">'+ data.headline[i]['headline_name']+'</option>';
                        }
                        headlineId.append(headline_item_el);
                    }

                    var targetSegmentId = $("#targetSegment").empty();
                    targetSegmentId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.targetSegment.length;i++){
                      if(campaignId != 0 && data.campaignList[0]['target_segment_code'] == data.targetSegment[i]['target_segment_code']){
                        var targetSegment_item_el = '<option selected value="'+ data.targetSegment[i]['target_segment_code'] +'">'+ data.targetSegment[i]['target_segment_name']+'</option>';
                      }
                      else {
                        var targetSegment_item_el = '<option value="'+ data.targetSegment[i]['target_segment_code'] +'">'+ data.targetSegment[i]['target_segment_name']+'</option>';
                      }
                      targetSegmentId.append(targetSegment_item_el);
                    }

                    var campaignTypeId = $("#campaignType").empty();
                    campaignTypeId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.campaignType.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['campaign_type_code'] == data.campaignType[i]['campaign_type_code']){
                          var campaignType_item_el = '<option selected value="'+ data.campaignType[i]['campaign_type_code'] +'">'+ data.campaignType[i]['campaign_type_name']+'</option>';
                        }
                        else {
                          var campaignType_item_el = '<option value="'+ data.campaignType[i]['campaign_type_code'] +'">'+ data.campaignType[i]['campaign_type_name']+'</option>';
                        }
                        campaignTypeId.append(campaignType_item_el);
                    }

                    var campaignSizeId = $("#campaignSize").empty();
                    campaignSizeId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.campaignSize.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['campaign_size_code'] == data.campaignSize[i]['campaign_size_code']){
                          var campaignSize_item_el = '<option selected value="'+ data.campaignSize[i]['campaign_size_code'] +'">'+ data.campaignSize[i]['campaign_size_name']+'</option>';
                        }
                        else {
                          var campaignSize_item_el = '<option value="'+ data.campaignSize[i]['campaign_size_code'] +'">'+ data.campaignSize[i]['campaign_size_name']+'</option>';
                        }
                        campaignSizeId.append(campaignSize_item_el);
                    }

                    var campaignVersionId = $("#campaignVersion").empty();
                    campaignVersionId.append('<option selected="selected" value="">--Select--</option>');
                    for(var i = 0; i < data.campaignVersion.length;i++){
                        if(campaignId != 0 && data.campaignList[0]['campaign_version_code'] == data.campaignVersion[i]['campaign_version_code']){
                          var campaignVersion_item_el = '<option selected value="'+ data.campaignVersion[i]['campaign_version_code'] +'">'+ data.campaignVersion[i]['campaign_version_name']+'</option>';
                        }
                        else {
                          var campaignVersion_item_el = '<option value="'+ data.campaignVersion[i]['campaign_version_code'] +'">'+ data.campaignVersion[i]['campaign_version_name']+'</option>';
                        }
                        campaignVersionId.append(campaignVersion_item_el);
                    }

                    if(campaignId != 0) {
                      var courseId = $("#courses").empty();
                      courseId.append('<option selected="selected" value="">--Select--</option>');
                      for(var i = 0; i < data.courseList.length; i++){
                        if(data.campaignList[0]['course_code'] == data.courseList[i]['course_code']){
                          var course_item_el = '<option selected value="'+ data.courseList[i]['course_code'] +'">'+ data.courseList[i]['course_name']+'</option>';
                        }
                        else {
                          var course_item_el = '<option value="'+ data.courseList[i]['course_code'] +'">'+ data.courseList[i]['course_name']+'</option>';
                        }
                        courseId.append(course_item_el);
                      }
                      
                      if(data.campaignList[0]['camp_edit_id'] != 0){
                        $("#campaignStatusDiv").show();
                        var campaignStatusId = $("#campaignStausId").empty();
                        campaignStatusId.append('<option selected="selected" value="">--Select--</option>');
                        for(var i = 0; i < data.campaignStatusList.length; i++){
                          if(data.campaignList[0]['campaign_status_id'] == data.campaignStatusList[i]['campaign_status_id']){
                            var campstatus_item_el = '<option selected value="'+ data.campaignStatusList[i]['campaign_status_id'] +'">'+ data.campaignStatusList[i]['campaign_status_name']+'</option>';
                          }
                          else {
                            var campstatus_item_el = '<option value="'+ data.campaignStatusList[i]['campaign_status_id'] +'">'+ data.campaignStatusList[i]['campaign_status_name']+'</option>';
                          }
                          campaignStatusId.append(campstatus_item_el);
                        }
                      }
                      $("#campaignDate").val(data.campaignList[0]['campaign_date']);
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
      var leadSource = $("#leadSource").val();
      var targetLocation = $("#targetLocation").val();
      var persona = $("#persona").val();
      var price = $("#price").val();
      var courses = $("#courses").val();
      var campDate = $("#campaignDate").val();
      var headline = $("#headline").val();
      var targetSegment = $("#targetSegment").val();
      var campaignType = $("#campaignType").val();
      var campaignSize = $("#campaignSize").val();
      var campaignVersion = $("#campaignVersion").val();
      var campaignStatus = $("#campaignStausId").val();
      
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
      if(leadSource == "" || leadSource == "undefined"){
        $("#leadSource-error").html("Please select a Lead Source");        
      }
      else {
        $("#leadSource-error").html("");
      }
      if(targetLocation == "" || targetLocation == "undefined"){
        $("#targetLocation-error").html("Please select a Target Location");        
      }
      else {
        $("#targetLocation-error").html("");
      }
      if(persona == "" || persona == "undefined"){
        $("#persona-error").html("Please select a Persona");        
      }
      else {
        $("#persona-error").html("");
      }
      if(price == "" || price == "undefined"){
        $("#price-error").html("Please select a Price");        
      }
      else {
        $("#price-error").html("");
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
      if(headline == "" || headline == "undefined"){
        $("#headline-error").html("Please select a Headline");        
      }
      else {
        $("#headline-error").html("");
      }
      if(targetSegment == "" || targetSegment == "undefined"){
        $("#targetSegment-error").html("Please select a Target Segment");        
      }
      else {
        $("#targetSegment-error").html("");
      }
      if(campaignType == "" || campaignType == "undefined"){
        $("#campaignType-error").html("Please select a Type");        
      }
      else {
        $("#campaignType-error").html("");
      }
      if(campaignSize == "" || campaignSize == "undefined"){
        $("#campaignSize-error").html("Please select a Size");        
      }
      else {
        $("#campaignSize-error").html("");
      }
      if(campaignVersion == "" || campaignVersion == "undefined"){
        $("#campaignVersion-error").html("Please select a Version");        
      }
      else {
        $("#campaignVersion-error").html("");
      }
      
      if(campaignStausId == "" || campaignStausId == "undefined"){
        $("#campaignStausId-error").html("Please select a Status");        
      }
      else {
        $("#campaignStausId-error").html("");
      }
      
      $("#add-campaign").submit(function (e) {
        
        if($("#institution-error").text() != "" || $("#programType-error").text() != "" || $("#campaignVersion-error").text() != "" || $("#campaignSize-error").text() != ""
        || $("#campaignType-error").text() != "" || $("#targetSegment-error").text() != "" || $("#headline-error").text() != "" || $("#campaignDate-error").text() != ""
        || $("#courses-error").text() != "" || $("#marketingAgency-error").text() != "" || $("#leadSource-error").text() != "" || $("#targetLocation-error").text() != ""
        || $("#persona-error").text() != "" || $("#price-error").text() != "" || $("#campaignStausId-error").text() != ""){
          e.preventDefault();
          return false;
        }
      }); 
          
    }

    function viewCampaign(campaignId) {
        $("#viewCampaignModal").modal('show');
        $.ajax({
          type:'get',
          url: "/ext-view-campaign",
          data: {'campaignId' : campaignId},
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

    function parameterCampaign(campId)
    {
        $("#exampleModalLabel").html("Create New Campaign");
        $("#parameterCheckModal").modal('show'); 
        $("#campaignId").val(campId); 
        $("#published").prop("checked", false);
        $("#parameterId").val('');
        $("#course-campaign").prop("checked", false);
        $("#text-param-error").html("");
        $("#course-campaign-error").text('');
        $("#published-error").text('');
        $("#text-param").prop("checked", false);
        $("#parameter-error").html("");
        $("input[name='text-param']").prop("checked", false); 
        $("#parameterDiv").hide();
    }

    function showParameterDiv(){    
      if($("input[name='text-param']:checked").val() == "1") {
        $("#parameterDiv").show();
      }
      else {
        $("#parameterDiv").hide();
      }
    }

    function VerifyParameter() {      
      if($("#published").prop('checked') == false ){
        $("#published-error").text('Please select form is published');
      }
      else {
        $("#published-error").text('');
        $("#published").val(true);
      }
      
      if($("#course-campaign").prop('checked') == false ){
        $("#course-campaign-error").text('Please select course is integrated');
      }
      else {
        $("#course-campaign-error").text('');
        $("#course-campaign").val(true);
      }
      
      if($("#text-yes").prop('checked'))
      {
        $("#text-param-error").html("");
      }
      else {
        $("#text-param-error").html("Please select one of the parameters");
      }

      if($("#text-no").prop('checked')) {
        $("#text-param-error").html("");
      }
      else {
        $("#text-param-error").html("Please select one of the parameters");
      }

      if($("input[name='text-param']:checked").val() == "1") {
        if($("#parameterId").val() == "") {
          $("#parameter-error").html("Please enter parameters");
        }
        else {
          $("#parameter-error").html("");
        }
      }
      
      $("#parameter-check").submit(function (e) { 
        if ($("#course-campaign-error").text() != "" || $("#published-error").text() != "" || $("#text-param-error").text() != "" || $("#parameter-error").text() != "") {
          e.preventDefault();
          return false;
        }
      });
    }

    function leadRequestCampaign(campId) {
        $("#confirmLeadFormModal").modal('show');
        $("#hdnLeadCampaignId").val(campId);
    }

    function confirmLeadGeneration() {     
      var campaignId = $("#hdnLeadCampaignId").val();
      $.ajax({
          type:'get',
          url: "/confirm-lead-campaign",
          data: {'campaignId' : campaignId},
          success:function(data){
            if(data){
              $.notify(data, "success");
              $("#confirmLeadFormModal").modal('hide');
              setTimeout(() => {
                window.location.href="{{'ext-camp'}}";
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
          url: "/confirm-edit-campaign",
          data: {'campaignId' : campaignId},
          success:function(data){
            if(data){
              $.notify(data, "success");
              $("#editRequestFormModal").modal('hide');
              setTimeout(() => {
                window.location.href="{{'ext-camp'}}";
              }, "2000");
              
            }
          }
      });
    }

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
                    "<th class='opacity-10'>ACTION</th>" +
                "</tr>" +
                "</thead><tbody>";
            campBody.append(campTheadItem);
            for(var i = 0; i < data.campaignList.length;i++){
              
              var campStatusItem = "";
              if(data.campaignList[i]['campaign_status_name'] == 'Active') {
                campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;'> " + data.campaignList[i]['campaign_status_name'] + "</button>";
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
              
              if((data.campaignList[i]['camp_accept_id'] && data.campaignList[i]['camp_accept'] == 0 && data.campaignList[i]['camp_accept_active'] == 1 && data.campaignList[i]['comments'] ) || (data.campaignList[i]['camp_edit_request'] == 1 && data.campaignList[i]['camp_edit_accept'] == 1 && data.campaignList[i]['camp_edit_active'] == 1)){                    
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-primary' onclick='createCampaign(" + data.campaignList[i]['campaign_id'] + ")'><span class='fa fa-pencil' style='font-size: small;'>&nbsp;Edit</span></button>";
              }
              else if(data.campaignList[i]['camp_accept_id'] && data.campaignList[i]['camp_accept'] == 1 && data.campaignList[i]['camp_accept_active'] == 1 && !data.campaignList[i]['camp_param_check_id']) {
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-info' onclick='parameterCampaign(" + data.campaignList[i]['campaign_id'] + ")'><span class='fa fa-sliders' style='font-size: small; color:white;'>&nbsp;Parameter</span></button>";
              }
              else if(data.campaignList[i]['camp_param_check_id'] && !data.campaignList[i]['camp_lead_request_id'] && (data.campaignList[i]['camp_integrated'] == 0 || data.campaignList[i]['lead_field'] )){
                campFormButtonItem = "<span>Campaign integration pending</span>";
              }
              else if(data.campaignList[i]['camp_edit_id'] && data.campaignList[i]['camp_edit_active'] == 1 && data.campaignList[i]['camp_edit_request'] == 0){
                campFormButtonItem = "<button type='button' class='btn btn-sm btn-success' onclick='editRequestCampaign(" + data.campaignList[i]['campaign_id'] + ")'><span class='fa fa-pencil-square-o' style='font-size: small; color:white;'>&nbsp;Edit Request</span></button>";
              }
              else if(data.campaignList[i]['camp_edit_active'] == 1 && data.campaignList[i]['camp_edit_request'] == 1 && data.campaignList[i]['camp_edit_accept'] == 0){ 
                campFormButtonItem = "<span>Edit approval pending</span>";
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
          $('#campaignTable').DataTable().destroy();
          $("#campaignTable").dataTable();
        }
      });
    }
 
</script>
@endsection