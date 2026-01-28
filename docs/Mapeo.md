# Mapeo de Controladores, Servicios y Componentes

## Estado Actual del Proyecto (26 de enero de 2026)

### ✅ Implementado:
- **Modelos (8)**: AttendanceLog, Department, Schedule, ScheduleChangeLog, ScheduleTemplate, Team, TimeOffRequest, User
- **Migraciones**: Todas las migraciones aplicadas correctamente
- **Base de datos**: Estructura completa con relaciones y constraints
- **Controladores (9)**: AuthController, DashboardController, ScheduleController, ScheduleTemplateController, TimeOffRequestController, AttendanceController, ReportController, UserController, Controller (base)
- **Servicios (6)**: ScheduleService, CsvImportService, PdfGeneratorService, TimeOffRequestService, AttendanceService, MetricsCalculator, ReportService
- **Componentes Livewire (3)**: TeamScheduleCalendar, ScheduleImporter, PendingRequestsTable, AttendanceTracker
- **Form Requests (7)**: StoreScheduleRequest, StoreScheduleTemplateRequest, StoreTeamScheduleRequest, UpdateScheduleRequest, UpdateScheduleTemplateRequest, TimeOffRequestFormRequest, AttendanceStoreRequest
- **Policies (4)**: SchedulePolicy, ScheduleTemplatePolicy, TimeOffRequestPolicy, AttendancePolicy
- **Exports (4)**: SchedulesExport, AttendanceExport, ComplianceReportExport, PunctualityReportExport
- **Jobs (5)**: ProcessScheduleImport, SendDailyScheduleReminders, GenerateMonthlyReports, CalculateMonthlyMetrics, MarkDailyAbsences
- **Observers (3)**: ScheduleObserver, TimeOffRequestObserver, AttendanceLogObserver
- **Events/Listeners (8)**: ScheduleCreated/ScheduleUpdated/SendScheduleReminder, TimeOffRequestSubmitted/NotifySupervisorOfRequest, TimeOffRequestApproved/NotifyUserOfApproval/UpdateAttendanceFromRequest, TimeOffRequestRejected/NotifyUserOfApproval, AttendanceRecorded
- **Rutas**: Actualizadas con rutas específicas para equipos, importación, solicitudes de tiempo libre, asistencia y reportes
- **Vistas**: Implementadas en carpetas auth, dashboard, schedules, schedule-templates, time-off-requests, users, attendance, reports
- **Tests**: Tests básicos de Pest

### ❌ Pendiente de Implementar:
- **Otros**: Middleware personalizado, notificaciones avanzadas (emails/SMS), reportes PDF/Excel completos, dashboard con métricas en tiempo real

### 📋 Próximas fases de desarrollo:
1. **✅ Fase 1 COMPLETADA**: Autenticación y gestión de usuarios
2. **✅ Fase 2 COMPLETADA**: Gestión de horarios
3. **✅ Fase 3 COMPLETADA**: Solicitudes de tiempo libre
4. **✅ Fase 4 COMPLETADA**: Asistencia y reportes

---
Usuario de prueba: admin@wfm.com / password (rol WFM)
---

## 1. Controladores (app/Http/Controllers)

### AuthController.php
**Responsabilidad:** Autenticación y gestión de sesión
**Métodos principales:**
- `login()` - Mostrar formulario de login
- `authenticate()` - Procesar login
- `logout()` - Cerrar sesión
- `register()` - Registro de usuarios (solo WFM)

### DashboardController.php
**Responsabilidad:** Vistas principales según rol
**Métodos principales:**
- `index()` - Dashboard principal (redirige según rol)
- `operatorDashboard()` - Vista operador
- `supervisorDashboard()` - Vista supervisor
- `departmentDashboard()` - Vista jefe departamento
- `wfmDashboard()` - Vista analista WFM
- `hrDashboard()` - Vista RRHH

### ScheduleController.php
**Responsabilidad:** Gestión de horarios
**Métodos principales:**
- `index()` - Listar horarios (filtrado por rol)
- `show($id)` - Ver detalle de horario
- `create()` - Formulario crear horario
- `store()` - Guardar horario
- `edit($id)` - Formulario editar
- `update($id)` - Actualizar horario
- `destroy($id)` - Eliminar horario
- `mySchedule()` - Horario del usuario actual
- `teamSchedule()` - Horarios del equipo
- `downloadPdf()` - Descargar horario PDF
- `copyPreviousWeek()` - Duplicar semana anterior
- `import()` - Vista importar CSV
- `processImport()` - Procesar CSV

### TimeOffRequestController.php
**Responsabilidad:** Gestión de solicitudes
**Métodos principales:**
- `index()` - Listar solicitudes
- `create()` - Formulario nueva solicitud
- `store()` - Guardar solicitud
- `show($id)` - Ver detalle
- `edit($id)` - Editar solicitud (solo pendientes)
- `update($id)` - Actualizar solicitud
- `destroy($id)` - Cancelar solicitud
- `pending()` - Solicitudes pendientes (supervisor)
- `approve($id)` - Aprobar solicitud
- `reject($id)` - Rechazar solicitud
- `bulkApprove()` - Aprobación masiva

### AttendanceController.php
**Responsabilidad:** Gestión de asistencia
**Métodos principales:**
- `index()` - Listar registros asistencia
- `show($id)` - Ver detalle
- `create()` - Registrar asistencia manual
- `store()` - Guardar asistencia
- `checkIn()` - Marcar entrada
- `checkOut()` - Marcar salida
- `myAttendance()` - Asistencia del usuario
- `teamAttendance()` - Asistencia del equipo

### ReportController.php
**Responsabilidad:** Generación de reportes
**Métodos principales:**
- `index()` - Vista principal reportes
- `attendanceReport()` - Reporte de asistencia
- `complianceReport()` - Reporte de cumplimiento
- `punctualityReport()` - Reporte de puntualidad
- `absenteeismReport()` - Reporte de ausentismo
- `teamSummary()` - Resumen por equipo
- `departmentSummary()` - Resumen por departamento
- `exportCsv()` - Exportar a CSV
- `exportPdf()` - Exportar a PDF
- `exportExcel()` - Exportar a Excel

### TeamController.php
**Responsabilidad:** Gestión de equipos
**Métodos principales:**
- `index()` - Listar equipos
- `create()` - Formulario crear equipo
- `store()` - Guardar equipo
- `show($id)` - Ver detalle equipo
- `edit($id)` - Editar equipo
- `update($id)` - Actualizar equipo
- `destroy($id)` - Eliminar equipo
- `members($id)` - Listar miembros del equipo
- `assignMembers($id)` - Asignar miembros

### DepartmentController.php
**Responsabilidad:** Gestión de departamentos
**Métodos principales:**
- `index()` - Listar departamentos
- `create()` - Crear departamento
- `store()` - Guardar departamento
- `show($id)` - Ver detalle
- `edit($id)` - Editar departamento
- `update($id)` - Actualizar
- `destroy($id)` - Eliminar
- `teams($id)` - Equipos del departamento

### UserController.php
**Responsabilidad:** Gestión de usuarios
**Métodos principales:**
- `index()` - Listar usuarios
- `create()` - Crear usuario
- `store()` - Guardar usuario
- `show($id)` - Ver perfil
- `edit($id)` - Editar usuario
- `update($id)` - Actualizar
- `destroy($id)` - Eliminar (soft delete)
- `assignRole($id)` - Asignar rol
- `changeTeam($id)` - Cambiar equipo
- `profile()` - Perfil del usuario actual
- `updateProfile()` - Actualizar perfil propio

### ScheduleTemplateController.php
**Responsabilidad:** Gestión de plantillas de horarios
**Métodos principales:**
- `index()` - Listar plantillas
- `create()` - Crear plantilla
- `store()` - Guardar plantilla
- `show($id)` - Ver detalle
- `edit($id)` - Editar plantilla
- `update($id)` - Actualizar
- `destroy($id)` - Eliminar
- `apply($id)` - Aplicar plantilla

---

## 2. Servicios (app/Services)

### ScheduleService.php
**Responsabilidad:** Lógica de negocio de horarios
**Métodos principales:**
- `createSchedule(array $data): Schedule`
- `updateSchedule(Schedule $schedule, array $data): Schedule`
- `deleteSchedule(Schedule $schedule): bool`
- `copySchedule(Schedule $schedule, Carbon $newDate): Schedule`
- `copyWeekSchedules(Carbon $weekStart): Collection`
- `importFromCsv(UploadedFile $file, ScheduleTemplate $template): int`
- `validateCsvFormat(array $data): bool`
- `bulkAssignSchedules(array $userIds, array $scheduleData): int`
- `getConflictingSchedules(int $userId, Carbon $date): ?Schedule`
- `calculateScheduleHours(Schedule $schedule): int`

### TimeOffRequestService.php
**Responsabilidad:** Lógica de solicitudes
**Métodos principales:**
- `createRequest(array $data): TimeOffRequest`
- `approveRequest(TimeOffRequest $request, User $approver, ?string $notes): bool`
- `rejectRequest(TimeOffRequest $request, User $approver, ?string $notes): bool`
- `cancelRequest(TimeOffRequest $request): bool`
- `bulkApprove(array $requestIds, User $approver): int`
- `validateRequestDates(Carbon $start, Carbon $end, int $userId): bool`
- `checkVacationBalance(User $user, int $requestedDays): bool`
- `processShiftSwap(TimeOffRequest $request): bool`
- `getPendingRequestsForSupervisor(int $supervisorId): Collection`
- `notifyApprovalStatus(TimeOffRequest $request): void`

### AttendanceService.php
**Responsabilidad:** Lógica de asistencia
**Métodos principales:**
- `recordCheckIn(User $user, ?string $method = 'manual'): AttendanceLog`
- `recordCheckOut(User $user, ?string $method = 'manual'): AttendanceLog`
- `createManualAttendance(array $data): AttendanceLog`
- `calculateAttendanceStatus(AttendanceLog $log): string`
- `syncWithSchedule(AttendanceLog $log): void`
- `getAttendanceForPeriod(int $userId, Carbon $start, Carbon $end): Collection`
- `markAbsences(Carbon $date): int`
- `justifyAbsence(AttendanceLog $log, TimeOffRequest $request): bool`

### MetricsCalculator.php
**Responsabilidad:** Cálculo de métricas
**Métodos principales:**
- `punctualityRate(User $user, Carbon $start, Carbon $end): float`
- `absenteeismRate(User $user, Carbon $start, Carbon $end): float`
- `scheduleComplianceRate(User $user, Carbon $start, Carbon $end): float`
- `averageWorkedHours(User $user, Carbon $start, Carbon $end): float`
- `teamMetrics(Team $team, Carbon $start, Carbon $end): array`
- `departmentMetrics(Department $dept, Carbon $start, Carbon $end): array`
- `lateArrivalsCount(User $user, Carbon $start, Carbon $end): int`
- `earlyDeparturesCount(User $user, Carbon $start, Carbon $end): int`
- `calculateTrends(array $metrics, string $period): array`

### ReportService.php
**Responsabilidad:** Generación de reportes
**Métodos principales:**
- `generateAttendanceReport(array $filters): Collection`
- `generateComplianceReport(array $filters): Collection`
- `generatePunctualityReport(array $filters): Collection`
- `generateAbsenteeismReport(array $filters): Collection`
- `generateTeamSummary(int $teamId, Carbon $start, Carbon $end): array`
- `generateDepartmentSummary(int $deptId, Carbon $start, Carbon $end): array`
- `applyFilters(Builder $query, array $filters): Builder`
- `formatReportData(Collection $data): array`

### NotificationService.php
**Responsabilidad:** Notificaciones
**Métodos principales:**
- `notifyRequestCreated(TimeOffRequest $request): void`
- `notifyRequestApproved(TimeOffRequest $request): void`
- `notifyRequestRejected(TimeOffRequest $request): void`
- `notifyScheduleChanged(Schedule $schedule): void`
- `notifyUpcomingSchedule(User $user): void`
- `sendDailyScheduleReminders(): int`
- `sendWeeklyReportToSupervisors(): int`

### CsvImportService.php
**Responsabilidad:** Importación CSV
**Métodos principales:**
- `parseScheduleCsv(UploadedFile $file): array`
- `validateCsvHeaders(array $headers): bool`
- `validateCsvRow(array $row, int $rowNumber): array`
- `importSchedules(array $data, ScheduleTemplate $template): array`
- `handleImportErrors(array $errors): void`
- `generateImportReport(array $results): array`

### PdfGeneratorService.php
**Responsabilidad:** Generación de PDFs
**Métodos principales:**
- `generateSchedulePdf(Collection $schedules, User $user): string`
- `generateReportPdf(array $data, string $reportType): string`
- `generateAttendancePdf(Collection $attendance, array $filters): string`
- `generateTeamSchedulePdf(Team $team, Carbon $start, Carbon $end): string`

---

## 3. Componentes Livewire (app/Http/Livewire)

### PendingRequestsTable.php
**Responsabilidad:** Tabla de solicitudes pendientes
**Propiedades:**
- `$requests` - Collection de solicitudes
- `$selectedRequests` - Array de IDs seleccionados
- `$filters` - Filtros aplicados
**Métodos:**
- `mount()`
- `loadRequests()`
- `approveRequest($id)`
- `rejectRequest($id)`
- `bulkApprove()`
- `applyFilters()`

### TeamScheduleCalendar.php
**Responsabilidad:** Calendario de horarios del equipo
**Propiedades:**
- `$teamId`
- `$currentWeek`
- `$schedules`
**Métodos:**
- `mount($teamId)`
- `loadSchedules()`
- `nextWeek()`
- `previousWeek()`
- `goToToday()`

### AttendanceTracker.php
**Responsabilidad:** Registro de asistencia en tiempo real
**Propiedades:**
- `$userId`
- `$todayAttendance`
- `$currentSchedule`
**Métodos:**
- `mount()`
- `checkIn()`
- `checkOut()`
- `refresh()`

### ScheduleImporter.php
**Responsabilidad:** Importador de horarios CSV
**Propiedades:**
- `$csvFile`
- `$templateId`
- `$preview`
- `$errors`
**Métodos:**
- `mount()`
- `uploadCsv()`
- `previewData()`
- `processImport()`
- `cancelImport()`

### MetricsDashboard.php
**Responsabilidad:** Dashboard de métricas
**Propiedades:**
- `$period` - Período seleccionado
- `$metrics` - Array de métricas
- `$chartData`
**Métodos:**
- `mount()`
- `loadMetrics()`
- `changePeriod($period)`
- `refreshData()`

### UserSelector.php
**Responsabilidad:** Selector de usuarios para asignaciones
**Propiedades:**
- `$selectedUsers`
- `$availableUsers`
- `$searchTerm`
**Métodos:**
- `mount()`
- `search()`
- `selectUser($userId)`
- `removeUser($userId)`
- `selectAll()`

---

## 4. Form Requests (app/Http/Requests)

### ScheduleStoreRequest.php
**Validaciones:**
- user_id (required, exists:users)
- date (required, date, unique con user_id)
- start_time (required, date_format:H:i)
- end_time (required, date_format:H:i, after:start_time)
- break/lunch times (nullable, date_format)

### ScheduleImportRequest.php
**Validaciones:**
- csv_file (required, file, mimes:csv,txt, max:10240)
- template_id (required, exists:schedule_templates)

### TimeOffRequestFormRequest.php
**Validaciones:**
- type (required, in:cambio_turno,dia_libre,vacaciones,etc)
- start_date (required, date, after_or_equal:today)
- end_date (required, date, after_or_equal:start_date)
- reason (required_if:type,vacaciones, max:500)
- swap_with_user_id (required_if:type,cambio_turno)

### AttendanceStoreRequest.php
**Validaciones:**
- user_id (required, exists:users)
- date (required, date, unique con user_id)
- check_in_time (required, date)
- check_out_time (nullable, date, after:check_in_time)

### UserStoreRequest.php
**Validaciones:**
- name (required, string, max:255)
- email (required, email, unique:users)
- employee_code (required, unique:users)
- team_id (required, exists:teams)
- role (required, exists:roles,name)

### TeamStoreRequest.php
**Validaciones:**
- name (required, string, max:100)
- department_id (required, exists:departments)
- supervisor_id (nullable, exists:users)

---

## 5. Policies (app/Policies)

### SchedulePolicy.php
**Métodos:**
- `viewAny(User $user): bool`
- `view(User $user, Schedule $schedule): bool`
- `create(User $user): bool`
- `update(User $user, Schedule $schedule): bool`
- `delete(User $user, Schedule $schedule): bool`
- `import(User $user): bool`

### TimeOffRequestPolicy.php
**Métodos:**
- `viewAny(User $user): bool`
- `view(User $user, TimeOffRequest $request): bool`
- `create(User $user): bool`
- `update(User $user, TimeOffRequest $request): bool`
- `delete(User $user, TimeOffRequest $request): bool`
- `approve(User $user, TimeOffRequest $request): bool`

### AttendancePolicy.php
**Métodos:**
- `viewAny(User $user): bool`
- `view(User $user, AttendanceLog $log): bool`
- `create(User $user): bool`
- `update(User $user, AttendanceLog $log): bool`
- `delete(User $user, AttendanceLog $log): bool`

### TeamPolicy.php
**Métodos:**
- `viewAny(User $user): bool`
- `view(User $user, Team $team): bool`
- `create(User $user): bool`
- `update(User $user, Team $team): bool`
- `delete(User $user, Team $team): bool`
- `manageMembers(User $user, Team $team): bool`

---

## 6. Exports (app/Exports)

### AttendanceExport.php
**Propiedades:**
- `$filters`
- `$data`
**Métodos:**
- `collection(): Collection`
- `headings(): array`
- `map($row): array`

### SchedulesExport.php
### ComplianceReportExport.php
### PunctualityReportExport.php

---

## 7. Jobs (app/Jobs)

### ProcessScheduleImport.php
**Responsabilidad:** Importación masiva en background
**Propiedades:**
- `$csvData`
- `$templateId`
- `$userId`

### SendDailyScheduleReminders.php
**Responsabilidad:** Enviar recordatorios diarios
**Debe ejecutarse:** Diariamente a las 7:00 AM

### GenerateMonthlyReports.php
**Responsabilidad:** Generar reportes mensuales automáticos
**Debe ejecutarse:** Primer día de cada mes

### CalculateMonthlyMetrics.php
**Responsabilidad:** Calcular y cachear métricas mensuales
**Debe ejecutarse:** Último día del mes

### MarkDailyAbsences.php
**Responsabilidad:** Marcar ausencias automáticamente
**Debe ejecutarse:** Diariamente a las 11:59 PM

---

## 8. Middleware

### CheckTeamAccess.php
**Responsabilidad:** Verificar acceso a recursos del equipo

### CheckDepartmentAccess.php
**Responsabilidad:** Verificar acceso a recursos del departamento

### EnsureActiveUser.php
**Responsabilidad:** Verificar que el usuario esté activo

---

## 9. Observers (app/Observers)

### ScheduleObserver.php
**Métodos:**
- `creating()` - Log antes de crear
- `created()` - Log después de crear
- `updating()` - Log antes de actualizar
- `updated()` - Log después de actualizar
- `deleting()` - Log antes de eliminar

### TimeOffRequestObserver.php
**Métodos:**
- `created()` - Notificar creación
- `updated()` - Notificar cambios de estado

### AttendanceLogObserver.php
**Métodos:**
- `saving()` - Calcular compliance
- `created()` - Sincronizar con schedule

---

## 10. Events & Listeners

### Events:
- `ScheduleCreated`
- `ScheduleUpdated`
- `TimeOffRequestSubmitted`
- `TimeOffRequestApproved`
- `TimeOffRequestRejected`
- `AttendanceRecorded`

### Listeners:
- `NotifySupervisorOfRequest`
- `NotifyUserOfApproval`
- `UpdateAttendanceFromRequest`
- `SendScheduleReminder`

---

## Resumen de la Arquitectura

### Estado Actual:
**Archivos implementados:** ~65 (8 modelos + 9 controladores + 6 servicios + 3 livewire + 7 requests + 4 policies + 4 exports + 5 jobs + 3 observers + 8 events/listeners + vistas + rutas)
**Archivos pendientes:** ~5

### Plan Completo:
**Total de archivos planeados:** ~70
**Controladores:** 10 (✅ 9 implementados)
**Servicios:** 8 (✅ 6 implementados)
**Livewire Components:** 6 (✅ 3 implementados)
**Form Requests:** 6 (✅ 7 implementados)
**Policies:** 4 (✅ 4 implementados)
**Exports:** 4 (✅ 4 implementados)
**Jobs:** 5 (✅ 5 implementados)
**Middleware:** 3
**Observers:** 3 (✅ 3 implementados)
**Events/Listeners:** 10 (✅ 8 implementados)

