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
      ajax: "db/user_list.php",
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
         {
            target: 7,
            visible: false,
        },
        {
            target: 8,
            visible: false,
        },
        // {
        //     target: 9,
        //     visible: false,
        // },
        {
          target: 11,
          visible: false,
      },

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

    //sidebar operations
    $("#user-nav").addClass("show");
    $("#user-nav").parent().find('a').removeClass("collapsed");
  });
  

  //  //Modal CRUD operations 
  //  function addData() {
  //   $(".frm-status").html("");
  //   $("#userModalLabel").html("Add New Log");
  
  //   // $("#ticket_id").val("");
  //   const dt = new Date();
  //   dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
  //   var current_datetime = dt.toISOString().slice(0, 16);

  //   $("#dates").val(current_datetime);
  //   $("#hrs").val("");
  //   $("#c_status").val(1);
  //   $("#what_is_done").val("");
  //   $("#what_is_pending").val("");
  //   $("#what_support_required").val("");
  // $('#editID').val(0);
  //   $("#userDataModal").modal("show");
  // }
  
  function editData(user_data) {//TODO
      $(".frm-status").html("");
  
      $("#userModalLabel").html("Edit User Data ");

      console.log(user_data);
      $("#username").val(user_data.username);
      $("#email").val(user_data.email);
      $("#employee_id").val(user_data.employee_id);
      $("#designation").val(user_data.designation);
      // $("#user_type").val(user_data.user_type);
      // $("#password").val(user_data.password);
      $("#fname").val(user_data.fname);
      $("#lname").val(user_data.lname);
      $("#user_type option").filter(function() {return this.text == user_data.user_type_name ;}).attr('selected', true);

      if(user_data.project_name) {
        const projectsArray =  user_data.project_name.split(",");
        projectsArray.forEach(element => {
          var project = element.trim();
          $("#projects option").filter(function() {return this.text == project ;}).attr('selected', true);
        });
      }
      

      // $("#user_type option").filter(function() {return this.text == user_data.user_type_name ;}).attr('selected', true);
  
      $('#editID').val(user_data.id);
      $("#userDataModal").modal("show");
  }

  function changePassword(user_data) {//TODO
    $(".frm2-status").html("");

    // $("#changePasswordModalLabel").html("Edit Log ");


    // $("#c_status option").filter(function() {return this.text == user_data.c_type_name ;}).attr('selected', true);
    // $("#dates").val(user_data.dates);
    // $("#hrs").val(user_data.hrs);
    // $("#what_is_done").val(user_data.what_is_done);
    // $("#what_is_pending").val(user_data.what_is_pending);
    // $("#what_support_required").val(user_data.what_support_required);

    $('#editID2').val(user_data.id);

    $("#changePasswordModal").modal("show");
  }

  function changeUserStatus(current_status, user_id) {

    let input_data_arr = [
      current_status,
      user_id
    ];

    console.log(input_data_arr);

    fetch("controller/user_eventHandler.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        request_type: "changeUserStatus",
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
  
            // $("#changePasswordModal").modal("hide");
            // $("#userDataFrm2")[0].reset();
          });
        } else {
          alert( data.error);
          //TODO - 
          // $(".frm2-status").html(
          //   '<div class="alert alert-danger" role="alert">' +
          //     data.error +
          //     "</div>"
          // );
        }
      })
      .catch(console.error);
  }
  
  function submitUserData() {
    $(".frm-status").html("");
    let input_data_arr = [
      document.getElementById("username").value,
      document.getElementById("email").value,
  
      document.getElementById("employee_id").value,
      document.getElementById("designation").value,
      
      document.getElementById("fname").value,
      document.getElementById("lname").value,

      document.querySelector('select[name="user_type"]').value,
      $('#projects').val(),
  
      document.getElementById('editID').value,
    ];
  
    fetch("controller/user_eventHandler.php", {
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
  
  function submitPasswordData() {
    $(".frm2-status").html("");

    // var current_password = document.getElementById("current_password").value;
    var password = document.getElementById("password").value;
    var cpassword = document.getElementById("cpassword").value;

    var errhtml = '';
     //TODO -enriching password validation will apply here as well
    // if(current_password == ''){
    //   errhtml += 'Old Password Filed is Empty !!';
    // } else
     if(password == ''){ 
      errhtml += 'New Password Filed is Empty !!';
    } else if(cpassword == ''){ 
      errhtml += 'Confirm Password Filed is Empty !!';
    } else if(cpassword != password){ 
      errhtml += 'Password and Confirm Password Field do not match  !!';
    } 

    if(errhtml) {
      $(".frm2-status").html(
        '<div class="alert alert-danger" role="alert">'+errhtml+'</div>'
      );
      return false;
    }

    let input_data_arr = [
      // current_password,
      password,
      cpassword,
      document.getElementById('editID2').value,
    ];
  
    fetch("controller/user_eventHandler.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        request_type: "changePassword",
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
  
            $("#changePasswordModal").modal("hide");
            $("#userDataFrm2")[0].reset();
          });
        } else {
          $(".frm2-status").html(
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
  
  
 