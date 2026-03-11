# Database Schema

Client Portal Agency System

Database Engine: MySQL
Framework: Laravel

All main tables support soft deletes.

---

# Users Table

Stores all system users.

users

id
name
email
password
role
two_factor_enabled
two_factor_secret
avatar
created_at
updated_at
deleted_at

Role values:

super_admin
project_manager
team_member
client

---

# Clients Table

Represents client companies or individuals.

clients

id
name
company_name
email
phone
address
notes
created_at
updated_at
deleted_at

---

# Projects Table

Main project container.

projects

id
client_id
project_manager_id
name
description
status
visibility
progress
start_date
deadline
show_team_to_client
created_at
updated_at
deleted_at

Status values:

planning
in_progress
completed
on_hold
cancelled

Visibility values:

draft
active
completed
archived

---

# Project Members Table

Links team members to projects.

project_members

id
project_id
user_id
role
created_at

Role values:

project_manager
team_member

---

# Milestones Table

Milestones divide projects into phases.

milestones

id
project_id
title
description
status
start_date
deadline
manual_override
created_at
updated_at
deleted_at

Status values:

pending
in_progress
completed

---

# Tasks Table

Tasks represent actionable work.

tasks

id
project_id
milestone_id
assigned_to
title
description
status
priority
review_stage
client_review_required
client_review_deadline
start_date
deadline
created_at
updated_at
deleted_at

Status values:

todo
in_progress
review
completed

Priority values:

low
medium
high
urgent

Review stage values:

team_review
client_review

---

# Subtasks Table

Subtasks break down tasks.

subtasks

id
task_id
title
status
created_at
updated_at
deleted_at

Status values:

todo
completed

---

# Files Table

Stores file previews and external file links.

files

id
project_id
milestone_id
task_id
uploaded_by
image_path
external_link
created_at
updated_at
deleted_at

Server stores only images.

External files must be stored using external cloud storage.

Examples:

Google Drive
Dropbox
OneDrive

---

# Comments Table

Supports discussions.

comments

id
user_id
project_id
milestone_id
task_id
message
image_path
external_link
created_at
updated_at
deleted_at

Comments may contain:

text
image
external link

---

# Activity Logs Table

Tracks important project events.

activity_logs

id
project_id
user_id
action
target_type
target_id
description
created_at

Example actions:

task_created
task_completed
file_uploaded
comment_added
milestone_completed

---

# Notifications Table

Handles system notifications.

notifications

id
user_id
project_id
type
title
message
is_read
created_at

Notification channels:

in_app
email

---

# Default Laravel Tables

password_resets

id
email
token
created_at

failed_jobs

id
uuid
connection
queue
payload
exception
failed_at

---

# Database Relationships

Client → Projects

Projects → Milestones

Milestones → Tasks

Tasks → Subtasks

Projects → Files

Projects → Comments

Projects → Activity Logs

Projects → Notifications

Projects → Project Members

---

# Indexing Recommendations

Add indexes on:

projects.client_id
projects.project_manager_id
tasks.project_id
tasks.assigned_to
milestones.project_id
comments.project_id
files.project_id

Indexes improve dashboard performance.

---

# Soft Delete Policy

The following tables must support soft deletes:

users
clients
projects
milestones
tasks
subtasks
files
comments

Activity logs and notifications should not be soft deleted.
