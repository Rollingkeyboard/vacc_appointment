$(document).ready(function() {
    let ssn_flag = true;
    let email_flag = true;
    let password_flag = true;
    let user_type;
    $.ajax({
        type: 'POST',
        async: 'false',
        url : "admin/Profile.php",
        dataType: "json",
        data: {type:'display'},
        success: function (response) {
            if(response.status) {
                user_type = response.role_sql.role_id;
                if (user_type === '1'){
                    $('#add_new_row').removeClass("hidden")
                }
                else if (user_type === '2'){
                    $('#add_new_row').removeClass("hidden")
                }
                else if (user_type === '3'){
                    // console.log(response.message);
                    $('#add_new_row').addClass("hidden")
                }
            }
        }
    });

    $('#pop_profile').click(function () {
        $.ajax({
            type: 'POST',
            async: 'false',
            url : "admin/Profile.php",
            dataType: "json",
            data: {type:'display'},
            success: function (response) {
                if(response.status) {
                    user_type = response.role_sql.role_id;
                    if (user_type === '1'){
                        // patient append goes here(default)
                        $('#col_pd_type').addClass("hidden");
                        $('#pd_type').removeAttr("required");
                        $('#col_ssn').removeClass("hidden")
                        // $('#ssn').attr("required", "required");
                        $('#col_dob').removeClass("hidden");
                        // $('#_dob').attr("required", "required");
                        $('#col_gender').removeClass("hidden");
                        $('#col_max_distance').removeClass("hidden");
                        // $('#max_distance').attr("required", "required");
                        $('#add_new_row').removeClass("hidden");
                        $('#col_phone').removeClass("hidden");
                        $('#col_address').removeClass("hidden");
                        /*
                        * populate patient profile
                        */
                        // $('#user_id').val(response.sql_result.patient_id);
                        $('#email').val(response.sql_result.patient_email);
                        $('#username').val(response.sql_result.patient_name);
                        $('#ssn').val(response.sql_result.ssn);
                        $('#dob').val(response.sql_result.birth);
                        $('#address').val(response.sql_result.patient_address);
                        $('#phone').val(response.sql_result.patient_phone);
                        $('#max_distance').val(response.sql_result.max_distance);
                        $('#password').val(response.sql_result.user_password);
                        $('#confirm').val(response.sql_result.user_password);
                    }
                    else if (user_type === '2'){
                        $('#col_ssn').addClass("hidden");
                        $('#ssn').removeAttr("required");
                        $('#col_dob').addClass("hidden");
                        $('#dob').removeAttr("required");
                        $('#col_gender').addClass("hidden");
                        $('#col_max_distance').addClass("hidden");
                        $('#max_distance').removeAttr("required");
                        $('#col_pd_type').removeClass("hidden");
                        // $('#provider_type').attr("required", "required");
                        $('#add_new_row').removeClass("hidden");
                        $('#col_phone').removeClass("hidden");
                        $('#col_address').removeClass("hidden");
                        /*
                        * populate provider profile
                        */
                        // $('#user_id').val(response.sql_result.patient_id);
                        $('#email').val(response.sql_result.provider_email);
                        $('#username').val(response.sql_result.provider_name);
                        $('#address').val(response.sql_result.provider_address);
                        $('#phone').val(response.sql_result.provider_phone);
                        $('#password').val(response.sql_result.user_password);
                        $('#confirm').val(response.sql_result.user_password);
                        $('#provider_type').val(response.sql_result.provider_type);


                    }
                    else if (user_type === '3'){
                    // console.log(response.message);
                        $('#col_pd_type').addClass("hidden");
                        $('#col_dob').addClass("hidden");
                        $('#col_gender').addClass("hidden");
                        $('#col_max_distance').addClass("hidden");
                        $('#col_ssn').addClass("hidden");
                        $('#add_new_row').addClass("hidden");
                        $('#col_phone').addClass("hidden");
                        $('#col_address').addClass("hidden");

                        $('#pd_type').removeAttr("required");
                        $('#ssn').removeAttr("required");
                        $('#dob').removeAttr("required");
                        $('#max_distance').removeAttr("required");
                        $('#phone').removeAttr("required");
                        $('#address').removeAttr("required");

                        $('#email').val(response.sql_result.user_name);
                        $('#username').val('Administrator');
                        $('#password').val(response.sql_result.user_password);
                        $('#confirm').val(response.sql_result.user_password);
                    }
                }
            }
        });
    });


    function isNumeric(str) {
        if (typeof str != "string") return false;	// we only process strings!
        return !isNaN(str) && 						// use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
            !isNaN(parseFloat(str)) 				// ...and ensure strings of whitespace fail
    }


    // $('#ssn').keyup(function() {
    //     let length = $(this).val().length;
    //     if (isNumeric($(this).val()) && length === 10) {
    //         $.post('admin/Profile.php', {ssn: $(this).val(),type:'ssn'}, function(data, textStatus, xhr) {
    //             // alert("Data: " + data + "\nStatus: " + textStatus);
    //             if (textStatus === 'success') {
    //                 if (data === '1') {
    //                     $('#dis_ssn').text('SSN is already registered');
    //                     ssn_flag = false;
    //                 }else{
    //                     $('#dis_ssn').text('');
    //                     ssn_flag = true;
    //                 }
    //             }
    //         })
    //         // .done(function() { alert('Request done!'); })
    //         // .fail(function(xhr, settings, ex) { alert('failed, ' + ex); });
    //     }
    //     else{
    //         $('#dis_ssn').text('Please enter valid SSN.');
    //     }
    // });

    // $('#email').blur(function() {
    //     if ($(this).val() !== '') {
    //         let reg = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
    //         if (reg.test($(this).val())) {
    //             $.post('admin/Profile.php', {email: $(this).val(),type: 'email'}, function(data, textStatus, xhr) {
    //                 // alert("Data: " + data + "\nStatus: " + textStatus);
    //                 if (textStatus === 'success') {
    //                     console.log(data);
    //                     if (data === '1') {
    //                         $('#dis_em').text('E-mail is already registered');
    //                         email_flag = false;
    //                     }else{
    //                         $('#dis_em').text('');
    //                         email_flag = true;
    //                     }
    //                 }
    //             });
    //         }
    //         else{
    //             $('#dis_em').text('E-mail format is incorrect');
    //             email_flag = false;
    //         }
    //     }else{
    //         $('#dis_em').text('');
    //     }
    // });

    $('#password').blur(function(){
        if ($(this).val() === '') {
            $('#dis_pwd').text('Password cannot leave empty');
        }
        else if($(this).val().length < 6){
            $('#dis_pwd').text('Password must be at least 6 characters/digits');
        }
        else{
            $('#dis_pwd').text('');
        }
    });

    $('#confirm').blur(function() {
        let val = $('#password').val();
        if (val !== '') {
            if ($(this).val() === '') {
                $('#dis_con_pwd').text('Please enter your password');
                password_flag = false;
            }
            else if($(this).val() !== val){
                $('#dis_con_pwd').text('Please make sure twice password are same');
                password_flag = false;
            }
            else{
                $('#dis_con_pwd').text('');
                password_flag = true;
            }
        }
        else{
            $('#dis_con_pwd').text('');
            password_flag = false;
        }
    });

    $('#upd').click(function() {
        // if (!(ssn_flag && email_flag && password_flag)) {
        if (!(password_flag)) {
            alert('Please check password.');
            return false;
        }
    });
});
