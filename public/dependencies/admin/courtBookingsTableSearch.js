$(document).ready(function () {
    
    $('#today-bookings-search').keyup(function(){
        // Search Text
        var search = $(this).val()
    
        // Hide all table tbody rows
        $('table#today-bookings tbody tr').hide()
    
        // Count total search result
        var len = $('table#today-bookings tbody tr:not(.today-notfound) td:contains("'+search+'")').length
        
        if(len > 0){
            // Searching text in columns and show match row
            $('table#today-bookings tbody tr:not(.today-notfound) td:contains("'+search+'")').each(function(){
                $(this).closest('tr').show()
            })
        } else {
            $('.today-notfound').show()
        }
        
    });
    
    $('#future-bookings-search').keyup(function(){
        // Search Text
        var search = $(this).val()
        
        // Hide all table tbody rows
        $('table#future-bookings tbody tr').hide()
        
        // Count total search result
        var len = $('table#future-bookings tbody tr:not(.future-notfound) td:contains("'+search+'")').length

        if(len > 0){
            // Searching text in columns and show match row
            $('table#future-bookings tbody tr:not(.future-notfound) td:contains("'+search+'")').each(function(){
                $(this).closest('tr').show()
            })
        } else {
            $('.future-notfound').show()
        }
        
    });
    
    $('#previous-bookings-search').keyup(function(){
        // Search Text
        var search = $(this).val()

        // Hide all table tbody rows
        $('table#previous-bookings tbody tr').hide()

        // Count total search result
        var len = $('table#previous-bookings tbody tr:not(.previous-notfound) td:contains("'+search+'")').length
        
        if(len > 0){
            // Searching text in columns and show match row
            $('table#previous-bookings tbody tr:not(.previous-notfound) td:contains("'+search+'")').each(function(){
                $(this).closest('tr').show()
            })
        } else {
            $('.previous-notfound').show()
        }

    });

    
    $('#all-bookings-search').keyup(function(){
        // Search Text
        var search = $(this).val()

        // Hide all table tbody rows
        $('table#all-bookings tbody tr').hide()

        // Count total search result
        var len = $('table#all-bookings tbody tr:not(.all-notfound) td:contains("'+search+'")').length

        if(len > 0){
            // Searching text in columns and show match row
            $('table#all-bookings tbody tr:not(.all-notfound) td:contains("'+search+'")').each(function(){
                $(this).closest('tr').show()
            })
        } else {
            $('.all-notfound').show()
        }

    });

    // Case-insensitive searching (Note - remove the below script for Case sensitive search )
    $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0
        }
    })
})