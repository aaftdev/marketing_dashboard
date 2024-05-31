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
                        <h5>Campaign Version</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createCampaignVersionID" onclick="createCampaignVersion(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-campaign-versionTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Version</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Version Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($campaignVersionList as $campaignVersion)
                                <tr>
                                    <td>{{ $campaignVersion->campaign_version_name }}</td>
                                    <td>{{ $campaignVersion->campaign_version_code }}</td>
                                    <td>
                                        @if($campaignVersion->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaignVersion->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Campaign Version" onclick="createCampaignVersion({{ $campaignVersion->campaign_version_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($campaignVersion->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Campaign Version" onclick="deleteCampaignVersion({{ $campaignVersion->campaign_version_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Campaign Version" onclick="deleteCampaignVersion({{ $campaignVersion->campaign_version_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Campaign Version Modal -->
<div class="modal fade" id="createCampaignVersionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Campaign Version</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-campaign-version-create')}}" method="post" id="campaignVersionForm">
          @csrf
          <input type="hidden" id="hdnCampaignVersionId" name="hdnCampaignVersionId" />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="campaignVersionCode">Campaign Version Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignVersionCode" name="campaignVersionCode" onchange="checkCampaignVersionCode();" maxlength="10" />
              <span id="campaign-version-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-5">
              <label for="campaignVersionCode">Campaign Version Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignVersionCode" name="campaignVersionCode" onchange="checkCampaignVersionCode();" maxlength="10" />
              <span id="campaign-version-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="campaignVersionName">Campaign Version</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignVersionName" name="campaignVersionName"  />
              <span id="campaign-version-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveCampaignVersionId" title="Save" onclick="saveCampaignVersion();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Campaign Version Modal -->
<div class="modal fade" id="deleteCampaignVersionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Campaign Version</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenCampaignVersionId" name="hiddenCampaignVersionId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this campaign version?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteCampaignVersion();">Yes</button>
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
        $("#it-campaign-versionTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaignVersion(campaignVersionId) {        
        $("#campaign-version-error").text("");
        $("#campaign-version-code-error").text("");
        if(campaignVersionId == 0) {
            $("#campaignVersionName").val('');
            $("#createCampaignVersionModal").modal('show');
            $("#saveCampaignVersionId").attr("title", "Save");
            $("#saveCampaignVersionId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Campaign Version");
            $("#campaignVersionCode").val('');
            $("#hdnCampaignVersionId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-campaign-version-edit",
                data: {'campaignVersionId' : campaignVersionId},
                success:function(data){
                    if(data){
                        $("#saveCampaignVersionId").attr("title", "Update");
                        $("#saveCampaignVersionId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Campaign Version")                       
                        $("#createCampaignVersionModal").modal('show');
                        $("#campaignVersionName").val(data[0]['campaign_version_name']);
                        $("#campaignVersionCode").val(data[0]['campaign_version_code']);
                        $("#hdnCampaignVersionId").val(campaignVersionId);        
                    }
                }
            });
        }
    }

    function saveCampaignVersion() {
        var campaignVersionId = $("#hdnCampaignVersionId").val();
        var campaignVersionCode = $("#campaignVersionCode").val();
        if($("#campaignVersionName").val() == "") {
            $("#campaign-version-error").text("Please enter campaign version");
        }
        else {
            $("#campaign-version-error").text("");
        }

        if($("#campaignVersionCode").val() == ""){
            $("#campaign-version-code-error").text("Please enter campaign version code");
        }
        else if($("#campaign-version-code-error").text() != "") {
            $("#campaign-version-code-error").text("Please provide unique campaign version code.");
        }
        else {
            $("#campaign-version-code-error").text("");
        }

        $("#campaignVersionForm").submit(function (e) {
            if($("#campaign-version-error").text() != "" || $("#campaign-version-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteCampaignVersion(campaignVersionId, identification) {
        $("#deleteCampaignVersionModal").modal('show');
        $("#hiddenCampaignVersionId").val(campaignVersionId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Campaign Version");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this campaign version?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Campaign Version");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this campaign version?");
        }
    }

    function confirmDeleteCampaignVersion() {
        $.ajax({
                type:'get',
                url: "/it-admin-campaign-version-delete",
                data: {'campaignVersionId' : $("#hiddenCampaignVersionId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-campaign-version'}}"}, 2000);     
                    }
                }
            });
    }

    function checkCampaignVersionCode() {
        var campaignVersionCode = $("#campaignVersionCode").val();
        var campaignVersionId = $("#hdnCampaignVersionId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-campaign-version-check',
            data: {'campaignVersionCode' : campaignVersionCode, 'campaignVersionId' : campaignVersionId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#campaign-version-code-error").text("Please provide unique campaign version code.");
                }
                else {
                    $("#campaign-version-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
