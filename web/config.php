<?php

class Config {
    private static $settings = [];

    public static function init() {
        // Load from environment variables if available, else defaults
        self::$settings['mysql_host'] = getenv('MYSQL_HOST') ?: '';
        self::$settings['mysql_user'] = getenv('MYSQL_USER') ?: '';
        self::$settings['mysql_pass'] = getenv('MYSQL_PASS') ?: '';
        self::$settings['mysql_db'] = getenv('MYSQL_DB') ?: '';
        self::$settings['domainname'] = getenv('DOMAIN_NAME') ?: '';
        self::$settings['subfolder'] = getenv('SUBFOLDER') ?: '';
        self::$settings['apikey'] = getenv('STEAM_API_KEY') ?: '';

        // Validate required fields
        if (empty(self::$settings['mysql_host']) || empty(self::$settings['mysql_user']) || empty(self::$settings['mysql_db'])) {
            throw new Exception('Database settings are incomplete. Please set environment variables.');
        }
        if (empty(self::$settings['domainname'])) {
            throw new Exception('Domain name is required.');
        }
    }

    public static function get($key) {
        return self::$settings[$key] ?? null;
    }
}

// Initialize config
try {
    Config::init();
} catch (Exception $e) {
    error_log('Config error: ' . $e->getMessage());
    die('Configuration error. Check logs.');
}

date_default_timezone_set('America/New_York'); // Consider making this configurable via env var
?>