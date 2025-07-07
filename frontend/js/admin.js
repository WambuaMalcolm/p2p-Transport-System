document.addEventListener('DOMContentLoaded', function () {
    fetchUsers();
    fetchBookings();

    const userSearchInput = document.getElementById('userSearchInput');
    userSearchInput.addEventListener('keyup', () => filterTable('usersTable', userSearchInput.value));

    const bookingSearchInput = document.getElementById('bookingSearchInput');
    bookingSearchInput.addEventListener('keyup', () => filterTable('bookingsTable', bookingSearchInput.value));

    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('../backend/logout.php').then(() => window.location.href = 'login.html');
    });
});

function fetchUsers() {
    fetch('../backend/fetch_all_users.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const usersList = document.getElementById('usersList');
                usersList.innerHTML = createUsersTable(data.users);
            } else {
                console.error(data.message);
            }
        });
}

function fetchBookings() {
    fetch('../backend/fetch_all_bookings.php')
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

function createUsersTable(users) {
    let table = '<table id="usersTable"><tr><th>ID</th><th>Name</th><th>Email</th><th>Registered</th><th>Action</th></tr>';
    users.forEach(user => {
        table += `<tr>
            <td>${user.id}</td>
            <td>${user.firstname} ${user.lastname}</td>
            <td>${user.email}</td>
            <td>${user.reg_date}</td>
            <td><button onclick="deleteUser(${user.id})">Delete</button></td>
        </tr>`;
    });
    table += '</table>';
    return table;
}

function createBookingsTable(bookings) {
    let table = '<table id="bookingsTable"><tr><th>ID</th><th>User</th><th>Pickup</th><th>Dropoff</th><th>Time</th><th>Action</th></tr>';
    bookings.forEach(booking => {
        table += `<tr>
            <td>${booking.id}</td>
            <td>${booking.firstname} ${booking.lastname}</td>
            <td>${booking.pickup_location}</td>
            <td>${booking.dropoff_location}</td>
            <td>${booking.booking_time}</td>
            <td><button onclick="deleteBooking(${booking.id})">Delete</button></td>
        </tr>`;
    });
    table += '</table>';
    return table;
}

function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user and all their bookings?')) return;

    fetch('../backend/delete_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchUsers();
            fetchBookings(); // Refresh bookings as well
        } else {
            alert(data.message);
        }
    });
}

function deleteBooking(bookingId) {
    if (!confirm('Are you sure you want to delete this booking?')) return;

    fetch('../backend/delete_booking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchBookings();
        } else {
            alert(data.message);
        }
    });
}

function filterTable(tableId, query) {
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    query = query.toLowerCase();

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header row
        const cells = rows[i].getElementsByTagName('td');
        let match = false;
        for (let j = 0; j < cells.length - 1; j++) { // Exclude action cell
            if (cells[j].innerText.toLowerCase().includes(query)) {
                match = true;
                break;
            }
        }
        rows[i].style.display = match ? '' : 'none';
    }
}