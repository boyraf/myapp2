MyApp2 – Laravel SACCO Demo Application
Project Overview

MyApp2 is a Laravel-based web application developed as a demo SACCO (Savings and Credit Cooperative Organization) system. The project was created to showcase my skills and knowledge of Laravel, including backend development, MVC architecture, database interaction, and view management.

The system simulates key SACCO workflows such as:

Dashboard access for members and admins

Loan application process for members

Admin-side views for managing members, loans, and dashboard insights

⚠️ Note: While admin and dashboard views are functional, the application currently does not integrate real payment services (MPESA, PayPal, Visa) — all financial transactions are simulated for demonstration purposes.

The goal is demonstrative: to highlight Laravel development skills rather than operate as a full production SACCO system.

Key Features
Member-side

Dashboard view (basic overview of account and loans)

Loan application form

Admin-side

Manage members

View loan applications

Dashboard with summary statistics

Technical Highlights

Backend: Laravel 10, PHP 8.x

Database: Microsoft SQL Server (SQLSRV)

Frontend: Blade templating engine, basic HTML/CSS

Architecture: MVC (Models, Views, Controllers)

Database Interaction: Eloquent ORM

Validation: Laravel request validation for forms

Environment Management: Fully configurable .env for database and optional services

Project Purpose

This project demonstrates my ability to:

Develop a full-stack Laravel application from scratch

Apply MVC principles and organize controllers, models, and views

Implement CRUD operations and data validation using Eloquent

Configure Laravel to work with SQL Server (SSMS) and environment-based settings

Prepare an application structure that can easily integrate real payment systems in the future

This is primarily a skills demonstration project for the company’s SACCO domain.

Limitations

Payment integrations (MPESA, PayPal, Visa) are not implemented; monetary actions are simulated

Member views beyond the dashboard and loan application are mostly placeholders

Security features (authentication/authorization) are basic; not production-ready

Styling/UI is minimal; focus is on backend functionality

Installation & Setup

Follow these steps to run the project locally:

1. Clone the repository
git clone https://github.com/boyraf/myapp2.git
cd myapp2

2. Install PHP dependencies
composer install

3. Install frontend dependencies (if applicable)
npm install
npm run dev

4. Configure environment variables

Copy .env.example to .env

cp .env.example .env


Update database settings for SQL Server (or another database):

DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=myapp2
DB_USERNAME=sa
DB_PASSWORD=yourpassword


Optional: Adjust other environment variables such as APP_URL, MAIL, etc.

5. Generate Laravel application key
php artisan key:generate

6. Run database migrations
php artisan migrate


Optional: Run seeders if provided

7. Serve the application
php artisan serve


Visit http://127.0.0.1:8000 in your browser

8. Optional Testing

If you have tests included:

php artisan test

Recommended Usage

Login as admin to explore all management dashboards

Login as member to access the dashboard and simulate loan application

Use this project to review Laravel code structure, database interactions, and MVC implementation

Future Enhancements (Optional)

Integrate real payment gateways (MPESA, PayPal, Visa)

Complete member views (statements, savings tracking, loan history)

Add role-based authentication and authorization

Improve frontend styling and UX

License

This project is for educational and internship demonstration purposes. Not for commercial use.