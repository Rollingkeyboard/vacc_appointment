$(document).ready(function () {
    let user_type;
    let html_btn = '<td name="buttons">'+
        '<div className="btn-group pull-right">'+
        '<button id="bEdit" type="button" className="btn btn-sm btn-default" '+
        'onClick="butRowEdit(this);" style="display: block;">'+
        '<span className="glyphicon glyphicon-pencil"> ✎ </span></button>'+
        '<button id="bElim" type="button" className="btn btn-sm btn-default" '+
        'onClick="butRowDelete(this);" style="display: block;"><span '+
        'className="glyphicon glyphicon-trash"> X </span></button>'+
        '<button id="bAcep" type="button" className="btn btn-sm btn-default" '+
        'style="display: none;" onClick="butRowAcep(this);"><span '+
        'className="glyphicon glyphicon-ok">  ✓ </span></button>'+
        '<button id="bCanc" type="button" className="btn btn-sm btn-default" '+
        'style="display: none;" onClick="butRowCancel(this);"><span '+
        'className="glyphicon glyphicon-remove"> → </span></button>'+
        '</div>'+
        '</td>' +
        '</tr>';



    let col_w_val = "0";
    let col_w = '<td><select class="form-control" aria-label="Default select example" name="weekday" id="weekday">' +
        '<option value="0">Select...</option>' +
        '<option value="1">Monday</option>' +
        '<option value="2">Tuesday</option>' +
        '<option value="3">Wednesday</option>' +
        '<option value="4">Thursday</option>' +
        '<option value="5">Friday</option>' +
        '<option value="6">Saturday</option>' +
        '<option value="7">Sunday</option>'+
        '</select> </td>';

    let col_t_val = "0";
    let col_t = '<td><select class="form-control" aria-label="Default select example" name="time_block" id="time_block">' +
        '<option value="0">Select...</option>' +
        '<option value="1">8AM</option>' +
        '<option value="2">12PM</option>' +
        '<option value="3">4PM</option>' +
        '</select> </td>';

    let col_st_val = "0";
    let col_st = '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
        '<option value="0">Select...</option>' +
        '<option value="pending">pending</option>' +
        '<option value="accepted">accepted</option>' +
        '<option value="declined">declined</option>' +
        '<option value="cancelled">cancelled</option>' +
        '<option value="vaccinated">vaccinated</option>' +
        '<option value="noshow">noshow</option>' +
        '</select> </td>';
    let col_acc_dec = '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
        '<option value="0">Select...</option>' +
        '<option value="accepted">accepted</option>' +
        '<option value="declined">declined</option>' +
        '</select> </td>';
    let patient_list ='';
    let col_pripority = '<td><select class="form-control" aria-label="Default select example" name="status" id="priority_lv">' +
        '<option value="0">Select...</option>' +
        '<option value="1">1</option>' +
        '<option value="2">2</option>' +
        '<option value="3">3</option>' +
        '</select> </td>';


    // cols = col_w + col_t + col_st;

    $.ajax({
        type: 'POST',
        async: 'false',
        url: "admin/AppointmentTime.php",
        dataType: "json",
        data: {user_id: 'get_user_id', user_type: 'get_user_type'},
        success: function (response) {
            if (response.status) {
                // u_id = response.role_sql_result.user_id;
                u_name = response.role_sql_result.user_name;
                user_type = response.role_sql_result.role_id;
                let result_data = response.time_slot_sql_result;
                if (user_type === '1') {
                    $('#user_type_header').text("Patient preferred time slot are below.");
                    $('#wellcome_header').text("Hello, " + u_name + " Welcome To Vaccination Appointment Page ");
                    $('#assign_to_table_head').append(
                        '<tr><th>Id</th><th>Weekday</th><th>Time Block</th><th>Status</th>' +
                        '<th>Action</th>th></tr>'
                    );
                    $.each(result_data, function (key, value) {
                        const res_map = new Map(Object.entries(value));
                        if (res_map.get("status") === "pending") {
                            col_acc_dec = '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
                                '<option value="0">Select...</option>' +
                                '<option value="accepted">accepted</option>' +
                                '<option value="declined">declined</option>' +
                                '</select> </td>';
                        } else if (res_map.get("status") === "accepted") {
                            col_acc_dec = '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
                                '<option value="0">Select...</option>' +
                                '<option value="cancelled">cancelled</option>' +
                                '</select> </td>';
                        } else {
                            col_acc_dec = '<td>-</td>';
                        }
                        $('#main_table').append(
                            '<tr id="row_' + key + '">' +
                            '<td>' + res_map.get("ppt_id") + '</td>' +
                            col_w + col_t + '<td>' + res_map.get("status") + '</td>' + col_acc_dec + html_btn
                            + '</tr>'
                        );
                        let curr_row = $("#row_" + key);
                        curr_row.find("select:eq(0)").val(res_map.get("w_id"));
                        curr_row.find("select:eq(1)").val(res_map.get("t_id"));
                        // curr_row.find("select:eq(2)").val(res_map.get("status"));
                    });

                } else if (user_type === '2') {
                    $('#user_type_header').text("Provider available time slot are below.");
                    $('#wellcome_header').text("Hello, " + u_name + " Welcome To Vaccination Appointment Page ");
                    $('#assign_to_table_head').append(
                        '<tr><th style="margin: 0;">Available Id</th><th>Weekday</th><th>Time Block</th><th>Status</th></tr>'
                    );
                    $.each(result_data, function (key, value) {
                        const res_map = new Map(Object.entries(value));
                        if (res_map.get("status") === "accepted") {
                            col_acc_dec = '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
                                '<option value="0">Select...</option>' +
                                '<option value="vaccinated">Vaccinated</option>' +
                                '<option value="noshow">No show</option>' +
                                '</select></td>';
                        } else {
                            col_acc_dec = '<td>' + res_map.get("status") + '</td>';
                        }
                        $('#main_table').append(
                            '<tr id="row_' + key + '">' +
                            '<td>' + res_map.get("pat_id") + '</td>' +
                            col_w + col_t + col_acc_dec + html_btn
                            + '</tr>'
                        );
                        let curr_row = $("#row_" + key);
                        curr_row.find("select:eq(0)").val(res_map.get("w_id"));
                        curr_row.find("select:eq(1)").val(res_map.get("t_id"));
                        // curr_row.find("select:eq(2)").val(res_map.get("status"));
                    });
                } else if (user_type === '3') {
                    let patient_result_data = response.patient_sql_result;
                    let priority_result_data = response.priority_sql_result;
                    patient_result_data.forEach(patient =>
                        patient_list += '<option value="' + patient.patient_id + '">'
                            + patient.patient_id + '_' + patient.patient_name + '</option>'
                    );
                    let col_pat_list = '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
                        '<option value="0">Select...</option>' +
                        patient_list +
                        '</select> </td>';

                    $('#user_type_header').text("Administrator assigned appointment time slot are below.");
                    $('#wellcome_header').text("Hello, Administrator! Welcome To Vaccination Appointment Page ");
                    $('#assign_to_table_head').append(
                        '<tr><th>Available Id</th><th>Provider</th><th>Weekday</th><th>Time Block</th><th>Assign to patient</th></tr>'
                    );
                    $.each(result_data, function (key, value) {
                        const res_map = new Map(Object.entries(value));
                        $('#main_table').append(
                            '<tr id="row_' + key + '">' +
                            '<td>' + res_map.get("pat_id") + '</td><td>' + res_map.get("provider_id") + '_' + res_map.get("provider_name") + '</td>' +
                            '<td>' + res_map.get("w_id") + '</td>' + '<td>' + res_map.get("t_id") + '</td>'
                            + col_pat_list + html_btn
                            + '</tr>'
                        );
                        let curr_row = $("#row_" + key);
                        // curr_row.find("select:eq(0)").val(res_map.get("w_id"));
                        // curr_row.find("select:eq(1)").val(res_map.get("t_id"));
                        // curr_row.find("select:eq(2)").val(res_map.get("status"));
                    });

                    $('#priority_assign_head').append(
                        '<tr><th>Id</th><th>Patient Name</th><th>Priority Level</th></tr>'
                    );


                    let click_btn = '<td name="buttons">'+
                        '<button type="button" class="priority_btn" onclick="priority_confirm(this)">Confirm</button>' +
                        '</td>';


                    priority_result_data.forEach(patient =>
                        $('#priority_assign_body').append(
                            '<tr>' +
                            '<td>' + patient.patient_id + '</td>' +
                            '<td>' + patient.patient_name + '</td>' +
                            col_pripority + click_btn +
                            '</tr>'
                        ));
                }

            }
        }
    });
});

function priority_confirm(elem){
    let row = elem.parentNode.parentNode;
    console.log(row);
    let patient_id = row.childNodes[0].innerHTML;
    let patient_name = row.childNodes[1].innerHTML;
    console.log(row.childNodes[2].childNodes[0].selectedIndex);
    let priority_level = row.childNodes[2].childNodes[0].selectedIndex;

    $.ajax({
        type: 'POST',
        url: "update_table_action.php",
        dataType: "json",
        data: {
            pa_id: patient_id, pa_name: patient_name, pri_lv: priority_level,
            action: 'priority'
        },
        success: function (response) {
            if (response.status) {
                // show update message
                // Add success
                alert(response.message)
            }
        }
    });
}