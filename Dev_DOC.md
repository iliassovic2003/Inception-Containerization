# Developer Documentation
*A comprehensive guide for developers to set up, build, and manage the Inception project, created by **izahr***

---

## 🔧 Prerequisites
### System Requirements

Before starting, ensure your system meets the following requirements:

- **Virtual Machine**: Required for the project
- **Docker**: Version 28.3.3 or higher
- **Docker Compose**: Version v2.36.2 or higher
- **Make**: GNU Make 4.0+
- **Git**: Version 2.49.1 For version control
- **Disk Space**: At least 10GB free space
- **Memory**: Minimum 2GB RAM

### Verify Installation

```bash
# Check Docker version
docker --version

# Check Docker Compose version
docker compose version

# Test Docker installation
docker run hello-world
```

## 🚀 Environment Setup from Scratch

### Step 1: Clone the Repository

```bash
git clone git@github.com:iliassovic2003/inception.git
cd inception
```


### Step 2: Project Directory Structure

The project follows this specific structure (as per subject requirements):

```
inception/
├── Makefile
├── secrets                          # Secret files (NOT in git)
│   ├── adminer_password.txt
│   ├── credentials.txt
│   ├── db_password.txt
│   ├── db_root_password.txt
│   └── vsftp_password.txt
└── srcs
    ├── docker-compose.yml            # Container orchestration
    ├── .env                          # Environment variables (NOT in git)
    └── requirements
        ├── bonus
        │   ├── adminer
        │   │   ├── Dockerfile
        │   │   └── tools
        │   │       └── init.sh
        │   ├── cAdvisor
        │   │   ├── Dockerfile
        │   │   └── tools
        │   │       └── cAdvisor_init.sh
        │   ├── ftp-server
        │   │   ├── Dockerfile
        │   │   ├── conf
        │   │   │   └── def.conf
        │   │   └── tools
        │   │       └── vsftp_init
        │   ├── redis-cache
        │   │   ├── Dockerfile
        │   │   └── config
        │   │       └── def.conf
        │   └── static-site
        │       ├── Dockerfile
        │       └── tools
        │           └── index.html
        ├── mariadb
        │   ├── Dockerfile
        │   ├── conf
        │   │   └── def.cnf
        │   └── tools
        │       └── mariaDB_script
        ├── nginx
        │   ├── Dockerfile
        │   ├── conf
        │   │   ├── def.conf
        │   │   └── ss.conf
        │   └── tools
        │       └── Nginx_script
        ├── tools
        └── wordpress
            ├── Dockerfile
            ├── conf
            └── tools
                └── wp_script

25 directories, 27 files
```

### Step 3: Create Data Directory on Host

According to the subject, volumes must be stored in `/home/<login>/data`:

```bash
mkdir -p /home/$USER/data/wordpress
mkdir -p /home/$USER/data/mariadb
mkdir -p /home/$USER/data/nginx
mkdir -p /home/$USER/data/cAdvisor
mkdir -p /home/$USER/data/adminer
mkdir -p /home/$USER/data/static-site
mkdir -p /home/$USER/data/redis-cache

chmod 755 /home/$USER/data
chmod 755 /home/$USER/data/wordpress
chmod 755 /home/$USER/data/mariadb
chmod 755 /home/$USER/data/nginx
chmod 755 /home/$USER/data/cAdvisor
chmod 755 /home/$USER/data/adminer
chmod 755 /home/$USER/data/static-site
chmod 755 /home/$USER/data/redis-cache
```

### Step 4: Update /etc/hosts

Configure your domain name to point to local IP:

```bash
sudo nano /etc/hosts
```

Add this line (replace `login` with your actual login):

```bash
127.0.0.1               login.42.fr
```
---

## 📝 Configuration Files

### 1. Environment Variables (.env)

An Example of `srcs/.env` file with the following variables:

```bash
#       HOST NAME
MAIN_NAME=izahr.42.fr

# 	    MYSQL SETUP
MYSQL_DATABASE=Dungeon
MYSQL_USER=Warden

#	    WORDPRESS SETUP
WP_HOME=https://izahr.42.fr
WP_SITEURL=izahr.42.fr
WP_ADMIN_USER=izahr
WP_ADMIN_EMAIL=test@izahr.42.fr
```

### 2. Docker Secrets
In The Directory `secrets/`, modify the password as an example:

- You can Simply Put a password
```bash
echo "YourSecureRootPassword123!" > secrets/db_root_password.txt
```

- Using OpenSSL method
```bash
echo "$(openssl rand -base64 24)" > secrets/vsftp_password.txt
```

- Using dev/urandom
```bash
cat /dev/urandom | tr -dc 'a-zA-Z0-9!@#$%^&*()_+-=' | head -c 24 > secrets/adminer_password.txt
```

and the list of tools goes on...

## Reset Everything

```bash
make fclean clean_data
make up
```

*This documentation is maintained as part of the 42 Inception project.*
