$(document).ready(function () {
    let user_type;
    let u_id;
    let html_btn = '<td name="buttons">'+
        '<div className="btn-group pull-right">'+
        '<button id="bEdit" type="button" className="btn btn-sm btn-default" '+
        'onClick="butRowEdit(this);" style="display: block;">'+
        '<span className="glyphicon glyphicon-pencil"> ✎ </span></button>'+
        '<button id="bElim" type="button" className="btn btn-sm btn-default" '+
        'onClick="butRowDelete(this);" style="display: block;"><span'+
        'className="glyphicon glyphicon-trash"> X </span></button>'+
        '<button id="bAcep" type="button" className="btn btn-sm btn-default" '+
        'style="display: none;" onClick="butRowAcep(this);"><span'+
        'className="glyphicon glyphicon-ok">  ✓ </span></button>'+
        '<button id="bCanc" type="button" className="btn btn-sm btn-default" '+
        'style="display: none;" onClick="butRowCancel(this);"><span'+
        'className="glyphicon glyphicon-remove"> → </span></button>'+
        '</div>'+
        '</td>' +
        '</tr>';

    let cols = "";
    cols += '<td><select class="form-control" aria-label="Default select example" name="weekday" id="weekday">' +
        '<option value="0">Select...</option>' +
        '<option value="1">Monday</option>' +
        '<option value="2">Tuesday</option>' +
        '<option value="3">Wednesday</option>' +
        '<option value="4">Thursday</option>' +
        '<option value="5">Friday</option>' +
        '<option value="6">Saturday</option>' +
        '<option value="7">Sunday</option>'+
        '</select> </td>';

    cols += '<td><select class="form-control" aria-label="Default select example" name="time_block" id="time_block">' +
        '<option value="0">Select...</option>' +
        '<option value="1">8AM</option>' +
        '<option value="2">12PM</option>' +
        '<option value="3">4PM</option>' +
        '</select> </td>';

    cols += '<td><select class="form-control" aria-label="Default select example" name="status" id="status">' +
        '<option value="0">Select...</option>' +
        '<option value="pending">pending</option>' +
        '<option value="accepted">accepted</option>' +
        '<option value="declined">declined</option>' +
        '<option value="cancelled">cancelled</option>' +
        '<option value="vaccinated">vaccinated</option>' +
        '<option value="noshow">noshow</option>' +
        '</select> </td>';

    $.ajax({
        type: 'POST',
        async: 'false',
        url: "admin/AppointmentTime.php",
        dataType: "json",
        data: {user_id: 'get_user_id', user_type: 'get_user_type'},
        success: function (response) {
            if (response.status) {
                u_id = response.role_sql_result.user_id;
                user_type = response.role_sql_result.role_id;
                let result_data = response.time_slot_sql_result;
                if (user_type === '1'){
                    $('#user_type_header').text("Patient preferred time slot are below.");
                    $('#wellcome_header').text("Hello, "+ u_id +" Welcome To Vaccination Appointment Page ");
                    $('#assign_to_table_head').append(
                        '<tr><th>Id</th><th>User Id</th><th>Weekday</th><th>Time Block</th><th>Status</th></tr>'
                    );
                    $.each(result_data, function (key, value) {
                        // console.log("Key: " + key + ", Value: " + value);
                        const res_map = new Map(Object.entries(value));
                        // console.log(res_map);

                        // $('#main_table').each(function (index, row) {
                        $('#main_table').append(
                            '<tr>' +
                                // <div className="dropdown">
                                //     <button className="btn btn-outline-secondary dropdown-toggle" type="button"
                                //             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                //         Active
                                //     </button>
                                //     <div className="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                //         <a className="dropdown-item" href="#">Active</a>
                                //         <a className="dropdown-item" href="#">Inactive</a>
                                //     </div>
                                // </div>
                                '<td>'+ res_map.get("ppt_id") + '</selec></td><td>' + res_map.get("patient_id")+ '</td>' +
                                '<td>' + res_map.get("w_id") + '</td><td>' + res_map.get("t_id") + '</td>' +
                                '<td>' + res_map.get("status") + '</td>' +
                                html_btn
                            + '</tr>'
                        )
                    });

                }else if(user_type === '2'){
                    $('#user_type_header').text("Provider available time slot are below.");
                    $('#wellcome_header').text("Hello, "+ u_id +" Welcome To Vaccination Appointment Page ");
                    $('#assign_to_table_head').append(
                        '<tr><th>Id</th><th>User Id</th><th>Weekday</th><th>Time Block</th><th>Status</th></tr>'
                    );
                    $.each(result_data, function (key, value) {
                        // console.log("Key: " + key + ", Value: " + value);
                        const res_map = new Map(Object.entries(value));

                        // $('#main_table').each(function (index, row) {
                        $('#main_table').append(
                            '<tr>' +
                                '<td>' + res_map.get("pat_id") + '</td><td>' + res_map.get("provider_id")+ '</td>' +
                                '<td>' + res_map.get("w_id") + '</td><td>' + res_map.get("t_id") + '</td>' +
                                '<td>' + res_map.get("status") + '</td>' +
                                html_btn
                            + '</tr>'
                        )
                    });
                }else if(user_type === '3'){
                    $('#user_type_header').text("Administrator assigned appointment time slot are below.");
                    $('#wellcome_header').text("Hello, Administrator! Welcome To Vaccination Appointment Page ");
                    $('#assign_to_table_head').append(
                        '<tr><th>Id</th><th>Provider Id</th><th>Weekday</th><th>Time Block</th><th>Assign to patient</th></tr>'
                    );
                    $.each(result_data, function (key, value) {
                        // console.log("Key: " + key + ", Value: " + value);
                        const res_map = new Map(Object.entries(value));

                        // $('#main_table').each(function (index, row) {
                        $('#main_table').append(
                            '<tr>' +
                            '<td>' + res_map.get("pat_id") + '</td><td>' + res_map.get("provider_id")+ '</td>' +
                            '<td>' + res_map.get("w_id") + '</td><td>' + res_map.get("t_id") + '</td>' +
                            '<td>' + res_map.get("status") + '</td>' +
                            html_btn
                            + '</tr>'
                        )
                    });
                }

            }
        }
    });
});