<?php
  session_start();
  if (isset($_SESSION['user'])) {
    header('location:index.php');
  }
 ?>
<!DOCTYPE html>
<html lang="en">
  <!-- header -->
  <?php require_once 'public/layouts/header.php' ?>

  <body>
  <!-- navi -->
  <?php require_once 'public/layouts/nav.php' ?>
  <!-- body content -->
    <div class="container">
      <div class="content">
          <div class="starter-template">
            <h1>Welcome To Vaccination Appointment Website </h1>
            <p class="lead">Please use your email to login and appointment anytime you prefer to receive vaccination .
                <br> If you have not register before, please register your account. Thank you.</p>
          </div>  
          <!-- register -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="register" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Register</h4>
              </div>
              <form action="admin/Register.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_type" class="col-sm-4 control-label">User Type:</label>
                        <div class="col-sm-3">
                            <label class="checkbox-inline">
                                <input type="radio" name="user_type" id="reg_pa" value="1" checked> Patient
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="user_type" id="reg_pd" value="2"> Provider
                            </label>
                        </div>
                    </div>
                    
                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">Name:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="username" id="username" minlength="2" maxlength="20" placeholder="Full Name" required="">
                    </div>
                    <!-- error message -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-6">
                      <input type="email" class="form-control" name="email" id="reg_email" placeholder="Email" required="">
                    </div>
                    <h6 style="color: red;" id="dis_em"></h6>
                  </div>

                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" id="password" placeholder="password" minlength="6" maxlength="20" required="">
                    </div>
                    <h6 style="color: red;" id="dis_pwd"></h6>
                  </div>

                  <div class="form-group">
                    <label for="confirm" class="col-sm-4 control-label">Confirm password:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm password" minlength="6" maxlength="20" required="">
                    </div>
                    <h6 style="color: red;" id="dis_con_pwd"></h6>
                  </div>

                    <div class="form-group" id="col_ssn" >
                        <label for="ssn" class="col-sm-4 control-label">SSN:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="ssn" id="ssn" placeholder="SSN" required="">
<!--                                 <--  minlength="10" maxlength="10" -->

                        </div>
                        <h6 style="color: red;" id="dis_ssn"></h6>
                    </div>

                    <div class="form-group" id="col_gender">
                        <label for="gender" class="col-sm-4 control-label">Gender:</label>
                        <div class="col-sm-3">
                            <label class="checkbox-inline">
                                <input type="radio" name="gender" id="reg_gender_m" value="male" checked> Male
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="gender" id="reg_gender_f" value="female"> Female
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="col_dob">
                        <label for="dob" class="col-sm-4 control-label">Date of Birth:</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="dob" id="dob" placeholder="" required="">
                        </div>
                        <h6 style="color: red;" id="dis_dob"></h6>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-sm-4 control-label">Phone:</label>
                        <div class="col-sm-6">
                            <input type="tel" class="form-control" name="phone" id="phone" placeholder="phone" minlength="10" maxlength="10" required="">
                        </div>
                        <h6 style="color: red;" id="dis_phone"></h6>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-sm-4 control-label">Address:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="address" id="address" placeholder="address"  required="">
                        </div>
                        <h6 style="color: red;" id="dis_addr"></h6>
                    </div>

                    <div class="form-group" id="col_max_distance">
                        <label for="max_distance" class="col-sm-4 control-label">Max Distance:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="max_distance" id="max_distance" placeholder="max distance"  required="">
                        </div>
                        <h6 style="color: red;" id="dis_max_dist"></h6>
                    </div>

                    <div class="form-group hidden" id="col_pd_type" >
                        <label for="provider_type" class="col-sm-4 control-label">Provider Type:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="provider_type" id="provider_type" aria-label="Default select example""  required="">
                                <option selected>Open this select menu</option>
                                <option value="hospital">Hospital</option>
                                <option value="doctor">Doctor</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <h6 style="color: red;" id="dis_pd_type"></h6>
                    </div>

                  
                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label"> Verification Code :</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="reg_code" placeholder="verification code" required="" maxlength="4" size="100">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                <span>Click to Switch</span>
                    </div>
                  </div>
<!--                    default check all inputs-->
                  <input type="hidden" name="type" value="all">
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>
                  <input type="reset" class="btn btn-warning" value ="reset" />
                  <button type="submit" class="btn btn-primary" id="reg">Register</button>
                </div>
              </form>
              </div>
            </div>
          </div>

          <!-- login -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="login" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
              </div>
              <form action="admin/Login.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">

<!--                    <div class="form-group">-->
<!--                        <label for="user_type" class="col-sm-4 control-label">User Type:</label>-->
<!--                        <div class="col-sm-3">-->
<!--                            <label class="checkbox-inline">-->
<!--                                <input type="radio" name="user_type" id="log_pa" value="0" checked> Patient-->
<!--                            </label>-->
<!--                            <label class="checkbox-inline">-->
<!--                                <input type="radio" name="user_type" id="log_pd" value="1"> Provider-->
<!--                            </label>-->
<!--                            <label class="checkbox-inline">-->
<!--                                <input type="radio" name="user_type" id="log_ad" value="2"> Admin-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </div>-->

                  <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-6">
                      <input type="email" class="form-control" name="email" id="log_email" placeholder="Email" required="">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" placeholder="password" minlength="6" maxlength="20" required="">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="remember_me" class="col-sm-4 control-label">Remember Me:</label>
                    <div class="col-sm-3">
                      <label class="checkbox-inline">
                        <input type="radio" name="remember_me" id="rem_yes" value="1" checked> Yes
                      </label>
                      <label class="checkbox-inline">
                        <input type="radio" name="remember_me" id="rem_no" value="0"> No
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label"> verification code :</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="log_code" placeholder="verification code" required="" maxlength="4">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                        <span>Click to Switch</span>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>
                  <input type="reset" class="btn btn-warning" value ="reset" />
                  <button type="submit" class="btn btn-primary" name="login">Login</button>
                </div>
              </form>
              </div>
            </div>
          </div>

      </div>

    </div><!-- /.container -->
    
    <!-- page bottom -->
    <?php require_once 'public/layouts/footer.php'; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

<!--  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
<!--  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->
<!--  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" ></script>-->

  <script src="public/js/check_register.js"></script>
  </body>
</html>