@extends('int-marketing-shared.int-master')

@section('int-content')
@if(session()->has('message'))
  <div class="alert alert-success" id="successMesgID" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="display: none">
    {{ session()->get('message') }}
    <button type="button" onclick="campNotify();" class="btn-close" style="float: right;" aria-label="Close"></button>
  </div>
@endif
<div class="col-lg-12 mb-4">
      <div class="nav-wrapper position-relative">
        <ul class="nav nav-pills nav-tabs p-1" role="tablist">
          @foreach($institutionList as $institute)
            <li class="nav-item">
              @if($institute->institution_name == "AAFT Online")
                  <a class="nav-link mb-0 px-0 py-2 mx-1 active" data-bs-toggle="tab" onclick="changeInstitution('{{ $institute->institution_name }}')" role="tab">              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @else
                  <a class="nav-link mb-0 px-0 py-2 mx-1" data-bs-toggle="tab" onclick="changeInstitution('{{ $institute->institution_name }}')" role="tab" >              
                  <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                  </a>
              @endif
            </li>
          @endforeach          
        </ul>
      </div>
    </div>

<div class="row mt-4">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Landing Page</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                      <div class="dropdown float-lg-end pe-4">
                                        
                      </div>
                    </div>                  
            </div>            
        </div>
        <div class="card-body px-1 pb-2">
            <div class="table-responsive">
            <table class="table table-striped mb-1" id="campaignTable">
            <thead>
                <tr>            
                    <th class="opacity-10">PROGRAM TYPE</th>
                    <th class="opacity-10">COURSE</th>                            
                    <th class="opacity-10">URL</th>                            
                    <th class="opacity-10">STATUS</th>                    
                </tr>
                </thead>
                <tbody>
                  @foreach($landingPageList as $lp)
                    <tr>                      
                        <td style="padding-left: 15px;"><span class="text-primary">{{ $lp->program_type_name }}</span></td>
                        <td style="padding-left: 15px;">{{ $lp->course_name }}</td>                                    
                        <td class="text-wrap" style="padding-left: 15px;">{{ $lp->camp_url }}</td>                                                 
                        <td style="padding-left: 15px;">
                            @if($lp->active == 1)
                                <button type="button" style="background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;">Active</button>
                            @elseif($lp->active == 0)
                                <button type="button" style="background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;">Inactive</button>  
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

<!-- View Campaign Modal -->
<div class="modal fade" id="viewCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <table class="table table-bordered" id="viewTableId">
            
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Approval Campaign Modal -->
<div class="modal fade" id="approvalCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Campaign Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hdnCampId" name="hdnCampId" />
        <p id="descriptionId">Do you wish to approve the campaign?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="buttonId" title="Accept" onclick="acceptLpCampaign();">Accept</button>
        <button type="button" class="btn btn-danger" title="Reject" onclick="rejectLpCampaign();">Reject</button>
      </div>
    </div>
  </div>
</div>
<!-- Reject Comment Campaign Modal -->
<div class="modal fade" id="rejectCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Campaign Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <input type="hidden" id="hidnCampId" name="hidnCampId" />
        <div class="row">
            <div class="col-md-3">
                <label for="rejectCampComment">Reason</label>
                <span class="text-danger">*</span>
            </div>
            <div class="col-md-9">
                <textarea class="form-control" name="rejectCampComment" id="rejectCampComment" cols="30" rows="5"></textarea>
                <span class="text-danger" id="rejectReasonValId"></span>
            </div>
        </div>
        <div class="row form-group mt-3">
            <div class="col-md-5">
              <button class="btn btn-primary" id="submit" title="submit" onclick="rejectCampaignComment();">Submit</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>                
        </div>
      </div>      
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.tutorialjinni.com/notify/0.4.2/notify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {        
        $("#intCampaignHomeID").removeClass( "active bg-primary" );
        $("#intLandingPageID").addClass( "active bg-primary" );
        $("#intCampaignID").removeClass( "active bg-primary" ); 
        $("#intCampaignFormID").removeClass("active bg-primary");
        $("#campaignTable").dataTable();  
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");           
        }       
    });

    function viewLpCampaign(id) {
        $.ajax({
          type:'get',
          url: "/int-view-lp-campaign-form",
          data: {'campaignId' : id},
          success:function(data){
            if(data){                
               $("#viewCampaignModal").modal('show');
               var campaignTable = $("#viewTableId").empty();
               for(var i=0; i<data.campaignList.length; i++){
                   var items  = "<tr><td>Insititution</td><td>" + data.campaignList[i]['institution_name'] + "</td></tr>"+
                                "<tr><td>Program Type</td><td>" + data.campaignList[i]['program_type_name'] + "</td></tr>"+
                                "<tr><td>Course</td><td>" + data.campaignList[i]['course_name'] + "</td></tr>"+
                                "<tr><td>Campaign Name</td><td>" + data.campaignList[i]['campaign_name'] + "</td></tr>"+
                                "<tr><td>Adset Name</td><td>" + data.campaignList[i]['adset_name'] +"</td></tr>" + 
                                "<tr><td>Ad Name</td><td>" + data.campaignList[i]['adname'] + "</td></tr>" + 
                                "<tr><td>Creative</td><td>" + data.campaignList[i]['creative'] + "</td></tr>" + 
                                "<tr><td>Leadsource</td><td>"+ data.campaignList[i]['leadsource_name'] +"</td></tr>" +
                                "<tr><td>Campaign Date</td><td>" + data.campaignList[i]["campaign_date"] + "</td></tr>" +
                                "<tr><td>Campaign Status</td><td>" + data.campaignList[i]["campaign_status_name"] + "</td></tr>" +
                                "<tr><td>Campaign Approval Status</td><td>" + data.campaignList[i]["campaign_size"] + "</td></tr>" +
                                "<tr><td>Campaign Approval Comment</td><td>" + data.campaignList[i]["approval_date"] + "</td></tr>";
                   campaignTable.append(items);
               }
            }
          }
      });
    }

    function approveLpCampaignForm(id) {
      $("#approvalCampaignModal").modal('show');
      $("#hdnCampId").val(id);
    }

    function rejectLpCampaign() {
      $("#approvalCampaignModal").modal('hide');
      $("#rejectCampaignModal").modal('show');
      $("#hidnCampId").val($("#hdnCampId").val());
      $("#rejectReasonValId").text('');
      $("#rejectCampComment").val('');
    }

    function acceptLpCampaign() {
      var campaignId = $("#hdnCampId").val();
      $.ajax({
          type:'get',
          url: "/accept-lp-campaign-form",
          data: {'campaignId' : campaignId, 'approval': 1, 'comment': ''},
          success:function(data){           
            if(data != ""){              
              $.notify(data, "success");              
              setTimeout(() => { window.location.href="{{'int-landing-page'}}"}, 2000);
            }
          }          
      });
    }

    function rejectCampaignComment() {      
      var campaignId = $("#hidnCampId").val();
      var comment = $("#rejectCampComment").val();
      $("#rejectReasonValId").text('');
      if(comment == "") {
        $("#rejectReasonValId").empty().text("Please enter the reason");
        $("#submit").submit(function (e) {        
          if($("#rejectReasonValId").text() != ""){
            e.preventDefault();
            return false;
          }
        });   
      }
      else {
        $.ajax({
            type:'get',
            url: "/accept-lp-campaign-form",
            data: {'campaignId' : campaignId, 'comment' : comment, 'approval': 0},
            success:function(data){            
              if(data != ""){              
                $("#rejectCampaignModal").modal('hide');
                $.notify(data, "success");
                setTimeout(() => { window.location.href="{{'int-landing-page'}}"}, 2000);
              }
            }          
        });
      }
    }

    function changeInstitution(institution) {
      $.ajax({
            type:'get',
            url: "/int-lp-change-institution",
            data: {'institution' : institution},          
            success:function(data){              
              var campBody = $("#campaignTable").empty();
              $("#hdnInstituteId").val(data.institutionId[0]);                
              if(data.landingPageList != "" && data.landingPageList != undefined){
                
                var campTheadItem = "<thead>" +
                "<tr>" +                    
                    "<th class='opacity-10'>PROGRAM TYPE</th>" +
                    "<th class='opacity-10'>COURSE</th>" +                    
                    "<th class='opacity-10'>URL</th>" +                    
                    "<th class='opacity-10'>STATUS</th>" +                    
                "</tr>" +
                "</thead><tbody>";
                campBody.append(campTheadItem);
                for(var i = 0; i < data.landingPageList.length;i++){
                  var campStatusItem = "";
                  if(data.landingPageList[i]['active'] == 1) {
                    campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;'> " + "Active" + "</button>";
                  }
                  else if (data.landingPageList[i]['active'] == 0) {
                    campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;'>" + "Inactive" + "</button>";
                  }                                

                  var campBodyItem = "<tr><td style='padding-left: 20px;'><span class='text-primary'>"+ data.landingPageList[i]['program_type_name'] +"</span></td>" +
                                     "<td style='padding-left: 20px;'>"+ data.landingPageList[i]['course_name'] +"</td>" +                                     
                                     "<td class='text-wrap' style='padding-left: 20px;'>"+ data.landingPageList[i]['camp_url'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ campStatusItem +"</td>" +
                                     "</tr>";
                  campBody.append(campBodyItem);
                }
                campBody.append("</tbody>")
              }
              $('#campaignTable').DataTable().destroy();
              $("#campaignTable").dataTable();
            }
        });
    }
</script>
@endsection