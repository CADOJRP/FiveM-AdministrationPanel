# JGN Admin Panel

Modern FiveM Administration Panel with Discord OAuth and real-time communication.

**Panel URL:** https://staff.jgn.gg

## Features

- ✅ **Discord OAuth** - Login with Discord, permissions sync from roles
- ✅ **Multi-Identifier Bans** - Ban by fivem:, discord:, license2:, steam:, etc.
- ✅ **Server Communication** - HTTP API with FiveM servers (WebSocket ready via Laravel Reverb)
- ✅ **Trust Score System** - Automatic player reputation tracking
- ✅ **Discord Bot** - /profile and /lookup commands
- ✅ **Discord Webhooks** - Automatic notifications for bans, kicks, and warns
- ✅ **Plesk Compatible** - Easy drag-and-drop deployment
- ✅ **No Cron Jobs** - Everything works in real-time
- ✅ **7 FiveM Identifiers** - fivem, discord, license2, license, steam, xbl, ip

## Requirements

| Component | Version |
|-----------|---------|
| PHP | 8.2 or 8.3 |
| MySQL | 8.x |
| Composer | 2.x |
| Node.js | 18+ (for Discord bot only) |

### Required PHP Extensions
- BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL

---

## Plesk Installation (Step-by-Step)

### Step 1: Create Domain in Plesk

1. Log into Plesk Panel
2. Go to **Websites & Domains** → **Add Domain**
3. Domain name: `staff.jgn.gg` (or your domain)
4. Enable **SSL/TLS Certificate** (Let's Encrypt)
5. Click **OK**

### Step 2: Create MySQL Database

1. Go to **Databases** → **Add Database**
2. Database name: `jgn_panel`
3. Create a database user with a strong password
4. Note down: database name, username, password

### Step 3: Configure PHP Settings

1. Go to **Websites & Domains** → **PHP Settings**
2. Set PHP version to **8.2** or **8.3**
3. Ensure these extensions are enabled:
   - `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json`, `xml`

### Step 4: Set Document Root

1. Go to **Websites & Domains** → **Hosting & DNS** → **Hosting Settings**
2. Change **Document Root** from `/httpdocs` to `/httpdocs/public`
3. Click **OK**

> ⚠️ **IMPORTANT**: The document root MUST point to the `/public` folder, not the main project folder!

### Step 5: Upload Files

**Option A: SSH/Terminal**
```bash
cd /var/www/vhosts/staff.jgn.gg/httpdocs

# Clone or upload files (entire new-panel folder contents go here)
# The structure should be:
# /httpdocs/app/
# /httpdocs/public/
# /httpdocs/composer.json
# etc.
```

**Option B: File Manager**
1. Go to **Files** in Plesk
2. Navigate to `httpdocs`
3. Upload all project files (app/, public/, composer.json, etc.)

### Step 6: Install Dependencies

Via Plesk Terminal or SSH:
```bash
cd /var/www/vhosts/staff.jgn.gg/httpdocs

# IMPORTANT: On Plesk, PHP is not in PATH. Use full path:
# Check available PHP versions:
ls /opt/plesk/php/*/bin/php

# Create required directories FIRST
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} storage/logs

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Install Composer dependencies (use PHP 8.2 or 8.3)
/opt/plesk/php/8.2/bin/php /usr/lib/plesk-9.0/composer.phar install --optimize-autoloader --no-dev

# Copy environment file
cp .env.example .env

# Generate application key
/opt/plesk/php/8.2/bin/php artisan key:generate
```

> 💡 **Tip**: Add PHP to your PATH for easier commands:
> ```bash
> export PATH=/opt/plesk/php/8.2/bin:$PATH
> ```


### Step 7: Configure Environment

Edit `.env` file with your settings:

```env
# ===== Panel Settings =====
APP_NAME="JGN Admin Panel"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://staff.jgn.gg
COMMUNITY_NAME="JGN Gaming"

# ===== Database (from Plesk Step 2) =====
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=jgn_panel
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# ===== Discord App (see Step 8) =====
DISCORD_CLIENT_ID=your_client_id
DISCORD_CLIENT_SECRET=your_client_secret
DISCORD_BOT_TOKEN=your_bot_token
DISCORD_GUILD_ID=your_server_id
DISCORD_REDIRECT_URI=https://staff.jgn.gg/auth/discord/callback

# ===== Discord Role IDs =====
DISCORD_ROLE_OWNER=123456789012345678
DISCORD_ROLE_ADMIN=123456789012345678
DISCORD_ROLE_MODERATOR=123456789012345678
DISCORD_ROLE_TRIAL=123456789012345678

# ===== Discord Webhook (Optional) =====
DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/xxx/xxx
```

### Step 8: Discord Application Setup

1. Go to https://discord.com/developers/applications
2. Click **New Application** → Name it "JGN Admin Panel"
3. Go to **OAuth2** → **General**:
   - Copy **Client ID** → paste to `DISCORD_CLIENT_ID`
   - Copy **Client Secret** → paste to `DISCORD_CLIENT_SECRET`
4. Go to **OAuth2** → **Redirects**:
   - Add: `https://staff.jgn.gg/auth/discord/callback`
5. Go to **Bot**:
   - Click **Add Bot**
   - Copy **Token** → paste to `DISCORD_BOT_TOKEN`
   - Enable: **Server Members Intent**, **Message Content Intent**

### Step 9: Get Discord Role IDs

1. Open Discord → User Settings → Advanced → Enable **Developer Mode**
2. Go to your Discord server → Server Settings → Roles
3. Right-click each role → **Copy ID**:
   - Owner role ID → `DISCORD_ROLE_OWNER`
   - Admin role ID → `DISCORD_ROLE_ADMIN`
   - Moderator role ID → `DISCORD_ROLE_MODERATOR`
   - Trial role ID → `DISCORD_ROLE_TRIAL`

### Step 10: Get Your Guild (Server) ID

1. Right-click your Discord server icon → **Copy ID**
2. Paste to `DISCORD_GUILD_ID`

### Step 11: Run Database Migrations

```bash
cd /var/www/vhosts/staff.jgn.gg/httpdocs
/opt/plesk/php/8.2/bin/php artisan migrate
```

### Step 12: Set File Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 13: Cache Configuration (Production)

```bash
/opt/plesk/php/8.2/bin/php artisan config:cache
/opt/plesk/php/8.2/bin/php artisan route:cache
/opt/plesk/php/8.2/bin/php artisan view:cache
```

### Step 14: Test the Panel

1. Visit https://staff.jgn.gg
2. Click **Login with Discord**
3. Authorize the application
4. You should be redirected to the dashboard!

---

## FiveM Resource Setup

### Step 1: Copy Resource

Copy the `fivem-resource` folder to your FiveM server:
```bash
/resources/[admin]/fivem-admin/
```

### Step 2: Configure Resource

Edit `config.lua`:
```lua
Config.PanelURL = "https://staff.jgn.gg"
Config.ServerToken = "YOUR_TOKEN_HERE"  -- Get from Panel → Servers
Config.ServerName = "Main Server"
```

### Step 3: Get Server Token

1. Login to the admin panel
2. Go to **Servers** → **Add Server**
3. Enter server name → Click **Add**
4. Copy the generated token
5. Paste into `config.lua`

### Step 4: Add to server.cfg

```cfg
ensure fivem-admin
```

### Step 5: Restart Server

Restart your FiveM server to load the resource.

---

## Discord Bot Setup (Optional)

### Step 1: Install Node.js

Ensure Node.js 18+ is installed on your server.

### Step 2: Install Dependencies

```bash
cd /path/to/discord-bot
npm install
```

### Step 3: Configure Bot

```bash
cp .env.example .env
nano .env
```

```env
DISCORD_BOT_TOKEN=your_bot_token_here
DISCORD_GUILD_ID=your_server_id_here
PANEL_API_URL=https://staff.jgn.gg
```

### Step 4: Run Bot

**Development:**
```bash
npm start
```

**Production (with PM2):**
```bash
npm install -g pm2
pm2 start bot.js --name "jgn-admin-bot"
pm2 save
pm2 startup
```

---

## Permission Levels

| Discord Role | Panel Permissions |
|--------------|-------------------|
| **Owner** | Full access to everything |
| **Admin** | Ban, Unban, Kick, Warn, Commend, Delete Records |
| **Moderator** | Ban, Kick, Warn, Commend |
| **Trial Mod** | Kick, Warn, Commend |

---

## Discord Commands

| Command | Description | Permission |
|---------|-------------|------------|
| `/profile` | View your own player profile | Everyone |
| `/lookup <query>` | Search for a player | Staff |
| `/stats` | View server statistics | Staff |

---

## API Reference

### FiveM Server API

All endpoints require `Authorization: Bearer <server_token>` header.

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/fivem/auth` | POST | Authenticate server |
| `/api/fivem/heartbeat` | POST | Update server status |
| `/api/fivem/check-ban` | POST | Check if player is banned |
| `/api/fivem/player/connect` | POST | Register player connection |
| `/api/fivem/player/disconnect` | POST | Register player disconnect |
| `/api/fivem/player` | GET | Get player info |

### Discord Bot API

Requires `X-Bot-Token: <bot_token>` header.

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/players/lookup` | GET | Search players by name/identifier |
| `/api/v1/players/by-discord/{id}` | GET | Get player by Discord ID |

---

## Troubleshooting

### "Failed to authenticate with Discord"
- Verify `DISCORD_CLIENT_ID` and `DISCORD_CLIENT_SECRET` are correct
- Check redirect URI matches exactly: `https://staff.jgn.gg/auth/discord/callback`
- Ensure SSL certificate is valid

### "You do not have permission to access the staff panel"
- Verify your Discord role IDs are correct in `.env`
- Ensure you have one of the configured roles in Discord
- Try logging out and back in

### "Database connection refused"
- Check database credentials in `.env`
- Verify MySQL is running
- Try `php artisan config:clear`

### Panel shows blank page
- Check PHP version is 8.2 or 8.3
- Run `php artisan config:cache`
- Check storage folder permissions: `chmod -R 775 storage`

### FiveM resource not connecting
- Verify `Config.PanelURL` is correct (with https://)
- Check server token matches
- Ensure API is accessible from server

---

## Security Features

- ✅ SQL Injection Protection (Eloquent ORM)
- ✅ CSRF Token Validation
- ✅ XSS Protection (Blade escaping)
- ✅ Rate Limiting on API (120 req/min for FiveM, 60 req/min for bot)
- ✅ Discord OAuth 2.0
- ✅ Encrypted Sessions
- ✅ Audit Logging
- ✅ robots.txt blocks search indexing

---

## Maintenance Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clean up expired bans
php artisan bans:cleanup

# Check database connection
php artisan db:show
```

---

## File Structure

```
new-panel/
├── app/
│   ├── Http/Controllers/    # Request handlers
│   ├── Models/              # Database models
│   ├── Events/              # Broadcasting events
│   ├── Providers/           # Service providers
│   └── Services/            # Business logic
├── config/                  # Configuration files
├── database/migrations/     # Database schema
├── discord-bot/             # Discord bot (Node.js)
├── fivem-resource/          # FiveM integration
│   ├── client/              # Client scripts
│   ├── server/              # Server scripts
│   └── config.lua           # Resource config
├── public/                  # Web root (document root)
├── resources/views/         # Blade templates
├── routes/                  # Route definitions
├── storage/                 # Logs, cache, sessions
└── .env.example             # Environment template
```

---

## License

MIT License - Feel free to modify and use for your community.

---

## Support

For issues or questions, contact the development team or open an issue in the repository.
