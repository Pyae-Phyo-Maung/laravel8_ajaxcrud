<div class="modal edit_country" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Country Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form action="{{route('update.country.detail')}}" method="post" id="updateCountry">
           @csrf
           <input type="hidden" name="cid">
           <div class="form-group">
               <label for="country_name">Country Name</label>
               <input type="text" id="country_name" class="form-control" name="country_name">
               <span class="text-danger error-text country_name_error"></span>
           </div>
           <div class="form-group">
               <label for="capital_city">Capital City</label>
               <input type="text" id="capital_city" class="form-control" name="capital_city">
               <span class="text-danger error-text capital_city_error"></span>
           </div>
           <button type="submit" class="btn btn-primary btn-block">Save changes</button>
       </form>
      </div>
    </div>
  </div>
</div>