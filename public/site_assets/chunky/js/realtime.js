$(function(){
    var seriesData = [ [], [], [], [], [] ];
    var random = new Rickshaw.Fixtures.RandomData(150);

    for (var i = 0; i < 150; i++) {
        random.addData(seriesData);
    }

    var palette = new Rickshaw.Color.Palette( { scheme: 'classic9' } );

// instantiate our graph!

    var graph = new Rickshaw.Graph( {
        element: document.getElementById("realtime-chart"),
        //width: $("#chart-container").width(),
        height: 300,
        renderer: 'area',
        stroke: true,
        preserve: true,
        series: [{
            color: $blue,
            data: seriesData[0],
            name: 'Belarus'
        }, {
            color: $green,
            data: seriesData[2],
            name: 'Canada'
        }, {
            color: $orange,
            data: seriesData[3],
            name: 'UK'
        }, {
            color: $red,
            data: seriesData[4],
            name: 'US'
        }
        ]
    } );

    graph.render();

    $(window).resize(function(){
        graph.width = $("#chart-container").width();
        graph.render();
    });

    var hoverDetail = new Rickshaw.Graph.HoverDetail( {
        graph: graph
    } );

    var annotator = new Rickshaw.Graph.Annotate( {
        graph: graph,
        element: document.getElementById('timeline')
    } );

    var legend = new Rickshaw.Graph.Legend( {
        graph: graph,
        element: document.getElementById('legend')

    } );

    var shelving = new Rickshaw.Graph.Behavior.Series.Toggle( {
        graph: graph,
        legend: legend
    } );

    var order = new Rickshaw.Graph.Behavior.Series.Order( {
        graph: graph,
        legend: legend
    } );

    var highlighter = new Rickshaw.Graph.Behavior.Series.Highlight( {
        graph: graph,
        legend: legend
    } );

    var ticksTreatment = 'glow';

    var xAxis = new Rickshaw.Graph.Axis.Time( {
        graph: graph,
        ticksTreatment: ticksTreatment
    } );

    xAxis.render();

    var yAxis = new Rickshaw.Graph.Axis.Y( {
        graph: graph,
        tickFormat: Rickshaw.Fixtures.Number.formatKMBT,
        ticksTreatment: ticksTreatment
    } );

    yAxis.render();


// add some data every so often

    var messages = [
        "Changed home page welcome message",
        "Minified JS and CSS",
        "Changed button color from blue to green",
        "Refactored SQL query to use indexed columns",
        "Added additional logging for debugging",
        "Fixed typo",
        "Rewrite conditional logic for clarity",
        "Added documentation for new methods"
    ];

    setInterval( function() {
        random.addData(seriesData);
        graph.update();

    }, 1500 );

    function addAnnotation(force) {
        if (messages.length > 0 && (force || Math.random() >= 0.95)) {
            annotator.add(seriesData[2][seriesData[2].length-1].x, messages.shift());
        }
    }

    addAnnotation(true);
    setTimeout( function() { setInterval( addAnnotation, 3000 ) }, 3000 );
});