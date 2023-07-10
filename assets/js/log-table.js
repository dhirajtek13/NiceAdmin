
  $(document).ready(function () {
    $('#dataList tfoot th').each(function () {
        var title = $(this).text();
        if(title) {
          $(this).html('<input type="text" placeholder="' + title + '"   size="6"  class="form-control" />');
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
      ajax: "db/log_list.php?ticket="+$("#ticketId").val(),
      scrollX: true,
      columnDefs: [
        {
          orderable: false,
          targets: 8,
        },
        {
          orderable: false,
          targets: 0,
        },
      ],
      fnRowCallback : function(nRow, aData, iDisplayIndex){
        // console.log(nRow, aData, iDisplayIndex);
        $("td:first", nRow).html(iDisplayIndex +1);
        return nRow;
      },
      aaSorting: [[ 1, "desc" ]],//sort by 2nd column i.e date logged
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

        // table.row($('.sorting_disabled')).remove().draw(false);
        setTimeout(() => {
          $("#dataList_wrapper").find(".sorting_disabled").removeClass("sorting_asc");

          $(".onlyDevAction").hide(); //hide edit/delete log for PM
        }, 100);

        
  });
  
  
  //Modal CRUD operations 
  function addData() {
    $(".frm-status").html("");
    $("#userModalLabel").html("Add New Log");
  
    // $("#ticket_id").val("");
    const dt = new Date();
    dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
    var current_datetime = dt.toISOString().slice(0, 16);

    $("#dates").val(current_datetime);
    $("#hrs").val("");
    $("#c_status").val(1);
    $("#what_is_done").val("");
    $("#what_is_pending").val("");
    $("#what_support_required").val("");
    $('#editID').val(0);
  
    $("#userDataModal").modal("show");
  }
  
  function editData(user_data) {
      $(".frm-status").html("");
  
      $("#userModalLabel").html("Edit Log ");
  
      // $("#ticket_id").val(user_data.ticket_id);
  
      $("#c_status option").filter(function() {return this.text == user_data.c_type_name ;}).attr('selected', true);
      $("#dates").val(user_data.dates);
      $("#hrs").val(user_data.hrs);
      $("#what_is_done").val(user_data.what_is_done);
      $("#what_is_pending").val(user_data.what_is_pending);
      $("#what_support_required").val(user_data.what_support_required);
  
      $('#editID').val(user_data.id);
      $("#userDataModal").modal("show");
  }
  
  function submitUserData() {
    $(".frm-status").html("");
    let input_data_arr = [
      document.getElementById("ticket_id").value,
      document.getElementById("ticket").value,
  
      document.getElementById("dates").value,
      document.getElementById("hrs").value,
      document.querySelector('select[name="c_status"]').value,
      
      document.getElementById("what_is_done").value,
      document.getElementById("what_is_pending").value,
      document.getElementById("what_support_required").value,
  
      document.getElementById('editID').value,
    ];
  
    fetch("controller/log_eventHandler.php", {
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
          $("#dataList").DataTable().draw();
          Swal.fire({
            title: data.msg,
            icon: "success",
          }).then((result) => {
            // Redraw the table
            
  
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
        fetch("controller/log_eventHandler.php", {
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
  