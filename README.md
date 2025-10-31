# Afri-Ticket Twig

A lightweight PHP/Twig ticketing system with a dashboard and user authentication, deployed on Render.

## Live Demo

[https://afri-ticket-twig.onrender.com](https://afri-ticket-twig.onrender.com)

## Features

- User registration & login
- Dashboard with ticket statistics
- Create, edit, and delete support tickets
- Responsive design with modern UI (TailwindCSS)
- Twig templating for clean frontend structure
- Persistent JSON storage for users and tickets

## Project Structure

├── public/ # Web root (contains index.php and assets)
├── src/
│ ├── Controllers/ # PHP controllers (Auth, Dashboard, Ticket)
│ └── Utils/ # Utility classes (FakeApi, Storage, Validations)
├── templates/ # Twig templates and partials
├── composer.json
├── composer.lock
└── Dockerfile

markdown
Copy code

## Requirements

- PHP 8.2+
- Composer
- Apache with `mod_rewrite` enabled
- Docker (optional, for Render deployment)

## Deployment

Deployed on [Render](https://render.com) using Docker. The Dockerfile sets up:

- PHP 8.2 with Apache
- Composer dependencies
- Apache DocumentRoot set to `/public`
- Routing via `.htaccess`

To run locally:

```bash
php -S localhost:8000 -t public
```
