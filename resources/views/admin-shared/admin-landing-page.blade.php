@extends('admin-shared.admin-master')

@section('adminContent')

<div class="row mt-4">
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
                <a class="nav-link mb-0 px-0 py-2 mx-1 active" data-bs-toggle="tab" onclick="changeLPInstitution('{{ $institute->institution_name }}')" role="tab">              
                <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                </a>
            @else
                <a class="nav-link mb-0 px-0 py-2 mx-1" data-bs-toggle="tab" onclick="changeLPInstitution('{{ $institute->institution_name }}')" role="tab" >              
                <span class="ms-1 text-uppercase" style="padding: 5px;"> {{ $institute->institution_name }}</span>
                </a>
            @endif
          </li>

          @endforeach

        </ul>
      </div>
    </div>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4 ">
        <div class="card card-backgroundcolor">
            <div class="card-header pb-0 ">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Landing Page</h5> 
                        <input type="hidden" id="hdnInstituteId" value="{{ $institutionId }}" />               
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                      <div class="dropdown float-lg-end pe-4">
                                         
                      </div>
                </div>                  
            </div>            
        </div>
        <div class="card-body px-1 pb-2">
            <div class="table-responsive">
            <table class="table align-items-center mb-1" id="lpTable">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.tutorialjinni.com/notify/0.4.2/notify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.slim.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {               
        $("#adminCampaignID").removeClass( "active bg-primary bg-gradient" );
        $("#adminLandingPageID").addClass( "active bg-primary bg-gradient" );
        $("#adminCampaignFormID").removeClass("active bg-primary bg-gradient");
        $("#adminHomeID").removeClass("active bg-primary bg-gradient");
        $('#lpTable').dataTable();          
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function changeLPInstitution(institutionName) {
        $.ajax({
            type:'get',
            url: "/ext-lp-change-institution",
            data: {'institution' : institutionName},          
            success:function(data){
              
              var campBody = $("#lpTable").empty();
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
                    campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598;'> " + "Active" + "</button>";
                  }
                  else if (data.landingPageList[i]['active'] == 0) {
                    campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107;'>" + "Inactive" + "</button>";
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
              $('#lpTable').DataTable().destroy();
              $("#lpTable").dataTable();
            }
        });
    }

</script>

@endsection