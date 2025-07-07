document.addEventListener('DOMContentLoaded', function() {
    fetch('../backend/check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const userInfo = document.getElementById('userInfo');
                userInfo.innerHTML = `
                    <p><strong>Name:</strong> ${data.user.firstname} ${data.user.lastname}</p>
                    <p><strong>Email:</strong> ${data.user.email}</p>
                `;
                fetchBookings();
            } else {
                window.location.href = 'login.html';
            }
        });

    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('../backend/logout.php').then(() => window.location.href = 'login.html');
    });
});

function fetchBookings() {
    fetch('../backend/fetch_bookings.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const bookingsList = document.getElementById('bookingsList');
                bookingsList.innerHTML = createBookingsTable(data.bookings);
            } else {
                console.error(data.message);
            }
        });
}

function createBookingsTable(bookings) {
    if (bookings.length === 0) return '<p>You have no bookings.</p>';
    let table = '<table><tr><th>ID</th><th>Pickup</th><th>Dropoff</th><th>Time</th><th>Action</th></tr>';
    bookings.forEach(booking => {
        table += `<tr>
            <td>${booking.id}</td>
            <td>${booking.pickup_location}</td>
            <td>${booking.dropoff_location}</td>
            <td>${booking.booking_time}</td>
            <td><button onclick="cancelBooking(${booking.id})">Cancel</button></td>
        </tr>`;
    });
    table += '</table>';
    return table;
}

function cancelBooking(bookingId) {
    if (!confirm('Are you sure you want to cancel this booking?')) return;

    fetch('../backend/cancel_booking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchBookings();
            showToast('Booking cancelled.');
        } else {
            alert(data.message);
        }
    });
}
