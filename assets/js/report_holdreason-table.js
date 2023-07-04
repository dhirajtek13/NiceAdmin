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

    var status_selected = $("#status_selector").val();
    var status_name = $("#status_selector option:selected").text();

    
    
    // setTimeout(() => {
    //     $("#dataList_wrapper").find(".sorting_disabled").removeClass("sorting_asc");
    //     $(".dt-button").addClass('dt-button_custom');
    // }, 100);

        //sidebar operations
        $("#report-nav").addClass("show");
        $("#report-nav").parent().find('a').removeClass("collapsed");


        statusChanged() //load data for default status i.e hold
  });


  function statusChanged() {
    var status_selected = $("#status_selector").val();
    var status_name = $("#status_selector option:selected").text();

    $("#dataList").DataTable().destroy();

    var table = $("#dataList").DataTable({
      dom: 'Bfrtip',
      buttons: [
          {
            extend: 'excel',
            title: 'PM Book - '+status_name+' and Reasons'
          }
      ],
      processing: true,
      serverSide: true,
      bLengthChange: false,
      // bFilter:false,
      ajax: "db/report_holdreason_list.php?status="+status_selected,
      scrollX: true,
      columnDefs: [
        {
          orderable: false,
          targets: 0,
        },
      ],
      fnRowCallback : function(nRow, aData, iDisplayIndex){
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
      $(".dt-button").addClass('dt-button_custom');
  }, 100);
    

  }
  

  
  
 