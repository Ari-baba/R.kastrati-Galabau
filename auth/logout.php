<?php
require_once __DIR__ . '/session.php';

logout_user();
header('Location: /auth/login.php');
exit;

