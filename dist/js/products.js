$(document).ready(function () {
  //append value in input field
  $(document).on("click", "a[data-role=update]", function () {
    var id = $(this).data("id");
    var productName = $("#" + id)
      .children("td[data-target=Productname]")
      .text();
    var Fournisseur = $("#" + id)
      .children("td[data-target=Fournisseur]")
      .text();
    var salePrice = $("#" + id)
      .children("td[data-target=Saleprice]")
      .text();
    var costPrice = $("#" + id)
      .children("td[data-target=Costprice]")
      .text();

    $("#Productname").val(productName);
    $("#Fournisseur").val(Fournisseur);
    $("#Saleprice").val(parseFloat(salePrice));
    $("#Costprice").val(parseFloat(costPrice));
    $("#userId").val(parseInt(id));
    $("#myModal").modal("toggle");
  });

  // update data
  $("#save").click(function () {
    var id = $("#userId").val();
    var productName = $("#Productname").val();
    var Fournisseur = $("#Fournisseur").val();
    var salePrice = $("#Saleprice").val();
    var costPrice = $("#Costprice").val();

    $.ajax({
      url: "api/ajax.php",
      method: "POST",
      data: {
        id: id,
        Productname: productName,
        Fournisseur: Fournisseur,
        Saleprice: salePrice,
        Costprice: costPrice,
        updateProductStatus: true,
      },
      success: (data) => {
        if (JSON.parse(data).status === "success") {
          $("#" + id)
            .children("td[data-target=Productname]")
            .text(productName);
          $("#" + id)
            .children("td[data-target=Fournisseur]")
            .text(Fournisseur);
          $("#" + id)
            .children("td[data-target=Saleprice]")
            .text(`${salePrice} DH`);
          $("#" + id)
            .children("td[data-target=Costprice]")
            .text(`${costPrice} DH`);
        }
        $("#myModal").modal("toggle");
      },
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
    $("#myModal2 #productId").val(dataId);
  });

  // send delete request to server:
  $("#delete").click(function () {
    var id = $("#myModal2 #productId").val();
    $.ajax({
      url: "api/ajax.php",
      method: "POST",
      data: {
        id: id,
        deleteProduct: true,
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

// calculate sale price:
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
