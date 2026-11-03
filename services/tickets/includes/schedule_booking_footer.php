<?php
/** Shared modals + scripts for Schedule book / manage pages. Expects $isScheduleAdmin. */
?>
<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
window.SR_SCHEDULE = {
	isAdmin: <?php echo !empty($isScheduleAdmin) ? 'true' : 'false'; ?>,
	role: <?php echo json_encode($userRole ?? ''); ?>
};
</script>
<script>
(function ($) {
	if (!$ || $.fn.selectpicker) {
		return;
	}
	$.fn.selectpicker = function (method) {
		if (method === 'refresh' && $.fn.select2) {
			return this.each(function () {
				var $el = $(this);
				try {
					if ($el.data('select2')) {
						$el.trigger('change.select2');
					} else {
						$el.select2({ width: '100%' });
					}
				} catch (e) {
					/* legacy bootstrap-select shim - ignore select2 compat gaps */
				}
			});
		}
		return this;
	};
})(window.jQuery);
</script>
<script src="choose_seat.js?v=<?php echo filemtime(__DIR__ . '/../choose_seat.js'); ?>"></script>
<script type="text/javascript" src="datatable/datatable.js"></script>
<script src="assets/js/schedule_trip_bookings.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/schedule_trip_bookings.js'); ?>"></script>
<script src="assets/js/schedule_book_trip.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/schedule_book_trip.js'); ?>"></script>
<script src="insert_ticket.js?v=<?php echo filemtime(__DIR__ . '/../insert_ticket.js'); ?>"></script>
<script src="save_foc.js"></script>
<script src="add_half_cost.js"></script>
<script src="adnc.js"></script>
<script src="header_location_manifest.js"></script>
<script src="header_location_seat.js"></script>
<script src="js/insert/insert_transit.js"></script>
<script src="add_seat.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="currency.js"></script>
<?php if (!empty($isScheduleAdmin)): ?>
<script src="js/insert/route_trip.js"></script>
<?php endif; ?>

<?php
include __DIR__ . '/schedule_booking_modals.php';
include dirname(__DIR__) . '/modal_collection.php';
if (!empty($isScheduleAdmin)) {
	include dirname(__DIR__) . '/modal/route_trip.php';
}
include dirname(__DIR__) . '/modal/transit.php';
?>

<script src="assets/js/bootstrap-jquery-bridge.js"></script>
<script>
$(function () {
	$('.selectpicker').select2();
	if ($.fn.select2) {
		$('.js-example-basic-single').select2({ width: '100%' });
	}
	if ($.fn.modal) {
		$('#foc_panel,#trans_panel,#advance_panel,.bs-example-modal-xl_manifest').modal({
			backdrop: 'static',
			keyboard: false
		});
	}
	if ($.fn.carousel) {
		$('#carouselExampleIndicators_manifest,#carouselExampleIndicators_manifest2').carousel({
			interval: false
		});
	}
	if (window.SR_SCHEDULE_TRIP && window.SR_scheduleSelectTrip) {
		window.SR_scheduleSelectTrip(window.SR_SCHEDULE_TRIP, window.SR_SCHEDULE_TRIP.route_name || '');
	}
});
</script>
