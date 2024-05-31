@extends('it-admin-shared.it-admin-master')

@section('it-adminContent')

<div class="row">
    @if(session()->has('message'))
      <div class="alert alert-success" id="successMesgID" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="display: none">
        {{ session()->get('message') }}
        <button type="button" onclick="campNotify();" class="btn-close" style="float: right;" aria-label="Close"></button>
      </div>
    @endif
    <div class="col-md-12">
        <button class="btn btn-outline-primary" id="btnBackID" onclick="goBack()"><span class="fa fa-backward">&nbsp;Back</span></button>
    </div>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 card-backgroundcolor">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <h5>Campaign Price</h5>                
                    </div>
                    <div class="col-lg-6 col-6 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="btn btn-primary" id="createCampaignPriceID" onclick="createCampaignPrice(0);" title="Create">
                                <i class="fa fa-plus" style="font-size: small;"> &nbsp;Create</i>
                            </a>                
                        </div>
                    </div>                  
                </div>            
            </div>
            <div class="card-body px-3 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-1" id="it-campaign-priceTable">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Price</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Campaign Price Code</th></th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-s font-weight-bolder opacity-10">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($campaignPriceList as $campaignPrice)
                                <tr>
                                    <td>{{ $campaignPrice->campaign_price_name }}</td>
                                    <td>{{ $campaignPrice->campaign_price_code }}</td>
                                    <td>
                                        @if($campaignPrice->active == 0)
                                            Inactive
                                        @else
                                            Active
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaignPrice->active == 1)
                                            <button type="button" class="btn btn-primary" title="Edit Campaign Price" onclick="createCampaignPrice({{ $campaignPrice->campaign_price_id }})"><span style="font-size:12px;">Edit</span></button>
                                        @endif
                                        @if($campaignPrice->active == 1)
                                            <button type="button" class="btn btn-danger" title="Delete Campaign Price" onclick="deleteCampaignPrice({{ $campaignPrice->campaign_price_id }}, 0)"><span style="font-size:12px;">Delete</span></button>
                                        @else
                                            <button type="button" class="btn btn-primary" title="Recover Campaign Price" onclick="deleteCampaignPrice({{ $campaignPrice->campaign_price_id }}, 1)"><span style="font-size:12px;">Recover</span></button>
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

<!-- Create Campaign Price Modal -->
<div class="modal fade" id="createCampaignPriceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Create Campaign Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <form action="{{ url('it-admin-campaign-price-create')}}" method="post" id="campaginPriceForm">
          @csrf
          <input type="hidden" id="hdnCampaignPriceId" name="hdnCampaignPriceId" />
          <div class="row form-group">
            <div class="col-md-5">
              <label for="campaignPriceCode">Campaign Price Code</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignPriceCode" name="campaignPriceCode" onchange="checkCampaignPriceCode();" maxlength="5" />
              <span id="campaign-price-code-error" class="text-danger"></span>
            </div>
          </div>
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <label for="campaignPriceName">Campaign Price</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-md-7">
              <input type="text" class="form-control" id="campaignPriceName" name="campaignPriceName"  />
              <span id="campaign-price-error" class="text-danger"></span>
            </div>
          </div>
          
          <hr />
          <div class="row form-group mt-2">
            <div class="col-md-5">
              <button class="btn btn-primary" id="saveCampaignPriceId" title="Save" onclick="saveCampaignPrice();">Save</button>
              <button data-bs-dismiss="modal" class="btn btn-danger" title="Cancel">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Campaign Price Modal -->
<div class="modal fade" id="deleteCampaignPriceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTitleId">Delete Campaign Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="hiddenCampaignPriceId" name="hiddenCampaignPriceId" /> 
        <input type="hidden" id="identificationId" name="identificationId" />       
        <p id="deleteMesgId">Are you sure you want to delete this campaign price?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" title="Yes" onclick="confirmDeleteCampaignPrice();">Yes</button>
        <button data-bs-dismiss="modal" class="btn btn-danger" title="No">No</button>
        </div>
    </div>    
  </div>
</  div>
<script type="text/javascript">
    $(document).ready(function() {        
        $("#it-adminCampaignID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminLandingPageID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminHomeID").removeClass( "active bg-primary bg-gradient" );
        $("#it-adminSettingsID").addClass( "active bg-primary bg-gradient" );
        $("#it-campaign-priceTable").dataTable();
        if($("#successMesgID").text() !="") {
          $.notify($("#successMesgID").text(), "success");          
        }
    });

    function createCampaignPrice(campaignPriceId) {        
        $("#campaign-price-error").text("");
        $("#campaign-price-code-error").text("");
        if(campaignPriceId == 0) {
            $("#campaignPriceName").val('');
            $("#createCampaignPriceModal").modal('show');
            $("#saveCampaignPriceId").attr("title", "Save");
            $("#saveCampaignPriceId").empty().html("Save");
            $("#modalTitleId").empty().html("Create Campaign Price");
            $("#campaignPriceCode").val('');
            $("#hdnCampaignPriceId").val('');
        }
        else {
            $.ajax({
                type:'get',
                url: "/it-admin-campaign-price-edit",
                data: {'campaignPriceId' : campaignPriceId},
                success:function(data){
                    if(data){
                        $("#saveCampaignPriceId").attr("title", "Update");
                        $("#saveCampaignPriceId").empty().html("Update"); 
                        $("#modalTitleId").empty().html("Update Campaign Price")                       
                        $("#createCampaignPriceModal").modal('show');
                        $("#campaignPriceName").val(data[0]['campaign_price_name']);
                        $("#campaignPriceCode").val(data[0]['campaign_price_code']);
                        $("#hdnCampaignPriceId").val(campaignPriceId);        
                    }
                }
            });
        }
    }

    function saveCampaignPrice() {
        
        var campaignPriceId = $("#hdnCampaignPriceId").val();
        var campaignPriceCode = $("#campaignPriceCode").val();
        if($("#campaignPriceName").val() == "") {
            $("#campaign-price-error").text("Please enter an campaign price");
        }
        else {
            $("#campaign-price-error").text("");
        }

        if($("#campaignPriceCode").val() == ""){
            $("#campaign-price-code-error").text("Please enter an campaign price code");
        }
        else if($("#campaign-price-code-error").text() != "") {
            $("#campaign-price-code-error").text("Please provide unique campaign price code.");
        }
        else {
            $("#campaign-price-code-error").text("");
        }

        $("#campaginPriceForm").submit(function (e) {
            
            if($("#campaign-price-error").text() != "" || $("#campaign-price-code-error").text() != "") {
                e.preventDefault();
                return false;
            }
        });
    }
    
    function deleteCampaignPrice(campaignPriceId, identification) {
        $("#deleteCampaignPriceModal").modal('show');
        $("#hiddenCampaignPriceId").val(campaignPriceId);
        $("#identificationId").val(identification);
        if(identification == 0) {
            $("#deleteTitleId").empty().html("Delete Campaign Price");
            $("#deleteMesgId").empty().html("Are you sure you want to delete this campaign price?");
        }
        else {
            $("#deleteTitleId").empty().html("Recover Campaign Price");
            $("#deleteMesgId").empty().html("Are you sure you want to recover this campaign price?");
        }
    }

    function confirmDeleteCampaignPrice() {
        $.ajax({
                type:'get',
                url: "/it-admin-campaign-price-delete",
                data: {'campaignPriceId' : $("#hiddenCampaignPriceId").val(), 'identification' : $("#identificationId").val()},
                success:function(data){
                    if(data){
                        $.notify(data, "success");
                        setTimeout(() => {window.location.href="{{'it-admin-campaign-price'}}"}, 2000);     
                    }
                }
            });
    }

    function checkCampaignPriceCode() {
        var campaignPriceCode = $("#campaignPriceCode").val();
        var campaignPriceId = $("#hdnCampaignPriceId").val();
        $.ajax({
            type:'get',
            url: '/it-admin-campaign-price-check',
            data: {'campaignPriceCode' : campaignPriceCode, 'campaignPriceId' : campaignPriceId},
            success:function(data){
                
                if(data != "" && data[0]['count'] > 0) {
                    $("#campaign-price-code-error").text("Please provide unique campaign price code.");
                }
                else {
                    $("#campaign-price-code-error").text("");
                }
            }
        });
    }
        
</script>

@endsection
