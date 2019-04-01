( function ( $ ) {

    /***************************************
     * Sidebar
     ***************************************/

    var controller = new slidebars();
    controller.init();

    $('.toggle-id-1').on('click', function (event) {
        // Stop default action and bubbling
        event.stopPropagation();
        event.preventDefault();

        // Toggle the Slidebar with id 'id-1'
        controller.toggle( 'id-1' );
    } );

    // close all active on content click.
    $('#sidebar-content-wrapper').on('click', function (event) {
        if (controller.getActiveSlidebar() ) {
            event.preventDefault();
            event.stopPropagation();
            controller.close();
        }
    });

    // close all active sidebars on window resize
    window.onresize = function(event) {
        if (controller.getActiveSlidebar() ) {
            event.preventDefault();
            event.stopPropagation();
            controller.close();
        }
    };

    /***************************************
     * Bulma
     ***************************************/

    // tabs
    $('body').find('.tabs li a').on('click', function(event)
    {
        event.preventDefault();

        let body = $('body');
        let lastActive = $(this).parents('ul').find('li.is-active');
        let nowActive = $(this).parents('li');

        lastActive.removeClass('is-active');
        nowActive.addClass('is-active');

        body.find(lastActive.data('content-id')).addClass('is-hidden');
        body.find(nowActive.data('content-id')).removeClass('is-hidden');
    });

    /***************************************
     * Galaxies
     ***************************************/

    function moveToGalaxy()
    {
        let galaxyNumber = $('#galaxy-value').val();
        if (!$.isNumeric(galaxyNumber)) {
            return;
        }

        let urlParts = window.location.href.split('/');

        urlParts.pop();

        document.location.href = urlParts.join('/') + '/' + galaxyNumber;
    }

    // head to galaxy with number in field galaxy-value
    $('#galaxy-submit').on('click', function (event)
    {
        moveToGalaxy();
    });

    // head to galaxy with number in field galaxy-value on enter
    $('#galaxy-value').on('keydown', function (event)
    {
        if (event.keyCode === 13) {
            moveToGalaxy();
        }
    });

    /***************************************
     * Countdown
     ****************************************/

    /**
     * @param {int} number
     * @param {int} length
     *
     * @returns {string}
     */
    function zerofill(number, length)
    {
        let pad = new Array(1 + length).join('0');
        return (pad + number).slice(-pad.length);
    }

    /**
     * @param {object} countdown
     * @param {string} countTo
     * @param {int} interval
     *
     * @return void
     */
    function countDownToTime(countdown, countTo, interval)
    {
        let now = new Date();
        let countToTime = new Date(countTo);

        let timeDifference = (countToTime - now);
        let secondsInADay = 60 * 60 * 1000 * 24;
        let secondsInAHour = 60 * 60 * 1000;

        //let days = Math.floor(timeDifference / (secondsInADay) * 1);
        let hours = Math.floor((timeDifference % (secondsInADay)) / (secondsInAHour) * 1);
        let mins = Math.floor(((timeDifference % (secondsInADay)) % (secondsInAHour)) / (60 * 1000) * 1);
        let secs = Math.floor((((timeDifference % (secondsInADay)) % (secondsInAHour)) % (60 * 1000)) / 1000 * 1);

        let newCountToDate = new Date(countTo);
        if (hours <= 0 && mins <= 0 && secs <= 0) {
            newCountToDate = new Date(newCountToDate);
            newCountToDate.setMinutes(newCountToDate.getMinutes() + interval);
        }

        countdown.text(zerofill(hours, 2) + ':' + zerofill(mins, 2) + ':' +  zerofill(secs, 2));

        let timeout = setInterval(function() {
            countDownToTime(countdown, newCountToDate.toString(), interval);
            clearInterval(timeout);
        }, 1000);
    }

    $.each($('body .countdown'), function(index, element)
    {
        countDownToTime($(this), $(this).data('countdown'), $(this).data('interval'));
    });

    /***************************************
     * Resort Fleet
     ****************************************/

    $('#fleet-from-all').on('change', function (event) {
        let fleetId = $(this).find('option:selected').data('fleet-id');
        $.each($(this).parents('form').find('.fleet-from'), function() {
            $(this).find('option[data-fleet-id="' + fleetId + '"]').prop('selected', true);
        });
    });

    $('#fleet-to-all').on('change', function (event) {
        let fleetId = $(this).find('option:selected').data('fleet-id');
        $.each($(this).parents('form').find('.fleet-to'), function() {
            $(this).find('option[data-fleet-id="' + fleetId + '"]').prop('selected', true);
        });
    });

    $('#fleet-unit-quantity-all').on('click', function (event) {
        $.each($(this).parents('form').find('.fleet-from'), function() {
            let quantity = $(this).find('option:selected').data('max-quantity');
            $(this).parents('tr').find('.fleet-unit-quantity').val(quantity);
        });
    });

    /***************************************
     * Mission
     ****************************************/

    function setMissionTicks(missionField)
    {
        let missionTicks = missionField.find('option:selected').data('max-ticks-mission');
        let missionTicksField = missionField.parents('form').find('.missionTicksField');

        missionTicksField.empty();
        for (let i = 1; i <= missionTicks; i++) {
            missionTicksField.append('<option value="' + i + '">' + i + '</option>');
        }
    }

    $.each($('select.missionField'), function()
    {
        setMissionTicks($(this));
        $(this).on('change', function (event) {
            setMissionTicks($(this));
        });
    });

    /***************************************
     * Build Forms
     ****************************************/

    $('.single-submit').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let button = $(this);
        button.addClass('is-loading');

        let action = button.data('action');
        let identifier = button.data('identifier');
        let value = 0;

        if (typeof button.data('submit-value') !== "undefined") {
            value = $('body').find(button.data('submit-value')).val();
        }

        $.post(action, { identifier: identifier, value: value }, function (result) {
            if (result.isSuccess === true) {
                window.location.href = result.message;
                return;
            }

            let indicator = button;
            if (typeof button.data('submit-value') !== "undefined") {
                indicator = $('body').find(button.data('submit-value'));
            }

            if (!indicator.hasClass('is-danger')) {
                indicator.addClass('is-danger');
            }

            button.removeClass('is-loading');
        }, 'json');
    });
})(jQuery);