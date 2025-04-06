<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/CourseList.php';

class CoursController
{
    /**
     * Retrieve all  course.
     *
     * @return void
     */
    public function getAllCourses(): void
    {
        $courses = CourseList::getAllCourses();
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($courses);
    }

    /**
     * Retrieve courses by categor id.
     *
     * @param string $categoryId
     * @return void
     */
    public function getCoursesByCategory(string $categoryId): void
    {
        $courses = CourseList::getCoursesByCategory($categoryId);
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($courses);
    }
}
