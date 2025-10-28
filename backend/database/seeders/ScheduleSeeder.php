<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate schedules for the next 30 days
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        // Time slots
        $timeSlots = [
            ['start' => '09:00', 'end' => '12:00'],
            ['start' => '13:00', 'end' => '16:00'],
            ['start' => '16:00', 'end' => '19:00'],
        ];

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Skip Sundays
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                foreach ($timeSlots as $slot) {
                    Schedule::create([
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'status' => 'available',
                    ]);
                }
            }

            $currentDate->addDay();
        }

        $this->command->info('Schedules created successfully for the next 30 days!');
    }
}
