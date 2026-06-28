<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Database\Eloquent\Model;

class PointService
{
    // Level thresholds: [level => min_points]
    public const LEVELS = [
        1 => ['name' => 'Pemula Rohani',  'min' => 0,    'icon' => 'fa-seedling',     'color' => '#94a3b8'],
        2 => ['name' => 'Penabur',        'min' => 100,  'icon' => 'fa-leaf',          'color' => '#22c55e'],
        3 => ['name' => 'Pelayan',        'min' => 250,  'icon' => 'fa-hands-holding', 'color' => '#3b82f6'],
        4 => ['name' => 'Murid Kristus',  'min' => 500,  'icon' => 'fa-book-bible',    'color' => '#8b5cf6'],
        5 => ['name' => 'Pewarta Injil',  'min' => 800,  'icon' => 'fa-bullhorn',      'color' => '#f59e0b'],
        6 => ['name' => 'Penjala Jiwa',   'min' => 1200, 'icon' => 'fa-star',          'color' => '#ef4444'],
    ];

    public function award(User $user, string $type, Model $pointable): ?UserPoint
    {
        // Prevent awarding the same point type for the same model twice
        $alreadyAwarded = UserPoint::where('user_id', $user->id)
            ->where('type', $type)
            ->where('pointable_type', get_class($pointable))
            ->where('pointable_id', $pointable->getKey())
            ->exists();

        if ($alreadyAwarded) {
            return null;
        }

        $points = UserPoint::POINTS_MAP[$type] ?? 0;
        $label  = UserPoint::LABELS[$type]     ?? $type;

        $record = UserPoint::create([
            'user_id'        => $user->id,
            'type'           => $type,
            'points'         => $points,
            'description'    => $label,
            'pointable_type' => get_class($pointable),
            'pointable_id'   => $pointable->getKey(),
        ]);

        $newTotal = $user->total_points + $points;
        $newLevel = $this->calculateLevel($newTotal);

        $user->update(['total_points' => $newTotal, 'level' => $newLevel]);

        return $record;
    }

    public function calculateLevel(int $totalPoints): int
    {
        $level = 1;
        foreach (self::LEVELS as $lvl => $data) {
            if ($totalPoints >= $data['min']) $level = $lvl;
        }
        return $level;
    }

    public function getLevelName(int $level): string
    {
        return self::LEVELS[$level]['name'] ?? 'Pemula Rohani';
    }

    public function getLevelIcon(int $level): string
    {
        return self::LEVELS[$level]['icon'] ?? 'fa-seedling';
    }

    public function getLevelColor(int $level): string
    {
        return self::LEVELS[$level]['color'] ?? '#94a3b8';
    }

    public function getLevelProgress(User $user): array
    {
        $current = $user->level;
        $next    = $current + 1;
        $total   = $user->total_points;

        $currentMin = self::LEVELS[$current]['min'] ?? 0;
        $nextMin    = self::LEVELS[$next]['min']    ?? null;

        if ($nextMin === null) {
            return [
                'current_level'    => $current,
                'current_name'     => $this->getLevelName($current),
                'current_icon'     => $this->getLevelIcon($current),
                'current_color'    => $this->getLevelColor($current),
                'next_level'       => null,
                'next_name'        => null,
                'points_to_next'   => 0,
                'percent'          => 100,
                'is_max'           => true,
            ];
        }

        $range  = $nextMin - $currentMin;
        $earned = $total - $currentMin;

        return [
            'current_level'  => $current,
            'current_name'   => $this->getLevelName($current),
            'current_icon'   => $this->getLevelIcon($current),
            'current_color'  => $this->getLevelColor($current),
            'next_level'     => $next,
            'next_name'      => $this->getLevelName($next),
            'next_icon'      => $this->getLevelIcon($next),
            'next_color'     => $this->getLevelColor($next),
            'points_to_next' => max(0, $nextMin - $total),
            'percent'        => min(100, (int) round(($earned / $range) * 100)),
            'is_max'         => false,
        ];
    }

    public function getLevelsData(): array
    {
        return self::LEVELS;
    }
}
