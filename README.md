# Project Setup Guide

## Prerequisites

Before running the project, ensure you have the following installed:

- PHP 8.1 or later
- MySQL 8.0 or later
- A web server (like Apache or Nginx)

## Getting Started

### 1. Clone the Repository

Clone the project repository to your local machine:

```bash
git clone https://github.com/SergioRicardo96/Technical-test-backend
cd your-repository-folder
```

## 2. Set Up the Environment

In this step, you'll configure the environment for your PHP project, including setting up the `.env` file and creating the database.

### 2.1. Create the `.env` File


```bash
cp .env.example .env
```

### 2.2. Edit the .env File
```ini
APP_NAME=tasks

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=task_db
DB_USERNAME=root
DB_PASSWORD=
```
## 3. Database

### 3.1. Create the Database
```sql
   CREATE DATABASE wifri CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.2. Import the SQL File

Run the provided SQL file `tasks_db.sql` to set up the database schema and initial data: