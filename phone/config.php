<?php
// config.php - Configuration file for CTM integration

// Load environment variables from .env file if it exists and if php-dotenv is available
if (file_exists(__DIR__ . '/.env') && class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// CTM Configuration
return [
    'ctm_secret' => getenv('CTM_SECRET'),
    'ctm_token' => getenv('CTM_TOKEN'),
    'ctm_account_id' => getenv('CTM_ACCOUNT_ID'),
    'ctm_host' => getenv('CTM_HOST') ?: 'app.calltrackingmetrics.com',
];
