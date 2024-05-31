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
                        <h5>Campaign Status</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createCampaignStatusID" onclick="createCampaignStatus(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-campaign-statusTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($campaignStatusList as $campaignStatus)
                                <tr>
                                    <td>{{ $campaignStatus->campaign_status_name }}</td>                                   
                                    <td>
                                        @if($campaignStatus->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaignStatus->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Campaign Status" onclick="createCampaignStatus({{ $campaignStatus->campaign_status_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($campaignStatus->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Campaign Status" onclick="deleteCampaignStatus({{ $campaignStatus->campaign_status_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Campaign Status" onclick="deleteCampaignStatus({{ $campaignStatus->campaign_status_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Campaign Status Modal -->
<div class="modal fade" id="createCampaignStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Campaign Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-campaign-status-create')}}" method="post" id="campaignStatusForm">
          @csrf
          <input type="hidden" id="hdnCampaignStatusId" name="hdnCampaignStatusId" />
          <div class="row form-group">
            <div class="col-md-5">
              <label for="campaignStatusName">Campaign Status</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignStatusName" name="campaignStatusName"  />
              <span id="campaign-status-error" class="text-danger"></span>
            </div>
          </div>
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveCampaignStatusId" title="Save" onclick="saveCampaignStatus();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Campaign Status Modal -->
<div class="modal fade" id="deleteCampaignStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Campaign Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenCampaignStatusId" name="hiddenCampaignStatusId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this campaign status?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteCampaignStatus();">Yes</button>
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
        $("#it-campaign-statusTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaignStatus(campaignStatusId) {        
        $("#campaign-status-error").text("");        
        if(campaignStatusId == 0) {
            $("#campaignStatusName").val('');
            $("#createCampaignStatusModal").modal('show');
            $("#saveCampaignStatusId").attr("title", "Save");
            $("#saveCampaignStatusId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Campaign Status");
            $("#campaignStatusCode").val('');
            $("#hdnCampaignStatusId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-campaign-status-edit",
                data: {'campaignStatusId' : campaignStatusId},
                success:function(data){
                    if(data){
                        $("#saveCampaignStatusId").attr("title", "Update");
                        $("#saveCampaignStatusId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Campaign Status")                       
                        $("#createCampaignStatusModal").modal('show');
                        $("#campaignStatusName").val(data[0]['campaign_status_name']);                        
                        $("#hdnCampaignStatusId").val(campaignStatusId);        
                    }
                }
            });
        }
    }

    function saveCampaignStatus() {
        var campaignStatusId = $("#hdnCampaignStatusId").val();
        
        if($("#campaignStatusName").val() == "") {
            $("#campaign-status-error").text("Please enter campaign status");
        }
        else {
            $("#campaign-status-error").text("");
        }

        $("#campaignStatusForm").submit(function (e) {
            if($("#campaign-status-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteCampaignStatus(campaignStatusId, identification) {
        $("#deleteCampaignStatusModal").modal('show');
        $("#hiddenCampaignStatusId").val(campaignStatusId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Campaign Status");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this campaign status?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Campaign Status");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this campaign status?");
        }
    }

    function confirmDeleteCampaignStatus() {
        $.ajax({
                type:'get',
                url: "/it-admin-campaign-status-delete",
                data: {'campaignStatusId' : $("#hiddenCampaignStatusId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-campaign-status'}}"}, 2000);     
                    }
                }
            });
    }
        
</script>

@endsection