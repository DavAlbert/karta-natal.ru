# Queue Worker Setup (Production)

## Server: VDS-KVM-SSD 2 CPU, 4GB RAM

### Queues

| Queue | Purpose | Workers | Memory | Timeout |
|-------|---------|---------|--------|---------|
| `chat` | AI Chat (high priority) | 1 | 256MB | 120s |
| `ai-reports` | Natal/Compatibility Reports | 1 | 512MB | 300s |
| `default` | Emails, misc | 1 | 128MB | 60s |

### Installation

```bash
# Install supervisor
sudo apt update
sudo apt install supervisor

# Copy config
sudo cp /var/www/natalnaya-karta/supervisor/laravel-worker.conf /etc/supervisor/conf.d/

# Update path if needed (default: /var/www/natalnaya-karta)
sudo nano /etc/supervisor/conf.d/laravel-worker.conf

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-workers:*
```

### Commands

```bash
# Status
sudo supervisorctl status

# Restart all workers
sudo supervisorctl restart laravel-workers:*

# Restart specific queue
sudo supervisorctl restart laravel-chat:*
sudo supervisorctl restart laravel-ai-reports:*

# Stop all
sudo supervisorctl stop laravel-workers:*

# View logs
tail -f /var/www/natalnaya-karta/storage/logs/worker-chat.log
tail -f /var/www/natalnaya-karta/storage/logs/worker-ai-reports.log
```

### After Deployment

```bash
# Restart workers to pick up new code
sudo supervisorctl restart laravel-workers:*

# Or gracefully (finish current job first)
php artisan queue:restart
```
