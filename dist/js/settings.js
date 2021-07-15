$(document).ready(function () {
  //append value in input field
  $(document).on("click", "a[data-role=update]", function () {
    var id = $(this).data("id");
    var fullname = $("#" + id)
      .children("td[data-target=fullname]")
      .text();
    var username = $("#" + id)
      .children("td[data-target=username]")
      .text();
    var email = $("#" + id)
      .children("td[data-target=email]")
      .text();

    $("#userId").val(parseInt(id));
    $("#fullname").val(fullname);
    $("#username").val(username);
    $("#email").val(email);

    // disable if admin:
    if (username === "admin") {
      $(".access-roles").css({ display: "none" });
    } else {
      $(".access-roles").css({ display: "block" });
    }

    $.ajax({
      url: "api/ajax.php?getUserAccessRoles",
      method: "GET",
      data: { user_id: id },
      success: (data) => {
        if (JSON.parse(data).status === "success") {
          const roles = JSON.parse(data).roles;
          $("#index_page").attr("checked", roles.index);
          $("#settings_page").attr("checked", roles.settings);
          $("#products_page").attr("checked", roles.products);
          $("#calculator_page").attr("checked", roles.calculator);
          $("#reports_page").attr("checked", roles.reports);
          $("#fees_page").attr("checked", roles.fees);
        }
      },
      error: (xhr, error) => {
        console.log(error);
      },
    });

    $("#myModal").modal("toggle");
  });
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
  $("#myModal2 #userId").val(dataId);
});

// send delete request to server:
$("#delete").click(function () {
  const id = $("#myModal2 #userId").val();
  $.ajax({
    url: "api/ajax.php",
    method: "POST",
    data: {
      userId: id,
      deleteUser: true,
    },
    success: (response) => {
      $("#myModal2").modal("toggle");
      if (JSON.parse(response).status === "success") {
        $("tr[id=" + id + "]").remove();
      }
    },
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
