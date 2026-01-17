# Project Documentation

*This documentation should provides a decent understanding of the project, showing the application of the containerization concept, created by **izahr**.*

---

## What Is Docker ?
"Docker is an open platform for developing, shipping, and running applications. which enables you to separate your applications from your infrastructure so you can deliver software quickly..."(1), Containers are the mear aspect of this technology, it consist that rather than using a virtual machine with whole installation of the OS can lead to ressource exhausting and time consuming, the container already uses the kernel of the host machine, and requires the dependencies and libraries to function.

## Benefits
### 🚀 Portability
No more "it works on my machine" problems.

### ⚡ Efficiency
Has Lower Ressources Usage, Faster Startup and Lightweight Than most of VMs.

### 🛡️ Isolation
Docker Uses Technics To Trick the containers into thinking that even if they are in same memory, they are isolated. Like the use of Kernel Namespaces
"...Namespaces provide the first and most straightforward form of isolation. Processes running within a container cannot see, and even less affect, processes running in another container, or in the host system..." (2). {will be discussed much further below}

### 📈 Scalability
Perfect for microservices architecture, where a failing service doesn't condemn the whole program.

---

## Dockerfile
"...A Dockerfile is a text file containing instructions for building your source code..."(3), an automatization to make a Docker Image, which is a product with multiple layers, implemented to setup the service in the container, and ensure the work of it.

---

## Docker Compose
In Simple Terms, It is the Responsable of the comminucation and the gestion of multiple docker containers, with a sets of rules to implement, creating of a network, and volumes where the data may persist.

---

## Docker Network
Allowing the container networking, Since The containers are isolated in memory, a network is then needed to communicate within it Using Sets Of Ports.

### Why Custom Bridge Network?
- **DNS Resolution**: Containers can reach each other by name
- **Isolation**: Separated from default bridge network
- **Security**: No direct host network access
- **Control**: Custom subnet and gateway configuration

### Docker Network vs Host Network

| Network Mode | Description | Use Case | Isolation |
|--------------|-------------|----------|-----------|
| **Bridge (default)** | Isolated internal network with NAT | Default for containers | ✅ Full |
| **Host** | Shares host's network stack | Performance-critical apps | ❌ None |
| **Custom Bridge** | User-defined isolated network | Multi-container apps | ✅ Full |
| **None** | No networking | Completely isolated tasks | ✅ Maximum |

---

## Docker Volume
"Volumes are persistent data stores for containers, created and managed by Docker..."(4), it is a mean to keep data from being erased, ranges from user uploads to themes and preferences. But given the options, you can choose how to implement those data storages.

### Docker Volumes vs Bind Mounts

| Feature | Docker Volumes | Bind Mounts |
|---------|---------------|-------------|
| **Management** | Managed by Docker | Direct host path |
| **Location** | `/var/lib/docker/volumes/` | Anywhere on host |
| **Portability** | High (Docker handles paths) | Low (hardcoded paths) |
| **Performance** | Optimized | Depends on host FS |
| **Backup** | Easy with Docker tools | Manual process |
| **Security** | Docker-controlled permissions | Host filesystem permissions |

---

## Security
Despite the isolation of containers, there are many means of penetrating the security of such project, that ranges from Http protocol penetrating, to password leaking over environnement.

### Password Security
Docker implemented The Docker Secrets, requieres a password to be in a single file, to be taken then and provide him in the /docker/run directory, with atmost security, instead of being in an env file that get served to each container.

| Feature | Docker Secrets | Environment Variables |
|---------|----------------|----------------------|
| **Security** | Encrypted at rest and in transit | Visible in `docker inspect` |
| **Storage** | In-memory tmpfs only | Stored in container config |
| **Visibility** | Only to authorized services | Visible to all processes |
| **Best For** | Passwords, API keys, certificates | Configuration, non-sensitive data |
| **Example** | Database passwords | Domain names, ports |

### HTTPS Protocol
Since NGinx is the Webserver Responsable of the https request, and the past history of cyber-violence, The TLS (short for Transport Layer Security) has been born into this world.

| TLS Version | Year | Status | Key Features | Security | Browser Support | Deprecated |
|-------------|------|--------|--------------|----------|----------------|------------|
| **SSL 3.0** | 1996 | ❌ **DEAD** | First widely used, flawed design | ❌ **Broken** | None | **YES** (2015) |
| **TLS 1.0** | 1999 | ❌ **DEPRECATED** | SSL 3.0 upgrade, RC4, MD5 hash | ❌ **Vulnerable** | Disabled by default | **YES** (2021) |
| **TLS 1.1** | 2006 | ❌ **DEPRECATED** | Protection against CBC attacks | ⚠️ **Weak** | Disabled by default | **YES** (2021) |
| **TLS 1.2** | 2008 | ✅ **SECURE** | AEAD ciphers, SHA-256, GCM mode | ✅ **Strong** | 99.9% | **NO** |
| **TLS 1.3** | 2018 | ✅ **RECOMMENDED** | 0-RTT, modern ciphers only, forward secrecy | ✅ **Strongest** | 98%+ | **NO** |

---

## 🏗️ Docker Engine Architecture

```text
┌─────────────────────────────────────────────────────────────────────────┐
│                            HOST SYSTEM                                  │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌──────────────────┐        IPC/TCP        ┌─────────────────────────┐ │
│  │   Docker CLI     │◀─────────────────────▶│    Docker Daemon        │ │
│  │   (Client)       │      (JSON/REST)      │    (Server - dockerd)   │ │
│  │                  │                       │                         │ │
│  │  • Go binary     │                       │  • Persistent process   │ │
│  │  • Commands:     │                       │  • Manages:             │ │
│  │     docker run   │                       │    - Containers         │ │
│  │     docker ps    │                       │    - Images             │ │
│  │     docker build │                       │    - Networks           │ │
│  │                  │                       │    - Volumes            │ │
│  └────────┬─────────┘                       └───────────┬─────────────┘ │
│           │                                            │                │
│           │  ┌─────────────────────────────────────────┘                │
│           │  │                                                          │
│           ▼  ▼                                                          │
│  ┌─────────────────┐                                                    │
│  │ Communication   │                                                    │
│  │  Channel:       │                                                    │
│  │                 │                                                    │
│  │  Option 1:      │          Option 2:                                 │
│  │  ┌──────────┐   │          ┌──────────┐                              │
│  │  │ Unix     │   │          │ TCP      │                              │
│  │  │ Socket   │   │          │ Socket   │                              │
│  │  │ /var/run/│   │          │ 0.0.0.0: │                              │
│  │  │ docker.  │   │          │ 2375     │                              │
│  │  │ sock     │   │          │          │                              │
│  │  └──────────┘   │          └──────────┘                              │
│  │                 │                                                    │
│  └─────────────────┘                                                    │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### Docker CLI
Or So Called docker, is the Command-line interface for Docker operations. it means it's where the user interact with certain commands to start and stop the services, inspect the volumes, debug...
that needs to communicate using a UNIX socket or a TCP connection with the Docker Daemon.

### Docker Daemon

---

## 📋 Service Overview

This infrastructure consists of the following services:

### Core Services
- **NGINX** - Secure web server (HTTPS only, TLSv1.2/1.3)
- **WordPress** - Content management system with PHP-FPM
- **MariaDB** - Database server for WordPress data
- **Redis** - Cache service for improved performance

### Bonus Services
- **FTP Server** - File upload/download service
- **Adminer** - Database management interface
- **Static Website** - Custom landing page
- **cAdvisor** - Container performance monitoring

---



(1) https://docs.docker.com/get-started/docker-overview/
(2) https://docs.docker.com/engine/security/#kernel-namespaces
(3) https://docs.docker.com/build/concepts/dockerfile/
(4) https://docs.docker.com/engine/storage/volumes/
