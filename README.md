# HRM System (Laravel)

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About This Project

This project is a Human Resources Management (HRM) system built with the Laravel framework. It aims to streamline various HR processes, including recruitment, employee management, leave tracking, and performance evaluation.

## Features

Based on the project structure, the application includes functionalities for managing:

*   **Candidates:** Tracking applicants through the recruitment process (creating, viewing, editing, status updates).
*   **Interviews:** Scheduling and managing interviews with candidates.
*   **Evaluations:** Conducting evaluations (possibly for interviews or performance reviews) using predefined criteria.
*   **Driving Tests:** Managing driving tests for candidates or employees (relevant if the company involves driving roles).
*   **Offers:** Creating and managing job offers for successful candidates.
*   **Employees:** Maintaining employee records.
*   **Leave Management:** Handling leave types and employee leave requests.
*   **Absences:** Tracking employee absences.
*   **Vehicles:** Managing company vehicles (possibly assigned to employees).
*   **Documents:** Storing relevant documents for candidates or employees.
*   **Users & Roles:** Managing application users and their permissions (using spatie/laravel-permission).
*   **Reporting:** Generating reports (basic reporting structure exists).
*   **Calendar View:** Likely for visualizing schedules (interviews, leaves, etc.).

## Built With

*   [Laravel](https://laravel.com/) - The PHP framework used.
*   PHP
*   MySQL (or other configured database)
*   [Tailwind CSS](https://tailwindcss.com/) - Frontend styling.
*   [Vite](https://vitejs.dev/) - Frontend build tool.
*   [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction) - For roles and permissions.
*   [Chart.js](https://www.chartjs.org/) - For rendering charts (likely in reports/dashboard).
*   [DOMPDF](https://github.com/dompdf/dompdf) - For generating PDFs (e.g., offer letters, reports).

## Getting Started

### Prerequisites

*   PHP (Check `composer.json` for specific version requirements)
*   Composer
*   Node.js & npm (or yarn)
*   A database server (e.g., MySQL, PostgreSQL)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone <your-repository-url>
    cd <project-directory>
    ```
2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```
3.  **Install Node.js dependencies:**
    ```bash
    npm install
    npm run build
    ```
4.  **Copy environment file:**
    ```bash
    cp .env.example .env
    ```
5.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```
6.  **Configure your `.env` file:**
    *   Set database connection details (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    *   Configure mail settings if needed.
7.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```
8.  **(Optional) Seed the database:**
    *   The project includes seeders for Admin User, Evaluation Criteria, Leave Types, and Vehicles.
    ```bash
    php artisan db:seed
    # or php artisan db:seed --class=SpecificSeederClass
    ```

### Running the Application

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` (or the specified address) in your browser.

## Security Vulnerabilities

If you discover a security vulnerability within this project, please follow Laravel's guidelines and send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). This project builds upon it.
