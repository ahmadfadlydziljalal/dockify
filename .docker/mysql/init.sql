CREATE DATABASE `dockify_test` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT ENCRYPTION = 'N';
CREATE DATABASE `dockify_support` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT ENCRYPTION = 'N';

/* Give grant permission to user `dzil` to access `dockify_test`*/
GRANT ALL PRIVILEGES ON `dockify_test`.* TO 'dzil'@'%';
GRANT ALL PRIVILEGES ON `dockify_support`.* TO 'dzil'@'%';
