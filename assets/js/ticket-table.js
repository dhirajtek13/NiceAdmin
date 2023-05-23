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
      ajax: "db/ticket_list.php",
      columnDefs: [
        {
          orderable: false,
          targets: 13,
        },
        {
          orderable: false,
          targets: 0,
        },
      ],
      scrollX:        true,
        // scrollY:        "300px",
        // scrollCollapse: true,
        // paging:         false,
        // fixedColumns:   {
        //     left: 1
        // },
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

        //sidebar operations
        $("#user-nav").addClass("show");
        $("#user-nav").parent().find('a').removeClass("collapsed");
  });
  
  $('#c_status').on('change', function() {
    var updatedValue = $('#c_status').find("option:selected").text();
    $('#updatedStatus').val(updatedValue);
    if(updatedValue != $('#previousStatus').val()){
      $("#remark").removeClass('d-none');
    } else {
      $("#remark").addClass('d-none');
    }
  });


  //Modal CRUD operations 
  function addData() {
    $(".frm-status").html("");
    $("#userModalLabel").html("Add New Ticket");
  
    $("#ticket_id").val("");
    $("#type_id").val(1);
    $("#c_status").val(5);
    $("#assignee_id").val(1);

    const dt = new Date();
    dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
    var current_datetime = dt.toISOString().slice(0, 16);
    $("#assigned_date").val(current_datetime);
    $("#plan_start_date").val("");
    $("#plan_end_date").val("");
    $("#actual_start_date").val("");
    $("#actual_end_date").val("");
  
    $("#planned_hrs").val("");
    $("#actual_hrs").val("");
  
    $('#editID').val(0);
    $("#userDataModal").modal("show");
  }
  
  function editData(user_data) {
      // console.log(user_data);
      $(".frm-status").html("");
      $("#userModalLabel").html("Edit Ticket #" + user_data.ticket_id);
  
      $("#ticket_id").val(user_data.ticket_id);
  
      $("#type_id option").filter(function() {return this.text == user_data.ticket_type ;}).attr('selected', true);
      $("#c_status option").filter(function() {return this.text == user_data.c_type_name ;}).attr('selected', true);
      $("#assignee_id option").filter(function() {return this.text == user_data.assignee ;}).attr('selected', true);  
    
      $("#assigned_date").val(user_data.assigned_date);
      $("#plan_start_date").val(user_data.plan_start_date);
      $("#plan_end_date").val(user_data.plan_end_date);
      $("#planned_hrs").val(user_data.planned_hrs);
      
      $('#editID').val(user_data.id);
      
      $("#actual_start_date").val(user_data.actual_start_date);
      $("#actual_end_date").val(user_data.actual_end_date);
    
      $("#actual_hrs").val(user_data.actual_hrs);
  
      $('#previousStatus').val(user_data.c_type_name);
      $('#updatedStatus').val(user_data.c_type_name);

      $("#userDataModal").modal("show");
  }
  
  function submitUserData() {
    $(".frm-status").html("");
    let input_data_arr = [
      document.getElementById("ticket_id").value,
      
      document.querySelector('select[name="type_id"]').value,
      document.querySelector('select[name="c_status"]').value,
      document.querySelector('select[name="assignee_id"]').value,
  
      document.getElementById("assigned_date").value,
      document.getElementById("plan_start_date").value,
      document.getElementById("plan_end_date").value,
      document.getElementById("planned_hrs").value,
      document.getElementById('editID').value,
      // document.getElementById("actual_start_date").value,
      // document.getElementById("actual_end_date").value,
      // document.getElementById("actual_hrs").value,

      document.getElementById("previousStatus").value,
      document.getElementById("updatedStatus").value,
      
    ];
  
    fetch("controller/ticket_eventHandler.php", {
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
            // table.draw();
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
  
  //TODO 
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
        fetch("eventHandler.php", {
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
  