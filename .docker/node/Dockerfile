FROM node:24.1.0-slim

ENV TZ=Asia/Jakarta
WORKDIR /var/www/html

# Install deps, layer caching lebih cepat
COPY package*.json ./
RUN npm install --legacy-peer-deps

# Copy semua source code
COPY . .

# Jalankan dev otomatis
CMD ["npm", "run", "dev"]
