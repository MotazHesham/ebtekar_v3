# Ebtekar Shipping — Modular Monolith Architecture

## Overview

Internal shipping is implemented as a **Modular Monolith** using [`nwidart/laravel-modules`](https://github.com/nWidart/laravel-modules). The **Order** aggregate remains in the core application (`App\Models\Order`, receipts). Shipping modules reference orders only through **contracts** and **DTOs**, never through business logic inside controllers.

```
┌─────────────────────────────────────────────────────────────────┐
│                        Core Application                          │
│  Order, ReceiptSocial, ReceiptCompany, User, Permissions         │
│  App\Integrations\Shipping\OrderSnapshotProvider (adapter)       │
└────────────────────────────┬────────────────────────────────────┘
                             │ Contracts / Events only
┌────────────────────────────▼────────────────────────────────────┐
│  Modules: Shipping │ Courier │ Dispatch │ Tracking │ Timeline   │
│           Returns │ Settlement │ Notifications │ Reports       │
└─────────────────────────────────────────────────────────────────┘
```

---

## Module Responsibilities

| Module | Owns | Does NOT own |
|--------|------|----------------|
| **Shipping** | `shipping_partners`, `delivery_orders` (Shipment), partner CRUD, shipment lifecycle, handoff to partner | Order lines, manufacturing, payments core |
| **Courier** | `deliver_men` (Courier profile), capacity, media, partner linkage | Shipment status transitions |
| **Dispatch** | Assignment rules, bulk assign, auto-assign (future) | Partner accounts |
| **Tracking** | Barcode scan handoff/receive, scan validation, live location (future) | Timeline persistence |
| **Timeline** | `delivery_timeline_events`, `delivery_notes`, `shipment_status_histories`, activity meta | Shipment business rules |
| **Returns** | Return reasons, return workflow, POD metadata hooks | Settlement math |
| **Settlement** | `delivery_settlements`, daily reconciliation | Shipment creation |
| **Notifications** | Listeners → queued notifications (push, SMS, WhatsApp later) | Domain state |
| **Reports** | Read models, exports, dashboards queries | Writes |

---

## Recommended Database Schema (scalable)

### Existing tables (Phase 1 — kept)

- `shipping_partners`
- `delivery_orders` — aggregate root for shipment (class: `Shipment`)
- `deliver_men` — courier profiles
- `delivery_timeline_events`
- `delivery_notes`
- `delivery_settlements`

### New tables (Phase 2+)

| Table | Module | Purpose |
|-------|--------|---------|
| `shipment_status_histories` | Timeline | Immutable status transitions (SLA, duration per stage) |
| `shipment_activity_logs` | Timeline | Generic audit (IP, device, actor) |
| `dispatch_batches` | Dispatch | Bulk assignment runs |
| `dispatch_batch_items` | Dispatch | Orders in a batch |
| `tracking_scans` | Tracking | Scan log (duplicate detection) |
| `return_cases` | Returns | Return header linked to shipment |
| `return_attachments` | Returns | Images |
| `courier_settlement_lines` | Settlement | Per-shipment collection lines |
| `notification_deliveries` | Notifications | Outbound message log |

### Column additions

```sql
-- delivery_orders
uuid CHAR(36) UNIQUE NOT NULL  -- public API / tracking links
current_courier_id BIGINT NULL -- denormalized for queries (FK deliver_men)

-- shipping_partners, deliver_men
uuid CHAR(36) UNIQUE NOT NULL
```

### Enums (PHP 8.1 backed enums per module)

- `Modules\Shipping\Enums\ShipmentStatus`
- `Modules\Returns\Enums\ReturnReason`
- `Modules\Settlement\Enums\SettlementStatus`
- `Modules\Courier\Enums\CourierStatus`

---

## Events Map (cross-module)

| Event | Publisher | Listeners |
|-------|-----------|-----------|
| `ShipmentCreated` | Shipping | Timeline → record created; Notifications → queue alert |
| `ShipmentHandedToPartner` | Shipping | Tracking → log scan; Notifications |
| `ShipmentStatusChanged` | Shipping | Timeline → status history + event row; Notifications; **App** → sync core `delivery_status` |
| `ShipmentAssignedToCourier` | Dispatch | Shipping → update FK; Timeline; Notifications |
| `ShipmentDelivered` | Shipping / Tracking | Settlement → prepare line; Notifications |
| `ShipmentReturned` | Returns | Timeline; Notifications; Reports cache invalidation |
| `CourierSettlementClosed` | Settlement | Notifications |
| `ScanMismatchDetected` | Tracking | Notifications → ops alert |

**Rule:** Modules MUST NOT call another module's repositories directly. Use events or injected **contracts**.

---

## Contracts (core `app/Contracts/Shipping`)

| Contract | Implemented by |
|----------|----------------|
| `OrderSnapshotProviderContract` | `App\Integrations\Shipping\OrderSnapshotProvider` |
| `ShipmentServiceContract` | `Modules\Shipping\Services\ShipmentService` |
| `CourierQueryContract` | `Modules\Courier\Services\CourierQueryService` |
| `TimelineRecorderContract` | `Modules\Timeline\Services\TimelineRecorder` |

---

## Permissions Map

| Permission | Module |
|------------|--------|
| `shipping_partner_*` | Shipping |
| `delivery_order_*` (legacy name) | Shipping (alias `shipment_*` later) |
| `deliver_man_*` | Courier |
| `delivery_scan_handoff` | Tracking |
| `delivery_assign_courier` | Dispatch |
| `delivery_settlement_access` | Settlement |
| `delivery_reports_access` | Reports |

---

## API Architecture (`/api/v1`)

| Prefix | Module | Examples |
|--------|--------|----------|
| `/api/v1/shipments` | Shipping | `GET /{uuid}`, `PATCH /{uuid}/status` |
| `/api/v1/partners` | Shipping | Partner dashboard stats |
| `/api/v1/couriers` | Courier | Profile, active shipments |
| `/api/v1/dispatch` | Dispatch | `POST /assign` |
| `/api/v1/tracking` | Tracking | `POST /scan/handoff`, `POST /scan/receive` |
| `/api/v1/timeline` | Timeline | `GET /shipments/{uuid}/timeline` |
| `/api/v1/returns` | Returns | `POST /shipments/{uuid}/return` |
| `/api/v1/settlements` | Settlement | `GET /couriers/{id}/today` |

- Auth: `auth:sanctum`
- Responses: `Modules\{Module}\Http\Resources\*`
- Versioning: URI prefix only (`v1`); breaking changes → `v2`

### Web routes (admin)

Registered per module under `admin` + middleware `auth`, `staff`:

- `admin/delivery-orders` → Shipping (backward compatible)
- `admin/shipping-partners` → Shipping
- `admin/deliver-men` → Courier

---

## Clean Architecture Layers (per module)

```
Http/Controllers     → HTTP only, authorize, delegate to Service
Http/Requests        → Validation
Http/Resources       → API transformation
Services/            → Business rules, transactions, dispatch events
Repositories/        → Eloquent queries only
Entities/            → Models
Enums/               → Statuses
Events/              → Domain events
Listeners/           → React to own or foreign events (via contract)
Jobs/                → Async work
Policies/            → Authorization
Providers/           → Bind contracts, merge config, load migrations
```

---

## Phase 1 file locations (after migration)

| Was (App) | Now (Module) |
|-----------|----------------|
| `database/migrations/2026_05_22_100*` | `Modules/*/Database/Migrations/2026_05_22_090*` |
| `app/Models/DeliveryOrder` | `Modules/Shipping/Entities/Shipment` (+ alias) |
| `app/Models/ShippingPartner` | `Modules/Shipping/Entities/ShippingPartner` (+ alias) |
| `app/Models/DeliverMan` | `Modules/Courier/Entities/Courier` (+ alias) |
| `app/Models/DeliveryTimelineEvent` | `Modules/Timeline/Entities/TimelineEvent` (+ alias) |
| `app/Models/DeliveryNote` | `Modules/Timeline/Entities/ShipmentNote` (+ alias) |
| `app/Models/DeliverySettlement` | `Modules/Settlement/Entities/Settlement` (+ alias) |
| `app/Services/DeliveryOrderService` | `Modules/Shipping/Services/ShipmentService` |
| `app/Http/Controllers/Admin/*Delivery*` | `Modules/Shipping/Http/Controllers/Admin/*` |
| `app/Http/Controllers/Admin/DeliverMan*` | `Modules/Courier/Http/Controllers/Admin/CourierWebController` |
| `resources/views/admin/deliveryOrders` | `Modules/Shipping/Resources/views/admin/deliveryOrders` |
| `resources/lang/*/delivery*.php` | `Modules/Shipping/Resources/lang/*` |

Core-only (stays in `app/`): `Integrations/Shipping/OrderSnapshotProvider`, `Contracts/Shipping/*`, `Listeners/Shipping/SyncCoreOrderOnShipmentStatusChanged`.

## Implementation Order

1. ✅ Module scaffolding + Phase 1 moved into modules
2. **Shipping** — entities, repo, service, events, web/API routes
3. **Timeline** — recorder + listeners for shipment events
4. **Courier** — profile module + media
5. **Dispatch** — assignment service + listener
6. **Tracking** — scan endpoints (Phase 2)
7. **Returns**, **Settlement**, **Notifications**, **Reports** — incremental

---

## Backward Compatibility

Legacy class aliases remain until admin views are namespaced:

- `App\Models\DeliveryOrder` → `Modules\Shipping\Entities\Shipment`
- `App\Models\ShippingPartner` → `Modules\Shipping\Entities\ShippingPartner`
- `App\Models\DeliverMan` → `Modules\Courier\Entities\Courier`

Legacy routes in `routes/web.php` are removed; module `RouteServiceProvider` registers equivalent routes.
