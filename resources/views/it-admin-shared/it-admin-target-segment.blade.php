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
                        <h5>Target Segment</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createTargetSegmentID" onclick="createTargetSegment(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-target-segmentTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Target Segment</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Target Segment Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($targetSegmentList as $targetSegment)
                                <tr>
                                    <td>{{ $targetSegment->target_segment_name }}</td>
                                    <td>{{ $targetSegment->target_segment_code }}</td>
                                    <td>
                                        @if($targetSegment->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($targetSegment->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Target Segment" onclick="createTargetSegment({{ $targetSegment->target_segment_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($targetSegment->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Target Segment" onclick="deleteTargetSegment({{ $targetSegment->target_segment_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Target Segment" onclick="deleteTargetSegment({{ $targetSegment->target_segment_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Target Segment Modal -->
<div class="modal fade" id="createTargetSegmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Target Segment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-target-segment-create')}}" method="post" id="targetSegmentForm">
          @csrf
          <input type="hidden" id="hdnTargetSegmentId" name="hdnTargetSegmentId" />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="targetSegmentCode">Target Segment Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="targetSegmentCode" name="targetSegmentCode" onchange="checkTargetSegmentCode();" maxlength="5" />
              <span id="target-segment-code-error" class="text-danger"></span>
            </div>
          </div>  
          <div class="row form-group">
            <div class="col-md-5">
              <label for="targetSegmentCode">Target Segment Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="targetSegmentCode" name="targetSegmentCode" onchange="checkTargetSegmentCode();" maxlength="5" />
              <span id="target-segment-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="targetSegmentName">Target Segment</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="targetSegmentName" name="targetSegmentName"  />
              <span id="target-segment-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveTargetSegmentId" title="Save" onclick="saveTargetSegment();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Target Segment Modal -->
<div class="modal fade" id="deleteTargetSegmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Target Segment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenTargetSegmentId" name="hiddenTargetSegmentId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this target segment?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteTargetSegment();">Yes</button>
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
        $("#it-target-segmentTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createTargetSegment(targetSegmentId) {        
        $("#target-segment-error").text("");
        $("#target-segment-code-error").text("");
        if(targetSegmentId == 0) {
            $("#targetSegmentName").val('');
            $("#createTargetSegmentModal").modal('show');
            $("#saveTargetSegmentId").attr("title", "Save");
            $("#saveTargetSegmentId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Target Segment");
            $("#targetSegmentCode").val('');
            $("#hdnTargetSegmentId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-target-segment-edit",
                data: {'targetSegmentId' : targetSegmentId},
                success:function(data){
                    if(data){
                        $("#saveTargetSegmentId").attr("title", "Update");
                        $("#saveTargetSegmentId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Target Segment")                       
                        $("#createTargetSegmentModal").modal('show');
                        $("#targetSegmentName").val(data[0]['target_segment_name']);
                        $("#targetSegmentCode").val(data[0]['target_segment_code']);
                        $("#hdnTargetSegmentId").val(targetSegmentId);        
                    }
                }
            });
        }
    }

    function saveTargetSegment() {
        var targetSegmentId = $("#hdnTargetSegmentId").val();
        var targetSegmentCode = $("#targetSegmentCode").val();
        if($("#targetSegmentName").val() == "") {
            $("#target-segment-error").text("Please enter target segment");
        }
        else {
            $("#target-segment-error").text("");
        }

        if($("#targetSegmentCode").val() == ""){
            $("#target-segment-code-error").text("Please enter target segment code");
        }
        else if($("#target-segment-code-error").text() != "") {
            $("#target-segment-code-error").text("Please provide unique target segment code.");
        }
        else {
            $("#target-segment-code-error").text("");
        }

        $("#targetSegmentForm").submit(function (e) {
            if($("#target-segment-error").text() != "" || $("#target-segment-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteTargetSegment(targetSegmentId, identification) {
        $("#deleteTargetSegmentModal").modal('show');
        $("#hiddenTargetSegmentId").val(targetSegmentId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Target Segment");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this target segment?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Target Segment");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this target segment?");
        }
    }

    function confirmDeleteTargetSegment() {
        $.ajax({
                type:'get',
                url: "/it-admin-target-segment-delete",
                data: {'targetSegmentId' : $("#hiddenTargetSegmentId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-target-segment'}}"}, 2000);     
                    }
                }
            });
    }

    function checkTargetSegmentCode() {
        var targetSegmentCode = $("#targetSegmentCode").val();
        var targetSegmentId = $("#hdnTargetSegmentId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-target-segment-check',
            data: {'targetSegmentCode' : targetSegmentCode, 'targetSegmentId' : targetSegmentId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#target-segment-code-error").text("Please provide unique target segment code.");
                }
                else {
                    $("#target-segment-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
