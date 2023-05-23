$(document).ready(function () {
  
  kip1_otd();

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

      // Parse the text
      var doc = parser.parseFromString(html, "text/html");

      // You can now even select part of that html as you would in the regular DOM
      // Example:
      var docArticle = doc.querySelector(".kpiTable1class").innerHTML;
      $("#kpiTable1").html(docArticle);
      // console.log(docArticle);
    })
    .catch(function (err) {
      console.log("Failed to fetch page: ", err);
    });
  // var url = window.location.origin+'?dateselected='+dateselected;
  // window.location.href = url;
}

