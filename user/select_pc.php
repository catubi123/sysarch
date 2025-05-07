// ...existing code...

function checkPCAvailability(lab) {
    $.ajax({
        url: '../admin/get_pc_status.php',
        method: 'GET',
        data: { lab: lab },
        success: function(response) {
            if (response.success) {
                response.pcs.forEach(pc => {
                    const pcElement = document.querySelector(`[data-pc="${pc.number}"]`);
                    if (pcElement) {
                        if (!pc.is_active) {
                            pcElement.classList.add('unavailable');
                            pcElement.classList.remove('available');
                            pcElement.title = 'This PC is marked as unavailable by admin';
                            pcElement.style.pointerEvents = 'none';
                        }
                    }
                });

                // Also check current reservations
                if (response.reservations) {
                    response.reservations.forEach(reservation => {
                        const pcElement = document.querySelector(`[data-pc="${reservation.pc_number}"]`);
                        if (pcElement) {
                            pcElement.classList.add('unavailable');
                            pcElement.classList.remove('available');
                            pcElement.title = 'This PC is currently reserved';
                            pcElement.style.pointerEvents = 'none';
                        }
                    });
                }
            }
        }
    });
}

// Add periodic refresh
setInterval(function() {
    const selectedLab = document.getElementById('labSelect').value;
    if (selectedLab) {
        checkPCAvailability(selectedLab);
    }
}, 5000);

// ...existing code...
