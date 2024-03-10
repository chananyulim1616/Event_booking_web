<script>
    
    var zoneSelect = document.getElementById('zone').value
    var ticketPrice = <?php echo json_encode($zones) ?>[document.getElementById('zone').value][0];
    var container = document.querySelectorAll('#rowname');
    var seats = document.querySelectorAll('.row .seat:not(.occupied)');
    if (document.getElementById('total_seat') != null) {
        var total_seat = document.getElementById('total_seat');
        var total = document.getElementById('total');
        total.innerText = total_seat.value * ticketPrice;
        document.getElementById('price_zone').innerHTML = ticketPrice
        total_seat.addEventListener('change', (e) => {
            total.innerText = e.target.value * ticketPrice;
        })
    }


    document.getElementById('ticket_type').innerHTML = zoneSelect

    var selectedValueRound = document.getElementById('round').value;
    document.getElementById('round_name').innerHTML = <?php echo json_encode($rounds) ?>[selectedValueRound]

    $(document).ready(function () {
        $('#zone').change(function () {
            var selectedValueZone = $(this).val();
            selectedValueRound = document.getElementById('round').value;
            updateSeatData(selectedValueZone, selectedValueRound);
        });
        $('#round').change(function () {
            var selectedValueZone = document.getElementById('zone').value;
            var selectedValueRound = $(this).val();
            updateSeatData(selectedValueZone, selectedValueRound);
        });
    });

    function updateSeatData(zoneSelect, roundSelect) {
        $.ajax({
            type: 'POST',
            url: 'create_table_booking.php',
            data: {
                zoneSelect: zoneSelect,
                roundSelect: roundSelect,
                eid: <?= $eid ?>
            },
            success: function (result) {
                $('div#result').replaceWith(result);
                var container = document.querySelectorAll('#rowname');
                var seats = document.querySelectorAll('.row .seat:not(.occupied)');
                total_seat = document.querySelector('input#total_seat');
                var total_seat = document.getElementById('total_seat');
                total = document.getElementById('total');

                zoneSelect = document.getElementById('zone').value
                var ticketPrice = <?php echo json_encode($zones) ?>[document.getElementById('zone').value][0];

                document.getElementById('ticket_type').innerHTML = zoneSelect
                document.getElementById('round_name').innerHTML = <?php echo json_encode($rounds) ?>[document.getElementById('round').value]
                document.getElementById('price_zone').innerHTML = ticketPrice
                
                if (!isNaN(total_seat.value * ticketPrice)) {
                    total.innerText = total_seat.value * ticketPrice;
                }
                total_seat.addEventListener('change', (e) => {
                    total.innerText = e.target.value * ticketPrice;
                })
                if (document.getElementById('seat-container') !== null) {
                    isEllipsisActive(document.getElementById('seat-container'))
                }
                function updateSelectedCount() {
                    try {
                        var selectedSeats = document.querySelectorAll('.rowcss .seat.selected');
                        var seatsIndex = [...selectedSeats].map((seat) => seat.id);
                        var selectedSeatsCount = selectedSeats.length;
                        total_seat.innerText = selectedSeatsCount;
                        total.innerText = selectedSeatsCount * ticketPrice;
                        if (seatsIndex.length <= 6) {
                            $.ajax({
                                type: 'POST',
                                url: 'get_NO_seat_selected.php',
                                data: {
                                    seatsIndex: JSON.stringify(seatsIndex)
                                },
                                success: function (result) {
                                    document.getElementById('NO_seat_selected').innerText = result;
                                },
                                error: function (xhr, textStatus, errorThrown) {
                                    console.log(textStatus);
                                }
                            });
                        }

                        return JSON.stringify(seatsIndex);
                    } catch (error) {
                        console.log(error);
                        return '[]'; // Return an empty array if an error occurs
                    }
                }
                container.forEach(element => {
                    element.addEventListener('click', (e) => {
                        if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
                            e.target.classList.toggle('selected')
                        }
                        var seat_list = JSON.parse(updateSelectedCount())

                        if (seat_list.length > 6) {
                            e.target.classList.toggle('selected')
                            updateSelectedCount()
                        }
                    });
                });
                $(document).ready(function () {
                    $('#payment').off('click');
                    $('#payment').click(function () {
                        event.preventDefault();
                        if (document.querySelector('input#total_seat') !== null) {
                            var total_t = document.getElementById('total_seat').value
                        } else {
                            var total_t = document.getElementById('total_seat').innerHTML
                        }
                        window.location.replace("payment.php?zoneSelect=" + document.getElementById('zone').value + "&total_seat=" +
                            total_t + "&seatID=" + updateSelectedCount() + "&round=" + document.getElementById('round').value + "&event_id="
                            + <?php echo $eid ?>);
                    });
                });
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error(textStatus); // Log any error messages
            }
        });
    }

    const plus = () => {
        var totalSeatElement = document.getElementById('total_seat');
        if (parseInt(totalSeatElement.value, 10) < totalSeatElement.max) {
            totalSeatElement.value = parseInt(totalSeatElement.value, 10) + 1;
            total.innerText = totalSeatElement.value * ticketPrice;
        }
    }

    const minus = () => {
        var totalSeatElement = document.getElementById('total_seat');
        if (parseInt(totalSeatElement.value, 10) > totalSeatElement.min) {
            totalSeatElement.value = parseInt(totalSeatElement.value, 10) - 1;
            total.innerText = totalSeatElement.value * ticketPrice;
        }
    }



    function updateSelectedCount() {
        try {
            var selectedSeats = document.querySelectorAll('.rowcss .seat.selected');
            var seatsIndex = [...selectedSeats].map((seat) => seat.id);
            var selectedSeatsCount = selectedSeats.length;

            total_seat.innerText = selectedSeatsCount;

            total.innerText = selectedSeatsCount * ticketPrice;

            $.ajax({
                type: 'POST',
                url: 'get_NO_seat_selected.php',
                data: {
                    seatsIndex: JSON.stringify(seatsIndex)
                },
                success: function (result) {

                    document.getElementById('NO_seat_selected').innerText = result;
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });

            return JSON.stringify(seatsIndex);
        } catch (error) {
            console.log(error);
            return '[]'; // Return an empty array if an error occurs
        }
    }

    $('#payment').click(function () {
        if (document.querySelector('input#total_seat') !== null) {
            var total_t = document.getElementById('total_seat').value
        } else {
            var total_t = document.getElementById('total_seat').innerHTML
        }
        window.location.replace("payment.php?zoneSelect=" + document.getElementById('zone').value + "&total_seat=" +
            total_t + "&seatID=" + updateSelectedCount() + "&round=" + document.getElementById('round').value + "&event_id="
            + <?php echo $eid ?>);
    });

    container.forEach(element => {
        element.addEventListener('click', (e) => {
            if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
                e.target.classList.toggle('selected')
            }
            var seat_list = JSON.parse(updateSelectedCount())
            if (seat_list.length > 6) {
                e.target.classList.toggle('selected')
                updateSelectedCount()
            }
            
        });
    });


    updateSelectedCount()

    const isEllipsisActive = (e) => {
        if (e.offsetWidth >= e.scrollWidth) {
            e.classList = 'containercss overflow-x-auto overflow-y-hidden p-2 justify-center'
        } else {
            e.classList = 'containercss overflow-x-auto overflow-y-hidden p-2'
        }
    }
    if (document.getElementById('seat-container') !== null) {
        isEllipsisActive(document.getElementById('seat-container'))
    }
</script>