document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('../backend/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showToast('Login successful! Redirecting...');
                    if (result.is_admin) {
                        setTimeout(() => window.location.href = 'admin.html', 1500);
                    } else {
                        setTimeout(() => window.location.href = 'profile.html', 1500);
                    }
                } else {
                    showToast(result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.');
            });
        });
    }

    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            if (data.pickup_location === data.dropoff_location) {
                showToast('Pickup and Dropoff locations cannot be the same.');
                return;
            }

            fetch('../backend/process_booking.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showToast('Booking successful! Redirecting...');
                    setTimeout(() => window.location.href = 'confirmation.html', 1500);
                } else {
                    showToast(result.message);
                    if (result.message === 'User not logged in') {
                        setTimeout(() => window.location.href = 'login.html', 1500);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.');
            });
        });
    }
});
