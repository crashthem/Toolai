(function ($) {

    $(document).ready(function (e) {
        $(function () {
            var foundin = $('a:contains("Exchange, Return & Refund")');
            foundin.html('Exchange, Return</br> & Refund');
        });

        // $(".prev.page-numbers").after($("#pageof"));

        $(".page-id-20 .yith-wcwl-icon").click(function () {
            $("#yith-wcwl-popup-message").show();
            setTimeout(function () {
                $("#yith-wcwl-popup-message").hide("slow");
            }, 2000);
        });
    });

    $(window).on('load', function (e) {

    });

})(jQuery);