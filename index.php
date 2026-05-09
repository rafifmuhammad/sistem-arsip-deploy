<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: /sistem-arsip/auth/");
    exit;
}

// kalau sudah login
header("Location: /sistem-arsip/dashboard/");
exit;