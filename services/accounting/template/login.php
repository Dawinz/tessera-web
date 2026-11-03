<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Tessera Accounts'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

    <div class="az-signin-wrapper">
      <div class="az-card-signin">
        <h1 class="az-logo">az<span>i</span>a</h1>
        <div class="az-signin-header">
          <h2>Welcome back!</h2>
          <h4>Please sign in to continue</h4>

          <div >
            <div class="form-group">
              <label>Email</label>
              <input type="text" id="username"class="form-control" placeholder="Enter your username">
            </div><!-- form-group -->
            <div class="form-group">
              <label>Password</label>
              <input type="password" id="password" class="form-control" placeholder="Enter your password" >
            </div><!-- form-group -->
            <button id="login" class="btn btn-az-primary btn-block">Sign In</button>
          </div>
        </div><!-- az-signin-header -->
        <div class="az-signin-footer">
          <p><a href="">Forgot password?</a></p>
          <p>Don't have an account? <a href="page-signup.html">Create an Account</a></p>
        </div><!-- az-signin-footer -->
      </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->


    <script src="/accounts/js/template/login.js"></script>
    <script src="/accounts/js/template/sweatalert2.js"></script>
    <script>
      $(function(){
        'use strict'

      });
    </script>
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
