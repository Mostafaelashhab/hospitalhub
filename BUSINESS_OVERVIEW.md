# HospitalHub - Business Overview

## What is HospitalHub

A comprehensive multi-tenant **clinic management SaaS platform** (Laravel 12, PHP 8.2+) for healthcare providers in Egypt. Supports clinics, hospitals, and specialists to manage operations, patient care, and business processes.

---

## Core Business Domains

### 1. Multi-Tenancy & Organization

- **Clinic** - main tenant entity (active/pending/suspended, free_mode, wallet/points system)
- **Branch** - multiple physical locations per clinic
- **User Roles:** super_admin, admin, doctor, employee (accountant/secretary), patient
- Granular role-based permissions per clinic with custom roles support

### 2. Patient Management

- Demographics, emergency contacts, medical history, allergies, blood type, national ID
- **Vital Signs** - BP, heart rate, temperature, weight, height, blood sugar, O2 saturation, BMI
- **Chronic Diseases** - tracking with severity, start/end dates
- **Current Medications** - dosage, frequency, duration, prescribed by doctor
- **Medical Notes** - appointment-linked, typed, staff-attributed
- **Patient Files** - document storage (PDF/images), downloadable
- **Photo Timeline** - before/after treatment photos
- **Insurance** - multiple policies per patient, active selection, expiry tracking

### 3. Appointment & Queue System

- **Scheduling** - date/time with recurring support (daily/weekly/biweekly/monthly)
- **Status Machine:**
  - `scheduled` → `confirmed` → `in_progress` → `completed`
  - Alternative: `scheduled`/`confirmed` → `cancelled` / `no_show`
- **Queue Management** - FIFO, auto queue number, check-in, call, skip
- **Waiting Room** - public display (no auth required)
- **Online Booking** - public booking without authentication
- **Doctor Leave** - blocks appointment creation during leave periods

### 4. Clinical Features

#### Diagnosis & Treatment
- Chief complaint, diagnosis text, prescription, lab/radiology orders
- Diagram data (JSON for sketch-based diagrams, e.g. dental charts)
- Doctor notes, clinic/branch/patient/doctor linkage
- **Diagnosis Templates** - reusable templates for common diagnoses

#### Prescriptions
- Links diagnosis to medication records
- Items: drug name, dosage, frequency, duration, instructions
- PDF generation for printing

#### Specialty Modules

**Pregnancy Tracking (OB/GYN):**
- LMP, EDD calculation, gestational week, trimester
- Status: active/completed/miscarriage
- Delivery details (date, type, baby gender, weight)
- Pregnancy visits with chronological tracking
- Progress percentage calculation

**Dental Charts:**
- FDI tooth numbering system (32 teeth, 8 quadrants)
- Per-tooth status: healthy, cavity, filling, crown, extraction, implant, root_canal, bridge, veneer, missing
- Historical charts (before/after treatment)

**Pediatric:**
- Growth chart functionality
- Age-appropriate vital signs

### 5. Pharmaceutical

- **Drug Database** - RapidAPI integration for drug info, prices, interactions
- Multilingual support (EN/AR)
- Drug details: generic name, manufacturer, class, indications, dosage, side effects, contraindications, interactions, warnings, pregnancy category, storage
- **Drug Interaction Checking** - severity levels, clinical notes

### 6. Financial System

#### Invoices
- Appointment-linked, service line items
- Pricing: subtotal → discount (coupon) → insurance coverage → patient share → total
- Payment methods: cash, card, bank_transfer, instapay
- Status: `unpaid` → `paid`/`partial`/`refunded`

#### Coupons
- Type: percentage or fixed amount
- Constraints: min order, max discount, date range, usage limits (global + per-patient)

#### Clinic Wallet
- Point-based credit system
- Transaction types: credit/debit with reference tracking
- Balance tracking per transaction

#### Patient Ledger
- Per-patient debt/credit tracking
- Running balance calculation
- Invoice linkage, payment method tracking

#### Expenses
- Category-based expense tracking
- Receipt/document attachment
- Custom categories per clinic, soft delete support

#### Recharge Requests
- Clinic point recharge with admin approval workflow
- Status: pending/approved/rejected

### 7. AI Features

#### AI Radiology Analysis
- X-ray image analysis via RapidAPI
- Three input methods: URL, file upload, storage path
- Language support (EN/AR), custom prompts
- Timeout handling (120s), error recovery (fallback from URL to file upload)

#### Drug Info Service
- RapidAPI drug database population
- Multi-format API data parsing
- Price extraction, image download/caching

### 8. Communication & Notifications

#### WhatsApp (OctopusTeam WAAPI)
- OTP delivery for authentication
- Appointment notifications and reminders
- Device status monitoring, health check endpoint
- Message logging

#### Other Channels
- Email (Laravel Mail)
- Database (in-app notifications)
- WebPush (browser push notifications)

#### Notification Types
- AppointmentCreated, AppointmentStatusChanged
- DiagnosisRecorded
- InvoiceUpdated, RechargeRequested
- NewClinicRegistered, WhatsAppDeviceOffline

### 9. Authentication & Security

- **OTP-Based Auth** via WhatsApp (6-digit, 5-min expiry)
- Rate limiting: 60s cooldown, 5/phone/hour, 10/IP/hour
- Failed attempts: 5 max → 30-min lockout
- Device offline fallback: auto-verify with admin notification
- Email verification, password reset, session management
- **Audit Logging** - user actions, model changes (old/new values), IP, user agent

### 10. Public Clinic Website

- Customizable appearance (colors, hero image)
- About section (EN/AR)
- Service showcase, doctor directory
- Online booking integration
- Social media links, SEO (sitemap.xml, robots.txt)

### 11. Platform Administration (Super Admin)

- Clinic management: approve, suspend, activate
- Point allocation/deduction
- Recharge request approval
- Platform-wide offers (percentage/fixed discount)
- WhatsApp message monitoring
- Platform settings configuration

### 12. Reviews & Offers

- **Reviews** - patient reviews of doctors (1-5 stars), visibility control
- **Offers** - platform-wide or clinic-specific, percentage/fixed, date ranges, multilingual

---

## Data Model (Key Entities)

| Domain | Models |
|--------|--------|
| Organization | Clinic, Branch, User, Specialty |
| Medical Staff | Doctor, DoctorSchedule, DoctorLeave |
| Patients | Patient, VitalSign, ChronicDisease, PatientMedication, MedicalNote, PatientFile, PhotoRecord, PatientInsurance |
| Appointments | Appointment (with queue fields) |
| Clinical | Diagnosis, DiagnosisTemplate, Prescription, PrescriptionItem |
| Specialty | Pregnancy, PregnancyVisit, DentalChart |
| Pharmacy | Drug, DrugInteraction |
| Financial | Invoice, InvoiceItem, Coupon, CouponUsage, ClinicWallet, WalletTransaction, PatientLedgerEntry, Expense, ExpenseCategory, RechargeRequest |
| Platform | Offer, ClinicOffer, PlatformSetting, Review |
| System | AuditLog, OtpCode, WhatsappMessage, PushSubscription |

---

## External Integrations

| Service | Provider | Purpose |
|---------|----------|---------|
| WhatsApp | OctopusTeam WAAPI | OTP, notifications, reminders |
| AI Radiology | RapidAPI | X-ray image interpretation |
| Drug Info | RapidAPI | Drug database, prices, interactions |
| Cloud Storage | AWS S3 | File storage (optional) |
| PDF Generation | Laravel DomPDF | Reports, prescriptions, invoices |
| QR Codes | Simple QRCode | Various QR generation |

---

## Tech Stack

- **Backend:** Laravel 12.x, PHP 8.2+
- **Database:** SQLite (configurable to MySQL/PostgreSQL)
- **Notifications:** WhatsApp, Email, WebPush, Database
- **PDF:** Laravel DomPDF
- **QR:** Simple QRCode
- **AI:** RapidAPI integrations
