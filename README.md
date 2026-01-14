# Personal Academic Manager

A monorepo project for managing personal academic tasks and resources.

## 📁 Project Structure

```
personal-academic-manager/
├── apps/
│   ├── backend/          # Laravel + Filament v4 Admin Panel
│   └── frontend/         # Reserved for PWA/Public UI
├── docs/                 # Project documentation
├── scripts/              # Development helper scripts
└── README.md             # This file
```

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel (latest stable compatible with Filament v4)
- **Admin Panel**: Filament v4 Panel Builder
- **Database**: SQLite (dev), MySQL (production)
- **Authentication**: Google SSO (laravel/socialite)
- **Packages**:
  - `spatie/laravel-tags` - Tagging system
  - `spatie/laravel-activitylog` - Activity logging
- **Assets**: Tailwind CSS (via Vite)

### Frontend
- Placeholder for future PWA/public UI implementation

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node >= 18
- npm

### Backend Setup

1. **Navigate to backend**
   ```bash
   cd apps/backend
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   # SQLite is pre-configured for development
   php artisan migrate
   ```

5. **Build Assets**
   ```bash
   npm run build
   # Or for development with hot reload:
   npm run dev
   ```

6. **Create Admin User**
   ```bash
   php artisan make:filament-user
   ```
   - Dev credentials (for local testing only):
     - Name: Bayu
     - Email: bayu@local.test
     - Password: DevOnly_ChangeMe123!

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

8. **Access Admin Panel**
   - Open browser: http://localhost:8000/admin
   - Login with admin credentials

## 📝 Notes

- **Database**: Currently using SQLite for development. MySQL should be configured for production.
- **Google SSO**: Requires `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` in `.env`
- **Cloud Storage**: R2 configuration placeholders available in `.env.example`

## 📚 Documentation

See the `docs/` folder for additional documentation.

## 🔐 Security

- Never commit `.env` files
- Change default dev credentials in production
- Configure proper CORS and security headers

## 📄 License

Private project.
