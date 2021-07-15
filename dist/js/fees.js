$(document).ready(function () {
  //append value in input field
  $(document).on("click", "a[data-role=update]", function () {
    var id = $(this).data("id");
    var feeName = $("#" + id)
      .children("td[data-target=fee_name]")
      .text();
    var feePrice = $("#" + id)
      .children("td[data-target=fee_price]")
      .text();

    $("#fee_name").val(feeName);
    $("#fee_price").val(feePrice);
    $("#feeId").val(parseInt(id));
    $("#myModal").modal("toggle");
  });

  $(document).ready(function () {
    $('[data-toggle="popover"]').popover({
      html: true,
      content:
        "<ul><li><strong>Total Orders</strong> : 100</li><li><strong>Total Delivred :</strong> 100</li><li><strong>Average Delivery : </strong>45.6</li></ul>",
      container: "body",
      placement: "left",
    });
  });

  $(document).ready(function () {
    $("#togsidebar").click(function () {
      $("#sideicon").toggleClass("fa-caret-right");
      $(".page-wrapper").toggleClass("active");
      $(".left-sidebar").toggleClass("hide-sidebar");
    });
  });

  $(document).on("click", "#addnew", function () {
    var dataId = $(this).data("id");
    $("#myModal3").modal("toggle");
  });

  $(document).on("click", "a[data-role=delete]", function () {
    var dataId = $(this).data("id");
    $("#myModal2").modal("toggle");
    $("#myModal2 #fid").val(dataId);
  });

  // send delete request to server:
  $("#deleteFee").click(function () {
    var id = $("#myModal2 #fid").val();
    console.log(id);
    $.ajax({
      url: "api/ajax.php",
      method: "POST",
      data: {
        feeId: id,
        deleteFee: true,
      },
      success: (response) => {
        $("#myModal2").modal("toggle");
        if (JSON.parse(response).status === "success") {
          $("tr[id=" + id + "]").remove();
        }
      },
    });
  });
});

(function (document) {
  "use strict";

  var TableFilter = (function (myArray) {
    var search_input;

    function _onInputSearch(e) {
      search_input = e.target;
      var tables = document.getElementsByClassName(
        search_input.getAttribute("data-table")
      );
      myArray.forEach.call(tables, function (table) {
        myArray.forEach.call(table.tBodies, function (tbody) {
          myArray.forEach.call(tbody.rows, function (row) {
            var text_content = row.textContent.toLowerCase();
            var search_val = search_input.value.toLowerCase();
            row.style.display =
              text_content.indexOf(search_val) > -1 ? "" : "none";
          });
        });
      });
    }

    return {
      init: function () {
        var inputs = document.getElementsByClassName("search-input");
        myArray.forEach.call(inputs, function (input) {
          input.oninput = _onInputSearch;
        });
      },
    };
  })(Array.prototype);

  document.addEventListener("readystatechange", function () {
    if (document.readyState === "complete") {
      TableFilter.init();
    }
  });
})(document);
