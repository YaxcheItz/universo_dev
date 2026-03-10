# UniversoDev 🚀

> A comprehensive platform for organizing, managing, and participating in programming tournaments and hackathons. 

[![Live Demo](https://img.shields.io/badge/Live_Demo-universo--dev.onrender.com-success?style=for-the-badge&logo=render)](https://universo-dev.onrender.com/login)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)

UniversoDev is a full-stack web application designed to bridge the gap between developers and competitive programming events. It provides a robust ecosystem for administrators to host tournaments, judges to evaluate submissions, and participants to showcase their skills, form teams, and earn recognition.

## ✨ Key Features

*   **Role-Based Access Control (RBAC):** Secure authentication and authorization with dedicated dashboards for Administrators, Judges, and Standard Users.
*   **Tournament Management:** End-to-end lifecycle management of programming tournaments (Open, In Progress, Upcoming, In Evaluation, Finished) with category filtering.
*   **Team & Project Collaboration:** Users can create teams, manage member requests, and submit projects (with file attachments) to specific tournaments.
*   **Evaluation System:** Dedicated interfaces for assigned judges to review projects, assign scores based on specific criteria, and provide feedback.
*   **Gamification & Profiles:** User profiles track completed projects, tournaments won, and accumulate total points. Includes skill tagging and social links (LinkedIn, Portfolio).
*   **Automated Certificate Generation:** Dynamically generates downloadable PDF certificates for participants and winners using `dompdf`.
*   **Real-time Notifications:** Event-driven architecture to notify users about tournament status changes, team invitations, and evaluation results.
*   **Comprehensive Reporting:** Admin tools for generating reports on users, tournaments, and evaluations.

## 🛠️ Tech Stack

**Backend:**
*   [Laravel](https://laravel.com/) (PHP) - Core Framework
*   Eloquent ORM - Database Interactions
*   Laravel Sanctum - Authentication
*   Barryvdh/Laravel-DomPDF - PDF Generation
*   PostgreSQL / SQLite - Relational Database

**Frontend:**
*   [Tailwind CSS](https://tailwindcss.com/) - Utility-first styling for a modern, responsive UI
*   Blade Templating Engine - Server-side rendering
*   Vite - Frontend build tool and asset bundler

**Infrastructure & Deployment:**
*   Docker - Containerization
*   [Render](https://render.com/) - Cloud Application Hosting

## 🚀 Live Demo

You can access the live application here: **[UniversoDev Live on Render](https://universo-dev.onrender.com/login)**

*(Note: The first load might take a few seconds as the instance wakes up from a dormant state on Render's free tier).*

**Demo Credentials:**
*   **Admin:** `admi@gmail.com` / `password`
*   **Judge:** `juez1@gmail.com` / `password`
*   **User:** `prueba1@gmail.com` / `password`

## 💻 Local Installation

To run this project locally, follow these steps:

### Prerequisites
*   PHP >= 8.2
*   Composer
*   Node.js & npm
*   Git

### Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/YaxcheItz/universo_dev.git
   cd universo_dev
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies & compile assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Configuration:**
   Copy the example `.env` file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database settings in the `.env` file (SQLite is easiest for local testing).*

5. **Run Migrations and Seeders:**
   This will set up your database schema and create the default test users.
   ```bash
   php artisan migrate --seed
   ```

6. **Create Storage Link:**
   Required for user avatars and project file uploads:
   ```bash
   php artisan storage:link
   ```

7. **Start the local development server:**
   ```bash
   php artisan serve
   ```
   *The application will be available at `http://localhost:8000`.*

## 📂 Project Structure Highlights

*   `app/Http/Controllers`: Contains the business logic for Admin, Tournaments, Teams, Projects, and Evaluations.
*   `app/Models`: Eloquent models defining relationships (e.g., User has many Teams, Tournament has many Projects).
*   `resources/views`: Blade templates organized by domain (`torneos`, `equipos`, `evaluaciones`, `admin`, `components`).
*   `app/Notifications` & `app/Events`: Handles asynchronous user notifications and system events.

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/YaxcheItz/universo_dev/issues).

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed with ❤️ by [YaxcheItz](https://github.com/YaxcheItz).*
