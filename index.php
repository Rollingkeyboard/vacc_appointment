<?php 
session_start();
if (!isset($_SESSION['user'])) {
    if (isset($_COOKIE['user'])) {
        $_SESSION['user'] = $_COOKIE['user'];
    } else {
        header('location:main.php');
        exit();
    }
}
if (isset($_SESSION['rem'])) {
    setcookie('user',$_SESSION['user'],time()+3600);
    unset($_SESSION['rem']);
}
?>
<!DOCTYPE html>
<html lang="en">
    <!-- header -->
    <?php require_once 'public/layouts/header.php' ?>

    <body>
    <!-- navi -->
    <?php require_once 'public/layouts/nav.php' ?>

    <!-- page content -->
    <div class="container">
        <div class="content">
            <div class="starter-template">
                <h1 id="wellcome_header"></h1>
                <div class="jumbotron" style="position: absolute; height: 60%; overflow-y: scroll;">
                    <div class="container">
                        <div class="row">
                        <h2 id="user_type_header"></h2>
                        <table id="editableTable" class="table table-bordered">
                            <thead id="assign_to_table_head">

                            </thead>
                            <tbody id="main_table">
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <p><a class="btn btn-primary btn-lg" role="button" id="add_new_row">add new appointment</a></p>
            </div>
        </div>

        <!-- profile -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="profile" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Your profile</h4>
                    </div>

                    <form action="admin/Profile.php" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="modal-body">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="username" class="col-sm-4 control-label">Name:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="username" value="" id="username" minlength="2" maxlength="20" placeholder="Full Name" required="">
                                </div>
                                <!-- error message -->
                                <h6 style="color: red;" id="dis_un"></h6>
                            </div>

                            <!-- email -->
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Email:</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" name="email" id="email" readonly="readonly" placeholder="Email" required="">
                                </div>
                                <h6 style="color: red;" id="dis_em"></h6>
                            </div>

                            <!-- password -->
                            <div class="form-group">
                                <label for="password" class="col-sm-4 control-label">Password:</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="password" minlength="6" maxlength="20" required="">
                                </div>
                                <h6 style="color: red;" id="dis_pwd"></h6>
                            </div>

                            <!-- conform password -->
                            <div class="form-group">
                                <label for="confirm" class="col-sm-4 control-label">Confirm password:</label>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm password" minlength="6" maxlength="20" required="">
                                </div>
                                <h6 style="color: red;" id="dis_con_pwd"></h6>
                            </div>

                            <!-- ssn -->
                            <div class="form-group" id="col_ssn">
                                <label for="ssn" class="col-sm-4 control-label">SSN:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ssn" id="ssn" readonly="readonly" placeholder="SSN" required="">
                                </div>
                                <h6 style="color: red;" id="dis_ssn"></h6>
                            </div>

                            <!-- gender -->
                            <div class="form-group" id="col_gender">
                                <label for="gender" class="col-sm-4 control-label">Gender:</label>
                                <div class="col-sm-3">
                                    <label class="checkbox-inline">
                                        <input type="radio" name="gender" id="yes" value="1" checked> Male
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="radio" name="gender" id="optionsRadios4" value="0"> Female
                                    </label>
                                </div>
                            </div>

                            <!-- date of birth -->
                            <div class="form-group" id="col_dob">
                                <label for="dob" class="col-sm-4 control-label">Date of Birth:</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="dob" id="dob" placeholder="" required="">
                                </div>
                                <h6 style="color: red;" id="dis_dob"></h6>
                            </div>

                            <!-- phone -->
                            <div class="form-group" id="col_phone">
                                <label for="phone" class="col-sm-4 control-label">Phone:</label>
                                <div class="col-sm-6">
                                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="phone" minlength="10" maxlength="10" required="">
                                </div>
                                <h6 style="color: red;" id="dis_phone"></h6>
                            </div>

                            <!-- address -->
                            <div class="form-group" id="col_address">
                                <label for="address" class="col-sm-4 control-label">Address:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="address" id="address" placeholder="address"  required="">
                                </div>
                                <h6 style="color: red;" id="dis_addr"></h6>
                            </div>

                            <!-- max distance -->
                            <div class="form-group" id="col_max_distance">
                                <label for="max_distance" class="col-sm-4 control-label">Max Distance:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="max_distance" id="max_distance" placeholder="max distance"  required="">
                                </div>
                                <h6 style="color: red;" id="dis_max_dist"></h6>
                            </div>

                            <!-- provider type -->
                            <div class="form-group hidden" id="col_pd_type" >
                                <label for="provider_type" class="col-sm-4 control-label">Provider Type:</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="provider_type" id="provider_type" aria-label="Default select example"  required="">
                                        <option selected>Open this select menu</option>
                                        <option value="hospital">Hospital</option>
                                        <option value="doctor">Doctor</option>
                                        <option value="pharmacy"Pharmacy</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <h6 style="color: red;" id="dis_pd_type"></h6>
                            </div>

                            <!-- captcha -->
                            <div class="form-group">
                                <label for="code" class="col-sm-4 control-label"> Verification Code :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="code" id="code" placeholder="verification code" required="" maxlength="4" size="100">
                                </div>
                            </div>
                            <!-- captcha switcher -->
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                                    <span>Click to Switch</span>
                                </div>
                            </div>
                            <!-- check all inputs -->
                            <input type="hidden" name="type" value="all">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>
                                <input type="reset" class="btn btn-warning" value ="reset" />
                            <button type="submit" class="btn btn-primary" id="upd">update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- profile -->

        <!-- page footer -->
        <?php require_once 'public/layouts/footer.php'; ?>

        <script src="public/js/update_profile.js"></script>
        <script src="public/js/appointment_time.js"></script>
        <script src="plugin/bootstable.js"></script>
        <script src="public/js/editable.js"></script>
    </body>
</html>