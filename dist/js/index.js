$(document).ready(function () {
  $("#togsidebar").click(function () {
    $(".page-wrapper").toggleClass("active");
    $(".left-sidebar").toggleClass("hide-sidebar");
    $("#sideicon").toggleClass("fa-caret-right");
  });
});

$("#dashboardis").daterangepicker({
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
});

function toggle(ID) {
  var element = document.getElementById(ID);
  if (element.style.display === "block") {
    element.style.display = "none";
  } else {
    element.style.display = "block";
  }
}

$(document).ready(function () {
  $("#togsidebar").click(function () {
    $("#sideicon").toggleClass("fa-caret-right");
    $(".page-wrapper").toggleClass("active");
    $(".left-sidebar").toggleClass("hide-sidebar");
  });
});

$(window).ready(() => {
  const url = window.location.href;

  if (url.match("t=(.+)?")) {
    const keyword = url.match("t=(.+)?")[1].replace("#", "");

    if (keyword !== "today") {
      var dateText = "";

      if (keyword === "all") {
        dateText = "Lifetime";
        $(".date-value").text("Lifetime");
      }
    }
  }
});
