# Doctor Prescription & Appointment Management Software

This document outlines the step-by-step implementation plan for building the Doctor Prescription & Appointment Management Software.

## User Review Required

> [!WARNING]
> **Node.js Version Issue:** As noticed earlier, your system is running Node.js v18.16.0, but Vite 7 requires v20.19+. Since you requested Bootstrap 5, we can bypass complex Vite builds by using standard CDNs for Bootstrap, jQuery, Select2, DataTables, and FullCalendar, which ensures a fast and reliable development process without depending heavily on Node.js. Please confirm if using CDNs for frontend libraries is acceptable.

> [!IMPORTANT]
> **Authentication UI:** You requested Laravel Breeze, which uses Tailwind CSS by default. Since you requested the dashboard to be Bootstrap 5, I plan to build the custom Admin Dashboard entirely in Bootstrap 5, while leaving the default login/registration pages as Breeze (Tailwind) to save time, unless you strictly want everything including login to be Bootstrap 5.

## Open Questions

1. **Frontend Assets:** Should we use CDNs for Bootstrap 5, jQuery, DataTables, Select2, and FullCalendar to keep it lightweight and avoid the Node.js version issue?
2. **Login/Auth Pages:** Is it okay to keep the default Laravel Breeze (Tailwind) styling for the Login page, and use Bootstrap 5 exclusively for the Dashboard and logged-in modules?

## Proposed Implementation Steps

The development will be broken down into the following logical phases:

### Phase 1: Environment Setup & Packages
- Configure `.env` for database connection.
- Install Spatie Laravel-Permission.
- Install Laravel Breeze (Auth scaffolding).
- Install Barryvdh Laravel-DomPDF.
- Set up roles and permissions (Admin, Doctor, Receptionist) via seeders.

### Phase 2: Database & Models (Migration & Relationships)
- Create migrations for:
  - `patients`
  - `appointments`
  - `medicines`
  - `medicine_transactions`
  - `prescriptions`
  - `prescription_items`
- Create Models with correct Eloquent relationships.
- Add database transaction logic structures for stock deduction.

### Phase 3: Dashboard Layout & UI (Bootstrap 5)
- Create `resources/views/layouts/dashboard.blade.php`.
- Set up the sidebar, top navigation, and white content area.
- Integrate Bootstrap 5, jQuery, and SweetAlert/Toastr for notifications.

### Phase 4: Patient & Medicine Modules
- **Patients**: Build `PatientController`, views, and AJAX forms for CRUD operations.
- **Medicines**: Build `MedicineController`, views, and AJAX forms. Implement stock management that rigidly logs IN/OUT via the `medicine_transactions` table.

### Phase 5: Appointment Module
- Build `AppointmentController` and views.
- Integrate FullCalendar.js to visualize appointments.
- Implement appointment status changes (New, Confirmed, Visited, Follow-up, No Show).

### Phase 6: Smart Prescription Module (Core)
- Build the dynamic prescription creation interface.
- Implement Select2 for searching medicines.
- **Critical Logic Integration**: Ensure saving the prescription utilizes DB transactions to:
  1. Save `prescriptions` and `prescription_items`.
  2. Auto-deduct `medicines.stock`.
  3. Log an OUT entry in `medicine_transactions`.
- Add follow-up date tracking.

### Phase 7: Follow-up & PDF System
- Create a dedicated view/widget for pending follow-ups.
- Build the PDF generation logic in `PrescriptionController`.
- Design a clean PDF layout using HTML/CSS suitable for DomPDF.

## Verification Plan

### Automated Tests
- While full automated testing is out of scope for the initial rapid build, I will test DB transactions manually or via Tinker to ensure stock deduction cannot fail silently.

### Manual Verification
- Walk through the entire user flow: Login -> Register Patient -> Book Appointment -> Write Prescription -> Check Medicine Stock -> Generate PDF.
- Verify role access (e.g., ensuring Receptionists cannot alter medicine stock directly if restricted).
