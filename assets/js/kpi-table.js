$(document).ready(function () {
  
  // kip1_otd();
  // kip2_odd();
  // fetchOTDData();

});

function kip1_otd() {
  var dateselected = $("#dateSelected").val();
  var projectselected = $("#project_selection").val();
  var actual_hrs = $("#project_selection").val();

  fetch("controller/otd_fetchHandler.php", {
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
// alert(222);
      // Parse the text
      var doc = parser.parseFromString(html, "text/html");

      // You can now even select part of that html as you would in the regular DOM
      // Example:
      console.log("console==>", doc);
      var docArticle = doc.querySelector(".kpiTable1class");
      $("#kpiTable1").append(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
  // var url = window.location.origin+'?dateselected='+dateselected;
  // window.location.href = url;
}

function kip2_odd() {
  var dateselected = $("#dateSelected").val();
  var projectselected = $("#project_selection").val();
  var actual_hrs = $("#project_selection").val();

  fetch("controller/odd_fetchHandler.php", {
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
      console.log(doc);
      // var docArticle = doc.querySelector(".kpiTable2class").innerHTML;
      // // alert(122);
      // $("#kpiTable1").append(123);

      var docArticle = doc.querySelector(".kpiTable1class");
      // console.log("console==>", doc);
      $("#kpiTable1").append(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
  // var url = window.location.origin+'?dateselected='+dateselected;
  // window.location.href = url;
}





