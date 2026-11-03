<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg add-route-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Add New Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_route_form" class="needs-validation" novalidate autocomplete="off">
                <div class="modal-body">
                    <ul class="wizard-nav mb-4" id="add_route_wizard_nav">
                        <li class="wizard-list-item">
                            <div class="list-item">
                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Basic Info">
                                    <i class="uil uil-info-circle"></i>
                                </div>
                            </div>
                        </li>
                        <li class="wizard-list-item">
                            <div class="list-item">
                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Departure Points">
                                    <i class="uil uil-map-marker"></i>
                                </div>
                            </div>
                        </li>
                        <li class="wizard-list-item">
                            <div class="list-item">
                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Arrival Point">
                                    <i class="uil uil-map-marker-alt"></i>
                                </div>
                            </div>
                        </li>
                        <li class="wizard-list-item">
                            <div class="list-item">
                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Ticket Price">
                                    <i class="uil uil-bill"></i>
                                </div>
                            </div>
                        </li>
                        <li class="wizard-list-item">
                            <div class="list-item">
                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Stops">
                                    <i class="uil uil-stop-circle"></i>
                                </div>
                            </div>
                        </li>
                        <li class="wizard-list-item">
                            <div class="list-item">
                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Trip Requirements">
                                    <i class="uil uil-clipboard-notes"></i>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <!-- end wizard-nav -->

                    <!-- Step 1: Basic Info -->
                    <div class="add-route-wizard-tab" id="basic_info">
                        <div class="text-center mb-4">
                            <h5>Basic Info</h5>
                            <p class="card-title-desc">General details about this route</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3" id="route_starting_point_wrap">
                                    <label class="form-label fw-semibold" for="route_starting_point">Route Starting
                                        Point <span class="text-danger">*</span></label>
                                    <select class="form-select selectpicker" id="route_starting_point"
                                        name="route_starting_point">
                                        <option value="Choose Starting Point">Route Starting Point</option>
                                        <?= $add_route_locations; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="route_ending_point_wrap">
                                    <label class="form-label fw-semibold" for="route_ending_point">Route Ending Point
                                        <span class="text-danger">*</span></label>
                                    <select class="form-select selectpicker" id="route_ending_point"
                                        name="route_ending_point">
                                        <option value="Choose Ending Point">Route Ending Point</option>
                                        <?= $add_route_locations; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3" id="journey_duration_wrap">
                                    <label class="form-label fw-semibold" for="journey_duration_value">Journey Duration
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="journey_duration_value"
                                            name="journey_duration_value" placeholder="e.g. 72" min="1" step="1"
                                            inputmode="numeric">
                                        <select class="form-select" id="journey_duration_unit"
                                            name="journey_duration_unit" style="max-width:120px;">
                                            <option value="Hours">Hour(s)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="identifer_wrap">
                                    <label class="form-label fw-semibold" for="identifer">Route Identifer <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="identifer" name="identifer"
                                        placeholder="e.g. DAR" maxlength="3" pattern="[A-Za-z]{1,3}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="distance_in_km_wrap">
                                    <label class="form-label fw-semibold" for="distance_in_km">Distance in km</label>
                                    <input type="text" class="form-control" id="distance_in_km" name="distance_in_km"
                                        placeholder="e.g. 3.4" inputmode="decimal" pattern="[0-9]*\.?[0-9]*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="map_coordinates_wrap">
                                    <label class="form-label fw-semibold" for="map_coordinates">Map Coordinates</label>
                                    <input type="text" class="form-control" id="map_coordinates" name="map_coordinates"
                                        placeholder="e.g. -6.7924, 39.2083">
                                </div>
                            </div>



                            <div class="col-12">
                                <div class="mb-3" id="description_wrap">
                                    <label class="form-label fw-semibold" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Add route description"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check mb-3" id="active_wrap">
                                    <input type="checkbox" class="form-check-input" id="active" name="active" checked>
                                    <label class="form-check-label" for="active">Active </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end step 1 -->
                    <div class="add-route-wizard-tab" id="pick_up_points">
                        <div class="text-center mb-4">
                            <h5>Depurture Points</h5>
                            <p class="card-title-desc">Where does this route start </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-2" id="pick_up_points_table">
                                <thead>
                                    <tr>
                                        <th>Departure Point</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="pick_up_points_rows">
                                    <tr class="pick-up-point-row">
                                        <td>
                                            <select class="form-select selectpicker" name="starting_point[]">
                                                <option value="Choose Starting Point">Choose Starting Point</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove_pick_up_point"
                                                tabindex="-1"><i class="uil uil-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-soft-success btn-sm" id="add_pick_up_point_btn">
                                <i class="uil uil-plus me-1"></i>Add Pick Up Point
                            </button>
                        </div>
                    </div>
                    <div class="add-route-wizard-tab" id="arrival_point">
                        <div class="text-center mb-4">
                            <h5>Arrival Point</h5>
                            <p class="card-title-desc">Where does this route end</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-2" id="arrival_point_table">
                                <thead>
                                    <tr>
                                        <th>Arrival Point</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="arrival_point_rows">
                                    <tr class="arrival-point-row">
                                        <td>
                                            <select class="form-select selectpicker" name="ending_point[]">
                                                <option value="Choose Ending Point">Choose Ending Point</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove_arrival_point"
                                                tabindex="-1"><i class="uil uil-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-soft-success btn-sm" id="add_arrival_point_btn">
                                <i class="uil uil-plus me-1"></i>Add Arrival Point
                            </button>
                        </div>
                    </div>
                    <!-- end step 4 -->
                    <div class="add-route-wizard-tab" id="ticket_price">
                        <div class="text-center mb-4">
                            <h5>Ticket Price</h5>
                            <p class="card-title-desc">Pricing and schedule for this route</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-2" id="ticket_price_table">
                                <thead>
                                    <tr>
                                        <th>Vehicle Class</th>
                                        <th>Fare Amount</th>
                                        <th>Currency</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="ticket_price_rows">
                                    <tr class="ticket-price-row">
                                        <td>
                                            <select class="form-select selectpicker" name="vehicle_type[]">
                                                <option value="Choose Vehicle Type">Choose vehicle segment</option>
                                                <?= $add_vehicle_class; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="uil uil-money-bill"></i></span>
                                                <input type="text" class="form-control" name="fare_amount[]"
                                                    placeholder="e.g. 500" inputmode="decimal"
                                                    pattern="[0-9,]*\.?[0-9]*">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select selectpicker" name="fare_currency[]">
                                                <option value="Choose Currency">Choose Currency</option>
                                                <?= $add_currency; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove_ticket_price"
                                                tabindex="-1"><i class="uil uil-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-soft-success btn-sm" id="add_ticket_price_btn">
                                <i class="uil uil-plus me-1"></i>Add Ticket Price
                            </button>
                        </div>
                    </div>
                    <!-- Step 5: Requirements -->
                    <div class="add-route-wizard-tab" id="stops">
                        <div class="text-center mb-4">
                            <h5>Stops</h5>
                            <p class="card-title-desc">Intermediate stops along this route</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-2" id="stops_table">
                                <thead>
                                    <tr>
                                        <th>Stop</th>
                                        <th>Duration</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="stops_rows">
                                    <tr class="route-stop-row">
                                        <td>
                                            <select class="form-select selectpicker route-stop-input" name="stops[]">
                                                <option value="Choose Stop">Choose Stop</option>
                                                <?php echo $add_stops; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="stop_duration[]"
                                                placeholder="e.g. 15 mins">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-stop"
                                                tabindex="-1"><i class="uil uil-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-soft-success btn-sm" id="add_stop_btn">
                                <i class="uil uil-plus me-1"></i>Add Stop points
                            </button>
                        </div>
                    </div>
                    <div class="add-route-wizard-tab" id="trip_requirements">
                        <div class="text-center mb-4">
                            <h5>Requirements</h5>
                            <p class="card-title-desc">Any special Documents required for this route</p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3 text-center" id="requirements_wrap">
                                    <label class="form-label fw-semibold d-block mb-3">Required Documents</label>
                                    <div
                                        class="d-flex flex-wrap justify-content-center gap-2 requirement-check-group">
                                        <label class="requirement-check-pill" for="requirement_id">
                                            <input type="checkbox" class="form-check-input" id="requirement_id"
                                                name="requirements[]" value="ID" checked>
                                            <span>National id card</span>
                                        </label>
                                        <label class="requirement-check-pill" for="requirement_passport">
                                            <input type="checkbox" class="form-check-input" id="requirement_passport"
                                                name="requirements[]" value="Passport">
                                            <span>Passport</span>
                                        </label>
                                        <label class="requirement-check-pill" for="requirement_visa">
                                            <input type="checkbox" class="form-check-input" id="requirement_visa"
                                                name="requirements[]" value="Visa">
                                            <span>Visa</span>
                                        </label>
                                        <label class="requirement-check-pill" for="requirement_yellow_fever_card">
                                            <input type="checkbox" class="form-check-input"
                                                id="requirement_yellow_fever_card" name="requirements[]"
                                                value="Yellow Fever Card">
                                            <span>Yellow Fever Card</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end step 5 -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary w-sm" id="add_route_prev">Previous</button>
                    <button type="button" class="btn btn-primary w-sm" id="add_route_next">Next</button>
                    <button type="submit" class="btn btn-primary px-4 d-none" id="add_route_submit">
                        <i class="uil uil-plus me-1"></i>Save Route
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
