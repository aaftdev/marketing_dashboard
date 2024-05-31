@extends('int-marketing-shared.int-master')

@section('int-content')
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
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header card-backgroundcolor" style="font-size: xx-large">
                    Campaign
                </div>
                <div class="card-body">
                    <div class="col-md-12" style="height: 400px; padding-left: 18%;" id="campaignChartDiv">
                        <canvas id="campaignChart"></canvas>
                    </div>
                    <hr />
                    <div class="table-responsive">
                        <table class="table table-striped mt-4" id="intMarketingCampaignTable" style="font-size: small">
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
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header card-backgroundcolor" style="font-size: xx-large">
                    Landing Page
                </div>
                <div class="card-body">
                <div class="col-md-12" style="height: 400px; padding-left: 18%;" id="lpCampaignChartDiv">
                    <canvas id="lpCampaignChart"></canvas>
                    </div>
                    <hr />
                    <div class="table-responsive">
                        <table class="table table-striped mt-4" id="intMarLpCampaignTable" style="font-size: small">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.tutorialjinni.com/notify/0.4.2/notify.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {        
          $("#adminCampaignID").removeClass( "active bg-primary" );
          $("#adminLandingPageID").removeClass( "active bg-primary" );
          $("#adminHomeID").addClass( "active bg-primary" );
          $("#intCampaignFormID").removeClass("active bg-primary");
          $("#intMarketingCampaignTable").dataTable();
          $("#intMarLpCampaignTable").dataTable();
        });
        var ctx = document.getElementById('campaignChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Campaign',
                    data: @json($leadCount),
                    backgroundColor: ['#003f5c', '#374c80', '#7a5195', '#bc5090', '#ef5675', '#ff764a', '#ffa600', '#1e81b0'],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        var atx = document.getElementById('lpCampaignChart').getContext('2d');
          var myChart = new Chart(atx, {
              type: 'pie',
              data: {
                  labels: @json($lpCamplabels),
                  datasets: [{
                      label: 'Landing Page Campaigns',
                      data: @json($lpCampCount),
                      backgroundColor: ['#ffa600', '#1e81b0'],
                      borderColor: 'rgba(75, 192, 192, 1)',
                      borderWidth: 1
                  }]
              }
          });

        function changeInstitution(institute) {          
          $.ajax({
            type:'get',
            url: "/int-home-change-institution",
            data: {'institution' : institute},          
            success:function(data){
              
              var campBody = $("#intMarketingCampaignTable").empty();
              var lpBody = $("#intMarLpCampaignTable").empty();
                $("#hdnInstitutionId").val(data.institutionId);
                
                $("#campaignChart").remove();
                $("#lpCampaignChart").remove();
                
                // document.getElementById('lpStatusChartId').destroy();
                // document.getElementById('lpAgencyChartId').destroy();
                
              if(data.campaignList != "" && data.campaignList != undefined){
                
                var campTheadItem = "<thead>" +
                                        "<th class='text-uppercase opacity-10'>Program Type</th>" + 
                                        "<th class='text-uppercase opacity-10'>Course</th> " +
                                        "<th class='text-uppercase opacity-10'>Campaign Name</th>" +
                                        "<th class='text-uppercase opacity-10'>Campaign Form Name</th>" +
                                        "<th class='text-uppercase opacity-10'>Leadsource</th>" +
                                        "<th class='text-uppercase opacity-10'>Agency</th>" +                        
                                        "<th class='text-uppercase opacity-10'>Status</th>" +
                                    "</thead><tbody>";
                campBody.append(campTheadItem);
                var campStatusItem = "";
                  
                for(var i = 0; i < data.campaignList.length;i++){
                  if(data.campaignList[i]['campaign_status_name'] == 'Active') {
                    campStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;'> " + data.campaignList[i]['campaign_status_name'] + "</button>";
                  }
                  else if (data.campaignList[i]['campaign_status_name'] == 'On Hold') {
                    campStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
                  }
                  else if (data.campaignList[i]['campaign_status_name'] == 'New') {
                    campStatusItem = "<button type='button' style='background-color: #217EFD4D; color: #217EFD; border:0px #217EFD4D;'>" + data.campaignList[i]['campaign_status_name'] + "</button>";
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
                $("#campaignChartDiv").append('<canvas id="campaignChart"></canvas>');
                $("#lpCampaignChartDiv").append('<canvas id="lpCampaignChart"></canvas>');
                var ctx1 = document.getElementById('campaignChart').getContext('2d');
                var myChart1 = new Chart(ctx1, {
                    type: 'polarArea',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Campaign',
                            data: data.leadCount,
                            backgroundColor: ['#003f5c', '#374c80', '#7a5195', '#bc5090', '#ef5675', '#ff764a', '#ffa600', '#1e81b0'],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    }
                });

                var atx1 = document.getElementById('lpCampaignChart').getContext('2d');
                var myChart1 = new Chart(atx1, {
                    type: 'pie',
                    data: {
                        labels: data.lpCamplabels,
                        datasets: [{
                            label: 'Landing Page Campaigns',
                            data: data.lpCampCount,
                            backgroundColor: ['#ffa600', '#1e81b0'],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
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
                    lpCampStatusItem =  "<button type='button' style='background-color: #1AD5984D; color: #1AD598; border:0px #1AD5984D;'> " + "Active" + "</button>";
                  }
                  else if (data.landingPageList[i]['active'] == 0) {
                    lpCampStatusItem = "<button type='button' style='background-color: #FFC1074D; color: #FFC107; border:0px #FFC1074D;'>" + "Inactive" + "</button>";
                  }
                  
                  var lpBodyItem = "<tr><td style='padding-left: 20px;'><span class='text-primary'>"+ data.landingPageList[i]['program_type_name'] +"</span></td>" +
                                     "<td style='padding-left: 20px;'>"+ data.landingPageList[i]['course_name'] +"</td>" +                                     
                                     "<td class='text-wrap' style='padding-left: 20px;'>"+ data.landingPageList[i]['camp_url'] +"</td>" +
                                     "<td style='padding-left: 20px;'>"+ lpCampStatusItem +"</td>" +
                                     "</tr>";
                  lpBody.append(lpBodyItem);
                }
                lpBody.append("</tbody>");
              }
              
              $('#intMarketingCampaignTable').DataTable().destroy();
              $("#intMarketingCampaignTable").dataTable();
              $('#intMarLpCampaignTable').DataTable().destroy();
              $("#intMarLpCampaignTable").dataTable();
              
            }
          });
        } 
      
    </script>
@endsection