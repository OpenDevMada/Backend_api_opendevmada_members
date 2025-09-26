# 🚀 InfinityFree Deployment Guide - OpenDevMada Backend API

## Step 1: Sign Up & Create Account

1. **Visit** [infinityfree.net](https://infinityfree.net)
2. **Click** "Create Account" → Sign up with email
3. **Verify** your email address
4. **Login** to your InfinityFree account

## Step 2: Create Hosting Account

1. **Click** "Create Account" in your dashboard
2. **Choose Domain**:
   - Free subdomain: `yoursite.infinityfreeapp.com`
   - Or use your own domain (if you have one)
3. **Select** "Create Account"
4. **Wait** for account creation (2-5 minutes)

## Step 3: Access Control Panel

1. **Click** "Go to Control Panel" or "cPanel"
2. **Login** with the credentials provided
3. You should see the cPanel dashboard

## Step 4: Create MySQL Database

1. **Find** "MySQL Databases" in cPanel
2. **Create Database**:
   - In the "New Database" field, type: `opendevmada`
   - It will be automatically prefixed to: `if0_xxxxxxx_opendevmada`
   - Click "Create Database"

**Note**: InfinityFree automatically uses your account username and vPanel password for database access. You don't need to create separate database users.

3. **Note down your database details** (from the "Current Databases" table):
   - **Database Host**: `sql306.infinityfree.com` (or similar)
   - **Database Name**: `if0_40033946_opendevmada` (your actual name)
   - **Username**: `if0_40033946` (your account username)
   - **Password**: opendevmada

## Step 5: Import Database Schema

1. **Click** "phpMyAdmin" in cPanel
2. **Select** your database from the left sidebar
3. **Click** "Import" tab
4. **Choose File** → Upload your `db/opendevmad_db.sql`
5. **Click** "Go" to import
6. **Verify** tables were created successfully

## Step 6: Update Environment Configuration

1. **Open** `__env_production.php` (created earlier)
2. **Replace** the placeholders with your actual database details:

```php
define("DB_HOST", "sql200.infinityfree.com"); // Your actual host
define("DB_NAME", "if0_xxxxxxx_opendevmada"); // Your actual database name
define("DB_USER", "if0_xxxxxxx_admin");       // Your actual username
define("DB_PASSWORD", "your_strong_password"); // Your actual password
```

3. **Save** and **rename** `__env_production.php` to `__env.php`

## Step 7: Upload Files to Server

### Option A: File Manager (Easier)
1. **Click** "File Manager" in cPanel
2. **Navigate** to `htdocs` folder
3. **Delete** default files (index.html, etc.)
4. **Upload** your project files:
   - Select "Upload" button
   - Choose all your PHP files
   - Upload folders: `config/`, `controllers/`, `models/`, `routes/`, `public/`
5. **Extract** if uploaded as zip

### Option B: FTP (Advanced)
1. **Use** FTP client (FileZilla recommended)
2. **Connect** with FTP credentials from cPanel
3. **Upload** all files to `/htdocs/` directory

## Step 8: Set File Permissions

1. **In File Manager**, right-click on `public/images/` folder
2. **Change Permissions** to `755` (rwx-r-x-r-x)
3. **Apply to subfolders** if any

## Step 9: Test Your API

1. **Visit** your site: `https://opendevmadaannuaire.infinityfree.me`
2. **Test endpoints**:
   - `https://opendevmadaannuaire.infinityfree.me/api/opendevmada/membres`
   - `https://opendevmadaannuaire.infinityfree.me/api/opendevmada/membre-login`

### Test Login Endpoint:
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -d '{"email":"landros00t@gmail.com","password":"your_password"}' \
  https://opendevmadaannuaire.infinityfree.me/api/opendevmada/membre-login
```

## Step 10: Configure CORS (If Needed)

If you have a frontend on a different domain, update `routes/api.php`:

```php
// Replace * with your frontend domain for security
header("Access-Control-Allow-Origin: https://yourfrontend.com");
```

## 🔧 Troubleshooting

### Common Issues:

1. **500 Error**: Check file permissions and `.htaccess`
2. **Database Connection Error**: Verify credentials in `__env.php`
3. **API Not Working**: Ensure `.htaccess` is uploaded and working
4. **Images Not Uploading**: Check `public/images/` permissions (755)

### Debug Mode:
- Temporarily enable error display in `index.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 📋 Checklist

- [ ] InfinityFree account created
- [ ] MySQL database created and imported
- [ ] `__env.php` updated with correct credentials
- [ ] All files uploaded to `/htdocs/`
- [ ] File permissions set correctly
- [ ] API endpoints tested
- [ ] Production mode enabled (errors hidden)

## 🎉 Your API is Live!

Your OpenDevMada Backend API is now deployed and accessible at:
`https://opendevmadaannuaire.infinityfree.me/api/opendevmada/`

## 📝 Next Steps

1. **Test all endpoints** thoroughly
2. **Update frontend** to use new API URL
3. **Monitor** performance and errors
4. **Consider** adding SSL certificate (Let's Encrypt available on InfinityFree)

---

**Need Help?** 
- InfinityFree has great documentation and community support
- Check their knowledge base for hosting-specific issues