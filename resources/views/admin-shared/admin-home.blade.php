@extends('admin-shared.admin-master')

@section('adminContent')

    <div class="row">
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header card-backgroundcolor" style="font-size: xx-large">
                    Campaign
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <canvas id="campaignChart" height="150px"></canvas>
                    </div>
                    <hr />
                    <div class="table-responsive">
                        <table class="table table-bordered mt-4" id="adminCampaignTable" style="font-size: small">
                            <thead>
                            <tr>                                
                                <th class="text-uppercase text-secondary text-left font-weight-bolder opacity-10">Program Type</th>
                                <th class="text-uppercase text-secondary text-left font-weight-bolder opacity-10">Campaign</th>
                                <th class="text-uppercase text-secondary text-left font-weight-bolder opacity-10">Lead Source</th>
                                <th class="text-uppercase text-secondary text-left font-weight-bolder opacity-10">Course</th>
                                <th class="text-uppercase text-secondary text-left font-weight-bolder opacity-10">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campaignList as $campaign)
                                <tr>                                    
                                    <td class="text-left">{{ $campaign->program_type_name }}</td>
                                    <td class="text-left">{{ $campaign->campaign_name }}</td>
                                    <td class="text-left">{{ $campaign->leadsource_name }}</td>
                                    <td class="text-left">{{ $campaign->course_name }}</td>
                                    <td class="text-left">{{ $campaign->campaign_status_name }}</td>                                
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
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {        
          $("#adminCampaignID").removeClass( "active bg-gradient-primary" );
          $("#adminLandingPageID").removeClass( "active bg-gradient-primary" );
          $("#adminHomeID").addClass( "active bg-gradient-primary" );
        });
        var ctx = document.getElementById('campaignChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Campaigns',
                    data: @json($leadCount),
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
                        text: 'Lead Source'
                    }
                }
            }
        });
      
    </script>
@endsection