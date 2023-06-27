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
      ajax: "db/project_list.php",
      scrollX: true,
      columnDefs: [
        {
          orderable: false,
          targets: 11,
        },
        {
          orderable: false,
          targets: 0,
        },
        // {
        //     target: 7,
        //     visible: false,
        // },
        // {
        //     target: 8,
        //     visible: false,
        // },
        // {
        //     target: 9,
        //     visible: false,
        // },

      ],
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
    $("#userModalLabel").html("Add New Project");
  
    $("#project_name").val("");
    $("#project_code").val("");
    $("#region").val("");
    $("#description").val("");
    $("#start_date").val("");
    $("#end_date").val("");
    $("#renewal_date").val("");
    $("#customer_name").val("");
    $("#planned_billing").val("");
    $("#actual_billing").val("");
    $('#editID').val(0);

    $("#userDataModal").modal("show");
  }
  
  function editData(user_data) {
      $(".frm-status").html("");
      $("#userModalLabel").html("Edit Project ");

      $("#project_name").val(user_data.project_name);
      $("#project_code").val(user_data.project_code);
      $("#region").val(user_data.region);
      $("#description").val(user_data.description);
      $("#start_date").val(user_data.start_date);
      $("#end_date").val(user_data.end_date);
      $("#renewal_date").val(user_data.renewal_date);
      $("#customer_name").val(user_data.customer_name);
      $("#planned_billing").val(user_data.planned_billing);
      $("#actual_billing").val(user_data.actual_billing);
  
      $('#editID').val(user_data.id);
      $("#userDataModal").modal("show");
  }

  
  function submitUserData() {
    $(".frm-status").html("");
    let input_data_arr = [
      document.getElementById("project_name").value,
      document.getElementById("project_code").value,
      document.getElementById("region").value,
      document.getElementById("description").value,
      document.getElementById("start_date").value,
      document.getElementById("end_date").value,
      document.getElementById("renewal_date").value,
      document.getElementById("customer_name").value,
      document.getElementById("planned_billing").value,
      document.getElementById("actual_billing").value,
      
      document.getElementById('editID').value,
    ];
  
    fetch("controller/project_eventHandler.php", {
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
        fetch("controller/project_eventHandler.php", {
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
  
  
 