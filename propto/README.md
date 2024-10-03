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
## Full Application
Run the app by simply using this command:
```
docker-compose up -d
```

This command will create 4 containers. Below is the list of containers created:
- Mongo Db
    - Local MongoDB instance.

- Mongo Express

    - This container is created to ease documents viewing. To view documents, please refer to this section: [(View MongoDB Data)](#viewing-mongodb-data)

- Propto-Backend
    - Rust Back-End instance.

- Propto-Frontend
    - Flutter Front-End instance.

## Running Separate Instances
### Synchronizer
To run synchronizer instance separately, Use these commands:
```
cd synchronizer/

node synchronizer.js
```

or run the instance with Docker:
```
cd synchronizer/

docker run -d
```

### Back-End
To run Back-End instance separately, Use these commands:
```
cd backend/

cargo run
```

or run the instance with Docker:
```
cd backend/

docker run -d
```

### Front-End
To run Front-End instance separately, First Make sure to adjust VS Code Target Device to your desired target device. Then Use these commands:
```
cd propto_flutter/

flutter run
```

or run the instance with Docker:
```
cd propto_flutter/

docker run -d
```

## Viewing MongoDB Data
To view MongoDB Data inserted using Synchronizer, Simply follow these steps:
- Open following url in your browser: `localhost:8081`
- Login using default credentials:
-- username: `admin`
-- password: `pass`