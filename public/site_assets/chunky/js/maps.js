$(function(){
    $("#basic").width("100%").height("100%").gmap3({
        map:{
            address:"Minsk, Belarus",
            options:{
                zoom: 4,
                mapTypeId: google.maps.MapTypeId.TERRAIN
            }
        }
    });
    $('#location').width("100%").height("100%").gmap3({
        getgeoloc:{
            callback : function(latLng){
                if (latLng){
                    $(this).gmap3({
                        marker:{
                            latLng:latLng
                        },
                        map:{
                            options:{
                                zoom: 5
                            }
                        }
                    });
                } else {
                    $(this).html("Can't find your location. Sorry :(");
                }
            }
        }
    });

    $('#address').keypress(function(e) {
        if(e.which == 13) {
            var addr = $(this).val();
            if ( !addr || !addr.length ) return;
            $("#location").gmap3({
                getlatlng:{
                    address:  addr,
                    callback: function(results){
                        if ( !results ) return;
                        $(this).gmap3({
                            marker:{
                                latLng:results[0].geometry.location
                            },
                            map:{
                                options:{
                                    center: results[0].geometry.location,
                                    zoom: 5
                                }
                            }
                        });
                    }
                }
            });
        }
    });

    $('#vector-world').width("100%").height("100%").vectorMap({
        map: 'world_en',
        backgroundColor: '#a5bfdd',
        borderColor: '#818181',
        borderOpacity: 0.25,
        borderWidth: 1,
        color: '#f4f3f0',
        enableZoom: true,
        hoverColor: $orange,
        hoverOpacity: null,
        normalizeFunction: 'linear',
        scaleColors: ['#b6d6ff', '#005ace'],
        selectedColor: $red,
        selectedRegion: null,
        showTooltip: true,
        onRegionClick: function(element, code, region){
            var $modal = $("#myModal");
            $modal.find(".modal-body p").html('You clicked <strong>'
                + region
                + '</strong> which has the code: '
                + code.toUpperCase());
            $modal.modal('show');
        }
    });
    function vectorDetailed(map){
        //jqvmap has a problem with destroying itself
        //so clear it hard way
        $('#vector-detailed').replaceWith("<div id='vector-detailed'></div>");
        var $map = $('#vector-detailed');
        $map.width("100%").height("100%").vectorMap({
            map: map,
            enableZoom: true,
            hoverColor: $orange,
            hoverOpacity: null,
            normalizeFunction: 'linear',
            scaleColors: ['#b6d6ff', '#005ace'],
            selectedColor: $red,
            selectedRegion: null,
            showTooltip: true,
            onRegionClick: function(element, code, region){
                var $modal = $("#myModal");
                $modal.find(".modal-body p").html('You clicked "'
                    + region
                    + '" which has the code: '
                    + code.toUpperCase());
                $modal.modal('show');
            }
        });
        if (map == 'europe_en'){
            $map.data('mapObject').zoomIn();
        }
    }

    vectorDetailed('europe_en');


    $(".selectpicker").selectpicker().on("change", function(){
        vectorDetailed($(this).val());
    });
    //selectpicker doesn't seem to be flexible enough (can't change template), so need to replace span.caret externally
    $('.selectpicker + .bootstrap-select span.caret').replaceWith("<i class='fa fa-caret-down'></i>");
    $('.selectpicker + .bootstrap-select span.pull-left').removeClass("pull-left");
});