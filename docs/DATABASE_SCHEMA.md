# Database Schema

Client Portal Agency System

Database Engine: MySQL  
Framework: Laravel  

All primary tables support soft deletes unless stated otherwise.

---

# Users Table

Stores all users including agency staff and clients.

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

Role values

super_admin  
admin  
project_manager  
team_member  
client  

Clients are simply users with role = client.

---

# Projects Table

Main project container.

projects

id  
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

Status values

planning  
in_progress  
completed  
on_hold  
cancelled  

Visibility values

draft  
active  
completed  
archived  

---

# Project Members Table

Defines which users belong to a project.

project_members

id  
project_id  
user_id  
role  
created_at  

Role values

project_manager  
team_member  
client  

This table controls project access.

---

# Milestones Table

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

Status values

pending  
in_progress  
completed  

---

# Tasks Table

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

Status values

todo  
in_progress  
review  
completed  

Priority values

low  
medium  
high  
urgent  

Review stage values

none  
team_review  
client_review  

assigned_to references users.id

---

# Subtasks Table

subtasks

id  
task_id  
title  
status  
created_at  
updated_at  
deleted_at  

Status values

todo  
completed  

---

# Files Table

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

Only images stored locally.

Other files must use external links.

---

# Comments Table

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

---

# Activity Logs Table

activity_logs

id  
project_id  
user_id  
action  
target_type  
target_id  
description  
created_at  

Activity logs must not use soft deletes.

---

# Notifications Table

notifications

id  
user_id  
project_id  
type  
title  
message  
is_read  
created_at  

Notification types

in_app  
email  

Notifications must not use soft deletes.

---

# Database Relationships

users → project_members  

projects → project_members  

projects → milestones  
projects → tasks  
projects → files  
projects → comments  
projects → activity_logs  
projects → notifications  

milestones → tasks  

tasks → subtasks  

---

# Indexing Recommendations

projects.project_manager_id  
tasks.project_id  
tasks.assigned_to  
milestones.project_id  
comments.project_id  
files.project_id  

Indexes improve dashboard performance.

---

# Soft Delete Policy

Soft delete enabled

users  
projects  
milestones  
tasks  
subtasks  
files  
comments  

No soft delete

activity_logs  
notifications