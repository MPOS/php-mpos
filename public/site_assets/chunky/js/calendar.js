$(function(){

    $('#external-events').find('div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },

        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
            var $modal = $("#edit-modal"),
                $btn = $('#create-event');
            $btn.off('click');
            $btn.click(function () {
                var title = $("#event-name").val();
                if (title) {
                    calendar.fullCalendar('renderEvent',
                        {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                        true
                    );
                }
                calendar.fullCalendar('unselect');
            });
            $modal.modal('show');
            calendar.fullCalendar('unselect');
        },
        editable: true,
        droppable:true,

        drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            $(this).remove();

        },

        // US Holidays
        events: [
            {
                title: 'All Day Event',
                start: new Date(y, m, 1)
            },
            {
                title: 'Long Event',
                start: new Date(y, m, d+5),
                end: new Date(y, m, d+7)
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: new Date(y, m, d-3, 16, 0),
                allDay: false
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: new Date(y, m, d+4, 16, 0),
                allDay: false
            },
            {
                title: 'Meeting',
                start: new Date(y, m, d, 10, 30),
                allDay: false
            },
            {
                title: 'Lunch',
                start: new Date(y, m, d, 12, 0),
                end: new Date(y, m, d, 14, 0),
                allDay: false
            },
            {
                title: 'Birthday Party',
                start: new Date(y, m, d+1, 19, 0),
                end: new Date(y, m, d+1, 22, 30),
                allDay: false
            },
            {
                title: 'Click for Okendoken',
                start: new Date(y, m, 28),
                end: new Date(y, m, 29),
                url: 'http://okendoken.com/'
            }
        ],

        eventClick: function(event) {
            // opens events in a popup window
            if (event.url){
                window.open(event.url, 'gcalevent', 'width=700,height=600');
                return false
            } else {
                var $modal = $("#myModal"),
                    $modalLabel = $("#myModalLabel");
                $modalLabel.html(event.title);
                $modal.find(".modal-body p").html(function(){
                    if (event.allDay){
                        return "All day event"
                    } else {
                        return "Start At: <strong>" + event.start.getHours() + ":" + (event.start.getMinutes() == 0 ? "00" : event.start.getMinutes()) + "</strong></br>"
                            + (event.end == null ? "" : "End At: <strong>" + event.end.getHours() + ":" + (event.end.getMinutes() == 0 ? "00" : event.end.getMinutes()) + "</strong>")
                    }
                }());
                $modal.modal('show');
            }
        }

    });

    $("#calendar-switcher").find("label").click(function(){
        calendar.fullCalendar( 'changeView', $(this).find('input').val() )
    });
    $("#today").click(function(){
        calendar.fullCalendar('today');
    });
});