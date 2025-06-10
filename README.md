# SysDelivery

SysDelivery é um sistema de gerenciamento de delivery desenvolvido com CodeIgniter e containerizado com Docker para facilitar a implantação e o desenvolvimento.

---

## Tecnologias

- PHP 7.x/8.x
- CodeIgniter 4
- MySQL (via container Docker)
- Docker & Docker Compose

---

## Pré-requisitos

- Docker instalado
- Docker Compose instalado (normalmente já vem com Docker Desktop)
- Git (opcional, para clonar o repositório)

---

## Como executar o projeto

### 1. Clone o repositório (se ainda não fez)

```bash
git clone https://github.com/Thiagohfc/SysDelivery.git
cd webserver

docker compose up -d

cd www

cd codeigniter4

php spark migrate

php spark serve
