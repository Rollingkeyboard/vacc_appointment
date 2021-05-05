$(document).ready(function () {
    $("input#submit").click(function(e){
        $.ajax({
            type: "POST",
            url: "process.php", //
            data: {
                'username':$('input[name=username]').val(),
                'email':$('input[name=email]').val()
            },
            success: function(msg){
                alert("ok");
                $('#add-post').modal('hide');
            },
            error: function(){
                alert("Something went wrong!");
            }
        });
    });
});