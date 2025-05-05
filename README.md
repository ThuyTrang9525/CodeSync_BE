# Student Progress and Goal Tracking System

## 📚 Course
Advanced Web Project

## 👨‍💻 Team Members
- Bùi Thị Thùy Trang – Team Leader
- Đoàn Minh Hoàng – Team Member
- Nguyễn Thị Hà Sang – Team Member
- Trần Văn Vinh – Team Member

## 📝 Project Description
This system allows admins, teachers, and students to interact with a unified platform to track academic progress and personal goals. The platform supports journaling, feedback, goal setting, and student management.

## 📅 Project Timeline
Organized in weekly sprints using [Trello/Notion/Google Sheets].

## 📁 Folder Structure

student-progress-system/
├── backend/ # Laravel backend
│ ├── app/
│ ├── routes/api.php
├── frontend/ # React frontend
│ ├── src/components/
│ ├── src/pages/
└── README.md


## 🔧 Tech Stack
- **Frontend**: React.js
- **Backend**: Laravel (PHP)
- **Database**: MySQL
- **Authentication**: Laravel Sanctum / JWT
- **Version Control**: GitHub + Branching (`main`, `dev`, `feature/*`)

## 👥 User Roles
| Role    | Permissions |
|---------|-------------|
| Admin   | Add/edit users, manage data |
| Teacher | View students, give feedback |
| Student | Journal entries, view feedback |

## 🧩 Core Features
| Feature                 | Description                             | Role      |
|------------------------|-----------------------------------------|-----------|
| Add student profile    | Collect personal & academic info        | Admin     |
| Log journal entry      | Student adds reflections/goals          | Student   |
| Give feedback          | Teacher reviews journal & comments      | Teacher   |
| View journal by date   | Browse student progress                 | Teacher   |
| Set academic goals     | Students define SMART goals             | Student   |

## 📌 API Design
Follows RESTful principles. Example endpoints:

POST /api/login
GET /api/journals
POST /api/goals
PUT /api/feedback/{id}

## 🔐 Authentication & RBAC
- Laravel Sanctum for token-based auth
- Middleware for role-based access control (Admin, Teacher, Student)

## 🖼️ UI Design
Wireframes include:
- Login Page
- Dashboards per role
- Journal Entry Form
- Feedback Panel

## ✅ Best Practices
- Commit regularly with clear messages
- Dummy data used for early UI testing
- Team meets twice a week
- All screens are mobile-friendly
- Weekly project log maintained

---

> 💡 *Start small, iterate fast. Focus on one feature at a time and test thoroughly!*
