$(document).ready(function () {
  try {
    tail.select("#selectprod", {
      search: true,
    });
  } catch (e) {}
  $(".table-content").show();
  $("#hideshow").on("click", function (event) {
    $(".table-content").toggle("hide");
    $(this).text("Hide Table");
  });
});

$(window).ready(() => {
  try {
    $(function () {
      $("#datetimepicker5").datetimepicker({
        format: "L",
        dateFormat: "mm-dd-yy",
        changeMonth: true,
        changeYear: true,
      });
    });
  } catch (e) {}
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
  $("#myModal5").modal("toggle");
});

$(document).ready(function () {
  //append value in input field
  $(document).on("click", "a[data-role=update]", function () {
    var id = $(this).data("id");
    let pid = $(this).data("row");
    let row = document.querySelector(`#row-${id}`);
    var productName = row.querySelector("td[data-target=Productname]")
      .textContent;
    var allOrders = row.querySelector("td.alle").textContent;
    var totalOrders = row.querySelector("td[data-target=Totalorders]")
      .textContent;
    var totalDelivred = row.querySelector("td[data-target=Totaldelivered]")
      .textContent;
    var ads = row.querySelector("td[data-target=Ads]").textContent;

    $("#productName").val(productName);
    $("#productName").attr("data-id", pid);
    $("#totalOrders").val(parseFloat(totalOrders));
    $("#allorders").val(parseFloat(allOrders));
    $("#totalDelivered").val(parseFloat(totalDelivred));
    $("#Ads").val(parseFloat(ads));
    $("#userId").val(id);
    $("#myModal").modal("toggle");
  });
  // update data
  $("#save").click(function (e, pid) {
    var id = $("#userId").val();
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
      success: function (data) {
        var row = document.querySelector(`#row-${id}`);
        row.querySelector(
          "td[data-target=Productname]"
        ).textContent = productName;
        row.querySelector("td.alle").textContent = allOrders;
        row.querySelector(
          "td[data-target=Totalorders]"
        ).textContent = totalOrders;
        row.querySelector(
          "td[data-target=Totaldelivered]"
        ).textContent = totalDelivred;
        row.querySelector("td[data-target=Ads]").textContent = `${ads} DH`;
        $("#myModal").modal("toggle");
      },
    });
  });
});

$(document).ready(() => {
  $(".dropdown a.btn").on("click", (e) => {
    $("#myModal3").modal("toggle");
    $("#calculate").on("click", (e) => {
      e.preventDefault();

      const quantity = $("input[name='quantity']").val();
      const product_cost = $("input[name='product_cost']").val();
      const delivery_cost = $("input[name='delivery_cost']").val();
      const confirm_fees = $("input[name='confirmation_fees']").val();
      const ads_cost = $("input[name='ads_cost']").val();
      const extra_profit = $("input[name='extra_profit']").val();

      $.ajax({
        url: "api/ajax.php",
        method: "POST",
        data: {
          calculate: "1",
          quantity: quantity,
          product_cost: product_cost,
          delivery_cost: delivery_cost,
          confirm_fees: confirm_fees,
          ads_cost: ads_cost,
          extra_profit: extra_profit,
        },
        success: (data) => {
          if (JSON.parse(data).status === "completed") {
            $(".result").text(`${JSON.parse(data).result}`);
            $(".result").addClass("alert-success");
          }
        },
        error: (err) => {
          console.log(err);
        },
      });
    });
  });
});
