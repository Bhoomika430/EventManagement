# Event Registration Management System
A PHP + MySQL + HTML/CSS mini project for managing registrations for **Marriage**, **Birthday**, and **Sports Day** events.

## Features
- Public homepage with three event categories (Marriage, Birthday, Sports Day)
- Browse upcoming events per category with date, venue, and description
- Public registration form with validation (name, email, phone, age, gender, address, guest count, notes)
- Capacity limit per event (registration closes automatically once full)
- Admin panel (login protected):
  - Dashboard with stats (total events, upcoming events, total registrations)
  - Add new events
  - View all registrations for a given event
  - Delete events (cascades to their registrations)
- Clean, responsive, color-themed CSS (gold for Marriage, pink for Birthday, green for Sports Day)

## Tech Stack
- **Frontend:** HTML5, CSS3
- **Backend:** PHP (procedural, mysqli with prepared statements)
- **Database:** MySQL / MariaDB

## Folder Structure
```
event_registration_system/
├── database.sql              # DB schema + sample data
├── index.php                 # Homepage
├── events.php                # Event listing by type
├── register.php               # Registration form + handler
├── success.php                # Confirmation page
├── css/style.css
├── includes/
│   ├── db_connect.php         # DB credentials — EDIT THIS
│   ├── header.php
│   ├── footer.php
│   └── admin_guard.php
└── admin/
    ├── login.php
    ├── logout.php
    ├── dashboard.php
    ├── add_event.php
    ├── view_registrations.php
    └── delete_event.php
```

## Setup Instructions (XAMPP / WAMP / LAMP)

1. **Copy the project folder** into your server's web root:
   - XAMPP: `C:\xampp\htdocs\event_registration_system`
   - WAMP: `C:\wamp64\www\event_registration_system`
   - Linux/LAMP: `/var/www/html/event_registration_system`

2. **Create the database:**
   - Open phpMyAdmin (or the MySQL CLI).
   - Import `database.sql`. This creates the `event_registration_db` database, its tables (`events`, `registrations`, `admin`), and inserts 3 sample events plus a default admin account.

3. **Configure the DB connection:**
   - Open `includes/db_connect.php` and update `$DB_HOST`, `$DB_USER`, `$DB_PASS` if they differ from the defaults (`localhost` / `root` / empty password).

4. **Start Apache + MySQL**, then visit:
   ```
   http://localhost/event_registration_system/
   ```

5. **Admin login:**
   ```
   URL:      http://localhost/event_registration_system/admin/login.php
   Username: admin
   Password: admin123
   ```

## Notes / Possible Extensions
- Admin password is stored in plain text in `database.sql` to keep the mini-project simple. For production use, hash it with `password_hash()` and verify with `password_verify()`.
- You could extend this project with: email confirmation on registration, CSV export of registrations, edit-event functionality, image uploads for events, or a search/filter bar on the events page.
