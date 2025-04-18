# Jadco CMS Backend Implementation Steps

This document outlines the step-by-step implementation plan for the Jadco CMS backend and admin dashboard based on the requirements in BACKEND-PRD.md.

## Phase 1: Project Setup

### Step 1: Database Configuration
- Ensure MySQL database connection is properly configured in `.env` file
- Create the database `jadco-new` if it doesn't exist

### Step 2: Create Base Models
- Create/update models for User, Role, Page, Section, Service, and Media
- Define relationships between models

### Step 3: Create Migrations
- Create migrations for roles, pages, sections, services, media tables
- Create pivot tables for image_page and image_section relationships

## Phase 2: Service-Repository Pattern Implementation

### Step 4: Create Repository Interfaces
- Create repository interfaces for each model in `app/Repositories/Interfaces`
- Define standard CRUD methods in a base repository interface

### Step 5: Implement Repositories
- Create concrete repository classes in `app/Repositories`
- Implement repository methods for each model

### Step 6: Create Services
- Create service classes in `app/Services`
- Implement business logic in services using repositories

## Phase 3: Admin Dashboard Development

### Step 7: Create Admin Controllers
- Create controllers for Dashboard, Page, Service, Media, and User management
- Implement controller methods using services

### Step 8: Create Admin Routes
- Define admin routes in `routes/web.php`
- Implement middleware for role-based access control

### Step 9: Create Blade Views
- Create layout templates for admin dashboard
- Create views for each section (dashboard, pages, services, media, users)
- Implement forms for CRUD operations

## Phase 4: API Development

### Step 10: Create API Controllers
- Create API controllers for Page, Service, and Media
- Implement API endpoints as specified in the PRD

### Step 11: Define API Routes
- Define API routes in `routes/api.php`
- Implement Sanctum authentication for API routes

## Phase 5: Seeders and Data Population

### Step 12: Create Seeders
- Create RoleSeeder, AdminSeeder, PageSeeder, SectionSeeder, ServiceSeeder, and MediaSeeder
- Implement logic to extract content from existing frontend

### Step 13: Run Migrations and Seeders
- Run migrations to create database tables
- Run seeders to populate database with initial data

## Phase 6: Testing and Deployment

### Step 14: Test Admin Dashboard
- Test CRUD operations for all entities
- Test role-based access control

### Step 15: Test API Endpoints
- Test all API endpoints for correct functionality
- Verify data integrity and relationships

### Step 16: Deploy to Production
- Configure production environment
- Deploy application to production server

## Implementation Notes

### Folder Structure
Follow the folder structure outlined in the PRD:
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── PageController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── MediaController.php
│   │   │   └── UserController.php
│   ├── Requests/
│   │   ├── PageRequest.php
│   │   ├── ServiceRequest.php
│   │   ├── MediaRequest.php
│   │   └── UserRequest.php
├── Models/
│   ├── User.php
│   ├── Media.php
│   ├── Page.php
│   ├── Section.php
│   └── Service.php
├── Services/
│   ├── PageService.php
│   ├── SectionService.php
│   ├── ServiceService.php
│   ├── MediaService.php
│   └── UserService.php
└── Repositories/
    ├── PageRepository.php
    ├── SectionRepository.php
    ├── ServiceRepository.php
    ├── MediaRepository.php
    └── UserRepository.php
```

### Database Schema
Implement the database schema as specified in the PRD:
- users: id, name, email, password, role, timestamps
- roles: id, name
- pages: id, slug, title, content, timestamps
- sections: id, page_id, title, content, order, timestamps
- services: id, title, description, image_id, timestamps
- media: id, file_name, file_path, file_type, uploaded_by, timestamps
- image_page: image_id, page_id
- image_section: image_id, section_id

### Admin User
Create default admin user:
- Email: admin@example.com
- Password: 111

### User Roles
Implement three user roles:
- Admin: Full permissions
- Editor: Can edit content/media
- Viewer: Read-only access