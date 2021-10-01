$(document).ready(function() {
    // run when the page is first loaded
    useRates()

    // General
    $("#name").keyup(function() {
        validationClassAddRemove("#name")
    })

    $("#domain").keyup(function() {
        validationClassAddRemove("#domain")
    })

    $("#phone").keyup(function() {
        validationClassAddRemove("#phone")
    })

    $("#address").keyup(function() {
        validationClassAddRemove("#address")
    })

    $("#start_time").change(function() {
        validateHours()
    })

    $("#end_time").change(function() {
        validateHours()
    })

    $("#courts_count").keyup(function() {
        validationClassAddRemove("#courts_count")
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
        $("#customer-header").removeClass($('#customer-header').attr('class').split(' ').pop());
        $("#customer-header").removeClass($('#customer-header').attr('class').split(' ').pop());
        $("#customer-header").addClass($("#customer_navbar").val())
        $("#customer-header").addClass($("#customer_navtext").val())
        $("#customer_invert_logo").val() == "normal" ? $("#customer-logo").removeClass('invert-logo') : $("#customer-logo").addClass('invert-logo')
    }

    function adminHeaderPreview() {
        $("#admin-header").removeClass($('#admin-header').attr('class').split(' ').pop());
        $("#admin-header").removeClass($('#admin-header').attr('class').split(' ').pop());
        $("#admin-header").addClass($("#admin_navbar").val())
        $("#admin-header").addClass($("#admin_navtext").val())
        $("#admin_navtext").val() == "navbar-light" ? $("#admin-logo").removeClass('invert-logo') : $("#admin-logo").addClass('invert-logo')
    }

    function managerHeaderPreview() {
        $("#manager-header").removeClass($('#manager-header').attr('class').split(' ').pop());
        $("#manager-header").removeClass($('#manager-header').attr('class').split(' ').pop());
        $("#manager-header").addClass($("#manager_navbar").val())
        $("#manager-header").addClass($("#manager_navtext").val())
        $("#manager_navtext").val() == "navbar-light" ? $("#manager-logo").removeClass('invert-logo') : $("#manager-logo").addClass('invert-logo')
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

    function validationClassAddRemove(objectID) {
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
})
