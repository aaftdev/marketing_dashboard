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
                        <h5>Campaign Size</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createCampaignSizeID" onclick="createCampaignSize(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-campaign-sizeTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Size</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Size Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($campaignSizeList as $campaignSize)
                                <tr>
                                    <td>{{ $campaignSize->campaign_size_name }}</td>
                                    <td>{{ $campaignSize->campaign_size_code }}</td>
                                    <td>
                                        @if($campaignSize->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaignSize->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Campaign Size" onclick="createCampaignSize({{ $campaignSize->campaign_size_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($campaignSize->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Campaign Size" onclick="deleteCampaignSize({{ $campaignSize->campaign_size_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Campaign Size" onclick="deleteCampaignSize({{ $campaignSize->campaign_size_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Campaign Size Modal -->
<div class="modal fade" id="createCampaignSizeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Campaign Size</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-campaign-size-create')}}" method="post" id="campaignSizeForm">
          @csrf
          <input type="hidden" id="hdnCampaignSizeId" name="hdnCampaignSizeId" />
          <div class="row form-group">
            <div class="col-md-5">
              <label for="campaignSizeCode">Campaign Size Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignSizeCode" name="campaignSizeCode" onchange="checkCampaignSizeCode();" maxlength="10" />
              <span id="campaign-size-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="campaignSizeName">Campaign Size</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignSizeName" name="campaignSizeName"  />
              <span id="campaign-size-error" class="text-danger"></span>
            </div>
          </div>          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveCampaignSizeId" title="Save" onclick="saveCampaignSize();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Campaign Size Modal -->
<div class="modal fade" id="deleteCampaignSizeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Campaign Size</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenCampaignSizeId" name="hiddenCampaignSizeId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this campaign size?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteCampaignSize();">Yes</button>
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
        $("#it-campaign-sizeTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaignSize(campaignSizeId) {        
        $("#campaign-size-error").text("");
        $("#campaign-size-code-error").text("");
        if(campaignSizeId == 0) {
            $("#campaignSizeName").val('');
            $("#createCampaignSizeModal").modal('show');
            $("#saveCampaignSizeId").attr("title", "Save");
            $("#saveCampaignSizeId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Campaign Size");
            $("#campaignSizeCode").val('');
            $("#hdnCampaignSizeId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-campaign-size-edit",
                data: {'campaignSizeId' : campaignSizeId},
                success:function(data){
                    if(data){
                        $("#saveCampaignSizeId").attr("title", "Update");
                        $("#saveCampaignSizeId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Campaign Size")                       
                        $("#createCampaignSizeModal").modal('show');
                        $("#campaignSizeName").val(data[0]['campaign_size_name']);
                        $("#campaignSizeCode").val(data[0]['campaign_size_code']);
                        $("#hdnCampaignSizeId").val(campaignSizeId);        
                    }
                }
            });
        }
    }

    function saveCampaignSize() {
        var campaignSizeId = $("#hdnCampaignSizeId").val();
        var campaignSizeCode = $("#campaignSizeCode").val();
        if($("#campaignSizeName").val() == "") {
            $("#campaign-size-error").text("Please enter campaign size");
        }
        else {
            $("#campaign-size-error").text("");
        }

        if($("#campaignSizeCode").val() == ""){
            $("#campaign-size-code-error").text("Please enter campaign size code");
        }
        else if($("#campaign-size-code-error").text() != ""){
            $("#campaign-size-code-error").text("Please provide unique campaign size code.");
        }
        else {
            $("#campaign-size-code-error").text("");
        }

        $("#campaignSizeForm").submit(function (e) {
            if($("#campaign-size-error").text() != "" || $("#campaign-size-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteCampaignSize(campaignSizeId, identification) {
        $("#deleteCampaignSizeModal").modal('show');
        $("#hiddenCampaignSizeId").val(campaignSizeId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Campaign Size");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this campaign size?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Campaign Size");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this campaign size?");
        }
    }

    function confirmDeleteCampaignSize() {
        $.ajax({
                type:'get',
                url: "/it-admin-campaign-size-delete",
                data: {'campaignSizeId' : $("#hiddenCampaignSizeId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-campaign-size'}}"}, 2000);     
                    }
                }
            });
    }

    function checkCampaignSizeCode() {
        var campaignSizeCode = $("#campaignSizeCode").val();
        var campaignSizeId = $("#hdnCampaignSizeId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-campaign-size-check',
            data: {'campaignSizeCode' : campaignSizeCode, 'campaignSizeId' : campaignSizeId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#campaign-size-code-error").text("Please provide unique campaign size code.");
                }
                else {
                    $("#campaign-size-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
