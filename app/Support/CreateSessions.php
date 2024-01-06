<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Session;
use App\Models\Group;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class CreateSessions
{
    public static function createFromNow(Group $group, array $data): void
    {
        $group->loadMissing('season');
        $dates = $group->season->starts_at->daysUntil($group->season->ends_at)->filter(function ($date) use ($data) {
            return $date->setTimeFromTimeString($data['starts_at'])->isFuture() && $date->is($data['day']);
        });
        static::create($group, $data, $dates);
    }

    public static function createFromBeginning(Group $group, array $data): void
    {
        $group->loadMissing('season');
        $dates = $group->season->starts_at->daysUntil($group->season->ends_at)->filter(function ($date) use ($data) {
            return $date->is($data['day']);
        });
        static::create($group, $data, $dates);
    }

    private static function create(Group $group, array $data, CarbonPeriod $dates): void
    {
        $data['after'] = $data['after'] ?? 0;
        $data['every'] = $data['every'] ?? 1;
        DB::transaction(function () use ($group, $data, $dates) {
            $mutual_id = Str::random(20);
            foreach ($dates as $i => $date) {
                if ($i >= intval($data['after'])) {
                    Session::create([
                        'mutual_id' => $mutual_id,
                        'group_id' => $group->id,
                        'subject_id' => $data['subject_id'],
                        'teacher_id' => $data['teacher_id'],
                        'room_id' => $data['room_id'],
                        'starts_at' => static::getDate($date, $data['starts_at']),
                        'ends_at' => static::getDate($date, $data['ends_at'], $data['starts_at']),
                        'starts_at_ramadan' => static::getDate($date, $data['starts_at_ramadan']),
                        'ends_at_ramadan' => static::getDate($date, $data['ends_at_ramadan'], $data['starts_at_ramadan']),
                    ]);
                    if (filled($data['every']) && intval($data['every']) === 0) {
                        break;
                    } elseif (filled($data['every']) && intval($data['every']) === 1) {
                        continue;
                    } elseif (filled($data['every']) && intval($data['every']) > 1) {
                        $dates->skip($data['every'] - 1);
                    }
                }
            }
            $group->teachers()->sync(Session::where('group_id', $group->id)->pluck('teacher_id'));
        });
    }

    private static function getDate(Carbon $date, ?string $time, ?string $conditionTime = null): ?Carbon
    {
        if (filled($time)) {
            $date = $date->copy()->setTimeFromTimeString($time);
            $conditionDate = filled($conditionTime) ? $date->copy()->setTimeFromTimeString($conditionTime) : null;
            return filled($conditionDate) && $conditionDate?->gt($date) ? $date->addDay() : $date;
        }
        return null;
    }
}
