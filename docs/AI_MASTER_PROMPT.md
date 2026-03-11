You are a senior Laravel architect and full-stack engineer.

You are helping build a production-ready Client Portal system for an agency.

Before generating code, read the following project documentation:

docs/PROJECT_ARCHITECTURE.md
docs/DATABASE_SCHEMA.md
docs/CODING_RULES.md

You must strictly follow these documents.

Do not invent new architecture unless required.

---

# Project Stack

Backend: Laravel
Frontend: Livewire
Styling: TailwindCSS
JS: Alpine.js

Database: MySQL

Hosting: Shared Hosting (Hostinger compatible)

The application must not depend on Node.js runtime servers.

---

# Development Rules

Follow Laravel best practices.

Controllers must remain thin.

Business logic must be implemented inside service classes.

Livewire components must handle UI logic.

Use Eloquent ORM for database interactions.

Use eager loading for relationships.

Avoid N+1 query problems.

Use pagination for large lists.

---

# Required System Modules

Generate the following modules:

User Management
Client Management
Project Management
Milestone Management
Task Management
Subtask Management
File Management
Comment System
Activity Log
Notification System
Dashboard
Calendar
Gantt Timeline

---

# Role System

Roles supported:

super_admin
project_manager
team_member
client

Use role-based authorization.

Clients cannot access internal team tasks unless enabled by project settings.

---

# Database Implementation

Create migrations based on DATABASE_SCHEMA.md.

Include foreign keys.

Use soft deletes where defined.

Generate models with relationships.

Example relationships:

Client hasMany Projects

Project belongsTo Client
Project hasMany Milestones
Project hasMany Tasks
Project hasMany Files
Project hasMany Comments

Milestone belongsTo Project
Milestone hasMany Tasks

Task belongsTo Milestone
Task belongsTo Project
Task hasMany Subtasks

Subtask belongsTo Task

---

# Services

Generate service classes for business logic.

Required services:

ProjectService
TaskService
MilestoneService
FileService
NotificationService

These services must handle:

project creation
team assignment
progress calculation
milestone completion
task workflow
file upload rules
notifications

---

# Task Workflow

Tasks follow this workflow:

To Do
In Progress
Review
Completed

Review flow:

Team Member → Project Manager Review → Optional Client Review

If client review deadline passes, the task is automatically completed.

Implement this logic inside TaskService.

---

# File Rules

Server stores only image files.

Allowed types:

png
jpg
jpeg
webp

Other file types must use external links.

---

# Notifications

Trigger notifications for:

task completed
file uploaded
comment added
milestone completed

Notifications must support:

in-app
email

---

# UI Implementation

Use Livewire components for UI.

Create components for:

DashboardOverview
ProjectList
ProjectDetails
MilestoneList
TaskBoard
TaskList
CommentSection
NotificationDropdown

Task management must support:

Kanban board
List view

---

# Dashboard

Client dashboard must display:

Active Projects
Completed Projects
Upcoming Deadlines
Recent Activity
Project List

---

# Calendar

Implement global calendar using:

project deadlines
milestone deadlines
task deadlines

Allow filtering by:

My Tasks
My Projects
All Projects

---

# Gantt Timeline

Each project must have a visual timeline showing:

milestones
tasks
deadlines

---

# Code Quality

Follow PSR-12 standard.

Use clear naming conventions.

Avoid duplicated code.

Create reusable components.

---

# Goal

Generate a clean, scalable Laravel project structure that implements the full client portal system described in the documentation.
