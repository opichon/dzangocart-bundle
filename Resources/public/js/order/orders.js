!function( $ ) {
    $.fn.orders = function( method ) {

        var settings,
            table;

        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, this.orders.defaults, options );

                return this.each(function() {
                    var $this = $( this );

                    $( ".filters_keyup input" ).keyup(function(event) {
                        event.stopPropagation();
                        table.api().draw();
                    });

                    $( ".filters select", $this ).change(function() {
                        table.api().draw();
                    });

                    table = $( 'table.table', this ).dataTable( $.extend( true, {}, settings.datatables, {
                        initComplete: function( settings, json ) {
                            $( this ).show();
                        },
                        ajax: {
                            data: function( data ) {
                                $( ".filters input, .filters select", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).val()
                                });

                                $( ".filters :checkbox", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).is( ":checked" ) ? 1 : 0
                                });

                            }
                        },
                        stateLoadParams: function( settings, data ) {
                            $( ".filters input, .filters select", $this ).each(function() {
                                $( this ).val( data[ $( this ).attr( "name" ) ] );
                            });

                            $( ".filters :checkbox", $this ).each(function() {
                                $( this ).attr( "checked", data[ $( this ).attr( "name" ) ] );
                            });
                        },
                        stateSaveParams: function( settings, data ) {
                            $( ".filters input, .filters select", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).val();
                            });

                            $( ".filters :checkbox", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).is( ":checked" );
                            });
                        }
                    } ) );

                    moment.lang( dzangocart.locale );

                    $( ".filters .period", this ).daterangepicker(
                        $.extend( true, {}, settings.daterangepicker,
                            {
                                startDate: moment( $( ".filters .date_from", $this ).val(), "YYYY-MM-DD" ),
                                endDate: moment( $( ".filters .date_to", $this ).val(), "YYYY-MM-DD" )
                            }
                        ),
                        function( start, end ) {
                            $( ".filters .date_from", $this ).val( start.format( "YYYY-MM-DD" ) );
                            $( ".filters .date_to", $this ).val( end.format( "YYYY-MM-DD" ) );
                            table.api().draw();
                        }
                    ).data( "daterangepicker" ).updateInputText();

//                    var widget = $( "[name='filters[customer]']" );
//
//                    var customers = new Bloodhound({
//                        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
//                        queryTokenizer: Bloodhound.tokenizers.whitespace,
//                        remote: {
//                            url: settings.typeahead.remote.url,
//                            replace: function( url, uriEncodedQuery ) {
//                                return url.replace( "__query__", uriEncodedQuery );
//                            }
//                        }
//                    });
//
//                    customers.initialize();
//
//                    widget.typeahead( null, {
//                        name: "customer",
//                        displayKey: "value",
//                        source: customers.ttAdapter()
//                    })
//                    .on( "typeahead:selected", function( e, datum ) {
//                        $( "[name='filters[customer_id]']" ).val( datum.id );
//                            table.api().draw();
//                    });
//
//                    widget.keyup( function( ) {
//                        if ( $(this).val() === "" ) {
//                            $( "[name='filters[customer_id]']" ).val( "" );
//                            table.api().draw();
//                        }
//                    })
                });
            }
        };

        if ( methods[ method ] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
        }
        else if ( typeof method === "object" || !method)  {
            return methods.init.apply( this, arguments );
        }
        else {
            $.error( "Method " +  method + " does not exist in jQuery.orders." );
        }
    };

    $.fn.orders.defaults = {
        datatables: {
            autoWidth: false,
            columns: [
                { data: "check" },
                { data: "date" },
                { data: "order_id" },
                { data: "customer" },
                { data: "currency" },
                { data: "amount_excl" },
                { data: "tax_amount" },
                { data: "amount_incl" },
                { data: "amount_paid" },
                { data: "affiliate" },
                { data: "test" },
                { data: "actions" }
            ],
            columnDefs: [
                { visible: false, targets: [ 0 ] },
                { orderable: false, targets: [ 0, 11 ] },
                { className: "number", targets: [ 5, 6, 7, 8 ] },
                { className: "actions", targets: [ 11 ] }
            ],
            language: {
                url: "/bundles/dzangocart/datatables/" + dzangocart.locale + ".json"
            },
            order: [ [ 1, 'asc' ] ],
            orderable: true,
            orderCellsTop: true,
            paging: true,
            processing: true,
            searching: false,
            serverSide: true,
            stateSave: false,
            stripeClasses: []
        },
        daterangepicker: {
            locale: { cancelLabel: 'Clear' },
            maxDate: moment(),
            minDate: moment('2009-01-01'),
            startDate: moment()
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
    if ( typeof dzangocart != 'undefined' ) {
        $( ".dzangocart.orders" ).orders( dzangocart.orders || {} );
    }
});
