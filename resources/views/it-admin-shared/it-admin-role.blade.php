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
                        <h5>Role</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createRoleID" onclick="createRole(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-roleTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Role</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($roleList as $role)
                                <tr>
                                    <td>{{ $role->role_name }}</td>
                                    <td>
                                        @if($role->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($role->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Role" onclick="createRole({{ $role->role_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($role->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Role" onclick="deleteRole({{ $role->role_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Role" onclick="deleteRole({{ $role->role_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-role-create')}}" method="post" id="roleForm">
          @csrf
          <input type="hidden" id="hdnRoleId" name="hdnRoleId" />
          <div class="row form-group">
            <div class="col-md-3">
              <label for="roleName">Role Name</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="roleName" name="roleName" />
              <span id="role-error" class="text-danger"></span>
            </div>
          </div>
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveRoleId" title="Save" onclick="saveRole();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Role Modal -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenRoleId" name="hiddenRoleId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this role?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteRole();">Yes</button>
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
        $("#it-roleTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createRole(roleId) {        
        $("#role-error").text("");
        if(roleId == 0) {
            $("#roleName").val('');
            $("#createRoleModal").modal('show');
            $("#saveRoleId").attr("title", "Save");
            $("#saveRoleId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Role");
            $("#hdnRoleId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-role-edit",
                data: {'roleId' : roleId},
                success:function(data){
                    if(data){
                        $("#saveRoleId").attr("title", "Update");
                        $("#saveRoleId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Role")                       
                        $("#createRoleModal").modal('show');
                        $("#roleName").val(data[0]['role_name']);
                        $("#hdnRoleId").val(roleId);        
                    }
                }
            });
        }
    }

    function saveRole() {
        var roleId = $("#hdnRoleId").val();
        if($("#roleName").val() == "") {
            $("#role-error").text("Please enter a role");
        }
        else {
            $("#role-error").text("");
        }
        $("#roleForm").submit(function (e) {
            if($("#role-error").text() != ""){
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteRole(roleId, identification) {
        $("#deleteRoleModal").modal('show');
        $("#hiddenRoleId").val(roleId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Role");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this role?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Role");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this role?");
        }
    }

    function confirmDeleteRole() {
        $.ajax({
                type:'get',
                url: "/it-admin-role-delete",
                data: {'roleId' : $("#hiddenRoleId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-role'}}"}, 2000);     
                    }
                }
            });
    }
        
</script>

@endsection