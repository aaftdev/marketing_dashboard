@extends('admin-shared.admin-master')

@section('adminContent')
    <div class="row">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4 card-backgroundcolor">
            <div class="row form-group mt-4">
                <div class="col-md-3 text-end">
                <label class="form-label" for="admin-institution" style="font-size: x-large; font-weight:600;">Institution</label>                
                </div>
                <div class="col-md-3">
                    <select name="admin-institution" class="form-control rounded-pill" id="admin-institution">
                        <option value="">--Select--</option>
                        @foreach($institutionList as $institution)
                            <option value="{{ $institution->institution_id }}">{{ $institution->institution_name }}</option>
                        @endforeach                  
                    </select>                
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" id="searchID" title="Search" onclick="searchAdminCampaign();"><span class="fa fa-search">&nbsp;Search</span></button>
                    <button class="btn btn-danger" id="clearID" title="Clear" onclick="clearAdminCampaign();"><span class="fa fa-eraser">&nbsp;Clear</span></button>
                </div>
            </div>
        <hr />
            <div class="px-1 pb-2" id="adminCampaignDiv" style="display:none;">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h3 class="mt-2 mb-4">Campaign</h3>
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <a class="btn btn-primary" id="adminCampaignDownloadId" title="Download">
                        <span class="fa fa-file-excel" style="font-size: small;">&nbsp; Download</span>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">                    
                    <hr />
                    <table class="table table-bordered mt-4" id="adminCampaignTable">
                        <thead>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-10">Institution</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-10">Program Type</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-10">Campaign</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-10">Lead Source</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-10">Course</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-10">Status</th>
                        </thead>
                        <tbody id="adminCampaignTableBody">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {        
          $("#adminCampaignID").addClass( "active bg-gradient-primary" );
          $("#adminLandingPageID").removeClass( "active bg-gradient-primary" );
          $("#adminHomeID").removeClass( "active bg-gradient-primary" );          
        });
        var institutionID;
        function searchAdminCampaign() {            
            institutionID = $('#admin-institution').val();            
            if(institutionID == "") {
                $.notify("Please select an institution", "warning");
            }
            else {
                var baseUrl = "{{ url("/admin-campaign-download") }}";
                var newUrl = baseUrl + "?institutionId=" + institutionID;
                $("#adminCampaignDownloadId").attr("href", newUrl);
                $.ajax({
                    type:'get',
                    url: "/admin-campaign-list",
                    data: {'institutionId' : institutionID},
                    success:function(data){    
                        $('#adminCampaignTable').dataTable();                    
                        if(data){
                            var campaignTableBody = $("#adminCampaignTableBody").empty();
                            for(var i = 0; i < data.campaignList.length;i++) {
                                var campaignTableTr = "<tr><td>" + data.campaignList[i]['institution_name'] + "</td><td>" + data.campaignList[i]['program_type_name'] + "</td><td>" + data.campaignList[i]['campaign_name'] + "</td><td>" + data.campaignList[i]['leadsource_name'] + "</td><td>" + data.campaignList[i]['course_name'] + "</td><td>" + data.campaignList[i]['campaign_status_name'] + "</td></tr>";
                                campaignTableBody.append(campaignTableTr);
                            }
                            //$('#adminCampaignTable').dataTable();
                            $("#adminCampaignDiv").show();
                        }
                        else {
                            $("#adminCampaignTableBody").empty();
                            
                        }
                    }
                });
            }
        }

        function clearAdminCampaign() {
            $('#admin-institution').val("");
            $("#adminCampaignDiv").hide();
        }

    </script>
@endsection