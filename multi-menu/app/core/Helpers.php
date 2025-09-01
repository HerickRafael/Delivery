<?php
function config($key = null) {
  static $cfg = null;
  if (!$cfg) $cfg = require __DIR__ . '/../config/app.php';
  return $key ? ($cfg[$key] ?? null) : $cfg;
}
function base_url(string $path = ''): string {
  $b = rtrim(config('base_url'), '/');
  $p = ltrim($path, '/');
  return $p ? "$b/$p" : $b;
}
function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
