<?php

session_start();
session_unset();
session_destroy();

header("Location: /sistem-arsip/auth");
exit;