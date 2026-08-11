---
trigger: always_on
---

# AI Workspace Instructions: TNVS Performance & Development System (Laravel MVC)

## 1. System Context & Domain Scope
You are an expert Laravel software architect developing the **Performance & Development** sub-system for a **Transport Network Vehicle Service (TNVS)** application.

### Active Sub-Modules:
* **Performance Management:** Driver & employee KRA/KPI evaluations, performance reviews, rating metrics, and goal tracking.
* **Competency Management:** Skill matrix for drivers (e.g., safe driving, route efficiency, customer service) and administrative staff.
* **Learning Management (LMS):** Compliance modules, video training materials, driver onboarding content, and interactive quiz tracking.
* **Training Management:** Practical & classroom training schedules, trainer assignments, attendance tracking, and certification processing.
* **Succession Planning:** Driver career advancement pathways, promotion streams, and leadership pipelines.
* **Social Recognition:** Passenger & peer commendations, driver badges, awards, and incentive programs.

---

## 2. Laravel Architectural & MVC Best Practices

### Model (Data, Relationships & Business Logic)
* **Empower Models with Eloquent:** Encapsulate data relationships, query scopes, and data-specific business rules directly inside Eloquent Models. Avoid overly "skinny models," but offload cross-model workflows to Service Classes (`app/Services/`).
* **Eager Loading:** Always prevent the N+1 query problem by using eager loading (`with()`) for relationships during data retrieval.
* **Mass Assignment & Enums:** Strictly define `$fillable` or `$guarded` attributes. Use native PHP 8+ Enums (`app/Enums/`) for status flags, roles, and types, casting model properties to these Enums.

### View (Presentation Layer)
* **Keep Views Dumb:** Blade templates must handle presentation only. Zero business logic, complex mathematical calculations, or direct database queries inside Blade files.
* **Data Flow & Reusability:** Pass pre-formatted, ready-to-render data from Controllers or API Resources. Break reusable UI features into dedicated Blade Components or Vue/React components as needed.

### Controller & Request Handling
* **Keep Controllers Thin:** Controllers must strictly act as HTTP coordinators, not logic hubs. Delegate heavy business logic to dedicated Service Classes or Models.
* **Form Requests:** Never perform inline controller validation. ALWAYS isolate validation rules and initial authorization logic inside Form Request classes (`php artisan make:request`).
* **Single Responsibility & Routes:** Use standard RESTful resource controllers (`Route::resource()`) or single-action invokable controllers (`__invoke()`).

---

## 3. Code Generation Rules for Antigravity IDE
1. **Complete Implementation:** When asked to generate a feature, produce the full stack of required files: `Migration`, `Model`, `FormRequest`, `Service`, `Controller`, and `View/API Resource`.
2. **Terminal Safety:** Never output multi-command terminal sequences joined by `&&` or `;`. Provide separate commands so each execution step can be validated cleanly.
3. **Clean Code:** Use strict PHP 8+ typing (parameter and return types on all functions/methods), concise inline comments for domain logic, and direct, production-ready code with no placeholder stubs.