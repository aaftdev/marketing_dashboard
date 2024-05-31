@extends('it-admin-shared.it-admin-master')

@section('it-adminContent')

<div class="row">
    @if(session()->has('message'))
      <div class="alert alert-success" id="successMesgID" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="display: none">
        {{ session()->get('message') }}
        <button type="button" onclick="campNotify();" class="btn-close" style="float: right;" aria-label="Close"></button>
      </div>
    @endif
    <div class="col-md-12">
        <button class="btn btn-outline-primary" id="btnBackID" onclick="goBack()"><span class="fa fa-backward">&nbsp;Back</span></button>
    </div>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Campaign Type</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createCampaignTypeID" onclick="createCampaignType(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-campaign-typeTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Type</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Type Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($campaignTypeList as $campaignType)
                                <tr>
                                    <td>{{ $campaignType->campaign_type_name }}</td>
                                    <td>{{ $campaignType->campaign_type_code }}</td>
                                    <td>
                                        @if($campaignType->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaignType->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Campaign Type" onclick="createCampaignType({{ $campaignType->campaign_type_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($campaignType->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Campaign Type" onclick="deleteCampaignType({{ $campaignType->campaign_type_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Campaign Type" onclick="deleteCampaignType({{ $campaignType->campaign_type_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Campaign Type Modal -->
<div class="modal fade" id="createCampaignTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Campaign Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-campaign-type-create')}}" method="post" id="campaignTypeForm">
          @csrf
          <input type="hidden" id="hdnCampaignTypeId" name="hdnCampaignTypeId" />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="campaignTypeCode">Campaign Type Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignTypeCode" name="campaignTypeCode" onchange="checkCampaignTypeCode();" maxlength="5" />
              <span id="campaign-type-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-5">
              <label for="campaignTypeCode">Campaign Type Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignTypeCode" name="campaignTypeCode" onchange="checkCampaignTypeCode();" maxlength="5" />
              <span id="campaign-type-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="campaignTypeName">Campaign Type</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignTypeName" name="campaignTypeName"  />
              <span id="campaign-type-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveCampaignTypeId" title="Save" onclick="saveCampaignType();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Campaign Type Modal -->
<div class="modal fade" id="deleteCampaignTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Campaign Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenCampaignTypeId" name="hiddenCampaignTypeId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this campaign type?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteCampaignType();">Yes</button>
        <button data-bs-dismiss="modal" class="btn btn-danger" title="No">No</button>
        </div>
    </div>    
  </div>
</  div>
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminLandingPageID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminHomeID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminSettingsID").addClass( "active bg-primary bg-gradient" );
        $("#it-campaign-typeTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaignType(campaignTypeId) {        
        $("#campaign-type-error").text("");
        $("#campaign-type-code-error").text("");
        if(campaignTypeId == 0) {
            $("#campaignTypeName").val('');
            $("#createCampaignTypeModal").modal('show');
            $("#saveCampaignTypeId").attr("title", "Save");
            $("#saveCampaignTypeId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Campaign Type");
            $("#campaignTypeCode").val('');
            $("#hdnCampaignTypeId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-campaign-type-edit",
                data: {'campaignTypeId' : campaignTypeId},
                success:function(data){
                    if(data){
                        $("#saveCampaignTypeId").attr("title", "Update");
                        $("#saveCampaignTypeId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Campaign Type")                       
                        $("#createCampaignTypeModal").modal('show');
                        $("#campaignTypeName").val(data[0]['campaign_type_name']);
                        $("#campaignTypeCode").val(data[0]['campaign_type_code']);
                        $("#hdnCampaignTypeId").val(campaignTypeId);        
                    }
                }
            });
        }
    }

    function saveCampaignType() {
        var campaignTypeId = $("#hdnCampaignTypeId").val();
        var campaignTypeCode = $("#campaignTypeCode").val();
        if($("#campaignTypeName").val() == "") {
            $("#campaign-type-error").text("Please enter campaign type");
        }
        else {
            $("#campaign-type-error").text("");
        }

        if($("#campaignTypeCode").val() == ""){
            $("#campaign-type-code-error").text("Please enter campaign type code");
        }
        else if($("#campaign-type-code-error").text() != ""){
            $("#campaign-type-code-error").text("Please provide unique campaign type code.");
        }
        else {
            $("#campaign-type-code-error").text("");
        }

        $("#campaignTypeForm").submit(function (e) {
            if($("#campaign-type-error").text() != "" || $("#campaign-type-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteCampaignType(campaignTypeId, identification) {
        $("#deleteCampaignTypeModal").modal('show');
        $("#hiddenCampaignTypeId").val(campaignTypeId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Campaign Type");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this campaign type?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Campaign Type");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this campaign type?");
        }
    }

    function confirmDeleteCampaignType() {
        $.ajax({
                type:'get',
                url: "/it-admin-campaign-type-delete",
                data: {'campaignTypeId' : $("#hiddenCampaignTypeId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-campaign-type'}}"}, 2000);     
                    }
                }
            });
    }

    function checkCampaignTypeCode() {
        var campaignTypeCode = $("#campaignTypeCode").val();
        var campaignTypeId = $("#hdnCampaignTypeId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-campaign-type-check',
            data: {'campaignTypeCode' : campaignTypeCode, 'campaignTypeId' : campaignTypeId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#campaign-type-code-error").text("Please provide unique campaign type code.");
                }
                else {
                    $("#campaign-type-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
