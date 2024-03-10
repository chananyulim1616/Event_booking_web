const currentDate = new Date();

const year = currentDate.getFullYear();
const month = String(currentDate.getMonth() + 1).padStart(2, '0');
const day = String(currentDate.getDate()).padStart(2, '0');
const hours = String(currentDate.getHours()).padStart(2, '0'); 
const minutes = String(currentDate.getMinutes()).padStart(2, '0'); 

const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

const submitButton = document.getElementById('submit_data');
const bgsubmitButton = document.getElementById('bg_submit_data');

function checkRequiredInputs() {
    var requiredInputs = document.querySelectorAll('input[required]');
    let allInputsHaveValue = true;
    requiredInputs.forEach(input => {
        if (input.value.trim() === '') {
            allInputsHaveValue = false;
        }
    });

    submitButton.disabled = !allInputsHaveValue;
    if (!allInputsHaveValue) {
        bgsubmitButton.classList = 'w-fit rounded-xl py-2 px-6 bg-gray-600'
    } else {
        bgsubmitButton.classList = 'w-fit rounded-xl py-2 px-6 bg-red-600'
    }
    // Attach the event listener to each required input
    requiredInputs.forEach(input => {
        input.addEventListener('input', checkRequiredInputs);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    checkRequiredInputs();
});
function del_datetime(id) {
    document.getElementById("group_date_time" + id).remove();
    checkRequiredInputs()
}

var count_datetime = 1;
function addNewDatetimeField() {
    const container = document.getElementById('datetime-container2');
    const newDatetimeField = document.createElement('div');
    newDatetimeField.setAttribute('id', `group_date_time${count_datetime}`);
    newDatetimeField.classList = 'flex gap-4 items-center'
    newDatetimeField.innerHTML = `
        <input type="datetime-local" name="datetime" min="${formattedDateTime}" 
        class="border-solid border-2 rounded-lg p-2 text-lg w-full" required>
        <button class='text-zinc-500 hover:text-red-600' id ='${count_datetime}' onclick="del_datetime(id)"> 
        <i class="fi fi-br-trash pt-1 text-2xl cursor-pointer"></i></button>
    `
    container.appendChild(newDatetimeField);
    count_datetime += 1
    checkRequiredInputs()
}

const previewImage = (event) => {
    const imageFiles = event.target.files;
    const imageFilesLength = imageFiles.length;
    if (imageFilesLength > 0) {
        const fileReader = new FileReader();

        fileReader.onload = function () {
            const imageBase64Src = fileReader.result; // This will contain the base64-encoded image data

            const imagePreviewElement = document.querySelector("#previewimg");
            imagePreviewElement.src = '';
            imagePreviewElement.classList = 'w-full';
            imagePreviewElement.src = imageBase64Src;
            const backdropPreviewElement = document.querySelector("#backdrop");
            backdropPreviewElement.classList = ('flex w-full justify-center items-center backdrop-blur-md \
            bg-cover bg-center bg-[url("' + imageBase64Src + '")]');
        };
        fileReader.readAsDataURL(imageFiles[0]);
    }
};



function arrayRemove(arr, value) {
    return arr.filter(function (geeks) {
        return geeks != value;
    });
}

let check_isEllipsisActive = true
const isEllipsisActive = (e) => {

    if (e.offsetWidth >= e.scrollWidth) {
        e.classList = 'containercss overflow-x-auto overflow-y-hidden p-2 justify-center'
    } else {
        e.classList = 'containercss overflow-x-auto overflow-y-hidden p-2'
    }
    if (check_isEllipsisActive) {
        e = e.id.replace(/a/, '')
        check_isEllipsisActive = false
        document.querySelector('#button' + e).click()
        return true
    } else {
        check_isEllipsisActive = true
        return false
    }
}


document.addEventListener('click', function (event) {
    var target = event.target;
    // Check if the clicked element is a radio button with the specified name
    if (target.type === 'radio' && target.getAttribute('name').startsWith('type_ticket')) {
        var x = target // Get the value of data-x attribute
        select(x);
    }
});



function select(x) {
    if (x.getAttribute('value') == 'seat_opt') {
        let re = /type_ticket/;
        x = x.getAttribute('name').replace(re, '')
        var info = document.querySelector('#info_for_create' + x)
        text = `
        <div class="movie-container">
            <label for="">การเลือก</label>
            <div class="flex pb-2 pt-3 border-2 border-solid rounded-lg justify-around" >
                <div class="flex flex-col justify-center items-center">
                    <div class="seat"></div>
                    <p>ไม่เลือก</p>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="seat selected"></div>
                    <p>เลือก</p>
                </div>
            </div>
        </div>
    <div>
        <label for="rows">จำนวนแถว:</label>
        <input class="border-solid border-2 rounded-lg p-2 text-lg w-full" type="number" name="rows" id="rows${x}" required>
    </div>
    <div>
        <label for="n_seat">จำนวนที่นั่งต่อแถว:</label>
        <input class="border-solid border-2 rounded-lg p-2 text-lg w-full" type="number" name="n_seat" id="n_seat${x}" required>
    </div>

    <div class='w-fit rounded-xl py-2 px-6' style="background-color:#191D88;">
        <button class="lg:text-lg text-md text-white font-bold" type='button' id='button${x}' onclick="create('${x}')">สร้างแผนผังที่นั่ง</button>
    </div>`;
        info.innerHTML = text
        info = document.querySelector('#block' + x);
        newDiv = document.createElement('div')
        newDiv.setAttribute('id', `a${x}`)
        info.appendChild(newDiv)
        document.querySelector(`#type_ticket${x}_1`).checked = true;
    } else {
        let re = /type_ticket/;
        x = x.getAttribute('name').replace(re, '')
        var info = document.querySelector('#info_for_create' + x)
        info.innerHTML = `<div>
            <label for="rows">จำนวนบัตร:</label>
            <input class="border-solid border-2 rounded-lg p-2 text-lg w-full" type="number" name="total" \
            id="total${x}" required>
        </div>`;
        document.querySelector(`#type_ticket${x}_2`).checked = true;
        if (document.querySelector(`#a${x}`) !== null) {
            info = document.querySelector('#block' + x);
            info.removeChild(info.lastChild);
        }
    }
    checkRequiredInputs()
}
var arr_check_zone = [1]

function del_block(id) {
    arr_check_zone = arrayRemove(arr_check_zone, id)
    var block = document.getElementById('block' + id)
    block.remove()
    checkRequiredInputs()
}
let check_zone = 2
function create_zone() {
    let info = document.querySelector('#info')
    let button = document.querySelector('#button')
    var newDiv = document.createElement("div");
    newDiv.setAttribute('id', `block${check_zone}`)
    newDiv.classList = 'relative px-6 py-8 text-lg border-solid border-2 rounded-lg'
    newDiv.innerHTML = `
        <button type="button" id="${check_zone}" onclick="del_block(id)" class='absolute top-[-0.75rem] right-[-0.75rem] bg-white 
        text-zinc-500 hover:text-red-600'>
            <i class="fi fi-br-circle-xmark text-2xl "></i>
        </button>
    <form class='center m-0'>
    <div class='flex flex-col justify-center gap-4'>
        <div>
            <label for="zone">ชื่อบัตรเข้าชม :</label>
            <input class="border-solid border-2 rounded-lg p-2 text-lg w-full" type="text" name="zone" id="zone${check_zone}" required>
        </div>
        <div>
            <label for="price">ราคา:</label>
            <input class="border-solid border-2 rounded-lg p-2 text-lg w-full" type="number" name="price" id="price${check_zone}" required>
        </div>
        <div class='flex justify-around' >
            <div class='flex gap-2'>
                <input  name='type_ticket${check_zone}' id='type_ticket${check_zone}_1' value='seat_opt' type='radio'></input>
                <label>แบบที่นั่ง</label>
            </div>
            <div  class='flex gap-2'>
                <input name='type_ticket${check_zone}' id='type_ticket${check_zone}_2' type='radio' value='no_seat_opt'></input>
                <label>แบบไม่มีที่นั่ง</label>
            </div>
        </div>
        <div class='flex flex-col justify-center gap-4' id='info_for_create${check_zone}'>
        </div>
        
    </div>
    </form>`;
    arr_check_zone.push(check_zone)
    check_zone += 1;
    info.appendChild(newDiv)
    checkRequiredInputs()
}

function toggleAllSeats(rowId) {
    var checkbox = document.querySelector(`#checkbox-${rowId}`)
    var seats = document.querySelectorAll(`#row-${rowId} .seat:not(.occupied)`);
    if (checkbox.checked == true) {
        seats.forEach(seat => seat.classList.add('selected'));
    }
    else {
        seats.forEach(seat => seat.classList.remove('selected'));
    }
}

function create(id) {
    let n_rows = document.querySelector('#rows' + id).value;
    let n_seats = document.querySelector('#n_seat' + id).value;

    let text = '<div class="row2 left"><div class="screen3"></div><p class="text-base">ชื่อ</p>'
    for (let row = 0; row < n_rows; row++) {
        text += `<div class='flex gap-2  items-center' id='row3' ><input id='name_row-${id}' class='h-6 border-solid border-2 rounded-lg text-base 
        w-8 text-center' required></input></div>`;
    }
    text += '</div>'
    text += '</div><div class="row2"><div class="container2"><div class="screen"></div></div>';

    for (let row = 0; row < n_rows; row++) {
        text += `<div class='rowcss' name='row-${id}' id='row-${row}-${id}'>`;

        for (let seat = 1; seat <= n_seats; seat++) {
            text += `<div class="seat" name= '${row}' id='${seat}'></div>`;
        }

        text += `<div class='flex gap-2 h-6 items-center' id='row3'></div>`;
        text += '</div>';
    }
    text += '</div><div class="row2 right"><div class="screen2"></div>'
    for (let row = 0; row < n_rows; row++) {
        text += `<div class='flex gap-2 h-6 items-center' id='row3'><input type='checkbox' id='checkbox-${row}-${id}' 
        onclick='toggleAllSeats("${row}-${id}")'></input><p>ทั้งหมด</p></div>`;
    }
    text += '</div>'
    var cont = document.querySelector('#a' + id);
    cont.innerHTML = text;
    var checkActive = isEllipsisActive(cont)
    if (checkActive) {
        return 2
    }
    var seats = cont.querySelectorAll('.seat');
    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            toggleSeatSelection(seat);
        });
    });
}

function toggleSeatSelection(seat) {
    if (!seat.classList.contains('occupied')) {
        seat.classList.toggle('selected');
        updateSelectedCount_ID(check_zone);
        updateSelectedCount_Col(check_zone);
    }
}

var seatsID = {}
var seatCol = {}
var seatNameCol = {}

function updateSelectedCount_ID(id) {
    var selectedSeats = document.querySelectorAll(`[name="row-${id}"] .seat.selected`);
    if (selectedSeats === null) {
        seatsID[id] = []
    } else {
        seatsID[id] = [...selectedSeats].map(seat => seat.id);
    }
    return seatsID[id]
}
function updateNameCol(id) {
    var selectedSeats = document.querySelectorAll(`#name_row-${id}`);
    seatNameCol[id] = [...selectedSeats].map(seat => seat.value);
    return seatNameCol[id]
}
function updateSelectedCount_Col(id) {
    var selectedSeats = document.querySelectorAll(`[name="row-${id}"] .seat.selected`);
    if (selectedSeats === null) {
        seatCol[id] = []
    } else {
        seatCol[id] = [...selectedSeats].map(seat => updateNameCol(id)[parseInt(seat.getAttribute("name"))]);
    }
    return seatCol[id]
}

async function sendAjaxRequest(arr_check_zone, count,dt) {
    const checkZone = arr_check_zone[count];
    updateNameCol(checkZone);
    updateSelectedCount_ID(checkZone);
    updateSelectedCount_Col(checkZone);
    var round = dt.length;
    var zone = document.querySelector(`#zone${checkZone}`).value;
    var rows = document.querySelector(`#rows${checkZone}`);
    var n_seat = document.querySelector(`#n_seat${checkZone}`);
    var price = document.querySelector(`#price${checkZone}`).value;
    var total = document.querySelector(`#total${checkZone}`);
    if (n_seat == null) {
        n_seat = 0
    } else {
        n_seat = n_seat.value
    }
    if (total == null) {
        total = 0
    } else {
        total = total.value
    }
    if (rows == null) {
        rows = 0
    } else {
        rows = rows.value
    }

    var dataToSend = {
      seats_ID: JSON.stringify(updateSelectedCount_ID(checkZone)),
      seats_Col: JSON.stringify(updateSelectedCount_Col(checkZone)),
      zone: zone,
      n_rows: rows,
      n_seat: n_seat,
      price: price,
      round: round,
      total: total
    };
    try {
      const response = await $.ajax({
        type: 'POST',
        url: 'query_org.php',
        data: dataToSend,
      });
    } catch (error) {
      // Handle errors if the request fails
      console.error('Error sending data to PHP');
    }
  }

  async function processArray(arr_check_zone,dt) {
    for (let count = 0; count < arr_check_zone.length; count++) {
      await sendAjaxRequest(arr_check_zone, count,dt);
    }
    window.location.replace('index_org.php')
}

function enter() {
    var newdt = [];
    const dt = document.querySelectorAll("input[name='datetime']");
    dt.forEach(function (input) {
        newdt.push(input.value);
    });
    newdt = JSON.stringify(newdt)
    var dataToSend = {
        name: document.querySelector('#eventName').value,
        place: document.querySelector('#eventLocation').value,
        sale_date: document.querySelector('#eventOpenDate').value,
        type: document.querySelector('#eventType').value,
        des: document.querySelector('#inp_htmlcode').value,
        poster: document.querySelector('#previewimg').src,
        dthd: newdt
    };
    $.ajax({
        type: 'POST',
        url: 'query_org2.php',
        data: dataToSend,
        success: function (response) {
            processArray(arr_check_zone,dt);
        },
        error: function () {
            // Handle errors if the request fails
            console.error('Error sending data to PHP');
        }
    });

}

function edit() {
    var url_string = window.location;
    var url = new URL(url_string);
    var name = url.searchParams.get("eventid");
    var dataToSend = {
        name: document.querySelector('#eventName').value,
        place: document.querySelector('#eventLocation').value,
        sale_date: document.querySelector('#eventOpenDate').value,
        type: document.querySelector('#eventType').value,
        des: document.querySelector('#inp_htmlcode').value,
        poster: document.querySelector('#previewimg').src,
        eventid: name
    };
        $.ajax({
            type: 'POST',
            url: 'query_org3.php',
            data: dataToSend,
            success: function (response) {
                window.location.replace('index_org.php')
            },
            error: function () {
                // Handle errors if the request fails
                console.error('Error sending data to PHP');
            }
        });
}
    