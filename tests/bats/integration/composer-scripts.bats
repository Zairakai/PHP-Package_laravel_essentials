#!/usr/bin/env bats
#
# Integration Tests: Composer Scripts
#
# Validates that the dev tooling environment is correctly installed
# and that key scripts from laravel-dev-tools are available and executable.
#

# Load test helpers
load '../helpers/test_helper'

setup() {
    setup_test_env
}

teardown() {
    teardown_test_env
}

# ============================================================================
# Vendor / Dependencies
# ============================================================================

@test "vendor/ directory exists (composer install was run)" {
    assert_dir_exists "${PROJECT_ROOT}/vendor"
}

@test "vendor/zairakai/laravel-dev-tools is installed" {
    assert_dir_exists "${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools"
}

@test "vendor/phpunit/phpunit is installed" {
    assert_dir_exists "${PROJECT_ROOT}/vendor/phpunit/phpunit"
}

@test "vendor/phpstan/phpstan is installed" {
    assert_dir_exists "${PROJECT_ROOT}/vendor/phpstan/phpstan"
}

@test "vendor/laravel/pint is installed" {
    assert_dir_exists "${PROJECT_ROOT}/vendor/laravel/pint"
}

# ============================================================================
# laravel-dev-tools Scripts
# ============================================================================

@test "setup-package.sh script exists and is executable" {
    local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/setup-package.sh"

    assert_file_exists "$script"
    [ -x "$script" ]
}

@test "phpstan.sh script exists and is executable" {
    local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/phpstan.sh"

    assert_file_exists "$script"
    [ -x "$script" ]
}

@test "cs-check.sh script exists and is executable" {
    local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/cs-check.sh"

    assert_file_exists "$script"
    [ -x "$script" ]
}

@test "test.sh script exists and is executable" {
    local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/test.sh"

    assert_file_exists "$script"
    [ -x "$script" ]
}

@test "rector-check.sh script exists and is executable" {
    local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/rector-check.sh"

    assert_file_exists "$script"
    [ -x "$script" ]
}

@test "insights.sh script exists and is executable" {
    local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/insights.sh"

    assert_file_exists "$script"
    [ -x "$script" ]
}

# ============================================================================
# PHP Environment
# ============================================================================

@test "php binary is available" {
    run command -v php

    [ "$status" -eq 0 ]
}

@test "php version is 8.3 or higher" {
    run php -r "echo PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;"

    [[ "$output" =~ ^8\.(3|4|5) ]] || [[ "$output" =~ ^9\. ]]
}

@test "phpunit binary is available via vendor" {
    run command -v "${PROJECT_ROOT}/vendor/bin/phpunit"

    [ "$status" -eq 0 ] || assert_file_exists "${PROJECT_ROOT}/vendor/bin/phpunit"
}

@test "phpstan binary is available via vendor" {
    assert_file_exists "${PROJECT_ROOT}/vendor/bin/phpstan"
}

@test "pint binary is available via vendor" {
    assert_file_exists "${PROJECT_ROOT}/vendor/bin/pint"
}

# ============================================================================
# Generated Config Files (by setup-package.sh)
# ============================================================================

@test "Makefile includes dev-tools targets" {
    assert_file_contains "${PROJECT_ROOT}/Makefile" "include"
}

@test "phpstan.neon includes a config source" {
    assert_file_contains "${PROJECT_ROOT}/phpstan.neon" "includes"
}
