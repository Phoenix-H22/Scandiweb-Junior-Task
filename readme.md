# Scandiweb E-commerce Test

Full-stack e-commerce implementation for Scandiweb's Junior Developer position.

## Project Structure

This monorepo contains both the backend API and frontend client:

### `/backend` - Backend
- PHP 8.1 backend application
- GraphQL API
- MySQL database
- Custom OOP framework
- PSR compliant

### `/frontend` - Frontend
- React 18 application
- TypeScript
- Apollo Client
- TailwindCSS
- Vite build system

## Quick Start

### Backend Setup
```bash
cd api
composer install
# Configure your database
php -S localhost:8000 -t public
```

### Frontend Setup
```bash
cd client
npm install
npm run dev
```

## Development

### Backend Development
The API runs on `http://localhost:8000` and provides:
- GraphQL endpoint at `/graphql`
- Product management
- Order processing
- Category filtering

### Frontend Development
The client runs on `http://localhost:5173` and features:
- Product listing
- Cart management
- Category filtering
- Responsive design

## Deployment

### Backend Deployment
1. Upload `/backend` contents to your PHP host
2. Configure your web server (Apache/Nginx)
3. Set up environment variables
4. Import database schema

### Frontend Deployment
1. Build the frontend:
   ```bash
   cd client
   npm run build
   ```
2. Deploy the `dist` folder to static hosting

## Environment Variables

### Backend (config/app.php)
```env
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'scandiweb');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Frontend (.env)
```env
VITE_BASE_URL=http://your-api-url
```

## Testing

Run the automated tests at: http://165.227.98.170/

## Live Demo
- Frontend: https://scandiweb.phoenixtechs.tech/
- Backend API: https://scandiweb.phoenixtechs.tech/api