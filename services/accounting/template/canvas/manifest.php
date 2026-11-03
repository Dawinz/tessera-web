<div class="offcanvas offcanvas-top sr-manifest-update-panel" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasTopLabel">Update manifest</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <input id="id_holder" type="hidden">
    <div class="row g-3 align-items-end">
      <div class="col-lg-4">
        <label class="form-label" for="choose_route_update">Route name</label>
        <select class="form-select" id="choose_route_update" name="route_name_update">
          <?php
          if ($select_route_num <= 0) {
            echo '<option value="">Routes not found</option>';
          } elseif ($select_route_num <= 1) {
            echo $route_full_name;
          } else {
            echo '<option value="" id="route_name_holder">Choose route</option>';
            echo $route_full_name;
          }
          ?>
        </select>
      </div>
      <div class="col-lg-4">
        <label class="form-label" for="trip_date_update">Trip date</label>
        <select class="form-select" id="trip_date_update">
          <option value="" id="trip_date_update_holder">Select trip date</option>
        </select>
      </div>
      <div class="col-lg-4">
        <label class="form-label" for="vehicle_update">Vehicle</label>
        <select class="form-select" id="vehicle_update" name="category">
          <option value="" id="vehicle_update_holder">Select vehicle</option>
        </select>
      </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4 pt-2 border-top">
      <button type="button" class="btn btn-primary" id="save_update"><span id="text_change">Save changes</span></button>
      <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="offcanvas">Close</button>
    </div>
  </div>
</div>
