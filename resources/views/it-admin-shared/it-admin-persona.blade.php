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
                        <h5>Persona</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createPersonaID" onclick="createPersona(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-PersonaTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Persona</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Persona Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($personaList as $persona)
                                <tr>
                                    <td>{{ $persona->persona_name }}</td>
                                    <td>{{ $persona->persona_code }}</td>
                                    <td>
                                        @if($persona->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($persona->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Persona" onclick="createPersona({{ $persona->persona_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($persona->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Persona" onclick="deletePersona({{ $persona->persona_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Persona" onclick="deletePersona({{ $persona->persona_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Persona Modal -->
<div class="modal fade" id="createPersonaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Persona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-persona-create')}}" method="post" id="personaForm">
          @csrf
          <input type="hidden" id="hdnPersonaId" name="hdnPersonaId" />
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="personaCode">Persona Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="personaCode" name="personaCode" onchange="checkPersonaCode();" maxlength="5" />
              <span id="persona-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-4">
              <label for="personaCode">Persona Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="personaCode" name="personaCode" onchange="checkPersonaCode();" maxlength="5" />
              <span id="persona-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="personaName">Persona</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="personaName" name="personaName"  />
              <span id="persona-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="savePersonaId" title="Save" onclick="savePersona();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Persona Modal -->
<div class="modal fade" id="deletePersonaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Persona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenPersonaId" name="hiddenPersonaId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this persona?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeletePersona();">Yes</button>
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
        $("#it-PersonaTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createPersona(personaId) {        
        $("#persona-error").text("");
        $("#persona-code-error").text("");
        if(personaId == 0) {
            $("#personaName").val('');
            $("#createPersonaModal").modal('show');
            $("#savePersonaId").attr("title", "Save");
            $("#savePersonaId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Persona");
            $("#personaCode").val('');
            $("#hdnPersonaId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-persona-edit",
                data: {'personaId' : personaId},
                success:function(data){
                    if(data){
                        $("#savePersonaId").attr("title", "Update");
                        $("#savePersonaId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Persona")                       
                        $("#createPersonaModal").modal('show');
                        $("#personaName").val(data[0]['persona_name']);
                        $("#personaCode").val(data[0]['persona_code']);
                        $("#hdnPersonaId").val(personaId);        
                    }
                }
            });
        }
    }

    function savePersona() {
        
        var personaId = $("#hdnPersonaId").val();
        var personaCode = $("#personaCode").val();
        if($("#personaName").val() == "") {
            $("#persona-error").text("Please enter an persona");
        }
        else {
            $("#persona-error").text("");
        }

        if($("#personaCode").val() == ""){
            $("#persona-code-error").text("Please enter persona code");
        }
        else if($("#persona-code-error").text() != "") {
            $("#persona-code-error").text("Please provide unique persona code.");
        }
        else {
            $("#persona-code-error").text("");
        }

        $("#personaForm").submit(function (e) {
            if($("#persona-error").text() != "" || $("#persona-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deletePersona(personaId, identification) {
        $("#deletePersonaModal").modal('show');
        $("#hiddenPersonaId").val(personaId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Persona");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this persona?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Persona");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this persona?");
        }
    }

    function confirmDeletePersona() {
        $.ajax({
                type:'get',
                url: "/it-admin-persona-delete",
                data: {'personaId' : $("#hiddenPersonaId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-persona'}}"}, 2000);     
                    }
                }
            });
    }

    function checkPersonaCode() {
        var personaCode = $("#personaCode").val();
        var personaId = $("#hdnPersonaId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-persona-check',
            data: {'personaCode' : personaCode, 'personaId' : personaId},
            success:function(data){
                if(data != "" && data[0]['count'] > 0) {
                    $("#persona-code-error").text("Please provide unique persona code.");
                }
                else {
                    $("#persona-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
