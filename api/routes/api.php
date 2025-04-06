<?php

declare(strict_types=1);

require_once __DIR__ . '/../controllers/CourseController.php';

$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Route for retrieving courses by category using /categories/{id}
if (preg_match('#^/categories/([\w-]+)$#', $uri, $matches)) {
    $categoryId = $matches[1];
    $controller = new CourseController();
    $controller->getCoursesByCategory($categoryId);
} elseif ($uri === '/courses' || $uri === '/api/courses') {
    $controller = new CourseController();
    $controller->getAllCourses();
} else {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Route not found']);
}
