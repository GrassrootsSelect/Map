(function ($){ $(document).ready(function () {

    $('#jstree_demo').jstree({
        'core' : {
            'data' : {
                "url" : '//'+document.location.hostname+'/tree/root',
                "data" : function (node) {
                    return { "id" : node.id };
                }
            },
            'check_callback': true
        }
    }).on('select_node.jstree', function (e, data) {
        console.log('data', data);

        if (data.node.parent == '#') {
            return true;
        }

        $.ajax({
            'url':'/editform/'+data.node.id,
            'complete':function (response) {
                $('.edit-form-wrapper').html(response.responseText);
            }
        })

    });

    $("select").select2({
        placeholder: "Select an item",
        allowClear: true
    });

});})(jQuery);