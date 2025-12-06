<?php

namespace Database\Factories;

// database/factories/CourseFactory.php

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        $title = $this->faker->sentence(3);
        $published = $this->faker->boolean(80); // 80% chance of being published

        return [
            'position' => $this->faker->numberBetween(1, 100),
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => $this->faker->sentence(10),
            'description' => $this->faker->paragraph(5),
            'user_id' => $this->faker->numberBetween(1, 5), // Assuming 5 users exist
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'duration' => $this->faker->numberBetween(60, 480),
            'price' => $this->faker->randomFloat(2, 0, 150),
            'is_published' => $published,
            'published_at' => $published ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'thumbnail' => $this->faker->imageUrl(640, 480, 'business'),
            'video_intro' => 'videos/intro-' . $this->faker->uuid() . '.mp4',
            'views' => $this->faker->numberBetween(0, 5000),
            'enrollments_count' => $this->faker->numberBetween(0, 1000),
            'rating' => $this->faker->randomFloat(2, 3.00, 5.00),
        ];
    }
}
