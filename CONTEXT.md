# KomX Platform — Project Context

> **This file is the canonical reference for all AI-assisted prompts.**
> Include it (or reference it) at the start of every conversation to maintain continuity.

---

## 1. Project Overview

| Field              | Value                                      |
| ------------------ | ------------------------------------------ |
| **Project Name**   | KomX                                       |
| **Repository**     | `yasir0722/KomX` (GitHub)                  |
| **Default Branch** | `main`                                     |
| **Developer**      | Solo developer                             |
| **Client**         | Single client platform                     |
| **Expected Users** | 500–600 members                            |
| **Budget**         | Constrained — minimal infrastructure spend |
| **Started**        | February 2026                              |

---

## 2. Tech Stack

| Layer          | Technology                                |
| -------------- | ----------------------------------------- |
| **Backend**    | Laravel (latest LTS)                      |
| **Frontend**   | Vue 3 + Vite (SPA inside Laravel)         |
| **Database**   | MySQL 8                                   |
| **Auth**       | Laravel Sanctum (session-based SPA auth)  |
| **Queue**      | Database driver (for future jobs)         |
| **Local Dev**  | Docker (Dockerfile + docker-compose)      |
| **Deployment** | Single server — DigitalOcean droplet      |
| **OS Target**  | Windows (dev), Ubuntu (production)        |

---

## 3. Architecture

### 3.1 Pattern: Modular Monolith

The codebase is a **single Laravel repo** but organised into domain-oriented modules
so it can be split into separate frontend/backend repos in the future if needed.

```
app/
├── Domain/           # Domain models, enums, value objects
│   ├── Member/
│   ├── Event/
│   ├── Attendance/
│   ├── Points/
│   └── Auth/
├── Actions/          # Single-purpose action classes (invokable)
│   ├── Member/
│   ├── Event/
│   └── Auth/
├── Services/         # Business logic orchestration
│   ├── Member/
│   ├── Event/
│   └── Auth/
├── Repositories/     # Data-access abstraction
│   ├── Member/
│   ├── Event/
│   └── Auth/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/   # Versioned API controllers
│   ├── Middleware/
│   │   └── Role/     # Role-based middleware
│   └── Requests/     # Form request validation
├── Models/           # Eloquent models (standard Laravel location)
└── Providers/
```

### 3.2 Frontend Structure (Vue 3 SPA)

```
resources/
├── js/
│   ├── app.js              # Vue entry point
│   ├── router/
│   │   └── index.js        # Vue Router
│   ├── stores/             # Pinia stores
│   │   └── auth.js
│   ├── views/
│   │   ├── Auth/
│   │   │   └── Login.vue
│   │   ├── Dashboard/
│   │   │   └── Index.vue
│   │   ├── Members/
│   │   ├── Events/
│   │   └── Reports/
│   ├── components/
│   │   ├── layout/
│   │   └── shared/
│   ├── composables/        # Reusable composition functions
│   ├── services/           # API service layer (axios)
│   │   └── api.js
│   └── utils/
├── views/
│   └── app.blade.php       # Single Blade shell for SPA
└── css/
    └── app.css
```

### 3.3 Key Principles

- **Controllers are thin** — no business logic, only request → service → response.
- **Services handle logic** — orchestrate actions, validate rules, interact with repos.
- **Actions are single-purpose** — one public `__invoke()` or `execute()` method.
- **Repositories abstract data access** — Eloquent queries live here, not in services.
- **Clean architecture mindset** — dependencies point inward (controllers → services → repos → models).

---

## 4. Features (Planned)

| Module               | Description                                          | Priority |
| -------------------- | ---------------------------------------------------- | -------- |
| **Auth**             | Login / logout / session via Sanctum                 | P0       |
| **Member Management**| CRUD members, profile, status                        | P0       |
| **Roles & Permissions** | Admin, Committee, Member — role-based UI/API      | P0       |
| **Events**           | Create/manage events                                 | P1       |
| **Attendance**       | Track attendance per event                           | P1       |
| **Points / Activity**| Activity/points tracking per member                  | P1       |
| **Dashboard**        | Reporting dashboard (basic charts & stats)           | P2       |

---

## 5. Roles

| Role          | Access Level                                              |
| ------------- | --------------------------------------------------------- |
| **Admin**     | Full access — manage members, events, settings, reports   |
| **Committee** | Manage events, attendance, view reports                   |
| **Member**    | View own profile, attendance, points                      |

---

## 6. API Design

- **Prefix:** `/api/v1`
- **Auth:** Sanctum session cookies (SPA mode — no tokens for first-party frontend)
- **Format:** JSON responses, standard Laravel API resources
- **Versioning:** URL-based (`/api/v1`, future `/api/v2`)
- **Error format:** Consistent JSON error envelope

```json
{
  "message": "Human-readable error",
  "errors": {
    "field": ["Validation message"]
  }
}
```

---

## 7. DevOps

### 7.1 Local Development (Docker)

| Service    | Image / Config                          |
| ---------- | --------------------------------------- |
| **app**    | PHP 8.3-FPM + Nginx (single container) |
| **mysql**  | MySQL 8.0                               |
| **node**   | Node 20 (for Vite dev server)           |

### 7.2 Production (DigitalOcean Single Server)

- **OS:** Ubuntu 22.04+
- **Web server:** Nginx + PHP-FPM
- **SSL:** Let's Encrypt (Certbot)
- **Process manager:** Supervisor (for queue workers)
- **Deployment:** Git pull + Artisan commands (manual or simple script)
- **Database:** MySQL on same server
- **Backups:** Scheduled mysqldump via cron

### 7.3 Environment

- `.env.example` maintained and kept current
- Queue driver: `database`
- Session driver: `database` or `file`
- Logging: `stack` channel (daily files + stderr)
- Cache: `file` (upgrade to Redis later if needed)

---

## 8. Code Style & Conventions

| Area               | Convention                                              |
| ------------------ | ------------------------------------------------------- |
| **PHP**            | PSR-12, Laravel conventions                             |
| **Vue**            | Composition API (`<script setup>`), single-file components |
| **Naming**         | `PascalCase` classes, `camelCase` methods/variables     |
| **Routes**         | `kebab-case` URIs, resourceful where possible           |
| **Migrations**     | Descriptive: `create_members_table`, `add_role_to_users_table` |
| **Tests**          | Feature tests for API, Unit tests for services          |
| **Commits**        | Conventional Commits (`feat:`, `fix:`, `chore:`, etc.)  |

---

## 9. Initial Commit Plan

| Step | Task                                       | Status    |
| ---- | ------------------------------------------ | --------- |
| 1    | Fresh Laravel install                      | ✅ Done    |
| 2    | Sanctum configured                         | ✅ Done    |
| 3    | Vue 3 SPA scaffolded (Vite)               | ✅ Done    |
| 4    | Basic auth flow (login / logout / session) | ✅ Done    |
| 5    | Example protected dashboard route          | ✅ Done    |
| 6    | Example Member module (migration, model, controller, service) | ✅ Done |
| 7    | Role middleware                            | ✅ Done    |
| 8    | Docker setup (app + mysql)                | ✅ Done    |
| 9    | README with deployment steps              | ✅ Done    |

---

## 10. File Naming Quick Reference

```
Controller : app/Http/Controllers/Api/V1/MemberController.php
Service    : app/Services/Member/MemberService.php
Action     : app/Actions/Member/CreateMember.php
Repository : app/Repositories/Member/MemberRepository.php
Model      : app/Models/Member.php
Migration  : database/migrations/xxxx_xx_xx_create_members_table.php
Request    : app/Http/Requests/Member/StoreMemberRequest.php
Resource   : app/Http/Resources/Member/MemberResource.php
Vue View   : resources/js/views/Members/Index.vue
Vue Store  : resources/js/stores/member.js
API Service: resources/js/services/memberService.js
```

---

## 11. Prompt Usage Guide

When starting a new AI conversation about this project, begin with:

```
I'm working on the KomX platform. Here is the project context:
[paste or attach CONTEXT.md]

My current task is: ...
```

This ensures the AI understands the full architecture, stack, conventions, and current state.

---

*Last updated: 2026-02-13*
