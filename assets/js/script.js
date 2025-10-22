$(document).ready(function() {
    $('.alert').delay(3000).fadeOut('slow');

    $('[data-bs-toggle="tooltip"]').tooltip();
});

function showTeamModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('teamModal' + id));
    modal.show();
}
