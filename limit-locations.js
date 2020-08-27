(function ($) {
    $(document).ready(function () {
        var currentURL = window.location.href;
        let expectedURL = 'https://thevanmancan.co.uk/submit-listing/';
        if (currentURL === expectedURL) {
            var planID = $("[name='plan_id']").val();
            var inputCity = $("#inputCity");

            $.get("https://thevanmancan.co.uk/wp-admin/admin-ajax.php?action=get_locations_limits&plan_id=" + planID, function (data, status) {
                var resp = JSON.parse(data);
                var limitNew = resp.limit ?? 1;
                inputCity.select2({
                    maximumSelectionLength: limitNew,
                    language: {
                        maximumSelectionLength: function (limit) {
                            return 'You have reached the maximum for your subscription/plan.';
                        }
                    },
                });
            });
        }
    });
})(jQuery);