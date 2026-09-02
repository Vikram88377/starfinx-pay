Starfinx Payments – Project Guide

Laravel 11 + Laravel Backpack | Pay-in & Payout Module

Project Overview

Laravel 11 + Laravel Backpack based Pay-in and Payout backend module.

Includes Merchants, Wallet/Balance, Pay-ins, Payouts, APIs, scheduled processing, validation, logging and Backpack CRUD.

Technology Stack

Laravel 11



Laravel Backpack

Eloquent ORM

Laravel Scheduler and Artisan Commands

Postman



API Endpoints:

POST /api/v1/payin — example JSON: { "merchant_id": 1, "amount": 500 }

POST /api/v1/payout — example JSON: { "merchant_id": 1, "amount": 200 }

API requests validate merchant_id and amount. New payments are created with PENDING status.

Pay-in Flow

API request → Form Request validation → PaymentService → unique PAYIN transaction ID → PENDING record.

php artisan payments processes pending records.

SUCCESS credits the merchant wallet; FAILED leaves the wallet unchanged; PENDING remains eligible for a later run.

Payout Flow

API request → validation → PaymentService → unique PAYOUT transaction ID → PENDING record.

On SUCCESS, wallet is debited after sufficient-balance check.

Insufficient balance results in FAILED and the wallet is not made negative.

Payment Processing Command

Manual processing: php artisan payments

The command processes pending pay-ins and payouts and records status/payment details.

Scheduler:

Local continuous scheduler: php artisan schedule

Configured scheduled task: payments every minute.


Wallet & Duplicate Safety

Wallet changes happen inside a database transaction.

lockForUpdate() protects relevant payment and wallet rows during processing.

Only PENDING records are eligible, and status is rechecked after locking.

This prevents duplicate credit/debit processing under concurrent execution.

Logging

Initiation logs transaction ID, merchant ID and amount.

Processing logs status changes, errors and insufficient-balance cases.

Backpack Admin

CRUD pages are provided for Merchants, Pay-ins, Payouts and Wallet/Balance.

Admin pages allow transaction and balance review and basic filtering.


