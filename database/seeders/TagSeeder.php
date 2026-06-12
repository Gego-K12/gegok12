<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpatieTag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // Basic student type
            // 'Hostel',
            // 'Bus Student',
            // 'Day Scholar',
            // 'New Admission',
            // 'Transfer Student',

            // // Academic
            // 'Topper',
            // 'Slow Learner',
            // 'Needs Academic Support',
            // 'Excellent Attendance',
            // 'Low Attendance',
            // 'Homework Pending',
            // 'Exam Performer',
            // 'Needs Remedial Class',

            // Activities / Talents
            'Sports',
            'Music',
            'Dance',
            'Drawing',
            'Drama',
            'Singing',
            'Musical Instrument',
            'Public Speaking',
            'Debate',
            'Quiz',
            'Science Club',
            'Math Club',
            'Literary Club',
            'NCC',
            'Scout',
            'Guide',

            // // Sports specific
            // 'Cricket',
            // 'Football',
            // 'Volleyball',
            // 'Basketball',
            // 'Athletics',
            // 'Kabaddi',
            // 'Kho Kho',
            // 'Chess',
            // 'Badminton',

            // // Fees / Admin
            // 'Fee Pending',
            // 'Fee Paid',
            // 'Scholarship',
            // 'Concession',
            // 'Documents Pending',
            // 'ID Card Pending',

            // // Health / Care
            // 'Medical Attention',
            // 'Food Allergy',
            // 'Medication Required',
            // 'Special Care',
            // 'Health Checkup Pending',

            // // Behavior / Discipline
            // 'Well Disciplined',
            // 'Needs Counseling',
            // 'Late Comer',
            // 'Uniform Issue',
            // 'Parent Meeting Required',

            // // Transport
            // 'Own Transport',
            // 'School Van',
            // 'School Bus',
            // 'Pickup Required',
            // 'Drop Required',
        ];

        foreach ($tags as $index => $tag) {
            SpatieTag::firstOrCreate(
                [
                    'tag_name' => $tag,
                    'type' => 'student',
                ],
                [
                    'name' => ['en' => $tag],
                    'slug' => ['en' => Str::slug($tag)],
                    'order_column' => $index + 1,
                ]
            );
        }
    }
}