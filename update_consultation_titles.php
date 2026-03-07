<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Consultation;

$consultations = Consultation::whereNull('title')->get();

$titles = [
    'Contract Review Needed',
    'Property Dispute Resolution',
    'Employment Termination Issue',
    'Business Partnership Agreement',
    'Debt Collection Advice',
    'Landlord-Tenant Dispute',
    'Intellectual Property Question',
    'Family Law Consultation',
    'Criminal Defense Inquiry',
    'Immigration Status Help',
    'Tax Compliance Question',
    'Corporate Governance Issue',
    'Real Estate Transaction',
    'Labor Code Violation',
    'Consumer Rights Protection',
    'Insurance Claim Dispute',
    'Breach of Contract',
    'Personal Injury Claim',
    'Estate Planning Advice',
    'Trademark Registration',
];

foreach ($consultations as $index => $consultation) {
    $title = $titles[$index % count($titles)];
    $consultation->update(['title' => $title]);
    echo "Updated consultation #{$consultation->id} with title: {$title}\n";
}

echo "\nDone! Updated " . $consultations->count() . " consultations.\n";
