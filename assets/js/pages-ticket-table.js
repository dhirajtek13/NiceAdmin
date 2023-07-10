// Initialize DataTables API object and configure table

  
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
      ajax: "db/pages-ticket_list.php",
      scrollX: true,
      columnDefs: [
        {
          orderable: false,
          targets: 12,
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
      orderCellsTop: true,
      //   fixedHeader: true,
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
    $("#c_status").val(1);
    $("#assignee_id").val(1);
  
    $("#assigned_date").val("");
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
      console.log(user_data);
      $(".frm-status").html("");
      $("#userModalLabel").html("Edit Ticket #" + user_data.ticket_id);
  
      $("#ticket_id").val(user_data.ticket_id);
  
      $("#type_id option").filter(function() {return this.text == user_data.ticket_type ;}).attr('selected', true);
      $("#c_status option").filter(function() {return this.text == user_data.c_type_name ;}).attr('selected', true);
      $("#assignee_id option").filter(function() {return this.text == user_data.assignee ;}).attr('selected', true);
  
      // $("#type_id").val(user_data.ticket_type);
      // $("#c_status").val(user_data.c_status);
      // $("#assignee_id").val(user_data.assignee_id);
    
      $("#assigned_date").val(user_data.assigned_date);
      $("#plan_start_date").val(user_data.plan_start_date);
      $("#plan_end_date").val(user_data.plan_end_date);
      $("#actual_start_date").val(user_data.actual_start_date);
      $("#actual_end_date").val(user_data.actual_end_date);
    
      $("#planned_hrs").val(user_data.planned_hrs);
      $("#actual_hrs").val(user_data.actual_hrs);
  
      $('#editID').val(user_data.id);
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

      document.getElementById("actual_start_date").value,
      document.getElementById("actual_end_date").value,
      document.getElementById("actual_hrs").value,
      
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

  function wbsData(user_data) {
    // console.log(user_data);
    var ticket_id = user_data.id;
    
    //fetch all the list of activities in this ticket
    fetch("controller/story_eventHandler.php", {
      method: "POST",
      dataType: "html",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        request_type: "fetch",
        ticket_id: ticket_id,
      }),
    })
    .then(function (response) {
      return response.text();
    })
    .then((html) => {
      
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, "text/html");
      var docArticle = doc.querySelector(".phptableclass2").innerHTML;
      console.log(docArticle);
      $("#phptable").html(docArticle);
      //
      })
      .catch(console.error);

    $('#ticket_id_wbshidden').val(user_data.ticket_id);
    $('#act_actual_hrs').val(user_data.actual_hrs);
    $('#parentID_wbshidden').val(user_data.id);
    $('#assignee_id_wbshidden').val(user_data.assignee_id);
    $('#projectID_wbshidden').val(user_data.project_id);

    $("#wbsDataModal").modal("show");
  }

  function submitWBSData() {
    $(".frm-status2").html("");
    let input_data_arr = [
      document.getElementById("activity_name").value,//0  
      // document.querySelector('select[name="type_id"]').value,//1
      // document.querySelector('select[name="c_status"]').value,//2
      // document.querySelector('select[name="assignee_id"]').value,//3
      document.getElementById("act_planned_hrs").value,//4
      document.getElementById("act_actual_hrs").value,//5
      document.getElementById('parentID_wbshidden').value,//8
      document.getElementById('ticket_id_wbshidden').value,//8
      document.getElementById('assignee_id_wbshidden').value,//8
      document.getElementById('projectID_wbshidden').value,//8
      
    ];

    // console.log(input_data_arr);alert(111);
  
    fetch("controller/ticket_eventHandler.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        request_type: "addActivity",
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
  
            $("#wbsDataModal").modal("hide");
            $("#wbsDataFrm")[0].reset();
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
  