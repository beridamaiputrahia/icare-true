<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\QrCodeService;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    public function __construct(private QrCodeService $qrService) {}

    public function show(Member $member)
    {
        $qrSvg      = $this->qrService->generateForMember($member);
        $memberNo   = $this->qrService->getMemberNumber($member);

        return view('qr.member-card', compact('member', 'qrSvg', 'memberNo'));
    }

    public function download(Member $member): Response
    {
        $png = $this->qrService->generatePngForMember($member);

        return response($png)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $member->id . '.png"');
    }

    public function svg(Member $member): Response
    {
        $svg = $this->qrService->generateForMember($member);

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
