<h1 align="center">Code Challenge API</h1>
<h4 align="center">By: Bryan Mangulabnan</h4>

## Requirements
- Docker
- API testing tool like Postman (https://www.postman.com/)

## Installation
1. `cd [Project's Base Dir]`
2. `docker-compose up`
3. Open postman and check `https://localhost/customers`

## Customer Import
- `bin/console data:customer-import` to store customers from the 3rd party (https://randomuser.me/api)
- Optional args to set the number of users to store `bin/console data:customer-import 125`. 100 users minimum and by default.
- Existing users (email) will just update their details
- User passwords are hashed (md5)

## RandomUserService (App\Service)
A service that calls the 3rd party API to fetch the collection users and return them as an array.

Users that are fetched are AU nationalities.

## CustomerRepository (App/Repository)
These were we store those fetched users into customers table by batch.

## Parameters (api/config/services.yml)
These where some configurations are stored just in case there are requirements changes.

## CustomersTest
- `testGetCustomers` to test `/customers` endpoint
- `testGetCustomerById` to test `/customers/{id}` endpoint for existing user
- `testGetNonExistentCustomerById` to test `/customers/{id}` endpoint for non-existing user

## Other Tools
1. Swagger (https://localhost/docs)
2. Admin UI (https://localhost/admin)
