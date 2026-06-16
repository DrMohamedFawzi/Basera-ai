<?php

declare(strict_types=1);

namespace App\Services;

/**
 * CareerRoadmapEngine
 *
 * يولد Roadmap phases بسيطة حسب أعلى matches.
 */
final class CareerRoadmapEngine
{
    /**
     * @param array $dna
     * @param array $matches (list of ['career'=>string,'score'=>int])
     * @return array{phase_1: array, phase_2: array, phase_3: array}
     */
    public function generateRoadmap(array $dna, array $matches): array
    {
        $top = $matches[0]['career'] ?? 'Backend Developer';

        // Prototype roadmap.
        // لاحقاً سيتم ربطه بمرحلة DB Roadmap Tasks + pruning engine.
        $phase1 = [
            'assessment_completed' => true,
            'tasks' => [
                ['title' => 'تثبيت أساسيات التخصص', 'type' => 'course', 'priority' => 'high'],
                ['title' => 'مشروع صغير Proof of Skill', 'type' => 'project', 'priority' => 'high'],
            ],
        ];

        $phase2 = [
            'goal' => 'رفع جاهزية التوظيف',
            'tasks' => [
                ['title' => 'بناء مشروع متوسط يغطي متطلبات المسار', 'type' => 'project', 'priority' => 'high'],
                ['title' => 'اختبار/تقييم داخلي للمهارات', 'type' => 'assessment', 'priority' => 'medium'],
            ],
        ];

        $phase3 = [
            'goal' => 'إثبات احترافي',
            'tasks' => [
                ['title' => 'Capstone Project وإضافة النتائج للملف المهني', 'type' => 'project', 'priority' => 'high'],
                ['title' => 'تحضير لمسارات التوظيف/الوظائف', 'type' => 'reading', 'priority' => 'medium'],
                ['title' => 'رفع شهادة (اختياري) لتعزيز الأدلة', 'type' => 'certificate', 'priority' => 'low'],
            ],
        ];

        return [
            'phase_1' => $phase1,
            'phase_2' => $phase2,
            'phase_3' => $phase3,
            '_top_career' => $top,
        ];
    }
}

