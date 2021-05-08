$( document ).ready(function() {
	let uid = null;
	let insert_new_row = false;
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
		url : "user_session_id_ajax.php",
		dataType: "json",
		data: {action:'add_new_time'},
		success: function (response) {
			if(response.status) {
				uid = response.data
			}
		}
	});
  $('#editableTable').SetEditable({
	  columnsEd: "2,3,4",
	  onEdit: function(columnsEd) {
		  if (insert_new_row === false){
			  let record_id = columnsEd[0].childNodes[1].innerHTML;
			  let user_id = columnsEd[0].childNodes[3].innerHTML;
			  let user_weekday = columnsEd[0].childNodes[5].innerHTML;
			  let user_time_block = columnsEd[0].childNodes[7].innerHTML;
			  let user_status = columnsEd[0].childNodes[9].innerHTML;
			  $.ajax({
				  type: 'POST',
				  url : "update_table_action.php",
				  dataType: "json",
				  data: {id:record_id, u_id:user_id, weekday:user_weekday
					  , time_block:user_time_block, status:user_status, action:'edit'},
				  success: function (response) {
					  if(response.status) {
						  // show update message
						  alert("Edit success")
					  }
				  }
			  });
		  }else{
			  let record_id = columnsEd[0].childNodes[0].innerHTML;
			  let user_id = columnsEd[0].childNodes[1].innerHTML;
			  let user_weekday = columnsEd[0].childNodes[2].innerHTML;
			  let user_time_block = columnsEd[0].childNodes[3].innerHTML;
			  let user_status = columnsEd[0].childNodes[4].innerHTML;
			  $.ajax({
				  type: 'POST',
				  url : "update_table_action.php",
				  dataType: "json",
				  data: {id:record_id, u_id:user_id, weekday:user_weekday
					  , time_block:user_time_block, status:user_status, action:'add'},
				  success: function (response) {
					  if(response.status) {
						  // show update message
						  alert("Add success")
					  }
				  }
			  });
		  }
	  },
	  onBeforeDelete: function(columnsEd) {
	  let record_id = columnsEd[0].childNodes[1].innerHTML;
	  $.ajax({
			type: 'POST',
			url : "update_table_action.php",
			dataType: "json",
			data: {id:record_id, action:'delete'},
			success: function (response) {
				if(response.status) {
					// show delete message
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
			'<td>'+ 'new appointment' + '</selec></td><td>' + uid+ '</td>' +
			cols +
			html_btn
			+ '</tr>'
		)
		// rowAddNewAndEdit('editableTable', ['new appointment', uid, 1, 1,'pending']);
	});
});