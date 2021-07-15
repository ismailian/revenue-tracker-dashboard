$(window).ready(() => {
  try {
    $(function () {
      $("#datetimepicker7").datetimepicker({
        format: "L",
      });
      $("#datetimepicker8").datetimepicker({
        useCurrent: false,
        format: "L",
      });
      $("#datetimepicker7").on("change.datetimepicker", function (e) {
        $("#datetimepicker8").datetimepicker("minDate", e.date);
      });
      $("#datetimepicker8").on("change.datetimepicker", function (e) {
        $("#datetimepicker7").datetimepicker("maxDate", e.date);
      });
    });
  } catch (e) {}
});

$(document).ready(function () {
  $("#togsidebar").click(function () {
    $(".page-wrapper").toggleClass("active");
    $(".left-sidebar").toggleClass("hide-sidebar");
    $("#sideicon").toggleClass("fa-caret-right");
  });
});

$("#demo").daterangepicker(
  {
    showWeekNumbers: false,
    timePickerSeconds: true,
    ranges: {
      Today: [moment(), moment()],
      Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Last 7 Days": [moment().subtract(6, "days"), moment()],
      "Last 30 Days": [moment().subtract(29, "days"), moment()],
      "This Month": [moment().startOf("month"), moment().endOf("month")],
      "Last Month": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
    parentEl: "body",
    startDate: "04/25/2020",
    endDate: "05/01/2020",
    opens: "left",
  },
  function (start, end, label) {
    console.log(
      "New date range selected: " +
        start.format("YYYY-MM-DD") +
        " to " +
        end.format("YYYY-MM-DD") +
        " (predefined range: " +
        label +
        ")"
    );
  }
);

$(document).ready(function () {
  document.querySelectorAll("#openup").forEach((o) => {
    o.addEventListener("click", () => {
      const dateId = o.parentElement.getAttribute("data-id");
      $(".modal-title").text(`Reports For Date: ${o.textContent}`);
      $.get({
        url: `api/ajax.php?getProductsByDate=${dateId}`,
        success: (data) => {
          $(".productsContainer").html(data);
          $("#centralModalFluid").modal("toggle");
          var total = 0;
          var expenses = 0;
          var net = document.querySelector(
            `tr[id='${dateId}'] td[data-target='Saleprice']`
          ).textContent;

          const row_capital = document.querySelectorAll(".tmp_capital");
          const row_expenses = document.querySelectorAll(".tmp_expenses");

          row_capital.forEach((r) => {
            total += parseInt(r.textContent);
          });
          row_expenses.forEach((r) => {
            expenses += parseInt(r.textContent);
          });

          const total_cp = document.querySelector(".total_cp");
          const net_cp = document.querySelector(".net_cp");
          const expenses_cp = document.querySelector(".expenses_cp");

          total_cp.textContent = `${total} DH`;
          net_cp.textContent = net;
          expenses_cp.textContent = `${expenses} DH`;
        },
      });
    });
  });

  // Date picker changed value:
  $(".datetimepicker-input.to").on("input", (e) => {
    const to = e.target.value; // value: (to)
    const from = $(".datetimepicker-input.from").val(); // value: (from)
    $.ajax({
      method: "GET",
      url: `api/ajax.php?getInBetweenDates&from=${from}&to=${to}`,
      success: (data) => {
        $(".table-content").html(data);
        document.querySelectorAll("#openup").forEach((o) => {
          o.addEventListener("click", () => {
            const dateId = o.parentElement.getAttribute("data-id");
            $.get({
              url: `api/ajax.php?getProductsByDate=${dateId}`,
              success: (data) => {
                $(".productsContainer").html(data);
                $(".modal").modal("toggle");
              },
            });
          });
        });
      },
    });
  });
});

// Transfered from Calculator.js
$(document).ready(function () {
  //append value in input field
  $(document).on("click", "a[data-role=update]", function () {
    const id = $(this).data("id");
    const pid = $(this).data("row");

    // fetch product info :
    $.ajax({
      url: `api/ajax.php?fetchProductInfo&pid=${id}`,
      method: "GET",
      success: (data) => {
        console.log(data);
        const productInfo = JSON.parse(data);

        $("#productName").val(productInfo.name);
        $("#productName").attr("data-id", pid);
        $("#totalOrders").val(parseInt(productInfo.confirmed));
        $("#allorders").val(parseInt(productInfo.orders));
        $("#totalDelivered").val(parseInt(productInfo.delivered));
        $("#Ads").val(parseFloat(productInfo.ads));
        $("#productId").val(id);
      },
      error: (xhr, error) => {
        console.log(error);
      },
    });

    $("#UpdateModal").modal("toggle");
  });

  // update data
  $("#save").click(function (e) {
    var id = $("#productId").val();
    var pid = $("#productName").attr("data-id");
    var productName = $("#productName").val();
    var allOrders = $("#allorders").val();
    var totalOrders = $("#totalOrders").val();
    var totalDelivred = $("#totalDelivered").val();
    var ads = $("#Ads").val();
    $.ajax({
      url: "api/ajax.php",
      method: "POST",
      data: {
        id: id,
        productId: pid,
        Productname: productName,
        Allorders: allOrders,
        Totalorders: totalOrders,
        Totaldelivred: totalDelivred,
        Ads: ads,
        updateProduct: true,
      },
      success: (data) => {
        if (JSON.parse(data).status === "success") {
          $("#UpdateModal").modal("toggle");
        }
      },
    });
  });
});

// delete product:
$(document).on("click", "a[data-role=delete]", function () {
  var dataId = $(this).data("id");
  $("#reportModal").modal("toggle");
  $("#reportModal #reportId").val(dataId);
});
$("#delete").click(function () {
  var id = $("#reportModal #reportId").val();
  $.ajax({
    url: "api/ajax.php",
    method: "POST",
    data: {
      id: id,
      deleteReport: true,
    },
    success: function (response) {
      if (JSON.parse(response).status === "success") {
        $(`tr[data-id="${id}"]`).remove();
        $("#reportModal").modal("toggle");
      }
    },
  });
});

// delete calc item:
$(document).on("click", "a[data-role=delete-calc]", function () {
  var calcId = $(this).data("id");
  $("#calcModal").modal("toggle");
  $("#calcModal #calcId").val(calcId);
});

$("#delete-calc").click(function () {
  var id = $("#calcModal #calcId").val();
  $.ajax({
    url: "api/ajax.php",
    method: "POST",
    data: {
      id: id,
      deleteCalculatorProduct: true,
    },
    success: function (response) {
      if (JSON.parse(response).status === "success") {
        $(`tr[id="${id}"]`).remove();
        $("#calcModal").modal("toggle");
      }
    },
  });
});

$(window).ready(() => {
  const url = window.location.href;

  if (url.match("t=(.+)?")) {
    const keyword = url.match("t=(.+)?")[1].replace("#", "");

    if (keyword !== "today") {
      var dateText = "";

      if (keyword === "yesterday") {
        dateText = "Yesterday";
      }
      if (keyword === "last_week") {
        dateText = "Last Week";
      }
      if (keyword === "this_month") {
        dateText = "This Month";
      }
      if (keyword === "last_month") {
        dateText = "Last Month";
      }
      if (keyword === "last_30_days") {
        dateText = "Last 30 Days";
      }
      if (keyword === "this_year") {
        dateText = "This Year";
      }
      if (keyword.match("range")) {
        const data = new URLSearchParams(url);
        dateText = `${data.get("from")} ~ ${data.get("to")}`;
      }

      document.querySelector(".date-label").textContent = "";
      document.querySelector(".date-value").textContent = dateText;
    }
  }
});

$(window).ready(() => {
  const url = window.location.href;

  if (url.match("t=(.+)?")) {
    const keyword = url.match("t=(.+)?")[1].replace("#", "");

    if (keyword === "all") {
      $(".date-value").text("Lifetime");
    }
  }
});
