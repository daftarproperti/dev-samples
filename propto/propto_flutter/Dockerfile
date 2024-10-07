FROM ghcr.io/cirruslabs/flutter:stable AS build

RUN groupadd flutter && useradd -m -g flutter flutter

RUN chown -R flutter:flutter /sdks/flutter

USER flutter

WORKDIR /home/flutter/app

RUN git config --global --add safe.directory /sdks/flutter

COPY --chown=flutter:flutter . .

RUN flutter config --enable-web && \
    flutter pub get && \
    flutter build web

FROM nginx:alpine

COPY --from=build /home/flutter/app/build/web /usr/share/nginx/html

COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
