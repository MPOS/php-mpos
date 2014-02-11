$(function(){

    //Backgrid part

    var Territory = Backbone.Model.extend({});

    var PageableTerritories = Backbone.PageableCollection.extend({
        model: Territory,
        url: "lib/examples/pageable-territories.json",
        state: {
            pageSize: 9
        },
        mode: "client" // page entirely on the client side
    });


    var pageableTerritories = new PageableTerritories(),
        initialTerritories = pageableTerritories;

    function createBackgrid(collection){
        var columns = [{
            name: "id", // The key of the model attribute
            label: "ID", // The name to display in the header
            editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
            // Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
            cell: Backgrid.IntegerCell.extend({
                orderSeparator: ''
            })
        }, {
            name: "name",
            label: "Name",
            // The cell type can be a reference of a Backgrid.Cell subclass, any Backgrid.Cell subclass instances like *id* above, or a string
            cell: "string" // This is converted to "StringCell" and a corresponding class in the Backgrid package namespace is looked up
        }, {
            name: "pop",
            label: "Population",
            cell: "integer" // An integer cell is a number cell that displays humanized integers
        }, {
            name: "url",
            label: "URL",
            cell: "uri" // Renders the value in an HTML <a> element
        }];
        if ($(window).width() < 768){
            //okendoken. removing URL-column for screens smaller than 768px
            columns.splice(3,1)
        }
        var pageableGrid = new Backgrid.Grid({
            columns: columns,
            collection: collection,
            footer: Backgrid.Extension.Paginator.extend({
                //okendoken. rewrite template to add pagination class to container
                template: _.template('<tr><td colspan="<%= colspan %>"><ul class="pagination"><% _.each(handles, function (handle) { %><li <% if (handle.className) { %>class="<%= handle.className %>"<% } %>><a href="#" <% if (handle.title) {%> title="<%= handle.title %>"<% } %>><%= handle.label %></a></li><% }); %></ul></td></tr>')
            }),
            className: 'table table-striped table-editable no-margin'
        });

        $("#table-dynamic").html(pageableGrid.render().$el);
    }

    var tableResize;

    $(window).resize(function(e) {
        clearTimeout(tableResize);
        tableResize = setTimeout(function(){
            createBackgrid(pageableTerritories);
        }, 200);
    });

    createBackgrid(pageableTerritories);

    $("#search").change(function(){

        var $that = $(this),
            filteredCollection = initialTerritories.fullCollection.filter(function(el){
            return ~el.get('name').toUpperCase().indexOf($that.val().toUpperCase());
        });
        createBackgrid(new PageableTerritories(filteredCollection, {
            state: {
                firstPage: 1,
                currentPage: 1
            }
        }));
    });


    pageableTerritories.fetch();

    //jQuery DataTables part

    /* Set the defaults for DataTables initialisation */
    $.extend( true, $.fn.dataTable.defaults, {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        }
    } );


    /* Default class modification */
    $.extend( $.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper form-inline"
    } );


    /* API method to get paging information */
    $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
    {
        return {
            "iStart":         oSettings._iDisplayStart,
            "iEnd":           oSettings.fnDisplayEnd(),
            "iLength":        oSettings._iDisplayLength,
            "iTotal":         oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage":          oSettings._iDisplayLength === -1 ?
                0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
            "iTotalPages":    oSettings._iDisplayLength === -1 ?
                0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
        };
    };


    /* Bootstrap style pagination control */
    $.extend( $.fn.dataTableExt.oPagination, {
        "bootstrap": {
            "fnInit": function( oSettings, nPaging, fnDraw ) {
                var oLang = oSettings.oLanguage.oPaginate;
                var fnClickHandler = function ( e ) {
                    e.preventDefault();
                    if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                        fnDraw( oSettings );
                    }
                };

                $(nPaging).append(
                    '<ul class="pagination">'+
                        '<li class="prev disabled"><a href="#">'+oLang.sPrevious+'</a></li>'+
                        '<li class="next disabled"><a href="#">'+oLang.sNext+'</a></li>'+
                        '</ul>'
                );
                var els = $('a', nPaging);
                $(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
                $(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
            },

            "fnUpdate": function ( oSettings, fnDraw ) {
                var iListLength = 5;
                var oPaging = oSettings.oInstance.fnPagingInfo();
                var an = oSettings.aanFeatures.p;
                var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

                if ( oPaging.iTotalPages < iListLength) {
                    iStart = 1;
                    iEnd = oPaging.iTotalPages;
                }
                else if ( oPaging.iPage <= iHalf ) {
                    iStart = 1;
                    iEnd = iListLength;
                } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
                    iStart = oPaging.iTotalPages - iListLength + 1;
                    iEnd = oPaging.iTotalPages;
                } else {
                    iStart = oPaging.iPage - iHalf + 1;
                    iEnd = iStart + iListLength - 1;
                }

                for ( i=0, ien=an.length ; i<ien ; i++ ) {
                    // Remove the middle elements
                    $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                    // Add the new list items and their event handlers
                    for ( j=iStart ; j<=iEnd ; j++ ) {
                        sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
                        $('<li '+sClass+'><a href="#">'+j+'</a></li>')
                            .insertBefore( $('li:last', an[i])[0] )
                            .bind('click', function (e) {
                                e.preventDefault();
                                oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
                                fnDraw( oSettings );
                            } );
                    }

                    // Add / remove disabled classes from the static elements
                    if ( oPaging.iPage === 0 ) {
                        $('li:first', an[i]).addClass('disabled');
                    } else {
                        $('li:first', an[i]).removeClass('disabled');
                    }

                    if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
                        $('li:last', an[i]).addClass('disabled');
                    } else {
                        $('li:last', an[i]).removeClass('disabled');
                    }
                }
            }
        }
    } );

    var unsortableColumns = [];
    $('#datatable-table').find('thead th').each(function(){
        if ($(this).hasClass( 'no-sort')){
            unsortableColumns.push({"bSortable": false});
        } else {
            unsortableColumns.push(null);
        }
    });

    $("#datatable-table").dataTable({
        "sDom": "<'row table-top-control'<'col-md-6 hidden-xs per-page-selector'l><'col-md-6'f>r>t<'row table-bottom-control'<'col-md-6'i><'col-md-6'p>>",
        "oLanguage": {
            "sLengthMenu": "_MENU_ &nbsp; records per page"
        },
        "aoColumns": unsortableColumns
    });

    $(".chzn-select, .dataTables_length select").select2({
        minimumResultsForSearch: 10
    });

});