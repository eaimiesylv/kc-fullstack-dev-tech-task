<?php

declare(strict_types=1);

require_once __DIR__ . '/../controllers/CoursController.php';

$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Route for retrieving courses by category using /categories/{id}
if (preg_match('#^/categories/([\w-]+)$#', $uri, $matches)) {
    $categoryId = $matches[1];
    $controller = new CoursController();
    $controller->getCoursesByCategory($categoryId);
} else {
    $controller = new CoursController();
    $controller->getAllCourses();
}
