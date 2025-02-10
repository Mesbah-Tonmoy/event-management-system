# Event Management System

## Overview
The **Event Management System** is a web-based application that allows users to create, manage, and register for events. The system provides event organizers with tools to manage attendees, generate reports, and control event capacity. It is built using **pure PHP (no frameworks)** and **MySQL** for the database.

## Features
### Core Functionalities
- **Home Page**: Showcase the closest upcoming event with a countdown and all the upcoming events in a grid view.
- **User Authentication**: Secure login and registration with password hashing.
- **Event Management**: Authenticated users can create, update, view, and delete events.
- **Event Dashboard**: Paginated, sortable, and filterable event display.
- **Attendee Registration**: Allow users to register for events while enforcing capacity limits.
- **Event Reports**: Export event attendee lists in CSV format.
- **Search Functionality**: Authenticated users can search for events by title & location, and attendees by name & email.

### Other Features
- **Load More Button**: Loads more events when clicked.
- **AJAX-based Registration**: Enhances user experience by avoiding full-page reloads.
- **API Endpoint**: Provides JSON responses for fetching event details. 
                    (API path: `event-management-system/includes/api/event-details.php`)

## Installation Instructions
### 1. Server Requirements
Ensure you have the following installed:
- PHP (>=7.4 recommended)
- MySQL
- Apache/Nginx server with PHP support
- Composer (optional, if adding dependencies in the future)

### 2. Database Setup
1. Create a new MySQL database:
   ```sql
   CREATE DATABASE event_management;
   ```
2. Import the provided SQL dump:
   ```sh
   mysql -u your_user -p event_management < database.sql
   ```
3. Configure database connection:
   - Create `config.php` file in `includes/` directory
   - Add the following values:
     ```php
     <?php
     $db_host = 'localhost';
     $db_name = 'event_management';
     $db_user = 'root';
     $db_pass = '';
     ?>
     ```

### 3. Run the Application
1. Go to your server's root directory (`htdocs` or equivalent for Apache).
2. Create a folder named `evs` and place the project files in it.
3. Start your local server (`Apache + MySQL`).
4. Access the project in a browser:
   ```
   http://localhost/evs/
   ```

### 4. Running the Seeder on an Empty Database
If you have an empty database and need to populate it with initial data, follow these steps:
1. Open your web browser.
2. Navigate to the following URL:
   ```
   http://localhost/evs/includes/seeder/dbSeeder.php
   ```
3. This will execute the seeder script and populate the database with default users, events, and event categories.
4. Once completed, check the database to verify the seeded data.

## Usage Guide
### 1. Login/Register
- If the seeder has been run, it has created an admin and user credentials. Otherwise you need to register as a regular user.
- Navigate to the login page (`login.php`).
- Use the default credentials:
  ```
  #### Admin Credentials ####
  Username: admin@evs.com
  Password: admin_01

  #### User Credentials ####
  Username: user@evs.com
  Password: user_01
  ```
  *(To register as a new user, navigate to the registration page (`register.php`), fill the form, and submit.)*

### 2. Creating Events
- After logging in, go to the "Create Event" page.
- Enter event details (Image, Title, Description, Category, Date, Time, Location, Capacity) and submit.

### 3. Registering for Events
- Users can browse available events.
- Click on "Register" to sign up as an attendee (subject to capacity limits).

### 4. Exporting Attendee Lists
- Admins can download attendee reports in CSV format by going to the "Attendees" page.
- Attendees list can be downloaded for specific events as well.

## Security Considerations
- **Password Hashing**: Uses PHP's `password_hash()` for secure storage.
- **Prepared Statements**: Prevents SQL injection attacks.
- **XSS Protection**: Ensure output sanitization (`htmlspecialchars()` in views).
- **Session Handling**: Protects against session hijacking.

## Deployment (Optional)
If deploying on a live server:
- Secure `config.php` by moving it outside the web root.
- Enable HTTPS.
- Set file permissions properly (`chmod 644` for sensitive files).

## Contribution
Feel free to fork the repository and submit pull requests for improvements.

## License
This project is open-source under the **MIT License**.

---
**Author:** Md. Mesbah Hossain  
**GitHub Repository:** [\[Mesbah-Tonmoy\]](https://github.com/Mesbah-Tonmoy)

