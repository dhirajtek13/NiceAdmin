// Initialize DataTables API object and configure table
$(document).ready(function () {
  fetchRUData();

  //sidebar operations
  $("#report-nav").addClass("show");
  $("#report-nav").parent().find("a").removeClass("collapsed");
});

function fetchRUData() {
  $("#dataList").DataTable().destroy();

  var start_date = $("#kpi_start_date").val();
  var end_date = $("#kpi_end_date").val();

  $("#dataList tfoot th").each(function () {
    var title = $(this).text();
    if (title) {
      $(this).html(
        '<input type="text" placeholder="' +
          title +
          '"   size="6" /  class="form-control">'
      );
    }
    if (title == "S.N") {
      $(this).html("");
    }
  });

  setTimeout(() => {
    $("#dataList_wrapper").find(".sorting_disabled").removeClass("sorting_asc");
    $(".dt-button").addClass("dt-button_custom");
  }, 100);

  var table = $("#dataList").DataTable({
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excel",
        title: "PM Book - 30% time left",
      },
    ],
    processing: true,
    serverSide: true,
    bLengthChange: false,
    // bFilter:false,
    // ajax: "db/report_timecompletion_list.php",
    ajax: {
      url: "db/report_timecompletion_list.php",
      data: {
        start_date: start_date,
        end_date: end_date,
      },
    },
    scrollX: true,
    columnDefs: [
      {
        orderable: false,
        targets: 0,
      },
    ],
    fnRowCallback: function (nRow, aData, iDisplayIndex) {
      $("td:first", nRow).html(iDisplayIndex + 1);
      return nRow;
    },
    orderCellsTop: true,
    initComplete: function () {
      // Apply the search
      this.api()
        .columns()
        .every(function () {
          var that = this;

          $("input", this.footer()).on("keyup change clear", function () {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
    },
  });
}
