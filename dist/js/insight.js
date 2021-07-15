$(document).ready(function () {
  $("#togsidebar").click(function () {
    $(".page-wrapper").toggleClass("active");
    $(".left-sidebar").toggleClass("hide-sidebar");
    $("#sideicon").toggleClass("fa-caret-right");
  });
});

$("#dashboardis2").daterangepicker({
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

// to trigger the apply event and send request:
$(".applyBtn").on("click", () => {
  // build url and send:
  const dateRange = $(".drp-selected").text().replace("#", null); // take date range:
  const dateFrom = dateRange.split("-")[0].trim(); // take (from) date:
  const dateTo = dateRange.split("-")[1].trim(); // take (to) date:
  // build and send url:
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].*?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=range&from=${dateFrom}&to=${dateTo}`;
  }
});

// other predefined dates
$("li[data-range-key='Today']").on("click", (e) => {
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].+?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=today`;
  }
});

$("li[data-range-key='Yesterday']").on("click", (e) => {
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].+?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=yesterday`;
  }
});

$("li[data-range-key='Last 7 Days']").on("click", (e) => {
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].+?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=last_week`;
  }
});

$("li[data-range-key='Last 30 Days']").on("click", (e) => {
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].+?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=last_30_days`;
  }
});

$("li[data-range-key='This Month']").on("click", (e) => {
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].+?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=this_month`;
  }
});

$("li[data-range-key='Last Month']").on("click", (e) => {
  const currentUrl = window.location;
  var postUrl = currentUrl.origin + currentUrl.pathname;
  if (currentUrl.search.length > 0) {
    const pid = currentUrl.search.match("pid=([0-9].+?)")[1].trim();
    window.location.href = `${postUrl}?pid=${pid}&t=last_month`;
  }
});

$(window).ready(() => {
  const url = window.location.href;

  if (url.match("t=(.+)?")) {
    const keyword = url.match("t=(.+)?")[1].replace("#", "");

    if (keyword) {
      var dateText = "";

      if (keyword === "today") {
        dateText = "Today";
      }
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
