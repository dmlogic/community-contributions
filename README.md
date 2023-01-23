# Community Contributions

This project provides tools to build a small community who can register, contribute to funding and keep updated as to how that funding is being spent.

## Features

* Database of properties, residents and funds
* User roles for Admin, Resident and Supplier
* Admins can create a request for funding which is emailed to Residents
* Residents can pay via Stripe or manually log an offline payment
* Admins can manually log & verify offline payments and attribute to residents
* Suppliers (or Admins) can log upcoming or completed works and the charge to the fund
* Dashboard shows the current state of funding and upcoming work
* Resident contributions are anonymised to all expect admins

## Software stack

This is Laravel 9 app built from a Breeze/Intertia/Vue.js starter kit.

It has been built to make the most of AWS free tier services:

* Application API (a custom domain can be forward to the generated endpoint)
* Lambda invocation via Bref and serverless deploy with Github actions
* SQLite data storage on EFS (likely to be the only chargeable item)

The app could also be hosted anywhere that supports PHP8.2 and common extensions.

## Local development

### Testing

## Building

## Deployment
