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
                        <h5>Agency</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createAgencyID" onclick="createAgency(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-agencyTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Agency</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Agency Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($agencyList as $agency)
                                <tr>
                                    <td>{{ $agency->agency_name }}</td>
                                    <td>{{ $agency->agency_code }}</td>
                                    <td>
                                        @if($agency->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($agency->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Agency" onclick="createAgency({{ $agency->agency_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($agency->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Agency" onclick="deleteAgency({{ $agency->agency_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Agency" onclick="deleteAgency({{ $agency->agency_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Agency Modal -->
<div class="modal fade" id="createAgencyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Agency</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-agency-create')}}" method="post" id="agencyForm">
          @csrf
          <input type="hidden" id="hdnAgencyId" name="hdnAgencyId" />
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="agencyCode">Agency Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="agencyCode" name="agencyCode" onchange="checkAgencyCode();" maxlength="5" />
              <span id="agency-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-4">
              <label for="agencyName">Agency Name</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="agencyName" name="agencyName"  />
              <span id="agency-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveAgencyId" title="Save" onclick="saveAgency();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Agency Modal -->
<div class="modal fade" id="deleteAgencyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenAgencyId" name="hiddenAgencyId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this agency?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteAgency();">Yes</button>
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
        $("#it-agencyTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createAgency(agencyId) {        
        $("#agency-error").text("");
        $("#agency-code-error").text("");
        if(agencyId == 0) {
            $("#agencyName").val('');
            $("#createAgencyModal").modal('show');
            $("#saveAgencyId").attr("title", "Save");
            $("#saveAgencyId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Agency");
            $("#agencyCode").val('');
            $("#hdnAgencyId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-agency-edit",
                data: {'agencyId' : agencyId},
                success:function(data){
                    if(data){
                        $("#saveAgencyId").attr("title", "Update");
                        $("#saveAgencyId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Agency")                       
                        $("#createAgencyModal").modal('show');
                        $("#agencyName").val(data[0]['agency_name']);
                        $("#agencyCode").val(data[0]['agency_code']);
                        $("#hdnAgencyId").val(agencyId);        
                    }
                }
            });
        }
    }

    function saveAgency() {
        debugger;
        var agencyId = $("#hdnAgencyId").val();
        var agencyCode = $("#agencyCode").val();
        if($("#agencyName").val() == "") {
            $("#agency-error").text("Please enter an agency");
        }
        else {
            $("#agency-error").text("");
        }

        if($("#agencyCode").val() == ""){
            $("#agency-code-error").text("Please enter an agency code");
        }
        else if($("#agency-code-error").text() != "") {
            $("#agency-code-error").text("Please provide unique agency code.");
        }
        else {
            $("#agency-code-error").text("");
        }

        $("#agencyForm").submit(function (e) {
            if($("#agency-error").text() != "" || $("#agency-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteAgency(agencyId, identification) {
        $("#deleteAgencyModal").modal('show');
        $("#hiddenAgencyId").val(agencyId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Agency");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this agency?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Agency");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this agency?");
        }
    }

    function confirmDeleteAgency() {
        $.ajax({
                type:'get',
                url: "/it-admin-agency-delete",
                data: {'agencyId' : $("#hiddenAgencyId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-agency'}}"}, 2000);     
                    }
                }
            });
    }

    function checkAgencyCode() {
        var agencyCode = $("#agencyCode").val();
        var agencyId = $("#hdnAgencyId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-agency-check',
            data: {'agencyCode' : agencyCode, 'agencyId' : agencyId},
            success:function(data){
                debugger;
                if(data != "" && data[0]['count'] > 0) {
                    $("#agency-code-error").text("Please provide unique agency code.");
                }
                else {
                    $("#agency-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
