$(document).ready(function() {

    // Initialize Coloris  Color Picker
    Coloris({
      
        // The bound input fields are wrapped in a div that adds a thumbnail showing the current color
        // and a button to open the color picker (for accessibility only). If you wish to keep your 
        // fields unaltered, set this to false, in which case you will lose the color thumbnail and 
        // the accessible button (not recommended).
        wrap: true,
      
        // Available themes: light, dark, polaroid, polaroid-dark.
        // More themes might be added in the future.
        theme: 'light',
      
        // The margin between the input fields and the color picker's dialog.
        margin: 2,
      
        // Set the prefered color string format:
        //  * hex: outputs #RRGGBB or #RRGGBBAA.
        //  * rgb: outputs rgb(R,G,B) or rgba(R,G,B,A).
        //  * mixed: defaults to #RRGGBB when alpha is 1, otherwise rgba(R,G,B,A).
        format: 'rgb',

        // Show an optional clear button and set its label
        clearButton: {
            show: false,
            label: 'Clear'
        },

      
        // An array of the desired color swatches to display. If omitted or the array is empty,
        // the color swatches will be disabled.
        swatches: [
            "#0D6EFD",
            "#6610F2",
            "#6F42C1",
            "#D63384",
            "#DC3545",
            "#FD7E14",
            "#FFC107",
            "#198754",
            "#20C997",
            "#F5F5F5",
            "#6C757D",
            "#212529",
        ]
    });

    // inject e.g. <small>s
    $("#prebook_days").text($(prebook_days_ahead).val() + ($(prebook_days_ahead).val() > 1 ? " days" : " day"))
    $("#cut_off_minutes").text($(booking_cut_off_time).val() + ($(booking_cut_off_time).val() > 1 ? " minutes" : " minute"))
    $("#precheckin_minutes").text($(precheckin_duration).val() + ($(precheckin_duration).val() > 1 ? " minutes" : " minute"))

    // General
    $("#name").keyup(function() {
        validateIsEmpty("#name")
        $(".company-name").text($("#name").val())
    })

    $("#domain").keyup(function() {
        validateIsEmpty(this)
        $(".company-login-domain").text($(this).val().toLowerCase())
        $(".company-display-domain").text($(this).val().toUpperCase())
    })

    $("#phone").keyup(function() {
        validateIsEmpty("#phone")
    })

    $("#address").keyup(function() {
        validateIsEmpty("#address")
    })

    $("#start_time, #end_time").change(function() {
        validateHours()
    })

    $("#courts_count").on("keyup change", function() {
        validateNumber("#courts_count", 1, null)
    })

    $("#prebook_days_ahead").on("keyup change", function() {
        validateNumber("#prebook_days_ahead", 1, null)
        var text = $(this).val() > 1 ? " days" : " day"
        $("#prebook_days").text($(this).val() + text)
    })

    $("#booking_cut_off_time").on("keyup change", function() {
        validateNumber("#booking_cut_off_time", 0, 30)
        var text = $(this).val() > 1 ? " minutes" : " minute"
        $("#cut_off_minutes").text($(this).val() + text)
    })

    $("#precheckin_duration").on("keyup change", function() {
        validateNumber("#precheckin_duration", 0, 30)
        var text = $(this).val() > 1 ? " minutes" : " minute"
        $("#precheckin_minutes").text($(this).val() + text)
    })

    $("#payment_grace_period").on("keyup change", function() {
        validateNumber("#payment_grace_period", 5, 15)
        var text = $(this).val() > 1 ? " minutes" : " minute"
        $("#payment_grace_period").text($(this).val() + text)
    })

    // FEATURES
    // Cancel Bookings Toggles
    $("#cancelBooking").change(function() {
        if ($("#cancelBooking").prop('checked')) {
            if ($("#adminRole").prop('checked')) {
                $("#adminCancelBooking").prop('disabled', false)
            }
        } else {
            $("#adminCancelBooking").prop('disabled', true)
            $("#adminCancelBooking").prop('checked', false)
        }
    })
    
    // Admin Toggles
    $("#adminRole").change(function() {
        if ($(this).prop('checked')) {
            $(".admin-toggles").prop('disabled', false)
        } else {
            $(".admin-toggles").prop('disabled', true)
            $(".admin-toggles").prop('checked', false)
        }
    })

    // UI Preview
    // call the color picker when color palette icon clicked
    $("#customer_navbar_toggle").click(function() {
        $("#customer_navbar_color").click()
    })

    $("#admin_navbar_toggle").click(function() {
        $("#admin_navbar_color").click()
    })

    $("#manager_navbar_toggle").click(function() {
        $("#manager_navbar_color").click()
    })

    // change color of header preview as user changes the color on the color picker
    $("#customer_navbar_color").on("input change", function() {
        var value = $(this).val()
        // var rgb = value.substring(4, value.length - 1)

        $("#customer-header").css('background-color', value)
    })
    
    $("#admin_navbar_color").on("input change", function() {
        var value = $(this).val()
        // var rgb = value.substring(4, value.length - 1)

        $("#admin-header").css('background-color', value)
    })

    $("#manager_navbar_color").on("input change", function() {
        var value = $(this).val()
        // var rgb = value.substring(4, value.length - 1)

        $("#manager-header").css('background-color', value)
    })

    // change text color (theme based off bootstrap navbar text class) when dark white round icon clicked
    $("#customer_navtext_toggle").click(function() {
        $("#customer-header").toggleClass("navbar-light navbar-dark")
        var textColor = $("#customer-header").hasClass("navbar-light") ? "navbar-light" : "navbar-dark"

        // inject text class into input field so server can save it
        $("#customer_navtext").val(textColor)
    })

    $("#admin_navtext_toggle").click(function() {
        $("#admin-header").toggleClass("navbar-light navbar-dark")
        $("#admin_logo").toggleClass("invert-logo")
        var textColor = $("#admin-header").hasClass("navbar-light") ? "navbar-light" : "navbar-dark"

        // inject text class into input field so server can save it
        $("#admin_navtext").val(textColor)
    })

    $("#manager_navtext_toggle").click(function() {
        $("#manager-header").toggleClass("navbar-light navbar-dark")
        $("#manager_logo").toggleClass("invert-logo")
        var textColor = $("#manager-header").hasClass("navbar-light") ? "navbar-light" : "navbar-dark"

        // inject text class into input field so server can save it
        $("#manager_navtext").val(textColor)
    })

    function validateHours() {

        var starttime = Number($("#start_time").val())
        var endtime = Number($("#end_time").val())

        if (endtime > starttime) {

            $("#save").prop("disabled", false)
            $("#start_time").addClass("is-valid")
            $("#start_time").removeClass("is-invalid")
            $("#end_time").addClass("is-valid")
            $("#end_time").removeClass("is-invalid")

        } else if (endtime < starttime) {

            $("#save").prop("disabled", true)
            $("#start_time").removeClass("is-invalid")
            $("#start_time").addClass("is-valid")
            $("#end_time").addClass("is-invalid")
            $("#end_time").removeClass("is-valid")

        } else if (endtime == starttime) {

            $("#save").prop("disabled", true)
            $("#start_time").addClass("is-invalid")
            $("#start_time").removeClass("is-valid")
            $("#end_time").addClass("is-invalid")
            $("#end_time").removeClass("is-valid")

        }
    }

    function validateIsEmpty(objectID) {
        if ($(objectID).val() == "") {
            $(objectID).addClass("is-invalid")
            $(objectID).removeClass("is-valid")
            $("#save").prop("disabled", true)
        } else {
            $(objectID).addClass("is-valid")
            $(objectID).removeClass("is-invalid")
            $("#save").prop("disabled", false)
        }
    }

    function validateNumber(objectID, min, max) {
        if ($(objectID).val() != "") {
            var number = $(objectID).val()

            if (max == null) {
                var maxValidate = true
                var minValidate = (number >= min) ? true : false
            } else {
                var maxValidate = (number <= max) ? true : false
                var minValidate = (number >= min) ? true : false
            }

            if (minValidate && maxValidate) {
                $(objectID).addClass("is-valid")
                $(objectID).removeClass("is-invalid")
                $("#save").prop("disabled", false)
            } else {
                $(objectID).addClass("is-invalid")
                $(objectID).removeClass("is-valid")
                $("#save").prop("disabled", true)
            }
        } else {
            $(objectID).addClass("is-invalid")
            $(objectID).removeClass("is-valid")
            $("#save").prop("disabled", true)
        }
    }
})
