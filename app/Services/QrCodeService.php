<?php

namespace App\Services;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateForMember(Member $member): string
    {
        $data = json_encode([
            'id'     => $member->id,
            'nama'   => $member->nama_lengkap,
            'no_anggota' => $this->getMemberNumber($member),
            'app'    => config('app.name'),
        ]);

        return QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($data);
    }

    public function generatePngForMember(Member $member): string
    {
        $data = json_encode([
            'id'     => $member->id,
            'nama'   => $member->nama_lengkap,
            'no_anggota' => $this->getMemberNumber($member),
            'app'    => config('app.name'),
        ]);

        return QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($data);
    }

    public function getMemberNumber(Member $member): string
    {
        return 'ICT-' . str_pad($member->id, 5, '0', STR_PAD_LEFT);
    }

    public function generateForUser(User $user): string
    {
        $data = json_encode([
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'app'     => config('app.name'),
        ]);

        return QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($data);
    }
}
