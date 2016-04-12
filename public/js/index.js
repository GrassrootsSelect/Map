(function($){
    $(document).ready(function () {
        jQuery('.do-lookup-btn').click(function (e) {
            e.preventDefault();

            var $target = $(e.currentTarget);
            var $form = $target.parents('form');
            var address = $form.find('input[name="street"]').val();

            var url = '/addressLookup/'+encodeURIComponent(address);
            $('.response-container')
                .removeClass('error')
                .html('looking up congressional district...')
            ;
            $.ajax({
                url: url
            }).done(function(response) {
                var data = JSON.parse(response);
                if (data.status != 'OK') {
                    $('.response-container').addClass('error');
                }

                $('.response-container').html(data.message);
            });

            return false;
        });
    });
})(jQuery);
