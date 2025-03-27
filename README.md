# Task Management System

This is a simple Task Management System built using Laravel and jQuery. It supports user authentication using JWT (JSON Web Tokens) and provides CRUD operations for managing tasks with pagination and filtering.

## Features
- User authentication with JWT.
- Create, update, delete, and list tasks.
- Filter tasks by priority and status.
- Paginated task listing with customizable limits.

## Installation

1. Clone the repository:
```bash
  git clone https://github.com/your-repo.git
```

2. Navigate to the project directory:
```bash
  cd your-project-directory
```

3. Install dependencies using Composer and NPM:
```bash
  composer install
  npm install
```

4. Create a `.env` file and set the database connection details:
```bash
  cp .env.example .env
  php artisan key:generate
```

5. Create the database using MySQL:
```bash
  mysql -u root -p
  CREATE DATABASE mini_task_manager;
```

6. Run database migrations:
```bash
  php artisan migrate
```

7. Start the server:
```bash
  php artisan serve
```

## API Endpoints

### Authentication
- `POST /api/login` - Authenticate the user and return a JWT token.
- `POST /api/register` - Register a new user.

### Tasks
- `GET /api/tasks` - List tasks with optional filters and pagination.
- `POST /api/tasks` - Create a new task.
- `PUT /api/tasks/{id}` - Update a task.
- `DELETE /api/tasks/{id}` - Delete a task.

## JWT Authentication

- After a successful login, a JWT token will be returned.
- The token must be stored in `localStorage`.
- Subsequent API requests should include the token in the Authorization header using the Bearer scheme:
```bash
Authorization: Bearer <token>
```

## Frontend Usage

1. Login using valid credentials to get a JWT token.
2. The token is stored in `localStorage`.
3. Tasks are fetched using the `/api/tasks` endpoint with pagination support.
4. You can edit, delete, and filter tasks using the provided buttons and dropdowns.

## Pagination

- You can navigate through pages using the **Next** and **Previous** buttons.
- Use the limit dropdown to adjust the number of tasks displayed per page.

## Example Response
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 2,
      "title": "Task Example",
      "priority": "Medium",
      "status": "Completed",
      "due_date": "2025-03-29"
    }
  ],
  "last_page": 7,
  "total": 65
}
```
## Notes
- Ensure the `.env` file has the correct `JWT_SECRET` set using the following command:
```bash
php artisan jwt:secret
```
- JWT tokens are validated using middleware to protect the API routes.
- Logout by clearing the token from `localStorage`.

## License
This project is licensed under the MIT License.
