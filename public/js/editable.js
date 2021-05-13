$( document ).ready(function() {
	let uid = null;
	let insert_new_row = false;
	let user_type = "";

	let html_btn = '<td name="buttons">'+
		'<div className="btn-group pull-right">'+
		'<button id="bEdit" type="button" className="btn btn-sm btn-default" '+
		'onClick="butRowEdit(this);" style="display: block;">'+
		'<span className="glyphicon glyphicon-pencil"> ✎ </span></button>'+
		'<button id="bElim" type="button" className="btn btn-sm btn-default" '+
		'onClick="butRowDelete(this);" style="display: block;"><span '+
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

	$.ajax({
		type: 'POST',
		async: 'false',
		url : "admin/AppointmentTime.php",
		dataType: "json",
		data: {user_id:'get_user_id', user_type:'get_user_type'},
		success: function (response) {
			if(response.status) {
				uid = response.role_sql_result.user_id;
				user_type = response.role_sql_result.role_id;
			}
		}
	});

	$(".priority_btn").click(function () {
		let row = $(this).parent();
		console.log(row);
		if (user_type === '3') {
			let patient_id = row.childNodes[0].innerHTML;
			let patient_name = row.childNodes[1].innerHTML;
			let priority_level = $(row).find("select:eq(0)").val();
			$.ajax({
				type: 'POST',
				url: "update_table_action.php",
				dataType: "json",
				data: {
					pa_id: patient_id, pa_name: patient_name, pri_lv: priority_level,
					user_type: user_type, action: 'priority'
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
	});

  $('#editableTable').SetEditable({
	  columnsEd: "2,3,4",
	  onEdit: function(columnsEd) {
		  // console.log(columnsEd[0]);
		  if (insert_new_row === false){
			  if (user_type === '3'){
				  let record_id = columnsEd[0].childNodes[0].innerHTML;
				  let provider_id = columnsEd[0].childNodes[1].innerHTML;
				  let user_weekday = columnsEd[0].childNodes[2].innerHTML;
				  let user_time_block = columnsEd[0].childNodes[3].innerHTML;
				  let patient_id = $(columnsEd[0]).find("select:eq(0)").val();
				  $.ajax({
					  type: 'POST',
					  url : "update_table_action.php",
					  dataType: "json",
					  data: {id:record_id, pd_id:provider_id, weekday:user_weekday
						  , time_block:user_time_block, pa_id:patient_id, user_type:user_type, action:'assign'},
					  success: function (response) {
						  if(response.status) {
							  // show update message
							  // Add success
							  alert(response.message)
						  }
					  }
				  });
			  }
			  else {
				  let record_id = columnsEd[0].childNodes[0].innerHTML;
				  let user_id = columnsEd[0].childNodes[1].innerHTML;
				  let user_weekday = $(columnsEd[0]).find("select:eq(0)").val();
				  let user_time_block = $(columnsEd[0]).find("select:eq(1)").val();
				  let user_status = $(columnsEd[0]).find("select:eq(2)").val();
				  console.log(user_status);
				  $.ajax({
					  type: 'POST',
					  url : "update_table_action.php",
					  dataType: "json",
					  data: {id:record_id, u_id:user_id, weekday:user_weekday
						  , time_block:user_time_block, status:user_status, user_type: user_type, action:'edit'},
					  success: function (response) {
						  if(response.status) {
							  // show update message
							  // Edit success
							  alert(response.message)
						  }
					  }
				  });
			  }
		  }
		  else{
				let record_id = columnsEd[0].childNodes[0].innerHTML;
				let user_id = columnsEd[0].childNodes[1].innerHTML;
				let user_weekday = $(columnsEd[0]).find("select:eq(0)").val();
				let user_time_block = $(columnsEd[0]).find("select:eq(1)").val();
				let user_status = $(columnsEd[0]).find("select:eq(2)").val();

				$.ajax({
					type: 'POST',
					url : "update_table_action.php",
					dataType: "json",
					data: {id:record_id, u_id:user_id, weekday:user_weekday
						, time_block:user_time_block, status:user_status, user_type: user_type, action:'add'},
					success: function (response) {
						if(response.status) {
							// show update message
							// Add success
							alert(response.message)
						}
					}
				});
			}
	  },
	  onBeforeDelete: function(columnsEd) {
	  let record_id = columnsEd[0].childNodes[0].innerHTML;
	  $.ajax({
			type: 'POST',
			url : "update_table_action.php",
			dataType: "json",
			data: {id:record_id, user_type: user_type, action:'delete'},
			success: function (response) {
				if(response.status) {
					// show delete message
					// delete success
					alert(response.message)
				}
			}
		});
	  },
	});
	$('#add_new_row').click(function() {
		insert_new_row = true;
		$('#main_table').append(
			'<tr>' +
			'<td>'+ 'new appointment' + '</td><td>' + uid + '</td>' +
			col_w + col_t + '<td>N/A</td>' + '<td>-</td>' +
			html_btn +
			'</tr>'
		)
	});
});