<?php

/**
 *
 * Check if the user is logged in
 *
 * @return bool
 */
function isLoggedIn() {
    if(Auth::check()) {
        return true;
    }
    return false;
}

/**
 * Determine if the user is an administrator
 *
 * @return bool
 */
function isAdmin() {
    if(isLoggedIn()) {
        $role = Auth::user()->role;

        if($role == "admin" || $role == "super_admin") {
            return true;
        }
    }
    return false;
}

/**
 * Determines if the user is a student
 *
 * @return bool
 */
function isStudent() {
    if(isLoggedIn()) {
        $role = Auth::user()->role;

        if($role == "student") {
            return true;
        }
    }
    return false;
}

/**
 * Determines if the user is a manager
 *
 * @return bool
 */
function isManager() {
    if(isLoggedIn()) {
        $role = Auth::user()->role;

        if($role == "manager") {
            return true;
        }
    }
    return false;
}

/**
 * Determine if the user is a member of staff
 *
 * @return bool
 */
function isStaff() {
    if(isLoggedIn()) {
        $role = Auth::user()->role;

        if($role == "staff" || $role == "priv_user" || $role == "slt" || $role == "ls_admin") {
            return true;
        }
    }
    return false;
}