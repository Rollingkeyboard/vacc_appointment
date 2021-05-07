$( document ).ready(function() {
	let uid = null;
	let insert_new_row = false;

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
		 // console.log("===edit=="+(this));
		 //  console.log("===edit=="+(insert_new_row));
		  if (insert_new_row === false){
			  // console.log(columnsEd[0].childNodes[0]);
			  // console.log(columnsEd[0].childNodes[1]);
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
			  // console.log(columnsEd[0].childNodes[0]);
			  // console.log(columnsEd[0].childNodes[1]);
			  // console.log(columnsEd[0].childNodes[2]);
			  // console.log(columnsEd[0].childNodes[3]);
			  // console.log(columnsEd[0].childNodes[4]);
			  // console.log(columnsEd[0].childNodes[5]);
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

		  // console.log(user_weekday);
		  // console.log(user_time_block);
		  // console.log(columnsEd[0].childNodes[5]);

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
		rowAddNewAndEdit('editableTable', ['new appointment', uid, 1, 1,'pending']);
	});
});