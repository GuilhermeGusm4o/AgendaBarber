FROM node:18

WORKDIR /app

COPY waitSymfony.sh /usr/local/bin/waitSymfony.sh

COPY . .

RUN chmod +x /usr/local/bin/waitSymfony.sh

RUN npm install
CMD ["sh", "/usr/local/bin/waitSymfony.sh", "npm", "run", "dev"]

