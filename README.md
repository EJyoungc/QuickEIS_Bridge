# EIS Bridge: QuickBooks Desktop to MRA EIS Integration Platform

EIS Bridge is a commercial-grade middleware platform that integrates QuickBooks Desktop with the Malawi Revenue Authority (MRA) Electronic Invoicing System (EIS). It implements the QuickBooks Web Connector (QBWC) SOAP protocol natively in Laravel, eliminating the need for a custom Windows Service or .NET background agent.

---

## 1. Features
- **Native SOAP Handler**: Handles QBWC authentication, request serialization, and response ingestion natively in PHP/Laravel.
- **Auto-Generated MRA Client**: Created directly from the MRA OpenAPI specification with strongly typed DTOs and HMAC-SHA512 request signing.
- **Asynchronous Sync Engine**: Offloads MRA API calls to Redis/Horizon queues for robust, high-performance background processing.
- **Livewire 4 + Flux UI Operations Console**: Multi-tenant Tailwind-powered dashboard utilizing premium Flux UI components (buttons, badges, fields, callouts, and modals) for full operational visibility, terminal onboarding, audit logs, and transaction replay controls.
- **Livewire Blaze**: Integrated for optimized Blade component rendering and high performance.
- **Robust Observability**: Logs all incoming SOAP requests and outgoing REST operations to database audits for compliance tracing.

---

## 2. Installation & Setup

### Prerequisites
- PHP 8.3+
- Composer
- SQLite, MySQL, or PostgreSQL database
- Redis (for Horizon queues)

### Setup Commands

1. **Clone the Repository and Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Migrations**:
   ```bash
   php artisan migrate
   ```

---

## 3. Usage & Integration Workflow

### Step 1: Register Tenant in Operations Dashboard
1. Boot the application: `php artisan serve` and access `http://localhost:8000`.
2. Enter the organization name, unique **Company TIN**, and the **Vendor Access Key** issued by MRA. Enter the **Terminal Activation Code (TAC)**.
3. Save the record.

### Step 2: Onboard Terminal
1. In the **Registered Tenant Companies** list table, locate the registered company and click the **Onboard** action button.
2. The middleware will send the signed confirmation payload to the MRA API, receive the terminal details, and securely save the returned `secret_key`, `terminal_id`, and `terminal_token`.
3. The tenant's status shifts to **Active**.

### Step 3: Configure QuickBooks Web Connector (QBWC)
1. Register a connection credential in the **Create QB Connector Credential** form (username/password) linked to the active tenant.
2. Generate a `.QWC` file referencing the Laravel SOAP endpoint: `https://your-domain.com/qbwc`.
3. Load the `.QWC` file into QuickBooks Web Connector on the user's computer.

### Step 4: Run Synchronizations
1. Create or edit an invoice in QuickBooks Desktop.
2. Trigger the sync in QBWC. The Web Connector will communicate with `/qbwc` over HTTPS.
3. The middleware captures the invoice, dispatches it to the background job queue, submits the signed payload to MRA, and updates the local status.

### Step 5: Monitor and Replay
1. Navigate to **Synchronized Invoices** to view synchronization outcomes, QR verification codes, and fiscal receipt details.
2. If any submission fails (e.g. due to MRA network down), click **Replay Sync** on the failed invoice row to manually queue it for resubmission.
3. Navigate to **SOAP & REST Audit Logs** to trace exact SOAP XML requests and raw REST JSON communications.

---

## 4. Developer Command Cheatsheet

- **Start Dev Servers (HTTP, Queue, Vite)**:
  ```bash
  composer run dev
  ```

- **Start Horizon Panel (Queue Manager)**:
  ```bash
  php artisan horizon
  ```

- **Run Formatter (Laravel Pint)**:
  ```bash
  vendor/bin/pint --dirty --format agent
  ```

- **Run Tests (Pest)**:
  ```bash
  php artisan test
  ```
