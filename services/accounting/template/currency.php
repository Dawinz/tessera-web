<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Currency'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
  </head>
  <body data-layout="horizontal" data-topbar="dark" data-layout-scrollable="true" class="sr-platform-body">

<?php include 'includes/top_header.php'; ?>

<div id="modaldemo1" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <h6 class="modal-title">Add Currency</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="signup" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row row-sm g-3">
            <div class="col-lg">
              <label class="form-label">Currency Name</label>
              <input class="form-control" placeholder="Name" type="text" name="name" id="name">
            </div>
            <div class="col-lg">
              <label class="form-label">Value</label>
              <input class="form-control" placeholder="Value" type="text" name="value" data-type="currency" id="value">
            </div>
            <div class="col-lg">
              <label class="form-label">Symbol</label>
              <input class="form-control" placeholder="Symbol" type="text" name="symbol" id="symbol">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label">Details</label>
            <textarea class="form-control" placeholder="Enter any details" name="detail"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-indigo" id="save">Save changes</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'canvas/update_currency.php'; ?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-accounts-page">
      <div class="row">
        <div class="col-lg-3 col-xl-2 mb-3">
          <div class="sr-side-panel">
            <label class="d-block">Add New Currency</label>
            <button class="btn btn-indigo btn-with-icon" data-bs-toggle="modal" data-bs-target="#modaldemo1">
              <i class="bx bx-plus"></i> Add Currency
            </button>
          </div>
        </div>
        <div class="col-lg-9 col-xl-10">
          <div class="sr-content-card">
            <div class="cname_route mb-3">Currencies</div>
            <div class="table-responsive">
              <table id="example1" class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Symbol</th>
                    <th>Status</th>
                    <th>Detail</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/insert/currency.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/update/update_currency.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
<script src="/accounts/js/template/fetch/fetch_currency.js<?php echo $url_time; ?>"></script>
</body>
</html>
