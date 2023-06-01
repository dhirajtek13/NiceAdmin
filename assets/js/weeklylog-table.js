$(document).ready(function () {
  

  // setTimeout(() => {
  //     $("#phptable").find(".sorting_disabled").removeClass("sorting_asc");
  //   }, 100);
  reloadData();
  fetchTicketStatusData();
  fetchEffortData();
  fetchCountData();
  fetchOTDData();


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

function reloadData() {
  var dateselected = $("#dateSelected").val();
  var projectselected = $("#project_selection").val();
  var actual_hrs = $("#hidden_actual_hrs").val();

  fetch("controller/weekly_fetchHandler.php", {
    method: "POST",
    dataType: "html",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "fetch",
      dateselected: dateselected,
      projectselected: projectselected,
      actual_hrs: actual_hrs,
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

      // You can now even select part of that html as you would in the regular DOM
      // Example:
      var docArticle = doc.querySelector(".phptableclass").innerHTML;
      $("#phptable").html(docArticle);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
  // var url = window.location.origin+'?dateselected='+dateselected;
  // window.location.href = url;
}

function fetchTicketStatusData() {
  var startdate = $("#ticket_status_start_date").val();
  var enddate = $("#ticket_status_end_date").val();
  var projectselected = $("#project_selection").val();
  // var actual_hrs = $("#project_selection").val();

  fetch("controller/ticketStatus_fetchHandler.php", {
    method: "POST",
    dataType: "html",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "fetch",
      startdate: startdate,
      enddate: enddate,
      projectselected: projectselected,
    }),
  })
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text();
    })
    .then(function (html) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, "text/html");
      var docArticle2 = doc.querySelector(".phptable2class").innerHTML;

      // console.log(docArticle2);
      $("#phptable2").html(docArticle2);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
}

function fetchEffortData() {
  var startdate = $("#efforts_start_date").val();
  var enddate = $("#efforts_end_date").val();
  var projectselected = $("#project_selection").val();
  // var actual_hrs = $("#project_selection").val();

  fetch("controller/efforts_fetchHandler.php", {
    method: "POST",
    dataType: "html",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "fetch",
      startdate: startdate,
      enddate: enddate,
      projectselected: projectselected,
    }),
  })
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text();
    })
    .then(function (html) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, "text/html");
      var docArticle3 = doc.querySelector(".phptable3class").innerHTML;

      // console.log(docArticle2);
      $("#effortTable_id").html(docArticle3);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
}

function fetchCountData() {
  var startdate = $("#counts_start_date").val();
  var enddate = $("#counts_end_date").val();
  var projectselected = $("#project_selection").val();
  // var actual_hrs = $("#project_selection").val();

  fetch("controller/counts_fetchHandler.php", {
    method: "POST",
    dataType: "html",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "fetch",
      startdate: startdate,
      enddate: enddate,
      projectselected: projectselected,
    }),
  })
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text();
    })
    .then(function (html) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, "text/html");
      var docArticle4 = doc.querySelector(".phptable4class").innerHTML;

      // console.log(docArticle2);
      $("#countTable_id").html(docArticle4);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
}

function fetchOTDData() {
  var startdate = $("#otd_start_date").val();
  var enddate = $("#otd_end_date").val();
  var projectselected = $("#project_selection").val();
  var actual_hrs = $("#hidden_actual_hrs").val();
  // alert(actual_hrs);
  // var actual_hrs = $("#project_selection").val();

  fetch("controller/kpi_fetchHandler.php", {
    method: "POST",
    dataType: "html",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      request_type: "fetch",
      startdate: startdate,
      enddate: enddate,
      projectselected: projectselected,
      actual_hrs:actual_hrs
    }),
  })
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text();
    })
    .then(function (html) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, "text/html");
      // console.log(doc);
      var docArticle2 = doc.querySelector(".kpiTable1class").innerHTML;

      $("#kpiTable1").html(docArticle2);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
}

function projectLoad() {
  var prj_selected = $("#project_selection").val();
  var url = window.location.origin + "?project=" + prj_selected;
  // $("#project_selection_value").val(prj_selected);
  window.location.href = url;

  //TODO - ajax to refresh the page data in each block. either reset date selector or use the same date filters.
  //also in ajax verify that this project really belongs to logged in user
}

// $(function() {
//   $('input[name="ticket_status_date_selection"]').daterangepicker({
//     opens: 'left'
//   }, function(start, end, label) {
//     console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
//   });
// });
