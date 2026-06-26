# Product Requirements Document

## 1. Product Name

**Little Joy Florist Ordering System**

## 2. Product Goal

Build a web-based ordering system that replaces manual flower ordering through WhatsApp with a structured and centrally managed application.

## 3. User Roles

### Guest

- view landing page;
- view product catalogue;
- search and filter products;
- view product details;
- register;
- login.

### Customer

- manage profile;
- manage cart;
- checkout;
- enter recipient and delivery information;
- upload payment proof;
- view order history;
- view status timeline;
- cancel eligible orders.

### Operator

- inspect orders;
- verify or reject payment proof;
- update order status;
- add operational notes;
- inspect stock.

### Admin

- all operator capabilities;
- manage categories;
- manage products;
- manage stock;
- manage operators;
- inspect customers;
- inspect reports and dashboard metrics.

## 4. Core Modules

### Authentication

Acceptance criteria:

1. Email must be unique.
2. Passwords must be hashed.
3. Guests cannot access protected pages.
4. Customers cannot access operator or admin pages.
5. Inactive users cannot log in.

### Categories

Data:

- name;
- slug;
- description;
- active status.

### Products

Data:

- category;
- name;
- slug;
- description;
- price;
- stock;
- image;
- active status;
- soft delete.

Acceptance criteria:

1. Price and stock cannot be negative.
2. Inactive products cannot be purchased.
3. Out-of-stock products are unavailable.
4. Historical order items remain intact.

### Catalogue

- pagination;
- category filter;
- availability filter;
- search;
- price sorting;
- product detail;
- related products.

### Cart

- add product;
- update quantity;
- remove item;
- clear cart;
- calculate subtotal.

Rules:

1. Customer must be logged in.
2. Quantity must not exceed stock.
3. Duplicate products update the quantity.
4. Server recalculates subtotal.

### Checkout

Required data:

- recipient name;
- recipient phone;
- address;
- delivery date;
- greeting message;
- customer note;
- subtotal;
- delivery fee;
- total.

Rules:

1. Cart cannot be empty.
2. Delivery date cannot be in the past.
3. Product stock and price must be rechecked.
4. Checkout uses a database transaction.
5. Order number must be unique.
6. Product name and price are stored as snapshots.
7. Cart is cleared after success.
8. Initial status is `pending_payment`.

Order number:

```text
LJ-YYYYMMDD-XXXX
```

### Payments

Method:

- manual bank transfer.

Statuses:

```text
pending
waiting_verification
verified
rejected
```

Rules:

1. Customers upload proof only for their own order.
2. Allowed files: JPG, JPEG, PNG, WebP.
3. Maximum size: 2 MB.
4. Rejection requires a reason.
5. Verification uses a transaction.
6. Successful verification reduces stock.
7. Duplicate verification is rejected.

### Orders

Statuses:

```text
pending_payment
waiting_verification
paid
processing
ready
shipped
completed
cancelled
rejected
```

Allowed transitions:

```text
pending_payment → waiting_verification
pending_payment → cancelled
waiting_verification → paid
waiting_verification → rejected
rejected → waiting_verification
rejected → cancelled
paid → processing
processing → ready
ready → shipped
shipped → completed
```

Every status change must create a history record.

### Inventory

Movement types:

```text
in
out
adjustment
```

Rules:

1. Stock decreases after payment verification.
2. Every stock change is recorded.
3. Stock cannot become negative.
4. Manual adjustments require a note.

### Dashboard

Admin metrics:

- total sales;
- orders today;
- pending payments;
- waiting verification;
- processing orders;
- shipped orders;
- completed orders;
- low-stock products;
- recent orders;
- weekly sales trend.

### Reports

Filters:

- start date;
- end date;
- order status;
- payment status.

Metrics:

- transaction count;
- total revenue;
- products sold;
- best-selling products;
- transaction details.

## 5. Database Tables

### users

```text
id
name
email
phone
password
role
address
is_active
email_verified_at
remember_token
created_at
updated_at
```

### categories

```text
id
name
slug
description
is_active
created_at
updated_at
```

### products

```text
id
category_id
name
slug
description
price
stock
image_path
is_active
created_at
updated_at
deleted_at
```

### carts

```text
id
user_id
created_at
updated_at
```

### cart_items

```text
id
cart_id
product_id
quantity
unit_price
subtotal
created_at
updated_at
```

### orders

```text
id
order_number
user_id
order_date
delivery_date
recipient_name
recipient_phone
delivery_address
greeting_message
customer_note
operator_note
subtotal
delivery_fee
total
payment_status
order_status
cancelled_at
completed_at
created_at
updated_at
```

### order_items

```text
id
order_id
product_id
product_name
unit_price
quantity
subtotal
created_at
updated_at
```

### payments

```text
id
order_id
payment_method
destination_bank
sender_bank
account_holder_name
amount
transfer_date
proof_path
verification_status
verified_by
verified_at
rejection_note
created_at
updated_at
```

### order_status_histories

```text
id
order_id
previous_status
current_status
note
changed_by
created_at
updated_at
```

### stock_movements

```text
id
product_id
movement_type
quantity
stock_before
stock_after
reference_type
reference_id
note
created_by
created_at
updated_at
```

## 6. Required Automated Tests

- registration and login;
- role authorization;
- catalogue visibility;
- cart quantity validation;
- checkout success and rollback;
- unique order number;
- price snapshots;
- payment upload validation;
- payment verification and rejection;
- stock reduction;
- stock history;
- legal and illegal status transitions;
- customer order ownership;
- admin CRUD;
- report filters;
- soft-deleted product visibility.

## 7. Seeder Requirements

Create:

- one admin;
- two operators;
- five customers;
- five categories;
- twenty products;
- sample orders;
- sample payments;
- status histories;
- stock movements.

## 8. Definition of Done

A module is complete only when:

1. Migration exists.
2. Model and relationships exist.
3. Validation exists.
4. Authorization exists.
5. React page exists.
6. Loading, empty, success, and error states exist.
7. Tests exist and pass.
8. Interface is responsive.
9. README is updated.

## 9. Out of Scope

- payment gateway;
- WhatsApp API;
- GPS tracking;
- automated shipping integration;
- multi-branch;
- loyalty points;
- complex coupons;
- live chat;
- AI recommendations;
- mobile app;
- marketplace;
- separate REST API;
- JWT authentication.
