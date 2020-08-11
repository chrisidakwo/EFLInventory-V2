$(document).ready(function() {
    var jqxhr = $.ajax({
        url: "/ajax/inventory/summary",
        type: "GET",
        dataType: "json"
    });

    jqxhr.done(function(response, textStatus, jqXHR) {
        $(".lblMonthSales").text(currencyFormat.format(response.month_sales));
        $(".lblLastMonthSales").text(currencyFormat.format(response.last_month_sales));

        $("#monthChart").sparkline(response.month_chart, {
            type: "bar",
            height: "35",
            barWidth: "4",
            resize: !0,
            barSpacing: "4",
            barColor: "#1e88e5"
        });

        $("#lastMonthChart").sparkline(response.last_month_chart, {
            type: "bar",
            height: "35",
            barWidth: "4",
            resize: !0,
            barSpacing: "4",
            barColor: "#1e88e5"
        });
    });
});