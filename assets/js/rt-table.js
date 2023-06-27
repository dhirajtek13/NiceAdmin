$(document).ready(function () {
    // setTimeout(() => {
    //     $("#phptable").find(".sorting_disabled").removeClass("sorting_asc");
    //   }, 100);
    // var start_date = $("#otd_start_date").val();
    // var end_date = $("#otd_end_date").val();
    fetchRUData();
  
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
  
  function fetchRUData() {
    // var dateselected = $("#dateSelected").val();
    // const dategiven = new Date(dateselected);
  
    // const month = dategiven.getMonth()+1;
    // const year = dategiven.getFullYear();

    var start_date = $("#kpi_start_date").val();
    var end_date = $("#kpi_end_date").val();
    // var config_actual_hrs = $("#hidden_actual_hrs").val();
    // var config_working_days = $("#hidden_working_days").val();
    var projectSelected = $("#hidden_project").val();
    // alert()
  
    $("#kpi_end_date").prop("min", start_date);
  
    fetch("db/rt_list.php", {
      method: "POST",
      dataType: "html",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        request_type: "fetch",
        startdate: start_date,
        enddate: end_date,
        // config_actual_hrs: config_actual_hrs,
        // config_working_days: config_working_days,
        projectSelected: projectSelected,
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
          console.log(docArticle);
        $("#phptable").html(docArticle);
        // console.log(docArticle);
      })
      .catch(function (err) {
        console.log("Failed to fetch page: ", err);
      });
  }
  
  
//   function updateLeave() {
//       //open modal form to let user enter leave details
//       //user can add new leave or if alrady checked then he can cancel his leave in form
//       // alert(11);
//       $("#userDataModal").modal('show');
//   }