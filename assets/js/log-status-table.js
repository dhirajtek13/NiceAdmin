$(document).ready(function () {


    var table = $("#phptable").DataTable({
      scrollX: true,
      bLengthChange: false,
      searching: false,
      paging: false,
      info: false,
      columnDefs: [{
        orderable: false,
        targets: "no-sort"
      }]
    });

    // setTimeout(() => {
    //     $("#phptable").find(".sorting_disabled").removeClass("sorting_asc");
    //   }, 100);
  });