document.addEventListener('DOMContentLoaded', function () {
    let loader = document.getElementById('loader');

    if (loader) {
        $(loader).fadeOut();
    }
});

function limitInput(input) {
    if (input.value.length > input.maxLength) input.value = input.value.slice(0, input.maxLength);
}

function openSweetAlert(id) {
    $(`#${id}`).fadeIn();
}

function closeSweetAlert(id) {
    $(`#${id}`).fadeOut();
}
