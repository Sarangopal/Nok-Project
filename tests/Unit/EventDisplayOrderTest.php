<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

/**
 * Test Event Display Order Functionality
 * 
 * This verifies that events are correctly ordered by the display_order field
 * and that the ordering works correctly on both the admin panel and homepage.
 */
class EventDisplayOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that events are ordered by display_order (ascending)
     */
    public function test_events_ordered_by_display_order(): void
    {
        // Create events with different display orders
        $event3 = Event::factory()->create([
            'title' => 'Event 3',
            'display_order' => 3,
            'is_published' => true,
            'event_date' => now()->addDays(10),
        ]);

        $event1 = Event::factory()->create([
            'title' => 'Event 1',
            'display_order' => 1,
            'is_published' => true,
            'event_date' => now()->addDays(10),
        ]);

        $event2 = Event::factory()->create([
            'title' => 'Event 2',
            'display_order' => 2,
            'is_published' => true,
            'event_date' => now()->addDays(10),
        ]);

        // Fetch events using the ordered scope
        $orderedEvents = Event::published()->ordered()->get();

        // Should be in order: event1, event2, event3
        $this->assertEquals('Event 1', $orderedEvents[0]->title);
        $this->assertEquals('Event 2', $orderedEvents[1]->title);
        $this->assertEquals('Event 3', $orderedEvents[2]->title);
    }

    /**
     * Test that display_order defaults to 0 if not set
     */
    public function test_display_order_defaults_to_zero(): void
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'is_published' => true,
            'event_date' => now()->addDays(5),
            // display_order not explicitly set
        ]);

        $this->assertEquals(0, $event->display_order);
    }

    /**
     * Test that events with same display_order are ordered by event_date
     */
    public function test_events_with_same_display_order_ordered_by_date(): void
    {
        $laterEvent = Event::factory()->create([
            'title' => 'Later Event',
            'display_order' => 1,
            'event_date' => now()->addDays(20),
            'is_published' => true,
        ]);

        $earlierEvent = Event::factory()->create([
            'title' => 'Earlier Event',
            'display_order' => 1,
            'event_date' => now()->addDays(10),
            'is_published' => true,
        ]);

        $orderedEvents = Event::published()->ordered()->get();

        // Earlier event should come first when display_order is same
        $this->assertEquals('Earlier Event', $orderedEvents[0]->title);
        $this->assertEquals('Later Event', $orderedEvents[1]->title);
    }

    /**
     * Test that display_order can be updated
     */
    public function test_display_order_can_be_updated(): void
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'display_order' => 1,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        $this->assertEquals(1, $event->display_order);

        // Update display_order
        $event->update(['display_order' => 5]);

        $this->assertEquals(5, $event->fresh()->display_order);
    }

    /**
     * Test homepage displays events in correct order
     */
    public function test_homepage_events_respect_display_order(): void
    {
        // Create multiple upcoming published events
        Event::factory()->create([
            'title' => 'Event C',
            'display_order' => 3,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Event A',
            'display_order' => 1,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Event B',
            'display_order' => 2,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        // Simulate homepage query
        $events = Event::published()
            ->upcoming()
            ->ordered()
            ->limit(5)
            ->get();

        $this->assertCount(3, $events);
        $this->assertEquals('Event A', $events[0]->title);
        $this->assertEquals('Event B', $events[1]->title);
        $this->assertEquals('Event C', $events[2]->title);
    }

    /**
     * Test that unpublished events are excluded even with display_order
     */
    public function test_unpublished_events_excluded_regardless_of_order(): void
    {
        Event::factory()->create([
            'title' => 'Published Event',
            'display_order' => 2,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Unpublished Event',
            'display_order' => 1, // Lower order, but unpublished
            'is_published' => false,
            'event_date' => now()->addDays(5),
        ]);

        $events = Event::published()->ordered()->get();

        // Should only return published event
        $this->assertCount(1, $events);
        $this->assertEquals('Published Event', $events[0]->title);
    }

    /**
     * Test that past events are excluded from homepage
     */
    public function test_past_events_excluded_from_homepage(): void
    {
        Event::factory()->create([
            'title' => 'Future Event',
            'display_order' => 2,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Past Event',
            'display_order' => 1, // Lower order, but past
            'is_published' => true,
            'event_date' => now()->subDays(5),
        ]);

        $events = Event::published()->upcoming()->ordered()->get();

        // Should only return future event
        $this->assertCount(1, $events);
        $this->assertEquals('Future Event', $events[0]->title);
    }

    /**
     * Test ordering with mixed display_order values including zero
     */
    public function test_ordering_with_zero_display_order(): void
    {
        Event::factory()->create([
            'title' => 'Order 5',
            'display_order' => 5,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Order 0',
            'display_order' => 0,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Order 3',
            'display_order' => 3,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        $events = Event::published()->ordered()->get();

        // Order should be: 0, 3, 5
        $this->assertEquals('Order 0', $events[0]->title);
        $this->assertEquals('Order 3', $events[1]->title);
        $this->assertEquals('Order 5', $events[2]->title);
    }

    /**
     * Test ordering with negative display_order values
     */
    public function test_ordering_with_negative_display_order(): void
    {
        Event::factory()->create([
            'title' => 'Order 1',
            'display_order' => 1,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Order -1',
            'display_order' => -1,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Order 0',
            'display_order' => 0,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        $events = Event::published()->ordered()->get();

        // Order should be: -1, 0, 1
        $this->assertEquals('Order -1', $events[0]->title);
        $this->assertEquals('Order 0', $events[1]->title);
        $this->assertEquals('Order 1', $events[2]->title);
    }

    /**
     * Test that display_order field exists in database
     */
    public function test_display_order_field_exists(): void
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'display_order' => 99,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'display_order' => 99,
        ]);
    }

    /**
     * Test ordering with large display_order values
     */
    public function test_ordering_with_large_display_order_values(): void
    {
        Event::factory()->create([
            'title' => 'Order 9999',
            'display_order' => 9999,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Order 1',
            'display_order' => 1,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->create([
            'title' => 'Order 500',
            'display_order' => 500,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        $events = Event::published()->ordered()->get();

        // Order should be: 1, 500, 9999
        $this->assertEquals('Order 1', $events[0]->title);
        $this->assertEquals('Order 500', $events[1]->title);
        $this->assertEquals('Order 9999', $events[2]->title);
    }

    /**
     * Test complete lifecycle: create, update order, verify ordering
     */
    public function test_complete_display_order_lifecycle(): void
    {
        // Create events
        $event1 = Event::factory()->create([
            'title' => 'First Event',
            'display_order' => 1,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        $event2 = Event::factory()->create([
            'title' => 'Second Event',
            'display_order' => 2,
            'is_published' => true,
            'event_date' => now()->addDays(5),
        ]);

        // Initial order
        $events = Event::published()->ordered()->get();
        $this->assertEquals('First Event', $events[0]->title);
        $this->assertEquals('Second Event', $events[1]->title);

        // Swap display orders
        $event1->update(['display_order' => 2]);
        $event2->update(['display_order' => 1]);

        // New order
        $events = Event::published()->ordered()->get();
        $this->assertEquals('Second Event', $events[0]->title);
        $this->assertEquals('First Event', $events[1]->title);

        // Unpublish one event
        $event2->update(['is_published' => false]);

        // Should only show published event
        $events = Event::published()->ordered()->get();
        $this->assertCount(1, $events);
        $this->assertEquals('First Event', $events[0]->title);
    }
}

