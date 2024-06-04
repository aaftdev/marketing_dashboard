@extends('ext-marketing.ext-master')

@section('content')



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
    <div class="row" id="extHomeContentId">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 card-backgroundcolor">
                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-outlined">task_alt</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Active</p>
                        <h4 class="mb-0" id="activeCount">{{ $activeCount }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    
                </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 card-backgroundcolor">
                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-outlined">pause_circle</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">On Hold</p>
                        <h4 class="mb-0" id="onHoldCount">{{$onHoldCount}}</h4>
                    </div>                    
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    
                </div>                
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 card-backgroundcolor">
                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-outlined">new_releases</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">New</p>
                        <h4 class="mb-0" id="newCount">{{ $newCount }}</h4>
                    </div>                    
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    
                </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 card-backgroundcolor">
                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-outlined">delete</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Delete</p>
                        <h4 class="mb-0" id="deleteCount">{{ $deleteCount }}</h4>
                    </div>
                    
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    
                </div>                
          </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h5>Campaigns</h5>                  
                </div>
                <div class="col-lg-6 col-6 my-auto text-end">
                  <div class="dropdown float-lg-end pe-0">
                    <input type="hidden" id="hdnInstitutionId" value="{{ $institutionId }}" />
                    
                  </div>
                </div>                
              </div>
            </div>
            <div class="card-body px-1 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0" id="homeCampaignTable">
                  <thead>
                    <tr>                        
                        <th class="text-uppercase opacity-10">Program Type</th>
                        <th class="text-uppercase opacity-10">Course</th>
                        <th class="text-uppercase opacity-10">Campaign Name</th>
                        <th class="text-uppercase opacity-10">Campaign Form Name</th>
                        <th class="text-uppercase opacity-10">Leadsource</th>
                        <th class="text-uppercase opacity-10">Agency</th>                        
                        <th class="text-uppercase opacity-10">Status</th>                                            
                    </tr>
                  </thead>
                  <tbody id="homeCampaignBody">
                    @foreach($campaignList as $campaign)                      
                      <tr>                        
                        <td style="padding-left: 15px;"><span class="text-primary">{{ $campaign->program_type_name }}</span></td>
                        <td style="padding-left: 15px;">{{ $campaign->course_name }}</td>
                        <td style="padding-left: 15px;">{{ $campaign->campaign_name }}</td>
                        <td style="padding-left: 15px;">{{ $campaign->campaign_form_name }}</td>                        
                        <td style="padding-left: 15px;">{{ $campaign->leadsource_name }}</td>
                        <td style="padding-left: 15px;">{{ $campaign->agency_name }}</td>                        
                        <td style="padding-left: 15px;">
                          @if($campaign->campaign_status_name == "Active")
                            <button type="button" style="background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;">{{ $campaign->campaign_status_name }}</button>
                          @elseif($campaign->campaign_status_name == "On Hold")
                            <button type="button" style="background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;">{{ $campaign->campaign_status_name }}</button>  
                          @elseif($campaign->campaign_status_name == "New")
                            <button type="button" style="background-color: #217EFD4D; color: #217EFD; border:0px #217EFD4D;">{{ $campaign->campaign_status_name }}</button>
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
    </div>
    <div class="row mt-4 mx-2" id="lpChartDiv">
      <div class="col-md-6 col-md-6 mb-xl-0 mb-4 " >
        <div class="card card-backgroundcolor px-9" style="height: 400px;" id="lpStatusChartDiv">
          <canvas class="text-center" id="lpStatusChartId" ></canvas>
        </div>
      </div>
      <div class="col-md-6 col-md-6 mb-xl-0 mb-4 " > 
        <div class="card card-backgroundcolor px-2" style="height: 400px;" id="lpAgencyChartDiv">
          <canvas class="text-center" id="lpAgencyChartId" ></canvas> 
        </div> 
      </div>
    </div>
    <div class="row mt-4 mx-2">
      <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h5>Landing Page</h5>                  
                </div>
                <div class="col-lg-6 col-6 my-auto text-end">
                  <div class="dropdown float-lg-end pe-0">                    
                    
                  </div>
                </div>                
              </div>
            </div>
            <div class="card-body px-1 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0" id="homelpCampTable">
                  <thead>
                    <tr>            
                        <th class="opacity-10">PROGRAM TYPE</th>
                        <th class="opacity-10">COURSE</th>                            
                        <th class="opacity-10">URL</th>                            
                        <th class="opacity-10">STATUS</th>                     
                    </tr>
                  </thead>
                  <tbody id="homeCampaignBody">
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
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script type="text/javascript">
        $(document).ready(function() {               
          $("#extCampaignID").removeClass( "active bg-primary bg-gradient" );
          $("#extCampaignHomeID").addClass( "active bg-primary bg-gradient" );
          $("#extCampaignFormID").removeClass("active bg-primary bg-gradient");
          $('#homeCampaignTable').dataTable();
          $('#homelpCampTable').dataTable();
          lpCharts();          
          // document.getElementById('sidebar-text-home').style.color = "white !important";
          // document.getElementById('sidebar-text-campaign').style.color = "black !important";
          // document.getElementById('sidebar-text-camp-form').style.color = "black !important";
          // document.getElementById('sidebar-text-lp').style.color = "black !important";

        });
        
        function lpCharts() {
          var ctx = document.getElementById('lpStatusChartId').getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'pie',
              data: {
                  labels: @json($labels),
                  datasets: [{
                      label: 'Landing Page Campaigns',
                      data: @json($lpCampCount),
                      backgroundColor: ['#003f5c', '#374c80'],
                      borderColor: 'rgba(75, 192, 192, 1)',
                      borderWidth: 1
                  }]
              }
          });

          var atx = document.getElementById('lpAgencyChartId').getContext('2d');          
          var myChart = new Chart(atx, {
              type: 'bar',
              data: {
                  labels: @json($lpLabels),
                  datasets: [{
                      label: 'Landing Page Campaigns',
                      data: @json($lpAgencyCount),
                      backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                        'rgba(255, 205, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(201, 203, 207, 0.2)'
                                      ],
                      borderColor: [
                                        'rgb(255, 99, 132)',
                                        'rgb(255, 159, 64)',
                                        'rgb(255, 205, 86)',
                                        'rgb(75, 192, 192)',
                                        'rgb(54, 162, 235)',
                                        'rgb(153, 102, 255)',
                                        'rgb(201, 203, 207)'
                                      ],
                      borderWidth: 1
                  }]
              },
              options: {
                  scales: {
                      y: {                        
                          beginAtZero: true,
                          text: 'Number of Campaigns'
                      },
                      x: {
                          text: 'Agency'
                      }
                  }
              }
          });
        }

        function changeInstitution(institute) {
          
          $.ajax({
            type:'get',
            url: "/ext-home-change-institution",
            data: {'institution' : institute},          
            success:function(data){
              
              var campBody = $("#homeCampaignTable").empty();
              var lpBody = $("#homelpCampTable").empty();
                $("#hdnInstitutionId").val(data.institutionId);
                $("#activeCount").empty().text(data.activeCount);
                $("#onHoldCount").empty().text(data.onHoldCount);
                $("#newCount").empty().text(data.newCount);
                $("#deleteCount").empty().text(data.deleteCount);
                $("#lpStatusChartId").remove();
                $("#lpAgencyChartId").remove();
                $("#lpChartDiv").hide();
                // document.getElementById('lpStatusChartId').destroy();
                // document.getElementById('lpAgencyChartId').destroy();
                
              if(data.campaignList != "" && data.campaignList != undefined){
                
                var campTheadItem = "<thead>" +
                "<tr>" +                    
                    "<th class='text-uppercase opacity-10'>PROGRAM TYPE</th>" + 
                    "<th class='text-uppercase opacity-10'>COURSE</th>" +
                    "<th class='text-uppercase opacity-10'>Campaign Name</th>" +
                    "<th class='text-uppercase opacity-10'>Campaign Form Name</th>" +
                    "<th class='text-uppercase opacity-10'>Leadsource</th>" + 
                    "<th class='text-uppercase opacity-10'>Agency</th>" +                                  
                    "<th class='text-uppercase opacity-10'>STATUS</th>" +                                                   
                    
                "</tr>" +
                "</thead><tbody>";
                campBody.append(campTheadItem);
                var campStatusItem = "";
                  
                for(var i = 0; i < data.campaignList.length;i++){
                  if(data.campaignList[i]['campaign_status_name'] == 'Active') {
                    campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D'> " + data.campaignList[i]['campaign_status_name'] + "</button>";
                  }
                  else if (data.campaignList[i]['campaign_status_name'] == 'On Hold') {
                    campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
                  }
                  else if (data.campaignList[i]['campaign_status_name'] == 'New') {
                    campStatusItem = "<button type='button' style='background-color: #217EFD4D; color: #217EFD; border:0px #217EFD4D'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
                  }
                  var campBodyItem = "<tr><td style='padding-left: 20px;'><span class='text-primary'>"+ data.campaignList[i]['program_type_name'] +"</span></td>" +
                                     "<td style='padding-left: 20px;'>"+ data.campaignList[i]['course_name'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ data.campaignList[i]['campaign_name'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ data.campaignList[i]['campaign_form_name'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ data.campaignList[i]['leadsource_name'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ data.campaignList[i]['agency_name'] +"</td>" +                                     
                                     "<td style='padding-left: 20px;'>"+ campStatusItem +"</td>" +
                                     "</tr>";
                  campBody.append(campBodyItem);
                }
                campBody.append("</tbody>")
              }
              if(data.landingPageList != "" && data.landingPageList != undefined){
                $("#lpChartDiv").show();
                $("#lpStatusChartDiv").append('<canvas id="lpStatusChartId"></canvas>');
                $("#lpAgencyChartDiv").append('<canvas id="lpAgencyChartId"></canvas>');
                var ctx = document.getElementById('lpStatusChartId').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Landing Page Campaigns',
                            data: data.lpCampCount,
                            backgroundColor: ['#003f5c', '#374c80'],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    }
                });

                var atx = document.getElementById('lpAgencyChartId').getContext('2d');          
                var myChart = new Chart(atx, {
                    type: 'bar',
                    data: {
                        labels: data.lpLabels,
                        datasets: [{
                            label: 'Landing Page Campaigns',
                            data: data.lpAgencyCount,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {                        
                                beginAtZero: true,
                                text: 'Number of Campaigns'
                            },
                            x: {
                                text: 'Agency'
                            }
                        }
                    }
                });
                var lpTheadItem = "<thead>" +
                    "<tr>" +            
                      "<th class='opacity-10'>PROGRAM TYPE</th>" +
                      "<th class='opacity-10'>COURSE</th>" +                      
                      "<th class='opacity-10'>URL</th>" +                      
                      "<th class='opacity-10'>STATUS</th>" +                     
                    "</tr>" +
                    "</thead><tbody>";
                lpBody.append(lpTheadItem);
                var lpCampStatusItem = "";
                  
                for(var i = 0; i < data.landingPageList.length;i++){
                  if(data.landingPageList[i]['active'] == 1) {
                    lpCampStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598;'> " + "Active" + "</button>";
                  }
                  else if (data.landingPageList[i]['active'] == 0) {
                    lpCampStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107;'>" + "Inactive" + "</button>";
                  }
                  
                  var lpBodyItem = "<tr><td style='padding-left: 20px;'><span class='text-primary'>"+ data.landingPageList[i]['program_type_name'] +"</span></td>" +
                                     "<td style='padding-left: 20px;'>"+ data.landingPageList[i]['course_name'] +"</td>" +                                     
                                     "<td style='padding-left: 20px;'>"+ data.landingPageList[i]['camp_url'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ lpCampStatusItem +"</td>" +
                                     "</tr>";
                  lpBody.append(lpBodyItem);
                }
                lpBody.append("</tbody>");
              }
              
              $('#homeCampaignTable').DataTable().destroy();
              $("#homeCampaignTable").dataTable();
              $('#homelpCampTable').DataTable().destroy();
              $("#homelpCampTable").dataTable();
              
            }
          });
        } 

        function downloadExtCampaign() {
          var institute = $("#hdnInstitutionId").val();
          window.location.href = "{{ url('excel-campaign') }}" + "/" + institute;
        }
      </script>
    
@endsection