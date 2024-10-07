# Propto

Propto is developer sample kit to kickstart developers to use and implement [DaftarProperti](https://daftarproperti.org/) into apps.

# Depedencies
- Docker. Refer to this guide to install: [Install Docker](https://docs.docker.com/engine/install/)

However to build applications separately, then make sure to install these components:
For Front-End:
- Flutter. Refer to this guide to install: [Install Flutter](https://docs.flutter.dev/get-started/install)

For Back-End:
- Rust. Refer to this guide to install: [Install Rust](https://www.rust-lang.org/tools/install)

For Synchronizers:
- NodeJS and NPM. Refer to this guide to install: [Install Node JS and NPM](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

# Running App
> 💡 **Tip:** 
> By default, only flutter-web applications will be exposed to localhost at port `8080`. To change this, adjust this line in `docker-compose.yml`:
> 
> ```
> flutter-web:
>   build:
>     context: ./propto_flutter
>     dockerfile: Dockerfile
>   container_name: flutter
>   ports:
>     - "<change_this_to_your_free_port>:80"
> ```
> 
> To expose any other exposeable service to localhost, simply uncomment the `ports` section in each service.

## Full Application
1. Create a `.env` file

2. Copy the value from `.env-example` to `.env` and provides any missing value

3. Run the app by simply using this command:
    ```
    docker-compose up -d
    ```

This command will create 5 containers. Below is the list of containers created:
- Mongo Db
    - Local MongoDB instance.

- Mongo Express

    - This container is created to ease documents viewing. To view documents, please refer to this section: [(View MongoDB Data)](#viewing-mongodb-data)

- Synchronizer
    - This container is create to synchronize data from blockchain to mongoDB instance.

- Propto-Backend
    - Rust Back-End instance.

- Propto-Frontend
    - Flutter Front-End instance.

Then open desired browser and view the app at `localhost:8080`.

## Running Separate Instances
To run separate instances, simply comment everything unnecessary in `docker-compose.yml` except for MongoDB and Mongo-Express since both are needed for local storage (If external mongoDB is used, simply adjust the env).

### Synchronizer
To run synchronizer instance separately, Use these commands:
```
cd synchronizer/

node synchronizer.js
```

### Back-End
To run Back-End instance separately, Use these commands:
```
cd backend/

cargo run
```

### Front-End
To run Front-End instance separately, First Make sure to adjust VS Code Target Device to your desired target device. Then Use these commands:
```
cd propto_flutter/

flutter run
```

or to try running in Platform VM instance, comment flutter-web instance in `docker-compose.yml` and run the docker-compose.
Make sure target device is set to either Android or IOS. Then run following command:
```
cd propto_flutter/

flutter run
```

to specify custom backend, run following command:
```
cd propto_flutter/

# For Web
flutter run --dart-define=WEB_BACKEND=<custom_backend_address_for_web>

# For Android or IOS
flutter run --dart-define=MOBILE_BACKEND=<custom_backend_address_for_mobile>
```

> 💡 **Tip:** 
> For Android or IOS, `localhost` is not supported. To use localhost in development machine, change `localhost` to `http://10.0.2.2:<port>`

## Viewing MongoDB Data
To view MongoDB Data inserted using Synchronizer, Simply follow these steps:
- Open following url in your browser: `localhost:8055`
- Login using default credentials:
-- username: `admin`
-- password: `pass`

# Resources
## Back-End

### cURL
Below attached a sample cURL command to test backend API:
```
curl --location 'http://localhost:8001/properties?search_text=Rumah&min_bathroom_count=4&max_bathroom_count=7&min_bedroom_count=4&max_bedroom_count=7&min_building_size=50&max_building_size=150&min_lot_size=100&max_lot_size=500&min_price=1000000&max_price=10000000000&ownership=shm' \
--header 'Content-Type: application/json'
```