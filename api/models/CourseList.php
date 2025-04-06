<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/DB.php';

class CourseList
{
    public string $course_id;
    public string $title;
    public string $description;
    public ?string $image_preview;
    public string $category_id;

    /**
     * Returns the common part of the SQL query used to fetch courses.
     *
     * @return string
     */
    private static function baseQuery(): string
    {
        return "
            SELECT 
                cl.course_id AS id,
                cl.title AS name, 
                cl.image_preview AS preview,
                cl.category_id, 
                cl.description, 
                c.parent AS parent_id,
                c.name AS main_category_name,
                cl.created_at,
                cl.updated_at,
                (
                    SELECT COUNT(*) 
                    FROM course_lists 
                    WHERE category_id = cl.category_id
                ) AS count_of_courses
            FROM course_lists cl
            LEFT JOIN categories c ON cl.category_id = c.id
        ";
    }

    /**
     * Return all courses along with the related category details and count.
     *
     * @return array
     */
    public static function getAllCourses(): array
    {
        $db  = DB::getInstance()->getConnection();
        $sql = self::baseQuery();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Return courses for a particular category along with category details and count.
     *
     * @param string $categoryId
     * @return array
     */
    public static function getCoursesByCategory(string $categoryId): array
    {
        $db  = DB::getInstance()->getConnection();
        $sql = self::baseQuery() . " WHERE cl.category_id = :categoryId";
        $stmt = $db->prepare($sql);
        $stmt->execute([':categoryId' => $categoryId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
