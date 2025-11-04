<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(2),
            'body' => $this->faker->paragraphs(5, true),
            'event_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'event_time' => $this->faker->time('g:i A'),
            'location' => $this->faker->address(),
            'banner_image' => null, // Can be overridden if needed
            'category' => $this->faker->randomElement([
                'Cultural',
                'Educational',
                'Social',
                'Charity',
                'Sports',
                'Meeting',
                'Workshop',
                'Other',
            ]),
            'is_published' => $this->faker->boolean(70), // 70% chance of being published
            'featured' => $this->faker->boolean(30), // 30% chance of being featured
            'display_order' => 0,
            'meta_description' => $this->faker->sentence(15),
        ];
    }

    /**
     * Indicate that the event is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the event is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    /**
     * Indicate that the event is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }

    /**
     * Indicate that the event is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => $this->faker->dateTimeBetween('now', '+6 months'),
        ]);
    }

    /**
     * Indicate that the event is past.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }
}

