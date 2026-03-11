# Client Portal Agency System

## Project Overview

This application is a client portal system used by an agency to manage and communicate project progress with clients.

The system allows clients to monitor project progress, view milestones, receive updates, download deliverables, and communicate with the project team.

The system is built using Laravel, Livewire, and TailwindCSS and is designed to run on shared hosting environments.

---

# Technology Stack

Backend:
Laravel (latest stable version)

Frontend:
Livewire
TailwindCSS
Alpine.js

Database:
MySQL

Architecture:
MVC + Livewire Components

Authentication:
Email + Password with optional 2FA

Hosting:
Shared hosting compatible (Hostinger)

---

# User Roles

The system supports four main user roles.

Super Admin
Project Manager
Team Member
Client

---

## Super Admin

Super Admin has full access to the system.

Responsibilities:

- manage users
- manage clients
- manage projects
- manage permissions
- view system activity

---

## Project Manager

Project Managers are responsible for managing project execution.

Responsibilities:

- create projects
- assign team members
- create milestones
- create tasks
- review completed tasks
- communicate with clients

---

## Team Member

Team members work on assigned tasks.

Responsibilities:

- update task status
- upload files
- comment on tasks
- mark tasks as completed

---

## Client

Clients can monitor project progress.

Clients can:

- view project progress
- view milestones
- view activity timeline
- download deliverables
- comment on project updates

Clients cannot see internal tasks unless enabled by project settings.

---

# Core System Modules

The system contains the following modules.

User Management
Client Management
Project Management
Milestone Management
Task Management
File Management
Comment System
Activity Log
Notification System
Dashboard
Calendar
Gantt Timeline

---

# Project Structure

Clients can have multiple projects.

Client
Projects
Milestones
Tasks
Subtasks

Projects also contain:

Files
Comments
Activity Logs
Notifications

---

# Project Visibility

Projects have visibility states.

draft
active
completed
archived

Clients only see projects with visibility "active" or "completed".

---

# Project Progress

Project progress is automatically calculated based on task completion.

Progress formula:

completed_tasks / total_tasks

---

# Milestones

Milestones group tasks into logical project phases.

Milestone completion can be:

automatic based on tasks
or manually overridden by Project Manager.

---

# Task Workflow

Tasks follow this workflow:

To Do
In Progress
Review
Completed

---

# Task Review System

Task review has two stages.

Stage 1: Project Manager Review

When a team member completes a task, it moves to review.

Project Manager can:

approve
request revision
send to client review

---

Stage 2: Client Review (Optional)

If client review is required:

client receives notification
client can approve or request revision

If client does not respond before the review deadline, the system automatically completes the task.

---

# Task Priority

Tasks support priority levels.

Low
Medium
High
Urgent

---

# File Management

The system only stores image files on the server.

Examples:

design_preview.png
screenshot_feedback.jpg

Other files must be stored externally using cloud storage.

Supported external storage examples:

Google Drive
Dropbox
OneDrive

External files are saved as URLs.

---

# Comment System

Comments can be added to:

Projects
Milestones
Tasks

Comments support:

text messages
image attachments
external file links

---

# Activity Log

The system automatically records important events.

Examples:

task created
task completed
file uploaded
comment added
milestone completed

Activity logs appear in the project timeline.

---

# Notification System

Notifications are delivered via:

In-app notifications
Email notifications

Examples:

task completed
file uploaded
comment added
milestone completed

---

# Dashboard

Client Dashboard contains:

Active Projects
Completed Projects
Upcoming Deadlines
Recent Activity
Project List

---

# Task Views

Task management supports two views.

Kanban Board
List View

Users can switch between views.

---

# Gantt Timeline

Each project includes a visual Gantt timeline.

The timeline displays:

Milestones
Tasks
Start dates
Deadlines

---

# Global Calendar

The system includes a global calendar showing:

Project deadlines
Milestone deadlines
Task deadlines

Users can filter by:

My Tasks
My Projects
All Projects

---

# Search and Filtering

The system supports global search and filtering.

Searchable items:

Projects
Tasks
Milestones
Activity logs

Filters include:

status
priority
deadline
assignee

---

# Security

Authentication:

Email + Password
Optional Two Factor Authentication

Authorization:

Role-based access control.

Permissions are enforced using middleware.

---

# Data Safety

All main models support soft deletes.

Soft deleted records can be restored by administrators.

---

# Laravel Coding Guidelines

Follow Laravel best practices.

Use:

Eloquent ORM
Service classes for business logic
Livewire components for UI

Avoid:

large controllers
duplicate logic
raw SQL queries

Controllers should remain thin.

Business logic should be placed in service classes.

---

# Performance Guidelines

Use eager loading for relationships.

Avoid N+1 query problems.

Use pagination for large datasets.

Cache frequently accessed queries when possible.

---

# Future Scalability

The architecture should allow future features such as:

client invoicing
time tracking
project billing
API integrations
mobile app support
