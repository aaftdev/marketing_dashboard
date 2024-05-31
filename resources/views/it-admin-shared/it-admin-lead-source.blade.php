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
                        <h5>Lead Source</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createLeadSourceID" onclick="createLeadSource(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-LeadSourceTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Lead Source</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($leadsourceList as $leadSource)
                                <tr>
                                    <td>{{ $leadSource->leadsource_name }}</td>
                                    <td>
                                        @if($leadSource->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($leadSource->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Lead Source" onclick="createLeadSource({{ $leadSource->leadsource_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif    
                                        @if($leadSource->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Lead Source" onclick="deleteleadSource({{ $leadSource->leadsource_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Lead Source" onclick="deleteleadSource({{ $leadSource->leadsource_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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


<!-- Create Lead Source Modal -->
<div class="modal fade" id="createLeadSourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Lead Source</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-lead-source-create')}}" method="post" id="leadSourceForm">
          @csrf
          <input type="hidden" id="hdnLeadSourceId" name="hdnLeadSourceId" />
          <div class="row form-group">
            <div class="col-md-3">
              <label for="leadSourceName">Lead Source</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="leadSourceName" name="leadSourceName" />
              <span id="leadSource-error" class="text-danger"></span>
            </div>
          </div>
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveLeadSourceId" title="Save" onclick="saveLeadSource();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Lead Source Modal -->
<div class="modal fade" id="deleteLeadSourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Lead Source</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenLeadSourceId" name="hiddenLeadSourceId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this Lead Source?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteLeadSource();">Yes</button>
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
        $("#it-LeadSourceTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createLeadSource(leadSourceId) {        
        $("#leadSource-error").text("");
        if(leadSourceId == 0) {
            $("#leadSourceName").val('');
            $("#createLeadSourceModal").modal('show');
            $("#saveLeadSourceId").attr("title", "Save");
            $("#saveLeadSourceId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Lead Source");
            $("#hdnLeadSourceId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-lead-source-edit",
                data: {'leadSourceId' : leadSourceId},
                success:function(data){
                    if(data){
                        $("#saveLeadSourceId").attr("title", "Update");
                        $("#saveLeadSourceId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Lead Source")                       
                        $("#createLeadSourceModal").modal('show');
                        $("#leadSourceName").val(data[0]['leadsource_name']);
                        $("#hdnLeadSourceId").val(leadSourceId);        
                    }
                }
            });
        }
    }

    function saveLeadSource() {
        var leadSourceId = $("#hdnLeadSourceId").val();
        if($("#leadSourceName").val() == "") {
            $("#leadSource-error").text("Please enter Lead Source");
        }
        else {
            $("#leadSource-error").text("");
        }
        $("#leadSourceForm").submit(function (e) {
            if($("#leadSource-error").text() != ""){
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteleadSource(leadSourceId, identification) {
        $("#deleteLeadSourceModal").modal('show');
        $("#hiddenLeadSourceId").val(leadSourceId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Lead Source");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this lead source?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Lead Source");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this lead source?");
        }
    }

    function confirmDeleteLeadSource() {
        $.ajax({
                type:'get',
                url: "/it-admin-lead-source-delete",
                data: {'leadSourceId' : $("#hiddenLeadSourceId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-lead-source'}}"}, 2000);     
                    }
                }
            });
    }
        
</script>

@endsection