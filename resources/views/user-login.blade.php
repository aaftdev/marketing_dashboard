
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.tutorialjinni.com/notify/0.4.2/notify.min.js"></script>
    
    <style>
      .card-backgroundcolor {
        background-color: white !important;
      }

      .field-icon {
        float: left;
        margin-left: 235px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
      }      
    </style>
  </head>
  <body class="g-sidenav-show  bg-gray-200"> 
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100">
      <span class="mask opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h3 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h3>
                  @if(session()->has('loginMessage'))
                    <div class="alert alert-success" id="successMesgID" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="display: none">
                      {{ session()->get('loginMessage') }}
                      <button type="button" onclick="campNotify();" class="btn-close" style="float: right;" aria-label="Close"></button>
                    </div>
                  @endif
                </div>
              </div>
              <div class="card-body">
                  <form action="{{ url('login-user')}}" method="post" id="loginForm">
                      @csrf
                      <div class="input-group input-group-outline my-3">                            
                          <input type="text" id="loginEmail" name="loginEmail" class="form-control" placeholder="Email">                            
                      </div>
                      <div class="input-group input-group-outline mb-3">                            
                          <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" class="form-control"> 
                          <!-- <i class="fa fa-fw fa-eye field-icon toggle-password" toggle="#cnfnPassword"></i>                -->
                      </div> 
                      <div class="input-group input-group-outline mb-3" id="errorsDiv" >
                        <ul class="text-danger">
                          <li id="errorEmailLi" style="display: none;"><span class="text-danger" id="email-error"></span></li>
                          <li id="errorPasswordLi" style="display: none;"><span class="text-danger" id="password-error"></span></li>
                        </ul>
                      </div>                   
                      <div class="text-center">
                          <button title="Sign in" class="btn bg-gradient-primary w-100 my-4 mb-2" onclick="userLogin()">Sign in</button>
                      </div>
                      <p class="mt-4 text-sm text-center">
                          <a href="#" class="text-primary text-gradient font-weight-bold">Forgot Password</a>
                      </p>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
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
    $(document).ready(function() {    
        debugger;
        if($("#successMesgID").text().trim() =="Invalid username or password.") {
          $.notify($("#successMesgID").text(), "warning");          
        }
        else if($("#successMesgID").text() != "") {
          $.notify($("#successMesgID").text(), "success"); 
        }
    });
      function userLogin() {
        
        var email = $("#loginEmail").val();
        var password = $("#loginPassword").val();

        if(email == "") {
          $("#email-error").text("Please enter email.");
          $("#errorEmailLi").show();
        }
        else {
          $("#email-error").text("");
          $("#errorEmailLi").hide();
        }

        if(password == "") {
          $("#password-error").text("Please enter password.");
          $("#errorPasswordLi").show();
        }
        else {
          $("#password-error").text("");
          $("#errorPasswordLi").hide();
        }

        $("#loginForm").submit(function (e) { 
          
          if($("#email-error").text() != "" || $("#password-error").text() != "") {
            e.preventDefault();
            return false;
          }
        });

      }

    $(".toggle-password").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });

  
</script>