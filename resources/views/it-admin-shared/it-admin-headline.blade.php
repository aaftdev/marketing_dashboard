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
                        <h5>Headline</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createHeadlineID" onclick="createHeadline(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-headlineTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Headline</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Headline Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($headlineList as $headline)
                                <tr>
                                    <td>{{ $headline->headline_name }}</td>
                                    <td>{{ $headline->headline_code }}</td>
                                    <td>
                                        @if($headline->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($headline->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Headline" onclick="createHeadline({{ $headline->headline_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($headline->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete CHeadline" onclick="deleteHeadline({{ $headline->headline_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Headline" onclick="deleteHeadline({{ $headline->headline_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Headline Modal -->
<div class="modal fade" id="createHeadlineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Headline</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-headline-create')}}" method="post" id="headlineForm">
          @csrf
          <input type="hidden" id="hdnHeadlineId" name="hdnHeadlineId" />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="headlineCode">Headline Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="headlineCode" name="headlineCode" onchange="checkHeadlineCode();" maxlength="5" />
              <span id="headline-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-5">
              <label for="headlineCode">Headline Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="headlineCode" name="headlineCode" onchange="checkHeadlineCode();" maxlength="5" />
              <span id="headline-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="headlineName">Headline</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="headlineName" name="headlineName"  />
              <span id="headline-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveHeadlineId" title="Save" onclick="saveHeadline();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Headline Modal -->
<div class="modal fade" id="deleteHeadlineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Headline</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenHeadlineId" name="hiddenHeadlineId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this headline?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteHeadline();">Yes</button>
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
        $("#it-headlineTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createHeadline(headlineId) {        
        $("#headline-error").text("");
        $("#headline-code-error").text("");
        if(headlineId == 0) {
            $("#headlineName").val('');
            $("#createHeadlineModal").modal('show');
            $("#saveHeadlineId").attr("title", "Save");
            $("#saveHeadlineId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Headline");
            $("#headlineCode").val('');
            $("#hdnHeadlineId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-headline-edit",
                data: {'headlineId' : headlineId},
                success:function(data){
                    if(data){
                        $("#saveHeadlineId").attr("title", "Update");
                        $("#saveHeadlineId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Headline")                       
                        $("#createHeadlineModal").modal('show');
                        $("#headlineName").val(data[0]['headline_name']);
                        $("#headlineCode").val(data[0]['headline_code']);
                        $("#hdnHeadlineId").val(headlineId);        
                    }
                }
            });
        }
    }

    function saveHeadline() {
        
        var headlineId = $("#hdnHeadlineId").val();
        var headlineCode = $("#headlineCode").val();
        if($("#headlineName").val() == "") {
            $("#headline-error").text("Please enter headline");
        }
        else {
            $("#headline-error").text("");
        }

        if($("#headlineCode").val() == ""){
            $("#headline-code-error").text("Please enter headline code");
        }
        else if($("#headline-code-error").text() != "") {
            $("#headline-code-error").text("Please provide unique headline code.");
        }
        else {
            $("#headline-code-error").text("");
        }

        $("#headlineForm").submit(function (e) {
            
            if($("#headline-error").text() != "" || $("#headline-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteHeadline(headlineId, identification) {
        $("#deleteHeadlineModal").modal('show');
        $("#hiddenHeadlineId").val(headlineId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Headline");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this headline?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Headline");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this headline?");
        }
    }

    function confirmDeleteHeadline() {
        $.ajax({
                type:'get',
                url: "/it-admin-headline-delete",
                data: {'headlineId' : $("#hiddenHeadlineId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-headline'}}"}, 2000);     
                    }
                }
            });
    }

    function checkHeadlineCode() {
        var headlineCode = $("#headlineCode").val();
        var headlineId = $("#hdnHeadlineId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-headline-check',
            data: {'headlineCode' : headlineCode, 'headlineId' : headlineId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#headline-code-error").text("Please provide unique headline code.");
                }
                else {
                    $("#headline-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
