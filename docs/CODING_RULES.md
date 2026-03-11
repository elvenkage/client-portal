# Coding Rules

Client Portal Agency System

This document defines coding standards and architectural rules for the project.

All AI-generated code must follow these rules.

---

# Framework

Backend: Laravel
Frontend: Livewire
Styling: TailwindCSS
JS: Alpine.js (minimal use)

The application must follow Laravel best practices.

---

# Architecture Pattern

Use the following architecture:

Controllers
↓
Services
↓
Models

Controllers should remain thin.

Business logic must be placed inside service classes.

---

# Folder Structure

app
├── Models
├── Http
│ ├── Controllers
│ └── Middleware
│
├── Livewire
│ ├── Dashboard
│ ├── Projects
│ ├── Milestones
│ ├── Tasks
│ └── Clients
│
├── Services
│ ├── ProjectService
│ ├── TaskService
│ ├── MilestoneService
│ ├── NotificationService
│ └── FileService

resources
├── views
│ ├── layouts
│ ├── dashboard
│ ├── projects
│ ├── milestones
│ └── tasks

---

# Controller Rules

Controllers must:

handle request validation
call service classes
return responses

Controllers must NOT:

contain complex business logic
perform database-heavy logic

Example:

Bad:

controller handles full project logic

Good:

controller calls ProjectService

---

# Service Layer

Service classes contain business logic.

Examples:

ProjectService
TaskService
MilestoneService

Responsibilities:

creating projects
assigning team members
updating project progress
calculating milestone completion
managing task workflow

---

# Model Rules

Use Eloquent models.

Models should define relationships.

Example:

Project hasMany Milestones

Milestone belongsTo Project

Task belongsTo Milestone

Subtask belongsTo Task

Avoid placing business logic inside models.

---

# Livewire Components

Use Livewire components for dynamic UI.

Component naming:

DashboardOverview
ProjectList
ProjectDetails
MilestoneList
TaskBoard
TaskList
CommentSection
NotificationDropdown

Each Livewire component should handle a single responsibility.

---

# Task Management UI

Task UI supports:

Kanban board
List view

Kanban columns:

To Do
In Progress
Review
Completed

Drag-and-drop should update task status.

---

# Review Workflow

Task review flow:

Team Member
↓
Project Manager Review
↓
Optional Client Review
↓
Auto Complete if deadline passes

TaskService must implement this workflow.

---

# Notifications

Notifications must be triggered for:

task completed
file uploaded
comment added
milestone completed

NotificationService handles this logic.

Notifications support:

in-app notifications
email notifications

---

# File Upload Rules

Server stores only image files.

Allowed:

png
jpg
jpeg
webp

Other file types must use external links.

Example:

Google Drive link
Dropbox link

FileService must validate file types.

---

# Security Rules

Use Laravel authentication.

Authentication methods:

email + password
optional two-factor authentication

Authorization must be role-based.

Roles:

super_admin
project_manager
team_member
client

Use middleware to protect routes.

---

# Database Rules

Use Eloquent ORM.

Avoid raw SQL unless necessary.

Always use eager loading when loading relationships.

Example:

Project::with('milestones.tasks')

This prevents N+1 query problems.

---

# Soft Deletes

The following models must use soft deletes:

User
Client
Project
Milestone
Task
Subtask
File
Comment

Activity logs should not use soft delete.

Notifications should not use soft delete.

---

# Pagination

Use pagination for large datasets.

Example:

projects list
tasks list
activity logs

Recommended page size:

10
20
50

---

# Search Rules

Global search must support:

projects
tasks
milestones
activity logs

Search must be optimized using indexed fields.

---

# Performance Guidelines

Use eager loading.

Use caching for frequently accessed queries.

Avoid loading large datasets without pagination.

Optimize dashboard queries.

---

# UI Guidelines

Use TailwindCSS for all styling.

Avoid custom CSS unless necessary.

Use reusable components.

Example:

cards
tables
buttons
modals

---

# Code Quality

Code must follow:

PSR-12 coding standard

Use clear naming conventions.

Example:

ProjectService
TaskController
MilestoneRepository

Avoid abbreviations.

---

# Testing

Future testing should include:

feature tests
unit tests

Example test areas:

task workflow
project progress calculation
permission checks

---

# Scalability

The system architecture should allow future features such as:

client billing
invoice generation
time tracking
API integration
mobile app integration
