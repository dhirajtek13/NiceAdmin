$(document).ready(function () {
  // setTimeout(() => {
  //     $("#phptable").find(".sorting_disabled").removeClass("sorting_asc");
  //   }, 100);
  fetchLeaveTrackerData();

  var table = $("#phptable").DataTable({
    scrollX: true,
    bLengthChange: false,
    processing: true,
    deferLoading: 57,
    searching: false,
    language: {
      loadingRecords: '&nbsp;',
      processing: '<div class="spinner"></div>'
    },
    paging: false,
    info: false,
    columnDefs: [
      {
        orderable: false,
        targets: "no-sort",
      },
    ],
  });
});

function fetchLeaveTrackerData() {
  var dateselected = $("#dateSelected").val();
  const dategiven = new Date(dateselected);

  const month = dategiven.getMonth()+1;
  const year = dategiven.getFullYear();

    //   console.log(year);
    $(".loader").addClass('spinner-border');
    $(".loadingtable").addClass('d-none');

  fetch("controller/leavetracker_fetchHandler.php", {
    method: "POST",
    dataType: "html",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "fetch",
      month: month,
      year: year,
    //   projectselected: projectselected,
    //   actual_hrs: actual_hrs,
    }),
  })
    .then(function (response) {
      // When the page is loaded convert it to text

      return response.text();
    })
    .then(function (html) {
      // Initialize the DOM parser
      var parser = new DOMParser();

      // Parse the text
      var doc = parser.parseFromString(html, "text/html");
        // console.log("----");
        
        // You can now even select part of that html as you would in the regular DOM
        // Example:
        var docArticle = doc.querySelector(".phptableclass").innerHTML;
        // console.log(docArticle);

        $(".org_thead").remove();//remove org thead //directly remove giving js error
      $("#phptable").html(docArticle);
      $(".loader").removeClass('spinner-border');
      $(".loadingtable").removeClass('d-none');
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
}


function submitUserData(request_type) {
  $(".frm-status").html("");
  let input_data_arr = [
    document.getElementById("leave_desc").value,
    document.getElementById("leave_type").value,
    document.getElementById("day_type").value,
    document.getElementById("leave_start_date").value,
    document.getElementById("leave_end_date").value,
    document.getElementById('editID').value,
    document.getElementById('userID').value,
  ];

  fetch("controller/leave_eventHandler.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: request_type,
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
          
          $("#userDataModal").modal("hide");
          $("#userDataFrm")[0].reset();
          
          $("#phptable").DataTable().draw(); //or reload the page
          fetchLeaveTrackerData();

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


function updateLeave(start_date, checked, user_id, leave_id, element) {
    //open modal form to let user enter leave details
    //user can add new leave or if alrady checked then he can cancel his leave in form
    // alert(checked);
    // element.preventDefault();
    $("#leave_start_date").val(start_date);
    //disable previous dates for leave end dates
    $("#leave_end_date").prop("min", start_date);
    $("#leave_end_date").val(start_date);
    // var current_datetime = dt.toISOString().slice(0, 16);
    // document.getElementsByName("leave_end_date")[0].min = leave_end_date;//disable previous dates

    if(!checked){
      $('#leave_desc').val('');
      $('#leave_type').val(1);
      $('#day_type').val(1);

      $(".frm-status").html("");
      $("#userModalLabel").html("Add New Leave");
      $('#editID').val(0);
      $('#userID').val(user_id);
      $(".deleteButton").hide();
      $(".submitleave").text('Submit');
      $("#userDataModal").modal('show');
      element.checked = false;
    } else {
      //uncheck this date//remove this leave//if two days then update to single date, if 3 days then update to 2days
      var dataleaveObj = JSON.parse(element.getAttribute('dataleave'));

      // console.log(dataleaveObj);
      $('#leave_desc').val(dataleaveObj.leave_leave_desc);
      $('#leave_type').val(dataleaveObj.leave_leave_type);
      $('#day_type').val(dataleaveObj.leave_day_type);
      $('#leave_start_date').val(dataleaveObj.leave_start_date);
      $('#leave_end_date').val(dataleaveObj.leave_end_date);

      $(".frm-status").html("");
      $("#userModalLabel").html("Update Leave");
      $('#editID').val(leave_id);
      $('#userID').val(user_id);
      $(".submitleave").text('Update');
      $(".deleteButton").show();
      $("#userDataModal").modal('show');
      element.checked = true;
    }

    //refresh table
    

}