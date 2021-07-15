$(document).ready(() => {
  // handle ajax login:
  $("#login").on("click", (e) => {
    e.preventDefault();
    const message = $("#message");
    if (message.length === 0) {
      const message = $("<div>Processing...</div>");
      message.addClass(["alert", "alert-primary", "rounded-0"]);
      message.attr("id", "message");
      message.insertBefore("#login");
      message.fadeOut(0);
      message.fadeIn(500);
    }

    const user = $("input[name='username']").val();
    const pass = $("input[name='password']").val();
    $.post({
      url: "api/ajax.php",
      data: {
        login: "submit",
        username: user,
        password: pass,
      },
      success: (data) => {
        const response = JSON.parse(data);
        if (response.login_status === "success") {
          message.text(response.message);
          window.location.href = "index.php";
        } else {
          message.text(response.message);
          message.addClass("alert-danger");
        }
      },
      error: (xhr, err) => {
        console.log(err);
      },
    });
  });

  // to trigger the apply event and send request:
  $(".applyBtn").on("click", (e) => {
    // build url and send:
    const dateRange = $(".drp-selected").text(); // take date range:
    const dateFrom = dateRange.split("-")[0].trim(); // take (from) date:
    const dateTo = dateRange.split("-")[1].trim(); // take (to) date:
    // build and send url:
    const url =
      window.location.pathname + `?t=range&from=${dateFrom}&to=${dateTo}`;
    window.location.href = url;
  });

  // other predefined dates
  $("li[data-range-key='Today']").on("click", (e) => {
    const url = window.location.pathname + "?t=today";
    window.location.href = url;
  });

  $("li[data-range-key='Yesterday']").on("click", (e) => {
    const url = window.location.pathname + "?t=yesterday";
    window.location.href = url;
  });

  $("li[data-range-key='Last 7 Days']").on("click", (e) => {
    const url = window.location.pathname + "?t=last_week";
    window.location.href = url;
  });

  $("li[data-range-key='Last 30 Days']").on("click", (e) => {
    const url = window.location.pathname + "?t=last_30_days";
    window.location.href = url;
  });

  $("li[data-range-key='This Month']").on("click", (e) => {
    const url = window.location.pathname + "?t=this_month";
    window.location.href = url;
  });

  $("li[data-range-key='Last Month']").on("click", (e) => {
    const url = window.location.pathname + "?t=last_month";
    window.location.href = url;
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
      if (keyword === "all") {
        dateText = "Lifetime";
        $(".date-value").text("Lifetime");
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
