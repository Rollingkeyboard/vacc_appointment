$(document).ready(function() {
	let ssn_flag = true;
	let email_flag = true;
	let password_flag = true;

	function isNumeric(str) {
		if (typeof str != "string") return false;	// we only process strings!
		return !isNaN(str) && 						// use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
			!isNaN(parseFloat(str)) 				// ...and ensure strings of whitespace fail
	}

	$('#ssn').keyup(function() {
		let length = $(this).val().length;
		if (isNumeric($(this).val()) && length === 10) {
			$.post('admin/Register.php', {ssn: $(this).val(),type:'ssn'}, function(data, textStatus, xhr) {
				// alert("Data: " + data + "\nStatus: " + textStatus);
				if (textStatus === 'success') {
					if (data === '1') {
						$('#dis_ssn').text('SSN is already registered');
						ssn_flag = false;
					}else{
						$('#dis_ssn').text('');
						ssn_flag = true;
					}
				}
			})
				// .done(function() { alert('Request done!'); })
				// .fail(function(xhr, settings, ex) { alert('failed, ' + ex); });
		}
		else{
			$('#dis_ssn').text('Please enter valid SSN.');
		}
	});

	$('#email').blur(function() {
		if ($(this).val() !== '') {
			let reg = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
			if (reg.test($(this).val())) {
				$.post('admin/Register.php', {email: $(this).val(),type: 'email'}, function(data, textStatus, xhr) {
					// alert("Data: " + data + "\nStatus: " + textStatus);
					if (textStatus === 'success') {
						console.log(data);
						if (data === '1') {
							$('#dis_em').text('E-mail is already registered');
							email_flag = false;
						}else{
							$('#dis_em').text('');
							email_flag = true;
						}
					}
				});
			}
			else{
				$('#dis_em').text('E-mail format is incorrect');
				email_flag = false;
			}
		}else{
			$('#dis_em').text('');
		}
	});

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

	$('#reg').click(function() {
		if (!(ssn_flag && email_flag && password_flag)) {
			alert('Please check page info!');
			return false;
		}
	});
});
