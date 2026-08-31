# EduBridge: Online Learning & Teaching Platform with Integrated AI Tutoring

EduBridge is a full-stack, enterprise-grade e-learning management platform built with Laravel and Tailwind CSS. The platform connects learners, instructors, and administrators within a unified digital campus, featuring real-time AI-powered tutoring, dual-gateway payment integration, automated assessment pipelines, and dynamic certification verification.

---

## Key Features

### 🎓 Learner Experience
* **Interactive Course Viewer:** Stream video and text lessons with real-time progress tracking.
* **Context-Aware AI Tutor:** An in-lesson assistant powered by Llama 3 (via Groq API) that answers questions strictly using lecture transcripts and context notes.
* **Assessment & Evaluation:** Interactive multiple-choice quizzes with instant grading and secure file upload assignment submissions.
* **Automated Certification:** Dynamic PDF certificate generation with unique verification hashes and QR validation.
* **Dual Payment Processing:** Secure checkout supporting international cards via Stripe and local mobile banking (bKash/Nagad) via SSLCommerz.

### 👨‍🏫 Instructor Portal
* **Course Planning & Curriculum Builder:** Modular builder for creating multi-section courses, lessons, and downloadable attachments.
* **Quiz & Assignment Configurator:** Configure scoring parameters, time limits, answer rationales, and grading rubrics.
* **Analytics & Financial Ledger:** Real-time metrics tracking student enrollments, retention curves, and net revenue cuts (70% instructor earnings).
* **Automated Payout Engine:** Withdrawal request module supporting direct bank transfers and mobile wallets.

### 🛡️ Administrative Control
* **Instructor Screening & Auditing:** Review instructor applications, video demos, and portfolio submissions with structured approval/rejection feedback workflows.
* **Sandbox Course QA:** Quality assurance testing environment to preview complete course structures before public storefront release.
* **Financial Oversight:** Centralized ledger monitoring the 30% platform commission cut, audit logs, and transaction reconciliations.
* **System Broadcasts:** Global announcement dispatch engine targeting all users, instructors, or learners specifically.

---

## Tech Stack

* **Backend Framework:** Laravel (PHP 8.x)
* **Frontend Architecture:** Blade Templates, Tailwind CSS, Vite, JavaScript (ES6+)
* **Database Engine:** MySQL
* **AI & LLM Orchestration:** Groq API (Llama 3 Model)
* **Payment Gateways:** Stripe API, SSLCommerz
* **PDF & Document Engine:** DomPDF / Laravel PDF

---

## Database Architecture Overview

* `users` — Authentication, profile details, and role assignments (`admin`, `instructor`, `learner`).
* `courses` — Course metadata, category associations, pricing boundaries, and publication lifecycle status.
* `lessons` — Curriculum modules, transcript text data for the AI context window, and media URLs.
* `instructor_applications` — Onboarding workflow tracking applicant credentials, demo links, and review states.
* `enrollments` — Active student course registrations and real-time progress completion percentages.
* `transactions` & `payout_requests` — Financial ledger management for platform commissions and instructor withdrawals.

---

## Installation & Setup

### Prerequisites
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL (XAMPP or standalone service)
