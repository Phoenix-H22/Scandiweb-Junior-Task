# Scandiweb E-commerce Test Project

## Project Overview

A full-stack e-commerce application built with React (Frontend) and PHP (Backend), featuring product listing, category filtering, cart management, and order processing.

### Backend Stack
- PHP 8.1
- MySQL 5.6
- GraphQL
- Custom OOP Framework
- PSR Standards Compliant

### Frontend Stack
- React 18
- TypeScript
- Apollo Client
- TailwindCSS
- Vite

## Key Features

- 🛍️ Product Listing with Category Filtering
- 🛒 Cart Management with Overlay
- 📦 Product Details with Attribute Selection
- 💳 Order Processing
- 🎨 Responsive Design
- 🔄 Real-time Cart Updates

## Project Structure

### Backend (/api)
```
app/
├── Controllers/       # Request handlers
├── Core/             # Framework core components
│   ├── Database/     # Database connection handling
│   ├── Errors/       # Error handling
│   ├── Interfaces/   # Contracts for models
│   ├── Model/        # Abstract base classes
│   └── Router/       # URL routing
├── GraphQL/          # GraphQL schema and resolvers
└── Models/           # Data models
```

### Frontend (/src)
```
src/
├── api/              # GraphQL queries and Apollo setup
├── components/       # Reusable UI components
├── contexts/         # React context providers
├── hooks/           # Custom React hooks
├── pages/           # Page components
└── utils/           # Helper functions
```

## Installation

### Backend Setup
```bash
# Clone repository
git clone [repository-url]

# Install dependencies
composer install

# Configure database
# Import database.sql
# Copy .env.example to .env and configure

# Start PHP server
php -S localhost:8000 -t public
```

### Frontend Setup
```bash
# Install dependencies
npm install

# Start development server
npm run dev

# Build for production
npm run build
```

## API Endpoints

The backend provides a GraphQL API endpoint at `/api/graphql` with the following main queries and mutations:

### Queries
- `categories`: Get all product categories
- `products`: Get products with optional category filter
- `product`: Get detailed product information

### Mutations
- `createOrder`: Process a new order

## Testing

The project includes automated tests to ensure functionality meets requirements. Tests can be run at:
http://165.227.98.170/

## Design Implementation

The frontend implementation follows the provided Figma design:
[Figma Design Link](https://www.figma.com/file/Keu02BI0W7eQpWn0AvqnVK/Full-Stack-Test-Designs)

## Live Demo

- Frontend: [Your Frontend URL]
- Backend API: [Your Backend URL]

## Development Principles

- Clean Code Architecture
- SOLID Principles
- DRY (Don't Repeat Yourself)
- Separation of Concerns
- Type Safety (TypeScript)
- Responsive Design
- Performance Optimization

## Notes

This implementation focuses on demonstrating:
- OOP principles in PHP
- React component architecture
- State management
- GraphQL integration
- Clean code practices
- Type safety with TypeScript

## License

This project is part of a technical assessment and is not licensed for public use.

## Contact

[Your Name]
- Email: [Your Email]
- LinkedIn: [Your LinkedIn]