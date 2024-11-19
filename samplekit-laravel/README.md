# Daftarproperti.org Listing Sample Kit on Laravel

## Requirements
- PHP ^8.2
- MongoDB extension

## How to Set Up

1. **Configure the .env File**  
   Copy `.env.example` to `.env`
   Update the `.env` file with the following settings:
   - `DB_CONNECTION` = mongodb
   - `DB_URL` = your MongoDB URL
   - `DB_DATABASE` = your database name
   - `COLLECTION_NAME` = "listings"
   - `DP_REVEAL_BASE_URL` = "https://reveal.daftarproperti.org"
   - `DP_REVEAL_REFERRER_ID` = your referrer id for daftarproperti
   - `DP_CONTRACT_ADDRESS` = (see details below)
   - `BLOCKCHAIN_PROVIDER_URL` = (any provider that supports the Polygon network)
   - `ABI_VERSION` = (see details below)

   You can find `DP_CONTRACT_ADDRESS` and `ABI_VERSION` at [Daftarproperti Blockchain](https://daftarproperti.org/_blockchain).

2. **Install Laravel Dependencies**  
   Run the following command in the root directory:
   ```bash
   composer install
   ```

3. **Run Laravel Migrations**  
   In the root directory, run:
   ```bash
   php artisan migrate
   ```

4. **Set Up Synchronizer**  
   Navigate to the `synchronizer` folder and install the required packages:
   ```bash
   npm install
   ```

5. **Run the Synchronizer**  
   In the `synchronizer` folder, execute:
   ```bash
   node synchronizer.js
   ```

6. **Start the Laravel Development Server**  
   In a different terminal, run:
   ```bash
   php artisan serve
   ```

Now, you can access the sample kit with the data listings!
