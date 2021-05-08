$(document).ready(function () {
    let counter = 0;

    $("#addrow").on("click", function () {
        let newRow = $("<tr>");
        let cols = "";
        cols += '<td><select name="weekday[]">' +
                    '<option value="">Select...</option>' +
                    '<option value="Monday">Monday</option>' +
                    '<option value="Tuesday">Tuesday</option>' +
                    '<option value="Wednesday">Wednesday</option>' +
                    '<option value="Thursday">Thursday</option>' +
                    '<option value="Friday">Friday</option>' +
                    '<option value="Saturday">Saturday</option>' +
                    '<option value="Sunday">Sunday</option>'+
            counter +  '</select> </td>';

        cols += '<td><select name="time_block[]">' +
            '<option value="">Select...</option>' +
            '<option value="8AM">8AM</option>' +
            '<option value="12PM">12PM</option>' +
            '<option value="4PM">4PM</option>' +
            counter +  '</select> </td>';

        cols += '<td><input type="text" class="form-control" name="provider" readonly="readonly"' + counter + '/></td>';
        cols += '<td><input type="text" class="form-control" name="provider_addr" readonly="readonly"' + counter + '/></td>';
        cols += '<td><input type="text" class="form-control" name="status" readonly="readonly"' + counter + '/></td>';

        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " value="Delete"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
    });
});



function calculateRow(row) {
    let price = +row.find('input[name^="price"]').val();

}

function calculateGrandTotal() {
    let grandTotal = 0;
    $("table.order-list").find('input[name^="price"]').each(function () {
        grandTotal += +$(this).val();
    });
    $("#grandtotal").text(grandTotal.toFixed(2));
}