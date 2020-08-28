(function ($) {
    $(document).ready(function () {
        var currentURL = window.location.href;
        let expectedURL = 'https://thevanmancan.co.uk/submit-listing/';
        if (currentURL === expectedURL) {
            var planID = $("[name='plan_id']").val();
            var inputCity = $("#inputCity");

            var limitNew = 5;
            inputCity.select2({
                maximumSelectionLength: limitNew,
                language: {
                    maximumSelectionLength: function (limit) {
                        return 'You have reached the maximum for your subscription/plan.';
                    }
                },
            });
        }
    });
})(jQuery);