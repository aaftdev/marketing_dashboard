@extends('it-admin-shared.it-admin-master')

@section('it-adminContent')
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-users')}}" style="text-decoration: none;"><h1 class="text-center">Users</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-role')}}" style="text-decoration: none;"><h1 class="text-center">Role</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-agency')}}" style="text-decoration: none;"><h1 class="text-center">Agency</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-lead-source')}}" style="text-decoration: none;"><h1 class="text-center">Lead Source</h1></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-program-type')}}" style="text-decoration: none;"><h1 class="text-center">Program Type</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-persona')}}" style="text-decoration: none;"><h1 class="text-center">Persona</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-campaign-price')}}" style="text-decoration: none;"><h1 class="text-center">Campaign Price</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-headline')}}" style="text-decoration: none;"><h1 class="text-center">Headline</h1></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-target-location')}}" style="text-decoration: none;"><h1 class="text-center">Target Location</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-campaign-type')}}" style="text-decoration: none;"><h1 class="text-center">Campaign Type</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-campaign-size')}}" style="text-decoration: none;"><h1 class="text-center">Campaign Size</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-campaign-version')}}" style="text-decoration: none;"><h1 class="text-center">Campaign Version</h1></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-campaign-status')}}" style="text-decoration: none;"><h1 class="text-center">Campaign Status</h1></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-header card-backgroundcolor">
                <div class="card-body" style="height:180px;">
                    <a href="{{ url('it-admin-target-segment')}}" style="text-decoration: none;"><h1 class="text-center">Target Segment</h1></a>
                </div>
            </div>
        </div>
    </div>    
</div>
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminLandingPageID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminHomeID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminSettingsID").addClass( "active bg-primary bg-gradient" );
    });
</script>
@endsection
