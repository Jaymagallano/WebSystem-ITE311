<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - ITE311</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">ITE311 - MAGALLANO</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url() ?>">Home</a>
                <a class="nav-link" href="<?= base_url('about') ?>">About</a>
                <a class="nav-link active" href="<?= base_url('contact') ?>">Contact</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>Contact Us</h1>
        <p>This is the contact page of our CodeIgniter application.</p>
    </div>
</body>
</html>
