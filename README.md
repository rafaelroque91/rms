# RMS Project

## Description
This project is a Resource Management System (RMS) built with PHP and Laravel. It provides a RESTful API for managing resources.

## Technologies
- PHP
- Laravel
- JavaScript
- NPM
- Composer

## Installation

1. Clone the repository:
    ```bash
    git clone git@github.com:rafaelroque91/rms.git
    ```

2. Navigate to the project directory:
    ```bash
    cd rms
    ```
3. Copy the `.env.example` file to `.env` and configure your environment variables:
   ```bash
   cp .env.example .env
   ```
4. run docker env
   ```bash
    docker-compose up -d
    ```

5. Connect to php container
7. Install the dependencies:
    ```bash
    composer install
    ```


5. Generate the application key:
    ```bash
    php artisan key:generate
    ```

6. Run the database migrations:
    ```bash
    php artisan migrate
    ```

7. Seed the database (optional):
    ```bash
    php artisan db:seed
    ```

## Running the Application

1. Start the development server:
    ```bash
    php artisan serve
    ```

2. Access the application at `http://localhost:59000`.

## Running Tests

To run the tests, use the following command:
```bash
php artisan test
```

## API Endpoints

### Authentication
- `GET /api/user` - Get authenticated user details (requires authentication)

### Resources
- `GET /api/resources` - List all resources
- `POST /api/resources` - Create a new resource
- `GET /api/resources/{id}` - Get a specific resource
- `PUT /api/resources/{id}` - Update a specific resource
- `DELETE /api/resources/{id}` - Delete a specific resource

## License
This project is licensed under the MIT License.
```
