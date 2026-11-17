# Harvest Hub — Farm Management System

A web-based farm management system that helps farmers digitally track their fields, crops, scheduled tasks, inventory and finances from one dashboard, replacing manual paperwork and guesswork.

Built as an individual school project at Greenview College.

## Features
- **User authentication** — registration and login with hashed passwords
- **Dashboard** — live overview of fields, active crops, pending tasks and upcoming schedule
- **Fields** — track plots of land, their size, soil type and location
- **Crops** — log what's planted, where, planting and harvest dates, and growth status
- **Schedule** — plan tasks like irrigation, spraying and harvesting, linked to fields and dates
- **Inventory** — track seeds, fertilizer, equipment and feed
- **Finances** — log income and expenses, with automatic balance calculation

Each user only has access to their own data.

## Screenshots

### Homepage
<img width="1904" height="963" alt="image" src="https://github.com/user-attachments/assets/f459ec5a-f5db-4d19-bff4-3de0a7c97fff" />
<img width="1892" height="968" alt="image" src="https://github.com/user-attachments/assets/83504ddd-2941-4849-bdf9-370eeeb42d05" />
<img width="1891" height="979" alt="image" src="https://github.com/user-attachments/assets/5ab00e65-627d-48e6-b2b0-b3cb2841df07" />

### Farmer have to Login
<img width="1911" height="948" alt="image" src="https://github.com/user-attachments/assets/7043b70a-b15a-470e-828c-9fedaa38009e" />


### Dashboard
<img width="1916" height="973" alt="image" src="https://github.com/user-attachments/assets/8ed6160b-2774-4b58-9950-bba9bf4d15be" />


### Fields and crops
<img width="1908" height="970" alt="image" src="https://github.com/user-attachments/assets/6bae1a94-91f9-4096-b076-656e8b03f17e" />
<img width="1914" height="978" alt="image" src="https://github.com/user-attachments/assets/03a387fd-316d-4414-a597-cb2da06dcb9e" />


### Finances
<img width="1904" height="970" alt="image" src="https://github.com/user-attachments/assets/0dacfc2f-26d4-40f5-9421-28e26d3f29b0" />
<img width="1916" height="968" alt="image" src="https://github.com/user-attachments/assets/d81035ab-274f-4edb-b838-98db0d6c9517" />



## Tech Stack
- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL

## Database Structure
The system uses 6 linked tables:
- `users` — farmer accounts
- `fields` — land plots, linked to a user
- `crops` — linked to a field
- `tasks` — scheduled activities, linked to a user and optionally a field
- `inventory` — stock items, linked to a user
- `expenses` — income and expense entries, linked to a user

## Setup Instructions
1. Install [XAMPP](https://www.apachefriends.org/)
2. Clone this repository into your `htdocs` folder:
   `git clone https://github.com/breezy-x2/farm-management-system.git`
3. Start Apache and MySQL in the XAMPP Control Panel
4. Open http://localhost/phpmyadmin, create a database called farm_management, and import farm_database.sql from this repository.
5. Update `config/db.php` if your database credentials differ from the defaults
6. Visit `http://localhost/farm-management-system/`
7. Register a new account to get started

## Project Structure
```
farm-management-system/
├── config/        → database connection
├── imgs/          → homepage images
├── index.html     → homepage
├── login.php      → login page
├── register.php   → registration page
├── dashboard.php  → main dashboard
├── fields.php     → manage fields
├── crops.php      → manage crops
├── tasks.php      → schedule tasks
├── inventory.php  → manage stock
├── expenses.php   → track finances
├── dash.css       → styling for internal pages
└── style.css      → homepage styling
```

## Future Improvements
- Charts and reports for finances and crop yields
- Password reset
- Mobile-friendly layout

## Author
Brian Chival — Software Developer student, Greenview College, 2026

[GitHub](https://github.com/breezy-x2) · [LinkedIn](https://www.linkedin.com/in/brianchival)
