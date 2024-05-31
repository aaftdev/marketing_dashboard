@extends('it-admin-shared.it-admin-master')

@section('it-adminContent')
  <style>  
      .field-icon {
        float: left;
        margin-left: 235px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
      }
    </style>
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
                        <h5>Users</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createUserID" onclick="createUsers(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-userTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Name</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Username</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Email</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Role</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($userRegistrationList as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role_name }}</td>                                    
                                    <td>
                                        @if($user->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit User" onclick="createUsers({{ $user->user_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif    
                                        @if($user->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete User" onclick="deleteUsers({{ $user->user_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover User" onclick="deleteUsers({{ $user->user_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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


<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-users-create')}}" method="post" id="userForm">
          @csrf
          <input type="hidden" id="hdnUserId" name="hdnUserId" />
          <div class="row form-group">
            <div class="col-md-4">
              <label for="firstName">First Name</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="firstName" name="firstName" />
              <span id="firstName-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="lastName">Last Name</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="lastName" name="lastName" />
              <span id="lastName-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="email">Email</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="email" name="email" />
              <span id="email-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="username">Username</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="username" name="username" />
              <span id="username-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2" id="passwordDiv">
            <div class="col-md-4">
              <label for="password">Password</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="password" class="form-control" id="password" name="password" />
              <i class="fa fa-fw fa-eye field-icon toggle-password" toggle="#password"></i>
              <span id="password-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2" id="confnPasswordDiv">
            <div class="col-md-4">
              <label for="cnfnPassword">Confirm Password</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="password" class="form-control" id="cnfnPassword" name="cnfnPassword" />
              <i class="fa fa-fw fa-eye field-icon toggle-password" toggle="#cnfnPassword"></i>
              <span id="cnfnPassword-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-4">
              <label for="role">Role</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <select name="role" id="role" class="form-control">
                <option value="">--Select--</option>
                @foreach($roleList as $role)
                    <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                @endforeach
              </select>
              <span id="role-error" class="text-danger"></span>
            </div>
          </div>
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveUserId" title="Save" onclick="saveUser();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenUserId" name="hiddenUserId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteUser();">Yes</button>
        <button data-bs-dismiss="modal" class="btn btn-danger" title="No">No</button>
        </div>
    </div>    
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").removeClass( "active bg-primary bg-gradient");
        $("#it-adminLandingPageID").removeClass( "active bg-primary bg-gradient");
        $("#it-adminHomeID").removeClass( "active bg-primary bg-gradient");
        $("#it-adminSettingsID").addClass( "active bg-primary bg-gradient");
        $("#it-userTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createUsers(userId) {        
        $("#firstName-error").text("");
        $("#lastName-error").text("");
        $("#email-error").text("");
        $("#username-error").text("");
        $("#role-error").text("");        
        $("#password-error").text("");
        $("#cnfnPassword-error").text("");

        if(userId == 0) {
            $("#firstName").val('');
            $("#lastName").val('');
            $("#email").val('');
            $("#username").val('');
            $("#role").val('');
            $("#password").val('');
            $("#cnfnPassword").val('');
            $("#createUserModal").modal('show');
            $("#saveUserId").attr("title", "Save");
            $("#saveUserId").empty().html("Save");
            $("#modalTitleId").empty().html("Create User");
            $("#hdnUserId").val('');
            $("#passwordDiv").show();
            $("#confnPasswordDiv").show();
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-users-edit",
                data: {'userId' : userId},
                success:function(data){
                    if(data){                        
                        $("#saveUserId").attr("title", "Update");
                        $("#saveUserId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update User")                       
                        $("#createUserModal").modal('show');
                        $("#firstName").val(data[0]['first_name']);
                        $("#lastName").val(data[0]['last_name']);
                        $("#email").val(data[0]['email']);
                        $("#username").val(data[0]['username']);
                        $("#role").val(data[0]['fk_role_id']);
                        $("#passwordDiv").hide();
                        $("#confnPasswordDiv").hide();
                        $("#hdnUserId").val(userId);        
                    }
                }
            });
        }
    }

    function saveUser() {
        debugger;
        var userId = $("#hdnUserId").val();
        var firstName = $("#firstName").val();
        var lastName = $("#lastName").val();
        var email = $("#email").val();
        var username = $("#username").val();
        var role = $("#role").val();
        var password = $("#password").val();
        var confirmPassword = $("#cnfnPassword").val();
        let pattern = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$"); 
        if(firstName == "") {
            $("#firstName-error").text("Please enter first name");
        }
        else {
            $("#firstName-error").text("");
        }

        if(lastName == "") {
            $("#lastName-error").text("Please enter last name");
        }
        else {
            $("#lastName-error").text("");
        }

        if(email == "") {
            $("#email-error").text("Please enter email");
        }
        else {
            $("#email-error").text("");
        }

        if(username == "") {
            $("#username-error").text("Please enter username");
        }
        else {
            $("#username-error").text("");
        }

        if(role == "") {
            $("#role-error").text("Please select role");
        }
        else {
            $("#role-error").text("");
        }

        if(password == "" && userId == 0) {
            $("#password-error").text("Please enter password");
        }
        else if(!pattern.test(password) && userId == 0) {
            $("#password-error").text("Password should contain atleast one uppercase, lowercase, number and special character.");
        }
        else {
            $("#password-error").text("");
        }

        if(confirmPassword == "" && userId == 0) {
            $("#cnfnPassword-error").text("Please enter confirm password");
        }
        else if(confirmPassword != password && userId == 0) {
            $("#cnfnPassword-error").text("Password and confirm password does not match");
        }
        else {
            $("#cnfnPassword-error").text("");
        }

        $("#userForm").submit(function (e) {          
            if($("#firstName-error").text() != "" || $("#lastName-error").text() != "" || $("#email-error").text() != "" || $("#username-error").text() != "" || $("#role-error").text() != "" || $("#password-error").text() != "" || $("#cnfnPassword-error").text() != "" ){
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteUsers(userId, identification) {
        $("#deleteUserModal").modal('show');
        $("#hiddenUserId").val(userId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete User");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this user?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover User");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this user?");
        }
    }

    function confirmDeleteUser() {
        $.ajax({
                type:'get',
                url: "/it-admin-users-delete",
                data: {'userId' : $("#hiddenUserId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-users'}}"}, 2000);     
                    }
                }
            });
    }

    $(".toggle-password").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
        
</script>

@endsection