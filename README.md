# P2P Transport System

## Project Description
The P2P Transport System is a web application designed to facilitate peer-to-peer transport bookings. Users can register, log in, book rides (either bus or train), and view their booking history. The system also includes an administrative interface for managing users and bookings.

## Features
*   **User Authentication:** Secure user registration and login.
*   **Session Management:** Maintains user sessions for a personalized experience.
*   **Booking System:**
    *   Select pickup and dropoff locations (based on Madaraka Express stations).
    *   Choose preferred date and time for booking.
    *   Select mode of transport (Bus or Train).
    *   Client-side validation for booking details.
*   **User Profile:** View personal information and booking history.
*   **Admin Dashboard:**
    *   View all registered users and their details.
    *   View all bookings.
    *   Search and filter users and bookings.
    *   Delete users and bookings.
*   **Responsive Design:** User-friendly interface across various devices.
*   **Toast Notifications:** Provides real-time feedback to users.
*   **Background Slideshow:** Dynamic background images for an engaging user experience.

## Technologies Used
*   **Frontend:**
    *   HTML5
    *   CSS3
    *   JavaScript (Vanilla JS)
*   **Backend:**
    *   PHP
    *   MySQL (Database)
*   **Web Server:**
    *   Apache (or any compatible PHP web server)

## Setup Instructions

### Prerequisites
Before you begin, ensure you have the following installed on your system:
*   **PHP** (version 7.4 or higher recommended)
*   **MySQL** (or MariaDB)
*   **Apache** (or Nginx, with PHP support configured)
*   A web browser (e.g., Google Chrome, Firefox)

### Database Setup

1.  **Create the Database:**
    Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or command line) and create a database named `p2p_transport`:
    ```sql
    CREATE DATABASE IF NOT EXISTS p2p_transport;
    USE p2p_transport;
    ```

2.  **Create Tables and Columns:**
    Execute the following SQL commands to create the `users` and `bookings` tables and add necessary columns:

    ```sql
    CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_admin TINYINT(1) DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS bookings (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        pickup_location VARCHAR(100) NOT NULL,
        dropoff_location VARCHAR(100) NOT NULL,
        booking_time DATETIME NOT NULL,
        mode_of_transport VARCHAR(50) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    -- Add is_admin column if it doesn't exist (for existing databases)
    ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) DEFAULT 0;
    -- Add mode_of_transport column if it doesn't exist (for existing databases)
    ALTER TABLE bookings ADD COLUMN IF NOT EXISTS mode_of_transport VARCHAR(50) NOT NULL;
    ```

3.  **Update Database Credentials:**
    Open `backend/db_connect.php` and update the `$password` variable with your MySQL `root` user's password:
    ```php
    function getDbConnection() {
        $servername = "localhost";
        $username = "root";
        $password = "YOUR_MYSQL_ROOT_PASSWORD"; // <--- UPDATE THIS
        $dbname = "p2p_transport";
        // ... rest of the code
    }

    // Also update in initializeDatabase() if you decide to re-enable it
    function initializeDatabase() {
        $servername = "localhost";
        $username = "root";
        $password = "YOUR_MYSQL_ROOT_PASSWORD"; // <--- UPDATE THIS
        $dbname = "p2p_transport";
        // ... rest of the code
    }
    ```
    *Note: The `initializeDatabase()` function is currently commented out/removed from `db_connect.php` to prevent automatic table creation. If you wish to use it, uncomment/re-add it and ensure the password is set.*

### Backend Setup

1.  **Place Backend Files:**
    Copy the contents of the `backend/` directory to your web server's document root (e.g., `/var/www/html/p2p-transport/backend/` for Apache).

### Frontend Setup

1.  **Place Frontend Files:**
    Copy the contents of the `frontend/` directory to your web server's document root (e.g., `/var/www/html/p2p-transport/frontend/` for Apache).

### Web Server Configuration (Apache Example)

Ensure your Apache configuration allows PHP execution and serves files from the correct directory. You might need to create a virtual host or place the project directly in the default document root.

## Usage

1.  **Access the Application:**
    Open your web browser and navigate to the URL where you deployed the `frontend` files (e.g., `http://localhost/p2p-transport/frontend/index.html`).

2.  **Register:**
    Click on "Register" to create a new user account. The very first user to register will automatically be assigned as an administrator.

3.  **Login:**
    Use your registered credentials to log in.

4.  **Book a Ride:**
    Navigate to the "Book a Ride" page, select your pickup/dropoff locations, date/time, and mode of transport.

5.  **View Profile:**
    Access your "Profile" page to see your user details and booking history.

## Admin Panel

The first user to register will automatically be granted administrator privileges.
*   **Access:** After logging in as an admin, you will be redirected to `admin.html`.
*   **Functionality:** From the admin dashboard, you can view and manage all users and bookings in the system.

## Contributing
Feel free to fork this repository, submit pull requests, or open issues for any bugs or feature requests.

## License
This project is open-source and available under the [MIT License](LICENSE.txt).

