@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Detail Hasil Individual Assessment</h2>
            <p class="text-xs text-slate-500">Submission Code: <span class="font-mono text-amber-700 font-bold">{{ $submission->submission_code }}</span></p>
        </div>
        <a href="{{ route('admin.results.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">← Kembali ke Laporan</a>
    </div>

    <!-- Participant Info Box -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
        <div>
            <span class="text-slate-400 uppercase font-bold text-[10px] block">Nama Peserta</span>
            <strong class="text-slate-900 text-sm block">{{ $submission->participant->name }}</strong>
        </div>
        <div>
            <span class="text-slate-400 uppercase font-bold text-[10px] block">Kode Peserta</span>
            <span class="font-mono font-bold text-amber-700 text-sm block">{{ $submission->participant->participant_code }}</span>
        </div>
        <div>
            <span class="text-slate-400 uppercase font-bold text-[10px] block">Institusi</span>
            <span class="text-slate-900 font-bold block">{{ $submission->participant->institution->name ?? '-' }}</span>
        </div>
        <div>
            <span class="text-slate-400 uppercase font-bold text-[10px] block">Program</span>
            <span class="text-slate-900 font-bold block">{{ $submission->participant->program->name ?? '-' }}</span>
        </div>
    </div>

    <!-- Pretest vs Posttest Delta Comparison Banner if available -->
    @if($pretestSubmission && $posttestSubmission)
        <div class="bg-gradient-to-r from-slate-900 to-navy-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl space-y-4">
            <h3 class="text-amber-400 font-bold text-xs uppercase tracking-wider font-serif">🔄 Komparasi Hasil Pretest vs Posttest</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                <div class="bg-navy-950 p-4 rounded-xl border border-slate-800">
                    <span class="text-[10px] text-slate-400 uppercase block font-bold">Skor Pretest</span>
                    <span class="text-2xl font-bold text-sky-400">{{ number_format($pretestSubmission->result->percentage, 1) }}%</span>
                </div>
                <div class="bg-navy-950 p-4 rounded-xl border border-slate-800">
                    <span class="text-[10px] text-slate-400 uppercase block font-bold">Skor Posttest</span>
                    <span class="text-2xl font-bold text-emerald-400">{{ number_format($posttestSubmission->result->percentage, 1) }}%</span>
                </div>
                <div class="bg-navy-950 p-4 rounded-xl border border-slate-800">
                    <span class="text-[10px] text-slate-400 uppercase block font-bold">Perkembangan Delta</span>
                    <span class="text-2xl font-bold font-mono {{ ($posttestSubmission->result->percentage - $pretestSubmission->result->percentage) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ ($posttestSubmission->result->percentage - $pretestSubmission->result->percentage) >= 0 ? '+' : '' }}{{ number_format($posttestSubmission->result->percentage - $pretestSubmission->result->percentage, 1) }}%
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- Score Breakdown per Dimension -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-bold font-serif text-slate-900 text-base">Capaian Per Dimensi Ruhiologi</h3>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="p-3">Kode</th>
                    <th class="p-3">Nama Dimensi</th>
                    <th class="p-3">Skor Mentah</th>
                    <th class="p-3">Persentase</th>
                    <th class="p-3">Interpretasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($submission->result->dimensionResults as $d)
                    <tr>
                        <td class="p-3 font-mono font-bold text-amber-700">{{ $d->dimension->code }}</td>
                        <td class="p-3 font-bold text-slate-900">{{ $d->dimension->name }}</td>
                        <td class="p-3 text-slate-600">{{ $d->score }} / {{ $d->max_score }}</td>
                        <td class="p-3 font-bold text-slate-900">{{ number_format($d->percentage, 1) }}%</td>
                        <td class="p-3 text-slate-700">{{ $d->interpretation }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Answers Breakdown Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-bold font-serif text-slate-900 text-base">Jawaban Per Butir Soal (Raw & Calculated Score)</h3>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="p-3 w-10">#</th>
                    <th class="p-3">Soal</th>
                    <th class="p-3">Direction</th>
                    <th class="p-3">Jawaban Opsi</th>
                    <th class="p-3">Nilai Mentah</th>
                    <th class="p-3">Skor Terhitung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($submission->answers as $idx => $ans)
                    <tr>
                        <td class="p-3 font-bold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="p-3 text-slate-900 font-medium">{{ $ans->question->question_text }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ans->question->scoring_direction === 'reverse' ? 'bg-amber-100 text-amber-900' : 'bg-slate-100 text-slate-600' }}">
                                {{ $ans->question->scoring_direction }}
                            </span>
                        </td>
                        <td class="p-3 font-semibold text-slate-800">{{ $ans->option->option_text ?? '-' }}</td>
                        <td class="p-3 text-slate-600 font-mono">{{ $ans->raw_value }}</td>
                        <td class="p-3 font-bold text-amber-700 font-mono">{{ $ans->calculated_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
