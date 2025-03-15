#!/usr/bin/env bash
echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Deployment setup complete!"
