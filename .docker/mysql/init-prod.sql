CREATE DATABASE `dockify_support` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT ENCRYPTION = 'N';

/* Give grant permission to user `dzil` to access `dockify_support`*/
GRANT ALL PRIVILEGES ON `dockify_support`.* TO 'dzil'@'%';
