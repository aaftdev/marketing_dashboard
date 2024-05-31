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
                        <h5>Target Location</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createTargetLocationID" onclick="createTargetLocation(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-target-locationTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Target Location</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Target Location Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($targetLocationList as $targetLocation)
                                <tr>
                                    <td>{{ $targetLocation->target_location_name }}</td>
                                    <td>{{ $targetLocation->target_location_code }}</td>
                                    <td>
                                        @if($targetLocation->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($targetLocation->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Target Location" onclick="createTargetLocation({{ $targetLocation->target_location_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($targetLocation->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Target Location" onclick="deleteTargetLocation({{ $targetLocation->target_location_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Target Location" onclick="deleteTargetLocation({{ $targetLocation->target_location_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Target Location Modal -->
<div class="modal fade" id="createTargetLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Target Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-target-location-create')}}" method="post" id="targetLocationForm">
          @csrf
          <input type="hidden" id="hdnTargetLocationId" name="hdnTargetLocationId" />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="targetLocationCode">Target Location Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="targetLocationCode" name="targetLocationCode" onchange="checkTargetLocationCode();" maxlength="5" />
              <span id="target-location-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-5">
              <label for="targetLocationCode">Target Location Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="targetLocationCode" name="targetLocationCode" onchange="checkTargetLocationCode();" maxlength="5" />
              <span id="target-location-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="targetLocationName">Target Location</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="targetLocationName" name="targetLocationName"  />
              <span id="target-location-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveTargetLocationId" title="Save" onclick="saveTargetLocation();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Target Location Modal -->
<div class="modal fade" id="deleteTargetLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Target Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenTargetLocationId" name="hiddenTargetLocationId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this target location?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteTargetLocation();">Yes</button>
        <button data-bs-dismiss="modal" class="btn btn-danger" title="No">No</button>
        </div>
    </div>    
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminLandingPageID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminHomeID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminSettingsID").addClass( "active bg-primary bg-gradient" );
        $("#it-target-locationTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createTargetLocation(targetLocationId) {        
        $("#target-location-error").text("");
        $("#target-location-code-error").text("");
        if(targetLocationId == 0) {
            $("#targetLocationName").val('');
            $("#createTargetLocationModal").modal('show');
            $("#saveTargetLocationId").attr("title", "Save");
            $("#saveTargetLocationId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Target Location");
            $("#targetLocationCode").val('');
            $("#hdnTargetLocationId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-target-location-edit",
                data: {'targetLocationId' : targetLocationId},
                success:function(data){
                    if(data){
                        $("#saveTargetLocationId").attr("title", "Update");
                        $("#saveTargetLocationId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Target Location")                       
                        $("#createTargetLocationModal").modal('show');
                        $("#targetLocationName").val(data[0]['target_location_name']);
                        $("#targetLocationCode").val(data[0]['target_location_code']);
                        $("#hdnTargetLocationId").val(targetLocationId);        
                    }
                }
            });
        }
    }

    function saveTargetLocation() {
        
        var targetLocationId = $("#hdnTargetLocationId").val();
        var targetLocationCode = $("#targetLocationCode").val();
        if($("#targetLocationName").val() == "") {
            $("#target-location-error").text("Please enter target location");
        }
        else {
            $("#target-location-error").text("");
        }

        if($("#targetLocationCode").val() == ""){
            $("#target-location-code-error").text("Please enter target location code");
        }
        else if($("#target-location-code-error").text() !="") {
            $("#target-location-code-error").text("Please provide unique target location code.");
        }
        else {
            $("#target-location-code-error").text("");
        }

        $("#targetLocationForm").submit(function (e) {
            
            if($("#target-location-error").text() != "" || $("#target-location-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteTargetLocation(targetLocationId, identification) {
        $("#deleteTargetLocationModal").modal('show');
        $("#hiddenTargetLocationId").val(targetLocationId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Target Location");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this target location?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Target Location");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this target location?");
        }
    }

    function confirmDeleteTargetLocation() {
        $.ajax({
                type:'get',
                url: "/it-admin-target-location-delete",
                data: {'targetLocationId' : $("#hiddenTargetLocationId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-target-location'}}"}, 2000);     
                    }
                }
            });
    }

    function checkTargetLocationCode() {
        var targetLocationCode = $("#targetLocationCode").val();
        var targetLocationId = $("#hdnTargetLocationId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-target-location-check',
            data: {'targetLocationCode' : targetLocationCode, 'targetLocationId' : targetLocationId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#target-location-code-error").text("Please provide unique target location code.");
                }
                else {
                    $("#target-location-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
