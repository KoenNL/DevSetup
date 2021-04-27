FROM node:lts as vue-build
WORKDIR /app
RUN yarn global add @vue/cli
COPY ./admin/vue/ ./
