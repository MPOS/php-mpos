
var nv = window.nv || {};

nv.version = '0.0.1a';
nv.dev = true //set false when in production

window.nv = nv;

nv.tooltip = {}; // For the tooltip system
nv.utils = {}; // Utility subsystem
nv.models = {}; //stores all the possible models/components
nv.charts = {}; //stores all the ready to use charts
nv.graphs = []; //stores all the graphs currently on the page
nv.logs = {}; //stores some statistics and potential error messages

nv.dispatch = d3.dispatch('render_start', 'render_end');

// *************************************************************************
//  Development render timers - disabled if dev = false

if (nv.dev) {
    nv.dispatch.on('render_start', function(e) {
        nv.logs.startTime = +new Date();
    });

    nv.dispatch.on('render_end', function(e) {
        nv.logs.endTime = +new Date();
        nv.logs.totalTime = nv.logs.endTime - nv.logs.startTime;
        nv.log('total', nv.logs.totalTime); // used for development, to keep track of graph generation times
    });
}

// ********************************************
//  Public Core NV functions

// Logs all arguments, and returns the last so you can test things in place
nv.log = function() {
    if (nv.dev && console.log && console.log.apply)
        console.log.apply(console, arguments)
    else if (nv.dev && console.log && Function.prototype.bind) {
        var log = Function.prototype.bind.call(console.log, console);
        log.apply(console, arguments);
    }
    return arguments[arguments.length - 1];
};


nv.render = function render(step) {
    step = step || 1; // number of graphs to generate in each timout loop

    render.active = true;
    nv.dispatch.render_start();

    setTimeout(function() {
        var chart, graph;

        for (var i = 0; i < step && (graph = render.queue[i]); i++) {
            chart = graph.generate();
            if (typeof graph.callback == typeof(Function)) graph.callback(chart);
            nv.graphs.push(chart);
        }

        render.queue.splice(0, i);

        if (render.queue.length) setTimeout(arguments.callee, 0);
        else { nv.render.active = false; nv.dispatch.render_end(); }
    }, 0);
};

nv.render.active = false;
nv.render.queue = [];

nv.addGraph = function(obj) {
    if (typeof arguments[0] === typeof(Function))
        obj = {generate: arguments[0], callback: arguments[1]};

    nv.render.queue.push(obj);

    if (!nv.render.active) nv.render();
};

nv.identity = function(d) { return d; };

nv.strip = function(s) { return s.replace(/(\s|&)/g,''); };

function daysInMonth(month,year) {
    return (new Date(year, month+1, 0)).getDate();
}

function d3_time_range(floor, step, number) {
    return function(t0, t1, dt) {
        var time = floor(t0), times = [];
        if (time < t0) step(time);
        if (dt > 1) {
            while (time < t1) {
                var date = new Date(+time);
                if ((number(date) % dt === 0)) times.push(date);
                step(time);
            }
        } else {
            while (time < t1) { times.push(new Date(+time)); step(time); }
        }
        return times;
    };
}

d3.time.monthEnd = function(date) {
    return new Date(date.getFullYear(), date.getMonth(), 0);
};

d3.time.monthEnds = d3_time_range(d3.time.monthEnd, function(date) {
        date.setUTCDate(date.getUTCDate() + 1);
        date.setDate(daysInMonth(date.getMonth() + 1, date.getFullYear()));
    }, function(date) {
        return date.getMonth();
    }
);


/*****
 * A no-frills tooltip implementation.
 *****/


(function() {

    var nvtooltip = window.nv.tooltip = {};

    nvtooltip.show = function(pos, content, gravity, dist, parentContainer, classes) {

        var container = document.createElement('div');
        container.className = 'nvtooltip ' + (classes ? classes : 'xy-tooltip');

        gravity = gravity || 's';
        dist = dist || 20;

        var body = parentContainer ? parentContainer : document.getElementsByTagName('body')[0];

        container.innerHTML = content;
        container.style.left = 0;
        container.style.top = 0;
        container.style.opacity = 0;

        body.appendChild(container);

        var height = parseInt(container.offsetHeight),
            width = parseInt(container.offsetWidth),
            windowWidth = nv.utils.windowSize().width,
            windowHeight = nv.utils.windowSize().height,
            scrollTop = window.scrollY,
            scrollLeft = window.scrollX,
            left, top;

        windowHeight = window.innerWidth >= document.body.scrollWidth ? windowHeight : windowHeight - 16;
        windowWidth = window.innerHeight >= document.body.scrollHeight ? windowWidth : windowWidth - 16;

        var tooltipTop = function ( Elem ) {
            var offsetTop = top;
            do {
                if( !isNaN( Elem.offsetTop ) ) {
                    offsetTop += (Elem.offsetTop);
                }
            } while( Elem = Elem.offsetParent );
            return offsetTop;
        }

        var tooltipLeft = function ( Elem ) {
            var offsetLeft = left;
            do {
                if( !isNaN( Elem.offsetLeft ) ) {
                    offsetLeft += (Elem.offsetLeft);
                }
            } while( Elem = Elem.offsetParent );
            return offsetLeft;
        }

        switch (gravity) {
            case 'e':
                left = pos[0] - width - dist;
                top = pos[1] - (height / 2);
                var tLeft = tooltipLeft(container);
                var tTop = tooltipTop(container);
                if (tLeft < scrollLeft) left = pos[0] + dist > scrollLeft ? pos[0] + dist : scrollLeft - tLeft + left;
                if (tTop < scrollTop) top = scrollTop - tTop + top;
                if (tTop + height > scrollTop + windowHeight) top = scrollTop + windowHeight - tTop + top - height;
                break;
            case 'w':
                left = pos[0] + dist;
                top = pos[1] - (height / 2);
                if (tLeft + width > windowWidth) left = pos[0] - width - dist;
                if (tTop < scrollTop) top = scrollTop + 5;
                if (tTop + height > scrollTop + windowHeight) top = scrollTop - height - 5;
                break;
            case 'n':
                left = pos[0] - (width / 2) - 5;
                top = pos[1] + dist;
                var tLeft = tooltipLeft(container);
                var tTop = tooltipTop(container);
                if (tLeft < scrollLeft) left = scrollLeft + 5;
                if (tLeft + width > windowWidth) left = left - width/2 + 5;
                if (tTop + height > scrollTop + windowHeight) top = scrollTop + windowHeight - tTop + top - height;
                break;
            case 's':
                left = pos[0] - (width / 2);
                top = pos[1] - height - dist;
                var tLeft = tooltipLeft(container);
                var tTop = tooltipTop(container);
                if (tLeft < scrollLeft) left = scrollLeft + 5;
                if (tLeft + width > windowWidth) left = left - width/2 + 5;
                if (scrollTop > tTop) top = scrollTop;
                break;
        }


        container.style.left = left+'px';
        container.style.top = top+'px';
        container.style.opacity = 1;
        container.style.position = 'absolute'; //fix scroll bar issue
        container.style.pointerEvents = 'none'; //fix scroll bar issue

        return container;
    };

    nvtooltip.cleanup = function() {

        // Find the tooltips, mark them for removal by this class (so others cleanups won't find it)
        var tooltips = document.getElementsByClassName('nvtooltip');
        var purging = [];
        while(tooltips.length) {
            purging.push(tooltips[0]);
            tooltips[0].style.transitionDelay = '0 !important';
            tooltips[0].style.opacity = 0;
            tooltips[0].className = 'nvtooltip-pending-removal';
        }


        setTimeout(function() {

            while (purging.length) {
                var removeMe = purging.pop();
                removeMe.parentNode.removeChild(removeMe);
            }
        }, 500);
    };


})();


nv.utils.windowSize = function() {
    // Sane defaults
    var size = {width: 640, height: 480};

    // Earlier IE uses Doc.body
    if (document.body && document.body.offsetWidth) {
        size.width = document.body.offsetWidth;
        size.height = document.body.offsetHeight;
    }

    // IE can use depending on mode it is in
    if (document.compatMode=='CSS1Compat' &&
        document.documentElement &&
        document.documentElement.offsetWidth ) {
        size.width = document.documentElement.offsetWidth;
        size.height = document.documentElement.offsetHeight;
    }

    // Most recent browsers use
    if (window.innerWidth && window.innerHeight) {
        size.width = window.innerWidth;
        size.height = window.innerHeight;
    }
    return (size);
};



// Easy way to bind multiple functions to window.onresize
// TODO: give a way to remove a function after its bound, other than removing alkl of them
nv.utils.windowResize = function(fun){
    var oldresize = window.onresize;

    window.onresize = function(e) {
        if (typeof oldresize == 'function') oldresize(e);
        fun(e);
    }
}

// Backwards compatible way to implement more d3-like coloring of graphs.
// If passed an array, wrap it in a function which implements the old default
// behavior
nv.utils.getColor = function(color) {
    if (!arguments.length) return nv.utils.defaultColor(); //if you pass in nothing, get default colors back

    if( Object.prototype.toString.call( color ) === '[object Array]' )
        return function(d, i) { return d.color || color[i % color.length]; };
    else
        return color;
    //can't really help it if someone passes rubbish as color
}

// Default color chooser uses the index of an object as before.
nv.utils.defaultColor = function() {
    var colors = d3.scale.category20().range();
    return function(d, i) { return d.color || colors[i % colors.length] };
}


// Returns a color function that takes the result of 'getKey' for each series and
// looks for a corresponding color from the dictionary,
nv.utils.customTheme = function(dictionary, getKey, defaultColors) {
    getKey = getKey || function(series) { return series.key }; // use default series.key if getKey is undefined
    defaultColors = defaultColors || d3.scale.category20().range(); //default color function

    var defIndex = defaultColors.length; //current default color (going in reverse)

    return function(series, index) {
        var key = getKey(series);

        if (!defIndex) defIndex = defaultColors.length; //used all the default colors, start over

        if (typeof dictionary[key] !== "undefined")
            return (typeof dictionary[key] === "function") ? dictionary[key]() : dictionary[key];
        else
            return defaultColors[--defIndex]; // no match in dictionary, use default color
    }
}



// From the PJAX example on d3js.org, while this is not really directly needed
// it's a very cool method for doing pjax, I may expand upon it a little bit,
// open to suggestions on anything that may be useful
nv.utils.pjax = function(links, content) {
    d3.selectAll(links).on("click", function() {
        history.pushState(this.href, this.textContent, this.href);
        load(this.href);
        d3.event.preventDefault();
    });

    function load(href) {
        d3.html(href, function(fragment) {
            var target = d3.select(content).node();
            target.parentNode.replaceChild(d3.select(fragment).select(content).node(), target);
            nv.utils.pjax(links, content);
        });
    }

    d3.select(window).on("popstate", function() {
        if (d3.event.state) load(d3.event.state);
    });
}

