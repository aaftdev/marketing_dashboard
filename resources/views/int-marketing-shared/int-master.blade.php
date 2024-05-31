
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
      Task Minder
    </title>
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
      .bg-primary{
        background-color: #83b5ff !important;
      }
      .bg-gradient-light{
        background-image: linear-gradient(195deg, #262de4 0%, #CED4DA 100%) !important;
      }
      .bg-skyblue{
        background-color: #e1e8f3 !important;
      }
      #sidebar-text {
        color:black;
      }
      .vertical {
            border-left: 1px solid black;
            height: 30px;
        }
      table.dataTable {
        font-size:14px;
      }
    </style>
  </head>
  <body class="g-sidenav-show  bg-skyblue">  
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-light" id="sidenav-main">
      <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ url('int-home') }}">        
          <span class="ms-1 font-weight-bold text-white" style="font-size: xx-large">Task Minder</span>
        </a>
      </div>
      <hr class="horizontal light mt-0 mb-2">
      <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-white active bg-primary" id="intCampaignHomeID" href="{{ url('int-home') }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-outlined">home</i>
              </div>
              <span class="nav-link-text ms-1">Home</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link text-white" id="intCampaignID" href="{{ url('int-campaign') }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-outlined">campaign</i>
              </div>
              <span class="nav-link-text ms-1">Campaign</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" id="intCampaignFormID" href="{{ url('int-campaign-form') }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-outlined">article</i>
              </div>
              <span class="nav-link-text ms-1">Campaign Form</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" id="intLandingPageID" href="{{ url('int-landing-page') }}">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-outlined">web</i>
              </div>
              <span class="nav-link-text ms-1">Landing Page</span>
            </a>
          </li>        
        </ul>
      </div>    
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
      <!-- Navbar -->
      <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
          <nav aria-label="breadcrumb">          
            <h6 class="font-weight-bolder mb-0" style="font-size: x-large">Welcome {{ session()->get('firstName'). " " . session()->get('lastName') }}</h6>
          </nav>
          <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav  justify-content-end">           
              <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0 dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-user fa-lg" aria-hidden="true" style="size"></i>
                </a>
                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                  <li class="text-end">
                    <a href="{{ url('logout') }}" class="dropdown-item">Logout</a>
                  </li>                
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="container-fluid py-4">
        @yield('int-content')
        <footer class="footer py-4  ">
          <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
              <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                  © <script>
                    document.write(new Date().getFullYear())
                  </script>,
                  made by
                  <a href="#" class="font-weight-bold">AAFT</a>
                </div>
              </div>              
            </div>
          </div>
        </footer>
      </div>
    </main>
  </body>

</html>

<script type="text/javascript">
  function getCourses(){
    var institutionCode = $("#campaign-institution").val();
    $.ajax({
      type:'get',
      url: "/courses/",
      data: {'institutionCode' : institutionCode},
      success:function(data){
        var coursesId = $("#courses").empty();
        coursesId.append('<option selected="selected" value="">--Select--</option>');
        if(data) {        
          for(var i = 0; i < data.courses.length;i++){
              var courses_item_el = '<option value="' + data.courses[i]['course_code']+'">'+ data.courses[i]['course_name']+'</option>';
              coursesId.append(courses_item_el);
          }
        }
      }
    });
  }
</script>