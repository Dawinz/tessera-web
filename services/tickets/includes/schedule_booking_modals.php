<?php
/** FOC / Transit / Advance / Print modals shared by Schedule and legacy routes redirect target. */
?>
<div id="foc_panel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="myModalLabel">FOC details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close_foc_panel"></button>
			</div>
			<div class="modal-body">
				<div id="foc_text" class="display_none"></div>
				<div class="form-floating">
					<textarea class="form-control floatingTextarea23" placeholder="Leave a comment here" id="floatingTextarea23" style="height: 100px"></textarea>
					<label for="floatingTextarea23">FOC details</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal" id="close_foc_panel2">Close</button>
				<button type="button" class="btn btn-primary" id="save_foc_d">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div id="trans_panel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Transit details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" id="close_trans_panel" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="trans_text" class="display_none"></div>
				<div class="form-floating">
					<textarea class="form-control" placeholder="Leave a comment here" id="transit_details" style="height: 100px"></textarea>
					<label for="transit_details">Transit details</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal" id="close_trans_panel2">Close</button>
				<button type="button" class="btn btn-primary" id="save_trans_d">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div id="advance_panel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Advance details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" id="close_advc_panel" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<h5 class="modal-title"><span class="t_price"></span> <span class="text-muted d-inline-block fw-normal font-size-15 ms-2">Ticket price</span></h5>
				<br>
				<div id="advance_chcek_text" class="display_none"></div>
				<label class="form-check-label" for="topbar-color-light">Advance amount</label>
				<input type="text" class="form-control advance_left" value="">
			</div>
			<div class="modal-footer">
				<h5 class="modal-title"><span style="color:#fc0335;" class="result_half">0</span> <span class="text-muted d-inline-block fw-normal font-size-15 ms-2">Balance</span></h5>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal" id="close_advc_panel2">Close</button>
				<button type="button" class="btn btn-primary save_avcance_d" id="save_avcance_d">Save</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade exampleModalFullscreen" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalFullscreenLabel">Print receipts</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="container_print" style="background-color:white;"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
