<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institution</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script> -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <style>
      .card-backgroundcolor {
        background-color: white !important;
      }
    </style>
</head>
<body class="g-sidenav-show  bg-gray-200">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mt-8">
                <div class="card">
                    <div class="card-header card-backgroundcolor">
                        <div class="card-body" style="height:180px;">
                            @foreach($institution_List as $institution)
                                @if($institution -> institution_name == "AAFT Online")
                                    <a href="{{ url('admin-home/'.$institution -> institution_id) }}" style="text-decoration: none;"><h1 class="text-center">AAFT Online</h1></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mt-8">
                <div class="card">
                    <div class="card-header card-backgroundcolor">
                        <div class="card-body" style="height:180px;">
                            @foreach($institution_List as $institution)
                                @if($institution -> institution_name == "Media Management Towers")
                                    <a href="{{ url('admin-home/'.$institution -> institution_id) }}" style="text-decoration: none;"><h1 class="text-center">Media Management Towers</h1></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mt-8">
                <div class="card">
                    <div class="card-header card-backgroundcolor">
                        <div class="card-body" style="height:180px;">
                            @foreach($institution_List as $institution)
                                @if($institution -> institution_name == "AAFT University Raipur")
                                    <a href="{{ url('admin-home/'.$institution -> institution_id) }}" style="text-decoration: none;"><h1 class="text-center">AAFT University Raipur</h1></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mt-8">
                <div class="card">
                    <div class="card-header card-backgroundcolor">
                        <div class="card-body" style="height:180px;">
                            @foreach($institution_List as $institution)
                                @if($institution -> institution_name == "AAFT Noida")
                                    <a href="{{ url('admin-home/'.$institution -> institution_id) }}" style="text-decoration: none;"><h1 class="text-center">AAFT Noida</h1></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mt-8">
                <div class="card">
                    <div class="card-header card-backgroundcolor">
                        <div class="card-body" style="height:180px;">
                            @foreach($institution_List as $institution)
                                @if($institution -> institution_name == "Art & Design Central")
                                    <a href="{{ url('admin-home/'.$institution -> institution_id) }}" style="text-decoration: none;"><h1 class="text-center">Art & Design Central</h1></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>