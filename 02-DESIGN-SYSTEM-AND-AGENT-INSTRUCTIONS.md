# Design System and Coding Agent Instructions

## 1. Design Direction

Use the provided Stitch HTML as the primary visual reference.

Style:

**Botanical Heritage**

Characteristics:

- premium florist identity;
- deep emerald green;
- warm cream surfaces;
- restrained gold accents;
- editorial serif headings;
- clean sans-serif body text;
- generous whitespace;
- subtle elevation;
- professional and not overly decorative.

## 2. Brand Naming

Public brand:

```text
Little Joy Jakarta
```

System name:

```text
Little Joy Management
```

Do not mix:

```text
BloomManager
Botanical Management
Florist Pro
Botanical Elegance
```

## 3. Typography

Heading:

```text
Libre Caslon Text
```

Body and UI:

```text
Plus Jakarta Sans
```

## 4. Colour Tokens

```ts
export const colors = {
    primary: '#064E3B',
    primaryDark: '#003527',
    primarySoft: '#B0F0D6',
    primaryMuted: '#95D3BA',
    secondary: '#8A486F',
    secondarySoft: '#FFD8EA',
    tertiary: '#735C00',
    tertiarySoft: '#FFE088',
    background: '#F9FAF8',
    surface: '#FFFFFF',
    surfaceLow: '#F3F4F2',
    surfaceHigh: '#E7E8E6',
    text: '#191C1D',
    textMuted: '#404944',
    outline: '#707974',
    outlineSoft: '#BFC9C3',
    success: '#166534',
    warning: '#A16207',
    danger: '#BA1A1A',
    info: '#075985',
};
```

## 5. Tailwind Rules

- Do not use Tailwind CDN.
- Use Vite.
- Store theme tokens centrally.
- Do not repeat theme config in pages.

## 6. Required Components

### Common

```text
AppLogo
PageTitle
Breadcrumbs
Button
IconButton
Input
Textarea
Select
Checkbox
DateInput
FileUpload
SearchInput
Modal
ConfirmDialog
Alert
Toast
Pagination
EmptyState
LoadingState
ErrorState
StatusBadge
CurrencyText
```

### Navigation

```text
PublicNavbar
MobilePublicMenu
DashboardSidebar
DashboardTopbar
ProfileMenu
Footer
```

### Products

```text
ProductCard
ProductGrid
ProductFilter
ProductImageGallery
ProductPrice
StockIndicator
QuantitySelector
RelatedProducts
```

### Cart and Checkout

```text
CartItem
CartSummary
CheckoutForm
RecipientForm
OrderSummary
PaymentInstructions
PaymentProofUploader
```

### Orders

```text
OrderCard
OrderTable
OrderStatusBadge
PaymentStatusBadge
OrderTimeline
OrderItemsTable
OrderDetailSummary
```

### Dashboard

```text
MetricCard
SalesHighlightCard
StatusSummaryCard
RecentOrdersTable
LowStockList
SalesTrendChart
DateRangeFilter
ReportSummary
```

## 7. Required Pages

### Public

```text
Home
ProductCatalogue
ProductDetail
About
Contact
Login
Register
```

### Customer

```text
Cart
Checkout
CheckoutSuccess
PaymentUpload
OrderHistory
OrderDetail
Profile
```

### Operator

```text
Dashboard
OrderList
OrderDetail
PaymentVerification
StockOverview
```

### Admin

```text
Dashboard
CategoryList
CategoryForm
ProductList
ProductForm
CustomerList
OperatorList
OperatorForm
StockMovementList
SalesReport
```

## 8. Stitch Conversion Rules

1. Treat Stitch HTML as visual reference only.
2. Split pages into React components.
3. Convert links to Inertia `Link`.
4. Replace `href="#"` with named Laravel routes.
5. Replace dummy data with typed Inertia props.
6. Replace DOM query selectors with React state.
7. Remove duplicated Tailwind config.
8. Normalize branding.
9. Use Indonesian UI labels.
10. Add loading, empty, success, and error states.
11. Add responsive behavior.
12. Improve accessibility.
13. Do not paste all generated HTML into one giant component.

## 9. Language Normalization

```text
Orders       → Pesanan
Inventory    → Produk & Stok
Customers    → Pelanggan
Analytics    → Laporan
Settings     → Pengaturan
Support      → Bantuan
Shipped      → Sedang Dikirim
Pending      → Menunggu
Processing   → Sedang Diproses
```

## 10. Responsive Rules

- public navigation becomes a drawer on mobile;
- dashboard sidebar becomes a drawer;
- cards stack on small screens;
- tables scroll or convert to mobile cards;
- checkout actions remain visible;
- avoid fixed oversized hero height.

## 11. Coding Agent Master Prompt

Read:

```text
docs/00-ARCHITECTURE-AND-STACK.md
docs/01-PRODUCT-REQUIREMENTS.md
docs/02-DESIGN-SYSTEM-AND-AGENT-INSTRUCTIONS.md
```

Selected stack:

```text
Laravel
React
TypeScript
Inertia.js
Tailwind CSS
MySQL
phpMyAdmin
Vite
```

### Initial Analysis

Before changing code:

1. Inspect the repository.
2. Identify Laravel, PHP, Node.js, and package versions.
3. Identify whether React, Inertia, TypeScript, Tailwind, and Vite exist.
4. Inspect authentication.
5. Inspect routes, migrations, models, controllers, requests, policies, services, pages, components, tests, factories, and seeders.
6. Inspect database configuration.
7. Create a gap analysis.
8. Do not rewrite existing working features.

### Architecture Rules

1. Laravel is the backend.
2. React is the frontend.
3. Inertia connects Laravel and React.
4. Do not create a separate REST API.
5. Do not install React Router for primary navigation.
6. Use Laravel named routes and Inertia links.
7. Use Laravel session authentication.
8. Use MySQL.
9. Use migrations as source of truth.
10. Use TypeScript.
11. Use Tailwind through Vite.
12. Do not add Supabase.
13. Do not add packages without justification.

### Backend Rules

1. Keep controllers thin.
2. Use Form Requests.
3. Use Policies.
4. Use services/actions for complex logic.
5. Use database transactions.
6. Recalculate money totals on the server.
7. Store historical product names and prices.
8. Record every status and stock change.
9. Use enums or centralized constants.
10. Protect ownership.

### Testing Rules

1. Use a separate MySQL testing database.
2. Never run destructive tests against development.
3. Add Feature Tests for every module.
4. Add Unit Tests for transitions, totals, order numbers, and stock logic.
5. Never disable failing tests.
6. Run the full suite after each phase.
7. Report exact results.

### Work Sequence

#### Phase 0: Analysis

Produce:

- repository summary;
- architecture assessment;
- package assessment;
- gap analysis;
- proposed folder structure;
- ERD Mermaid;
- route map;
- component map;
- implementation phases;
- risks and decisions.

Do not modify code.

#### Phase 1: Foundation

- React/Inertia foundation;
- TypeScript;
- Tailwind;
- authentication;
- roles;
- middleware;
- layouts;
- navigation;
- design tokens;
- tests.

#### Phase 2: Categories and Products

- migrations;
- models;
- factories;
- seeders;
- validation;
- policies;
- admin pages;
- catalogue;
- tests.

#### Phase 3: Cart

- cart and cart items;
- stock validation;
- subtotal;
- tests.

#### Phase 4: Checkout and Orders

- checkout;
- order number;
- orders and order items;
- snapshots;
- transactions;
- tests.

#### Phase 5: Payments

- proof upload;
- verification;
- rejection;
- tests.

#### Phase 6: Status and Inventory

- status transitions;
- status history;
- stock movements;
- safe stock reduction;
- tests.

#### Phase 7: Dashboard and Reports

- metrics;
- recent orders;
- low stock;
- charts;
- reports;
- tests.

#### Phase 8: Final Refinement

- responsive;
- accessibility;
- error pages;
- seed data;
- README;
- black-box document;
- final test run.

### First Task

Perform Phase 0 only.

Do not modify application code yet.

Return:

1. Repository analysis.
2. Gap analysis.
3. Recommended architecture.
4. Mermaid ERD.
5. Route map.
6. Component map.
7. Phase implementation plan.
8. Risks and assumptions.
