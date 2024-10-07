FROM rust:1.81-alpine3.19

RUN apk add --no-cache \
    musl-dev \
    gcc \
    libc-dev \
    build-base \
    linux-headers

WORKDIR /app

COPY Cargo.toml Cargo.lock ./

RUN mkdir src && echo "fn main() {}" > src/main.rs

RUN --mount=type=cache,target=/usr/src/app/target \
    --mount=type=cache,target=/usr/local/cargo/git/db \
    --mount=type=cache,target=/usr/local/cargo/registry \
    cargo build --release

COPY src src
RUN touch src/main.rs

RUN --mount=type=cache,target=/usr/src/app/target \
    --mount=type=cache,target=/usr/local/cargo/git/db \
    --mount=type=cache,target=/usr/local/cargo/registry \
    cargo build --release

CMD ["./target/release/propto-backend"]