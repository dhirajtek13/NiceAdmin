// Initialize DataTables API object and configure table

  
$(document).ready(function () {

  $('#dataList tfoot th').each(function () {
      var title = $(this).text();
      if(title) {
        $(this).html('<input type="text" placeholder="' + title + '"   size="6" /  class="form-control">');
      }
      if(title == 'S.N') {
        $(this).html("");
      }
  });

  var table = $("#dataList").DataTable({
    processing: true,
    serverSide: true,
    bLengthChange: false,
    // bFilter:false,
    ajax: "db/ticket_status_list.php",
    scrollX: true,
    // columnDefs: [
    //   {
    //     orderable: false,
    //     targets: 10,
    //   },
    //   {
    //     orderable: false,
    //     targets: 0,
    //   },
    //   {
    //       target: 7,
    //       visible: false,
    //   },
    //   {
    //       target: 8,
    //       visible: false,
    //   },
    //   {
    //       target: 9,
    //       visible: false,
    //   },

    // ],
    fnRowCallback : function(nRow, aData, iDisplayIndex){
      // console.log(nRow, aData, iDisplayIndex);
      $("td:first", nRow).html(iDisplayIndex +1);
      return nRow;
    },
    orderCellsTop: true,
    initComplete: function () {
        // Apply the search
        this.api()
            .columns()
            .every(function () {
                var that = this;

                $('input', this.footer()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
    },
  });
  
  setTimeout(() => {
      $("#dataList_wrapper").find(".sorting_disabled").removeClass("sorting_asc");
  }, 100);
});


//  //Modal CRUD operations 
 function addData() {
  $(".frm-status").html("");
  $("#userModalLabel").html("Add New Type");

  // $("#ticket_id").val("");
  // const dt = new Date();
  // dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
  // var current_datetime = dt.toISOString().slice(0, 16);

  // $("#dates").val(current_datetime);
  // $("#hrs").val("");
  // $("#c_status").val(1);
  $("#type_name").val("");  
  $("#userDataModal").modal("show");
}

function editData(user_data) {//TODO
    $(".frm-status").html("");

    $("#userModalLabel").html("Edit Type ");

    // console.log(user_data);
    // $("#username").val(user_data.username);
    // $("#email").val(user_data.email);
    // $("#employee_id").val(user_data.employee_id);
    // $("#designation").val(user_data.designation);
    // $("#user_type").val(user_data.user_type);
    // $("#password").val(user_data.password);
    $("#type_name").val(user_data.type_name);
    // $("#lname").val(user_data.lname);
    // $("#user_type option").filter(function() {return this.text == user_data.user_type_name ;}).attr('selected', true);

    $('#editID').val(user_data.id);
    $("#userDataModal").modal("show");
}


function submitUserData() {
  $(".frm-status").html("");
  let input_data_arr = [
    document.getElementById("type_name").value,  
    document.getElementById('editID').value,
  ];

  fetch("controller/ticketstatus_eventHandler.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "addEdit",
      user_data: input_data_arr,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status == 1) {
        Swal.fire({
          title: data.msg,
          icon: "success",
        }).then((result) => {
          // Redraw the table
          $("#dataList").DataTable().draw();

          $("#userDataModal").modal("hide");
          $("#userDataFrm")[0].reset();
        });
      } else {
        $(".frm-status").html(
          '<div class="alert alert-danger" role="alert">' +
            data.error +
            "</div>"
        );
      }
    })
    .catch(console.error);
}



function deleteData(user_id) {
  Swal.fire({
    title: "Are you sure to Delete?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Delete event
      fetch("controller/ticketstatus_eventHandler.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          request_type: "deleteUser",
          user_id: user_id,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status == 1) {
            Swal.fire({
              title: data.msg,
              icon: "success",
            }).then((result) => {
              $("#dataList").DataTable().draw();
            });
          } else {
            Swal.fire(data.error, "", "error");
          }
        })
        .catch(console.error);
    } else {
      Swal.close();
    }
  });
}


