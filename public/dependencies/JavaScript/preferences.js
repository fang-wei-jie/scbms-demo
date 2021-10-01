$(document).ready(function() {
    // run when the page is first loaded
    useRates()

    // General
    $("#name").keyup(function() {
        validateIsEmpty("#name")
        $(".company-name").text($("#name").val())
    })

    $("#domain").keyup(function() {
        validateIsEmpty("#domain")
        $(".company-domain").text($("#domain").val())
    })

    $("#phone").keyup(function() {
        validateIsEmpty("#phone")
    })

    $("#address").keyup(function() {
        validateIsEmpty("#address")
    })

    $("#start_time").change(function() {
        validateHours()
    })

    $("#end_time").change(function() {
        validateHours()
    })

    $("#courts_count").keyup(function() {
        validateIsEmpty("#courts_count")
    })

    // Rate Toggles
    $("#rate").change(function() {
        useRates()
    })

    $("#ratePerHour").keyup(function() {
        validateRate()
    })

    // UI Preview
    $("#customer_navbar").change(function() {
        customerHeaderPreview()
    })

    $("#customer_navtext").change(function() {
        customerHeaderPreview()
    })

    $("#customer_invert_logo").change(function() {
        customerHeaderPreview()
    })

    $("#admin_navbar").change(function() {
        adminHeaderPreview()
    })

    $("#admin_navtext").change(function() {
        adminHeaderPreview()
    })

    $("#manager_navbar").change(function() {
        managerHeaderPreview()
    })

    $("#manager_navtext").change(function() {
        managerHeaderPreview()
    })

    // enable tooltip everywhere
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    function validateRate() {
        if ($("#ratePerHour").val() >= 1 && $("#ratePerHour").val() < 100 && $.isNumeric($("#ratePerHour").val()) ) {
            $("#ratePerHour").removeClass('is-invalid')
            $("#ratePerHour").addClass('is-valid')
            $("#save").prop('disabled', false)
        } else {
            $("#ratePerHour").addClass('is-invalid')
            $("#ratePerHour").removeClass('is-valid')
            $("#save").prop('disabled', true)
        }
    }

    function customerHeaderPreview() {
        updateHeaderPreview("#customer-header", "#customer_navbar", "#customer_navtext", "#customer_logo", "#customer_invert_logo")
    }

    function adminHeaderPreview() {
        updateHeaderPreview("#admin-header", "#admin_navbar", "#admin_navtext", "#admin_logo", "#admin_invert_logo")
    }

    function managerHeaderPreview() {
        updateHeaderPreview("#manager-header", "#manager_navbar", "#manager_navtext", "#manager_logo", "#manager_invert_logo")
    }

    function useRates() {
        if ($("#rate").prop('checked')) {
            $("#rph").hide()
            $("#smallUseRates").text("Let customer enjoy different price rate for your specified conditions")
            $("#save").prop('disabled', false)
            $("#weekdayWeekend").prop('disabled', false)
            $("#adminRates").prop('disabled', false)
        } else {
            $("#rph").show()
            $("#smallUseRates").text("Use a single price rate for all bookings")
            $("#ratePerHour").focus()
            validateRate()
            $("#weekdayWeekend").prop('disabled', true)
            $("#adminRates").prop('disabled', true)
            $("#weekdayWeekend").prop('checked', false)
            $("#adminRates").prop('checked', false)
        }
    }

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

        } else if (endtime = starttime) {

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

    function updateHeaderPreview(header, navbar, navtext, logo, invert) {
        $(header).removeClass($(header).attr('class').split(' ').pop());
        $(header).removeClass($(header).attr('class').split(' ').pop());
        $(header).addClass($(navbar).val())
        $(header).addClass($(navtext).val())

        if (header.includes("customer")) {
            invert = $(invert).val()
        } else {
            invert = ($(navtext).val() == "navbar-light") ? "normal" : "invert"
        }
        invert == "normal" ? $(logo).removeClass('invert-logo') : $(logo).addClass('invert-logo')
    }
})
