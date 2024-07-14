$(document).ready(function() {


  alertify.set('notifier', 'position', 'top-right');

    $(document).on('click', '.increment', function() {

        var quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();

        var currentValue = parseInt(quantityInput.val());

        if (!isNaN(currentValue)) {
          var qtyVal = currentValue + 1;
          quantityInput.val(qtyVal);
          quantityIncDec(productId, qtyVal);
        }

    });

    $(document).on('click', '.chargeIncrement', function() {
     
     var quantityInput = $(this).closest('.qtyChargeBox').find('.qty');
     var chargeId = $(this).closest('.qtyChargeBox').find('.chargeId').val();

     var currentValue = parseInt(quantityInput.val());

     if (!isNaN(currentValue)) {
      var qtyVal = currentValue + 1;
      quantityInput.val(qtyVal);
      quantityChargeIncDec(chargeId, qtyVal);  // Add this line

     }
    });

    $(document).on('click', '.expenseIncrement', function() {
     
     var quantityInput = $(this).closest('.qtyExpenseBox').find('.qty');
     var expenseId = $(this).closest('.qtyExpenseBox').find('.expenseId').val();

     var currentValue = parseInt(quantityInput.val());

     if (!isNaN(currentValue)) {
      var qtyVal = currentValue + 1;
      quantityInput.val(qtyVal);
      quantityExpenseIncDec(expenseId, qtyVal);  // Add this line

     }
    });

    $(document).on('click', '.decrement', function() {

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();

        var currentValue = parseInt($quantityInput.val());

        if (!isNaN(currentValue) && currentValue > 1) {
          var qtyVal = currentValue - 1;
          $quantityInput.val(qtyVal);
          quantityIncDec(productId, qtyVal);
        }

    });

    $(document).on('click', '.chargeDecrement', function() {
     
     var quantityInput = $(this).closest('.qtyChargeBox').find('.qty');
     var chargeId = $(this).closest('.qtyChargeBox').find('.chargeId').val();

     var currentValue = parseInt(quantityInput.val());

     if (!isNaN(currentValue) && currentValue > 1) {
      var qtyVal = currentValue - 1;
      quantityInput.val(qtyVal);
      quantityChargeIncDec(chargeId, qtyVal);  // Add this line

     }
    }); 

    $(document).on('click', '.expenseDecrement', function() {
     
     var quantityInput = $(this).closest('.qtyExpenseBox').find('.qty');
     var expenseId = $(this).closest('.qtyExpenseBox').find('.expenseId').val();

     var currentValue = parseInt(quantityInput.val());

     if (!isNaN(currentValue) && currentValue > 1) {
      var qtyVal = currentValue - 1;
      quantityInput.val(qtyVal);
      quantityExpenseIncDec(expenseId, qtyVal);  // Add this line

     }
    });

    $(document).on('click', '.proceedToPlace', function() {

    var plate = $('#plate').val();
    var payment_mode = $('#payment_mode').val();
    var order_status = $('#order_status').val();

    if (plate == '') {
      swal("Enter Plate Number", "Enter Plate Number", "warning");
      return false;
    }

    if (payment_mode == '') {
      swal("Select Payment Mode", "Select your payment mode", "warning");
      return false;
    }

    if (order_status == '') {
      swal("Select Order Status", "Select Order Status", "warning");
      return false;
    }

   

    $.ajax({
      url: 'orders-code.php',
      type: 'POST',
      data: {
        'proceedToPlaceBtn': true,
        'plate': plate,
        'payment_mode': payment_mode,
        'order_status': order_status,
        
      },
      success: function (response) {
        console.log("Server response:", response); // Log the response
        

        try {
          var res = JSON.parse(response); // Assuming response is valid JSON
        } catch (error) {
          console.error("Error parsing JSON response:", error);
          swal("Error", "Invalid server response", "error");
          return; // Exit the success function if parsing fails
        }

        if (res.status == 200) {
          window.location.href = "order-summary.php";
        } else if (res.status == 404) {
          swal(res.message, res.message, res.status_type, {
            buttons: {
              catch: {
                text: "Add Customer",
                value: "catch"
              },
              cancel: "Cancel"
            }
          })
          .then((value) => {
            switch(value){
              case "catch":
                $('#c_plate').val(plate);
                $('#addCustomerModal').modal('show');
                break;
              default:
            }
          });
        } else {
          swal(res.message, res.message, res.status_type);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
        swal("Error", "There was an issue with the request", "error");
      }
    });
});


  $(document).on('click', '.saveCustomer', function() {
    var c_id = $('#c_id').val();
    var c_name = $('#c_name').val();
    var c_plate = $('#c_plate').val();
    var c_phone = $('#c_phone').val();
    var c_email = $('#c_email').val();
    var c_address1 = $('#c_address1').val();
    var c_address2 = $('#c_address2').val();
    var c_city = $('#c_city').val();
    var c_poscode = $('#c_poscode').val();
    var c_state = $('#c_state').val();

    if (c_id !== '' && c_name !== '' && c_plate !== '' && c_address1 !== '' && c_city !== '' && c_poscode !== '' && c_state !== '') {
        // Check if c_name is not empty
        if (c_name !== '') {
            var data = {
                'saveCustomerBtn': true,
                'cid': c_id,
                'name': c_name,
                'plate': c_plate,
                'phone': c_phone,
                'email': c_email,
                'address1': c_address1,
                'address2': c_address2,
                'city': c_city,
                'poscode': c_poscode,
                'state': c_state,
            };

            $.ajax({
                url: 'orders-code.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    var res = JSON.parse(response);

                    if (res.status == 200) {
                        swal(res.message, res.message, res.status_type);
                        $('#addCustomerModal').modal('hide');
                    } else {
                        swal(res.message, res.message, res.status_type);
                    }
                }
            });

        } else {
            swal("Please enter customer name.", "", "warning");
        }
    } else {
        swal("Please fill in required fields.", "", "warning");
    }
});


    $(document).on('click', '#saveOrder', function() {

       $.ajax({
         type: 'POST',
         url: 'orders-code.php',
         data: {
          'saveOrder': true
         },
         success: function(response){
          
          var res = JSON.parse(response);

          if (res.status == 200) {
            swal(res.message, res.message, res.status_type);
            $('#orderPlaceSuccessMessage').text(res.message);
            $('#orderSuccessModal').modal('show');

          }else{
            swal(res.message, res.message, res.status_type);
          }
         }
       
       
            });
    });  



    function quantityIncDec(prodId, qty) {
        $.ajax({
          url: 'orders-code.php',
          type: 'POST',
          data: {
            'productIncDec': true,
            'product_id': prodId,
            'quantity': qty,
          },
          success: function(response) {
            try {
              var res = JSON.parse(response); // Assuming response is valid JSON

              if (res.status == 200) {
                $('#productArea').load(' #productContent');
                alertify.success(res.message);
              } else {
                $('#productArea').load(' #productContent');
                alertify.error(res.message);
              }
            } catch (error) {
              console.error("Error parsing JSON response:", error);
              // Handle the error appropriately (e.g., display an error message)
            }
          }
        });
    }

    function quantityChargeIncDec(chargeId, qty) {
        $.ajax({
        url: 'orders-code.php',
        type: 'POST',
        data: {
            'chargeIncDec': true,
            'chargeId': chargeId,
            'charge_quantity': qty,
        },
        success: function(response) {
            try {
                var res = JSON.parse(response); // Assuming response is valid JSON

                if (res.status == 200) {
                    $('#chargeArea').load(' #chargeContent');
                    alertify.success(res.message);
                } else {
                    $('#chargeArea').load(' #chargeContent');
                    alertify.error(res.message);
                }
            } catch (error) {
                console.error("Error parsing JSON response:", error);
                // Handle the error appropriately (e.g., display an error message)
            }
        }
    });
    }


    
    function quantityExpenseIncDec(expenseId, qty){
      $.ajax({
        url: 'expense-code.php',
        type: 'POST',
        data: {
          'expenseIncDec': true,
          'expenseId': expenseId,
          'expense_quantity': qty,
        },
        success: function (response){
           try {
                var res = JSON.parse(response); // Assuming response is valid JSON

                if (res.status == 200) {
                    $('#expenseArea').load(' #expenseContent');
                    alertify.success(res.message);
                } else {
                    $('#expenseArea').load(' #expenseContent');
                    alertify.error(res.message);
                }
            } catch (error) {
                console.error("Error parsing JSON response:", error);
                // Handle the error appropriately (e.g., display an error message)
            }
        }
      });
      
    }


    function printMyBillingArea(){
      var divContents = document.getElementById("myBillingArea").innerHTML;
      var a  = window.open('','');
      a.document.write('<html><title>Pen-B System</title>');
      a.document.write('<body style="font-family: fangsong;">');
      a.document.write(divContents);
      a.document.write('</body></html>');
      a.document.close();
      a.print();

    }


  


});
