$(document).ready(function () {
  // setTimeout(() => {
  //     $("#phptable").find(".sorting_disabled").removeClass("sorting_asc");
  //   }, 100);
  fetchLeaveTrackerData();

  var table = $("#phptable").DataTable({
    scrollX: true,
    bLengthChange: false,
    searching: false,
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
      $("#phptable").html(docArticle);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
}



function submitUserData() {
  $(".frm-status").html("");


  let input_data_arr = [
    document.getElementById("leave_desc").value,
    document.getElementById("leave_type").value,

    document.getElementById("day_type").value,
    document.getElementById("leave_start_date").value,
    // document.querySelector('select[name="c_status"]').value,
    
    document.getElementById("leave_end_date").value,
    // document.getElementById("what_is_pending").value,
    // document.getElementById("what_support_required").value,
    
    document.getElementById('editID').value,

    // document.getElementById("remark").value,
    // document.getElementById("previousStatus").value,
    // document.getElementById("updatedStatus").value,
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
       
        
        Swal.fire({
          title: data.msg,
          icon: "success",
        }).then((result) => {
          // Redraw the table
          
          $("#userDataModal").modal("hide");
          $("#userDataFrm")[0].reset();
          
          $("#dataList").DataTable().draw();
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


function updateLeave(start_date, checked, t) {
    //open modal form to let user enter leave details
    //user can add new leave or if alrady checked then he can cancel his leave in form
    // alert(checked);
    console.log(t);
    // alert(start_date);

    $("#leave_start_date").val(start_date);

    if(!checked){
      $("#userDataModal").modal('show');
    } else {
      //uncheck this date//remove this leave//if two days then update to single date, if 3 days then update to 2days
      
      
    }

    //refresh table
    

}