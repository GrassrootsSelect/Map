// Started here, went forward. THANK YOU! http://bl.ocks.org/mbostock/4657115
var stateObjects = {};

(function ($) {$(document).ready(function () {
    var centered,
        width = $("#content-wrapper").width(),
        height = $("#content-wrapper").height()
        ;

    if ($(window).width() < width) {
        width = $(window).width();
    }
    if ($(window).height() < height) {
        height = $(window).height();
    }
    var projection = d3.geo.albersUsa()
        .scale(1280)
        .translate([width / 2, height / 2])
        ;

    var path = d3.geo.path()
        .projection(projection)
        ;
    var svg = d3.select("#content-wrapper").append("svg")
        .attr("width", width)
        .attr("height", height)
        ;
    var container = svg.append('g');

    var errorLog = function (error) {
        console.log(error);
    };

    var $box = $('#info-box');
    $box.fadeOut();
    var endZoomAndCenter = function () {
        var x, y, k;

        x = width / 2;
        y = height / 2;
        k = 1;
        centered = null;
        $('#info-box').hide();

        container.selectAll("path")
            .classed("active", centered && function(d) { return d === centered; });

        container.classed("active", centered && function(d) { return d === centered; });

        container.selectAll("path").transition()
            .duration(750)
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");

        $('.zoom-hide-layer').fadeIn();
    };
    var startZoomAndCenter = function (d) {
        var x, y, k;

        var centroid = path.centroid(d);
        x = centroid[0]-80;
        y = centroid[1];
        k = 4;
        centered = d;


        $('.zoom-hide-layer').fadeOut();
        $('.map-help-wrapper').remove();

        $box.find('.loading-message').show();
        $box.find('#info-box-closer').hide();
        $box.find('#info-box-more').hide();
        $box.find('.info-content').html('').hide();
        $box.find('.state-name').text(d.properties.name);
        $box.find('#info-box-more').data('state', d.properties.abbr);

        $box.fadeIn();

        container.selectAll("path")
            .classed("active", centered && function(d) { return d === centered; });

        container.classed("active", centered && function(d) { return d === centered; });

        container.selectAll("path").transition()
            .duration(750)
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");

        $.ajax({
            url: '/static/statePreview/'+d.properties.abbr,
            complete: function(response) {

                $box.find('.info-content').html(response.responseText+'<div class="clear"></div>').show();
                $box.find('#info-box-closer').show();
                $box.find('#info-box-more').show();
                $box.find('.loading-message').hide();
            }
        });

    };
    var clicked = function (d) {
        console.log(d);
        if (d && !centered) {
            startZoomAndCenter(d);
        } else {
            endZoomAndCenter();
            startZoomAndCenter(d);
        }
    };
    queue()
        .defer(d3.json, "/js/us-states-extended.json?a1")
        .defer(d3.json, "/js/us-congress-113.json?b")
        .await(ready);

    function ready(error, us, congress) {
        if (error) { return errorLog(error); }

        var districts = congress.objects.districts,
            neighbors = topojson.neighbors(districts.geometries);
        container.append("clipPath")
            .attr("id", "clip-land")
            .append("use")
            .attr("xlink:href", "#land");

        container.append("g")
            .attr("id", "states")
            .attr("class", "statesOverlay")
            .selectAll("path")
            .data(us.features)
            .enter().append("path")
            .attr("d", path)
            .attr("data-state", function(d){
                return d.properties.name
            })
            .on("click", clicked);

        $.each(us.features, function (idx, itm) {
            stateObjects[itm.properties.abbr] = itm;
        });

        container.append("districtWrp")
            .attr("class", "districts")
            .attr("clip-path", "url(#clip-land)")
            .selectAll("path")
            .data(topojson.feature(congress, congress.objects.districts).features)
            .enter().append("path")
            .attr("d", path)
        ;
        container.append("path")
            .attr("class", "district-boundaries")
            .datum(topojson.mesh(congress, congress.objects.districts, function(a, b) { return a !== b && (a.id / 1000 | 0) === (b.id / 1000 | 0); }))
            .attr("d", path);
    };

    d3.select(self.frameElement).style("height", height + "px");

    $('#info-box-closer').click(function (e) {
        endZoomAndCenter();
    });

    $('#info-box-more').click(function (e) {
        var state = $(e.target).data('state');
        document.location = '/state/'+state;
    });

    $('.map-help-dismiss').click('.map-help-wrapper', function () {
        $('.map-help-wrapper').remove();
    });

    $('.do-address-lookup-button').click(function (e) {
        e.preventDefault();

        var $target = $(e.target);
        var $form = $target.parents('form');
        var address = $form.find('input[name="addr"]').val();

        var url = '/lookupState/'+encodeURIComponent(address);
        $.ajax({
            url: url
        }).done(function(response) {
            var data = JSON.parse(response);
            if (data.status != 'OK') {
                alert('error');
            }
            startZoomAndCenter(stateObjects[data.state]);
        });

        return false;
    });

});})(jQuery);