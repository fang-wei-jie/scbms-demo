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

    // Operation
    $("#start_time").change(function() {
        // get new start and end time
        var starttime = Number($("#start_time").val())
        var endtime = Number($("#end_time").val())
        
        // empty and repopulate endtime field
        $("#end_time").empty()
        for (i = (starttime + 1); i <= 24; i++) {
            $("#end_time").append(new Option(i + ":00", i))
        }

        // reselect end time if new starttime is still earlier than new end time
        if (endtime > starttime) {
            $("#end_time").prop("value", endtime)
        }
    })

    // promopt changes not saved for operation hours
    $("#start_time, #end_time").change(function() {
        changesNotSaved()
    })
    
    $("#courts_count").on("keyup change", function() {
        validateNumber(this, 1, null)
    })

    $("#prebook_days_ahead").on("keyup change", function() {
        validateNumber(this, 1, null)
    })

    $("#booking_cut_off_time").on("keyup change", function() {
        validateNumber(this, 0, 30)
    })

    $("#precheckin_duration").on("keyup change", function() {
        validateNumber(this, 0, 30)
    })

    $("#payment_grace_period").on("keyup change", function() {
        validateNumber(this, 5, 15)
    })

    // Presence
    $("#name").keyup(function() {
        validateIsEmpty("#name")
        validateNotAsianChar("#name", "name", "Name")
        $(".company-name").text($("#name").val())
    })

    $("#domain").keyup(function() {
        validateIsEmpty(this)
        validateNotAsianChar("#domain", "domain", "Domain")
        $(".company-login-domain").text($(this).val().toLowerCase())
        $(".company-display-domain").text($(this).val().toUpperCase())
    })

    $("#phone").keyup(function() {
        validateIsEmpty("#phone")
    })

    $("#address").keyup(function() {
        validateIsEmpty("#address")
    })

    $("#registration").keyup(function() {
        validateNotAsianChar("#registration", "registration", "Registration")
    })

    $("#map_lat, #map_long").on("keyup change", function() {
        changesNotSaved()
    })

    // Features
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

    // any toggle changes, prompt changes not saved
    $(".form-check-input").change(function() {
        changesNotSaved()
    })

    // UI
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

    // prompt changes not saved when UI previews changed
    $("#customer_navbar_toggle, #admin_navbar_toggle, #manager_navbar_toggle, #customer_navtext_toggle, #admin_navtext_toggle, #manager_navtext_toggle").click(function() {
        changesNotSaved()
    })

    $("#customer_navbar_color, #admin_navbar_color, #manager_navbar_color, .form-control-file").on("input change", function() {
        changesNotSaved()
    })

    function validateIsEmpty(objectID) {
        if ($(objectID).val() == "") {
            makeFieldInvalid(objectID)
            saveBlocked()
        } else {
            makeFieldValid(objectID)
            changesNotSaved()
        }
    }

    function validateNotAsianChar(inputField, forLabel, originalLabel) {
        if($(inputField).val().match(/^[\w\s()-]*$/)) {
            makeFieldValid(inputField)
            $("label[for = '" + forLabel + "']").text(originalLabel)
            changesNotSaved()
        } else {
            makeFieldInvalid(inputField)
            $("label[for = '" + forLabel + "']").text("Only alphabet, numbers, and underscore")
            saveBlocked()
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
                makeFieldValid(objectID)
                changesNotSaved()
            } else {
                makeFieldInvalid(objectID)
                saveBlocked()
            }
        } else {
            makeFieldInvalid(objectID)
            saveBlocked()
        }
    }

    // display valid check message for input
    function makeFieldValid(objectID) {
        $(objectID).addClass("is-valid")
        $(objectID).removeClass("is-invalid")
    }

    // display invalid check message for input
    function makeFieldInvalid(objectID) {
        $(objectID).addClass("is-invalid")
        $(objectID).removeClass("is-valid")
    }

    // save button notify user why data cannot be saved
    function saveBlocked() {
        
        $("#save").prop("disabled", true)
        $("#save").text("Error Found")
        $("#save").removeClass("btn-outline-primary")
        $("#save").addClass("btn-primary")

    }

    // save button notify user that the data is not saved yet
    function changesNotSaved() {

        $("#save").prop("disabled", false)
        $("#save").text("Save Changes")
        $("#save").removeClass("btn-outline-primary")
        $("#save").addClass("btn-primary")

    }
})
