
document.addEventListener('DOMContentLoaded', function () {
    var dropdownButton = document.getElementById("dropdownDefaultButton");
    var dropdownMenu = document.getElementById("dropdown");
    dropdownButton.addEventListener("click", function () {
        dropdownMenu.classList.toggle("hidden");
    });
    //
    var dropdownButton2 = document.getElementById("dropdownDefaultButton2");
    var dropdownMenu2 = document.getElementById("dropdown2");
    var userLink2 = document.getElementById("userLink2");

    userLink2.addEventListener("click", function (e) {
        e.preventDefault();
        dropdownMenu2.classList.toggle("hidden");
    });

});