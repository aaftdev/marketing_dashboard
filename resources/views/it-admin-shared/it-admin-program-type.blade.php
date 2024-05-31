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
                        <h5>Program Type</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createProgramTypeID" onclick="createProgramType(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-program-typeTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Program Type</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Program Type Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($programTypeList as $programType)
                                <tr>
                                    <td>{{ $programType->program_type_name }}</td>
                                    <td>{{ $programType->program_code }}</td>
                                    <td>
                                        @if($programType->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($programType->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Program Type" onclick="createProgramType({{ $programType->program_type_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($programType->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Program Type" onclick="deleteProgramType({{ $programType->program_type_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Program Type" onclick="deleteProgramType({{ $programType->program_type_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Program Type Modal -->
<div class="modal fade" id="createProgramTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Program Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-program-type-create')}}" method="post" id="programTypeForm">
          @csrf
          <input type="hidden" id="hdnProgramTypeId" name="hdnProgramTypeId" />
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="programTypeCode">Program Type Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="programTypeCode" name="programTypeCode" onchange="checkProgramTypeCode();" maxlength="5" />
              <span id="program-type-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-4">
              <label for="programTypeCode">Program Type Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="programTypeCode" name="programTypeCode" onchange="checkProgramTypeCode();" maxlength="5" />
              <span id="program-type-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="programTypeName">Program Type</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="programTypeName" name="programTypeName"  />
              <span id="program-type-error" class="text-danger"></span>
            </div>
          </div>          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveProgramTypeId" title="Save" onclick="saveProgramType();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Program Type Modal -->
<div class="modal fade" id="deleteProgramTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Program Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenProgramTypeId" name="hiddenProgramTypeId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this Program Type?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteProgramType();">Yes</button>
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
        $("#it-program-typeTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createProgramType(programTypeId) {        
        $("#program-type-error").text("");
        $("#program-type-code-error").text("");
        if(programTypeId == 0) {
            $("#programTypeName").val('');
            $("#createProgramTypeModal").modal('show');
            $("#saveProgramTypeId").attr("title", "Save");
            $("#saveProgramTypeId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Program Type");
            $("#programTypeCode").val('');
            $("#hdnProgramTypeId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-program-type-edit",
                data: {'programTypeId' : programTypeId},
                success:function(data){
                    if(data){
                        $("#saveProgramTypeId").attr("title", "Update");
                        $("#saveProgramTypeId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Program Type")                       
                        $("#createProgramTypeModal").modal('show');
                        $("#programTypeName").val(data[0]['program_type_name']);
                        $("#programTypeCode").val(data[0]['program_code']);
                        $("#hdnProgramTypeId").val(programTypeId);        
                    }
                }
            });
        }
    }

    function saveProgramType() {
        var programTypeId = $("#hdnProgramTypeId").val();
        var programTypeCode = $("#programTypeCode").val();
        if($("#programTypeName").val() == "") {
            $("#program-type-error").text("Please enter program type");
        }
        else {
            $("#program-type-error").text("");
        }

        if($("#programTypeCode").val() == ""){
            $("#program-type-code-error").text("Please enter program type code");
        }
        else if($("#program-type-code-error").text() != "") {
            $("#program-type-code-error").text("Please provide unique program type code.");
        }
        else {
            $("#program-type-code-error").text("");
        }

        $("#programTypeForm").submit(function (e) {
            if($("#program-type-error").text() != "" || $("#program-type-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteProgramType(programTypeId, identification) {
        $("#deleteProgramTypeModal").modal('show');
        $("#hiddenProgramTypeId").val(programTypeId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Program Type");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this program type?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Program Type");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this program type?");
        }
    }

    function confirmDeleteProgramType() {
        $.ajax({
                type:'get',
                url: "/it-admin-program-type-delete",
                data: {'programTypeId' : $("#hiddenProgramTypeId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-program-type'}}"}, 2000);     
                    }
                }
            });
    }

    function checkProgramTypeCode() {
        var programTypeCode = $("#programTypeCode").val();
        var programTypeId = $("#hdnProgramTypeId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-program-type-check',
            data: {'programTypeCode' : programTypeCode, 'programTypeId' : programTypeId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#program-type-code-error").text("Please provide unique program type code.");
                }
                else {
                    $("#program-type-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
