<?php

$konek = mysqli_connect("localhost", "root", "", "absenrfid");

if (!$konek) {
    die("Connection failed: " . mysqli_connect_error());
}
