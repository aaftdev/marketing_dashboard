@extends('int-marketing-shared.int-master')

@section('int-content')
@if(session()->has('message'))
  <div class="alert alert-success" id="successMesgID" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="display: none">
    {{ session()->get('message') }}
    <button type="button" onclick="campNotify();" class="btn-close" style="float: right;" aria-label="Close"></button>
  </div>
@endif
<div class="row mt-4">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Create New Landing Page</h5>                
                    </div>
                                                    
                </div>            
            </div>
          <div class="card-body px-1 pb-2">
              <div class="table-responsive">
                <form name="add-landing-page" id="add-landing-page" method="post" action="{{ url('store-landing-page') }}" enctype="multipart/form-data" >
                      @csrf
                      <input type="hidden" name="landing_page_id" id="landing_page_id" value="{{ $landingPageId }}" />
                      @if(count($landingPageList) > 0)
                        <input type="hidden" name="hdnCourseId" id="hdnCourseId" value="{{ $landingPageList[0]->fk_course_id }}" />
                      @endif  
                      <div class="row form-group">
                          <div class="col-md-3">
                          <label class="form-label" for="landing_page_institution">Institution</label>
                          <span class="text-danger">*</span>
                          </div>
                          <div class="col-md-4">
                          <select name="landing_page_institution" class="form-control" id="landing_page_institution" onchange="getLandingPageCourses();">
                              <option value="">--Select--</option>
                              @foreach($institutionList as $institution)
                                @if($landingPageId != 0 && $landingPageList[0]->institution_id == $institution->institution_id)
                                  <option value="{{ $institution -> institution_id }}" selected>{{ $institution->institution_name }}</option>
                                @else
                                  <option value="{{ $institution->institution_id }}">{{ $institution->institution_name }}</option>
                                @endif
                              @endforeach                  
                          </select>                
                          <span id="institution-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="landing_page_course">Course</label>
                          <span class="text-danger">*</span>
                          </div>
                          <div class="col-md-4">
                          <select name="landing_page_course" class="form-control" id="landing_page_course">
                              <option value="">--Select--</option>                  
                          </select>                
                          <span id="course-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="landing_page_title">Title</label>
                          <span class="text-danger">*</span>
                          </div>
                          <div class="col-md-4">
                            @if($landingPageId != 0)
                                <input type="text" id="landing_page_title" name="landing_page_title" class="form-control" value="{{ $landingPageList[0]->title }}" />
                            @else
                                <input type="text" id="landing_page_title" name="landing_page_title" class="form-control" />
                            @endif                
                          <span id="title-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="description">Description</label>              
                          </div>
                          <div class="col-md-4">
                              @if($landingPageId != 0)
                                  <textarea name="description" class="form-control" id="description" cols="30" rows="4">{{ $landingPageList[0]->description }}</textarea>
                              @else  
                                  <textarea name="description" class="form-control" id="description" cols="30" rows="4">
                                  </textarea> 
                              @endif
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="files">Attach</label>              
                          </div>
                          <div class="col-md-4">
                            @if($landingPageId != 0)
                                <input type="file" name="attach[]" id="inputFile" multiple class="form-control">
                                <ul>
                                  @foreach($landingFileList as $landingFile)
                                    <li>{{ $landingFile->file_name }}</li>
                                  @endforeach
                                </ul>
                            @else
                              <input type="file" name="attach[]" id="inputFile" multiple class="form-control">
                            @endif               
                          </div>                                                    
                      </div>

                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="assignee">Assignee</label>
                          <span class="text-danger">*</span>
                          </div>
                          <div class="col-md-4">
                          <select name="assignee" class="form-control" id="assignee">
                              <option value="">--Select--</option>
                              {{ $name = session()->get('firstName') . " " . session()->get('lastName') }}
                              @foreach($assigneeList as $assignee)
                                  @if($landingPageId != 0 && $landingPageList[0]->assignee == $assignee->user_id)
                                      <option value="{{ $assignee->user_id }}" selected>{{ $assignee->assignee }}</option>
                                  @elseif($name == $assignee->assignee)
                                      <option value="{{ $assignee->user_id }}" selected>{{ $assignee->assignee }}</option>
                                  @else 
                                      <option value="{{ $assignee->user_id }}">{{ $assignee->assignee }}</option>
                                  @endif
                              @endforeach                  
                          </select>                
                          <span id="assignee-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="assigner">Assigner</label>
                          <span class="text-danger">*</span>
                          </div>
                          <div class="col-md-4">
                          <select name="assigner" class="form-control" id="assigner">
                              <option value="">--Select--</option>
                              @foreach($assigneeList as $assigner)
                                  @if($landingPageId != 0 && $landingPageList[0]->assigner == $assigner->user_id)
                                      <option value="{{ $assigner->user_id }}" selected>{{ $assigner->assignee }}</option>
                                  @else
                                      <option value="{{ $assigner->user_id }}">{{ $assigner->assignee }}</option>
                                  @endif
                              @endforeach                  
                          </select>                
                          <span id="assigner-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="developmentType">Development Type</label>
                          <span class="text-danger">*</span>
                          </div>
                          <div class="col-md-4">
                          <select name="developmentType" class="form-control" id="developmentType">
                              <option value="">--Select--</option>
                              @foreach($developmentTypeList as $devType)
                                  @if($landingPageId != 0 && $landingPageList[0]->fk_development_id == $devType->development_type_id)
                                      <option value="{{ $devType->development_type_id }}" selected>{{ $devType->development_type_name }}</option>
                                  @else
                                      <option value="{{ $devType->development_type_id }}">{{ $devType->development_type_name }}</option>
                                  @endif
                              @endforeach                  
                          </select>                
                          <span id="developmentType-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="issue">Issue</label>                          
                          </div>
                          <div class="col-md-4">
                          <select name="issue" class="form-control" id="issue">
                              <option value="">--Select--</option>
                              @foreach($issueList as $issue)
                                  @if($landingPageId != 0 && $landingPageList[0]->fk_issue_id == $issue->issue_id)
                                      <option value="{{ $issue->issue_id }}" selected>{{ $issue->issue_name }}</option>
                                  @else                                        
                                      <option value="{{ $issue->issue_id }}">{{ $issue->issue_name }}</option>
                                  @endif
                              @endforeach
                          </select>                
                          <span id="issue-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="priorityId">Priority</label>                          
                          </div>
                          <div class="col-md-4">
                              <select name="priorityId" class="form-control" id="priorityId">
                                  <option value="">--Select--</option>
                                  @foreach($priorityList as $priority)
                                      @if($landingPageId != 0 && $landingPageList[0]->fk_priority_id == $priority->priority_id)
                                          <option value="{{ $priority->priority_id }}" selected>{{ $priority->priority_name }}</option>
                                      @else
                                          <option value="{{ $priority->priority_id }}">{{ $priority->priority_name }}</option>
                                      @endif
                                  @endforeach
                              </select>                
                              <span id="priority-error" class="text-danger"></span>
                          </div>
                      </div>
                      <div class="row form-group mt-2">
                          <div class="col-md-3">
                          <label class="form-label" for="status">Status</label>                          
                          </div>
                          <div class="col-md-4">
                          <select name="status" class="form-control" id="status">
                              <option value="">--Select--</option>
                              @foreach($statusList as $status)
                                  @if($landingPageId != 0 && $landingPageList[0]->fk_lp_status_id == $status->lp_status_id)
                                      <option value="{{ $status->lp_status_id }}" selected>{{ $status->lp_status }}</option>
                                  @else  
                                      <option value="{{ $status->lp_status_id }}">{{ $status->lp_status }}</option>
                                  @endif
                              @endforeach                  
                          </select>                
                          <span id="status-error" class="text-danger"></span>
                          </div>
                      </div>
                      @if($landingPageId != 0)
                          <div class="row form-group mt-2">
                              <div class="col-md-3">
                              <label class="form-label" for="comments">Comments</label>                          
                              </div>
                              <div class="col-md-4">
                              <textarea class="form-control" name="comments" id="comments" cols="30" rows="4"></textarea>       
                              <span id="comments-error" class="text-danger"></span>
                              </div>
                          </div>

                          <div class="row form-group">
                              @foreach($landingPageCommentList as $comment)
                                <p>{{ $comment -> lp_comment }}</p>
                              @endforeach
                          </div>
                      @endif
                      <hr />
                      <div class="row form-group mt-2">
                          <div class="col-md-5">
                          <button class="btn btn-primary" id="create" title="Create" onclick="CreateLandingPage();">Create</button>
                          <a data-bs-dismiss="modal" class="btn btn-danger" title="Cancel" href="{{ url('int-landing-page') }}">Cancel</a>
                          </div>
                      </div>
                  </form>
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
        $("#intCampaignHomeID").removeClass( "active bg-gradient-primary" );
        $("#intLandingPageID").addClass( "active bg-gradient-primary" );
        $("#intCampaignID").removeClass( "active bg-gradient-primary" );
        
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");
          window.location.href="{{'int-landing-page'}}";           
        }
        else if($("#successMesgID").text() == "Error in creating a task") {
          $.notify($("#successMesgID").text(), "error");
        }
        
        if($("#landing_page_id").val() != 0)
        {
          getLandingPageCourses();
          $("#create").empty().text("Update");
          $("#create").prop("title", "Update");  
        }
    });

    function getLandingPageCourses(){      
        var institutionId = $("#landing_page_institution").val();
        $.ajax({
            type:'get',
            url: "/get-courses",
            data: {'institutionId' : institutionId},
            success:function(data) {
              if(data) {
                var courses = $("#landing_page_course").empty();
                var courseId = $("#hdnCourseId").val();
                courses.append("<option value=''>--Select--</option>");
                var course_item_el = "";
                for(var i=0; i<data.courseList.length; i++){
                  if(courseId == data.courseList[i]['course_id'])  
                  {
                    course_item_el = '<option selected value="'+ data.courseList[i]['course_id']+'">'+ data.courseList[i]['course_name'] +'</option>';
                  }
                  else
                  {
                    course_item_el = '<option value="'+ data.courseList[i]['course_id']+'">'+ data.courseList[i]['course_name'] +'</option>';
                  }
                  courses.append(course_item_el);
                }
              }    
            }
        });
    }

    function CreateLandingPage(){
      var institution = $("#landing_page_institution").val();
      var course = $("#landing_page_course").val();
      var title = $("#landing_page_title").val();
      var assigne = $("#assignee").val();
      var assigner = $("#assigner").val();
      var developmentType = $("#developmentType").val();
      
      if(institution == "") {
        $("#institution-error").text("Please select an institution.");
      }
      else {
        $("#institution-error").text("");
      }

      if(institution == "") {
        $("#course-error").text("Please select an institution.");
      }
      else if(institution != "" && course == "") {
        $("#course-error").text("Please select a course.");
      }
      else {
        $("#course-error").text("");
      }

      if(title == "") {
        $("#title-error").text("Please enter a title");
      }
      else {
        $("#title-error").text("");
      }

      if(assigne == "") {
        $("#assignee-error").text("Please select an assignee");
      }
      else {
        $("#assignee-error").text("");
      }

      if(assigner == "") {
        $("#assigner-error").text("Please select an assigner");
      }
      else {
        $("#assigner-error").text("");
      }

      if(developmentType == ""){
        $("#developmentType-error").text("Please select a development type");
      }
      else {
        $("#developmentType-error").text("");
      }

      $("#add-landing-page").submit(function (e) {
        if($("#institution-error").text() != "" || $("#course-error").text() != "" || $("#title-error").text() != "" || $("#assigner-error").text() != ""
        || $("#assignee-error").text() != "" || $("#developmentType-error").text() != "" ){
          e.preventDefault();
          return false;
        }
      }); 
    }
    // $('input[type="file"]').change(function(){
    //     previewFile(this);
    // });

    // function previewFile(input){
    //     var file = input.files[0];
    //     var reader = new FileReader();
    //     reader.onload = function(e){
    //         $('#previewImg').attr('src', e.target.result);
    //     }
    //     reader.readAsDataURL(file);
    // }
</script>
@endsection