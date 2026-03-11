# DOMAIN MODEL

Agency
│
├── Users
│     ├── Internal Users
│     │      ├── super_admin
│     │      ├── admin
│     │      ├── project_manager
│     │      └── team_member
│     │
│     └── Client Users
│            └── client
│
└── Projects
      │
      ├── Project Members
      │       ├── project_manager
      │       ├── team_member
      │       └── client
      │
      ├── Milestones
      │      └── Tasks
      │            └── Subtasks
      │
      ├── Files
      ├── Comments
      ├── Activity Logs
      └── Notifications

The Client Portal system is designed for agencies to collaborate with internal teams and external clients.

All people in the system are stored in the users table.

Users are divided into internal agency members and external client users.

---

# AGENCY

The agency manages projects and collaborates with clients.

---

# USERS

All system users are stored in the users table.

Users are categorized into two groups.

## Internal Users

Internal users manage the system and projects.

Roles:

super_admin  
admin  
project_manager  
team_member  

---

## Client Users

External users who can access projects assigned to them.

Role:

client

Clients only see projects where they are members.

---

# PROJECTS

Projects represent work delivered by the agency.

Each project can contain:

milestones  
tasks  
files  
comments  
activity logs  

Projects are managed by a project manager and team members.

Clients can be added as project members.

---

# PROJECT MEMBERS

Project membership defines who has access to a project.

Table: project_members

A project member can be:

project_manager  
team_member  
client  

Membership controls access to:

tasks  
files  
comments  
activity  

---

# TASKS

Tasks belong to projects.

Tasks may contain:

description  
priority  
deadline  
assignee  
review stage  

Tasks may require client review.

---

# FILES

Files belong to projects.

Files can be uploaded by internal users or clients depending on permissions.

---

# COMMENTS

Comments belong to tasks or projects.

Both internal users and clients may comment depending on permissions.

---

# ACTIVITY LOGS

Activity logs track actions inside the system.

Examples:

task created  
task moved  
comment added  
file uploaded  

---

# PERMISSION MODEL

Permissions are determined by:

user role  
project membership  

---

# CLIENT ACCESS

Clients can access:

projects they belong to  
tasks inside those projects  
files inside those projects  
comments inside those projects  

Clients cannot access:

team management  
system settings  
internal dashboards