@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="adminEventsManager()">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
        <div>
            <div class="inline-flex items-center gap-2 bg-[#F4F1EA] text-[#8C703E] border border-[#DFD8C8] text-[10px] font-mono font-semibold px-2.5 py-0.5 rounded-md uppercase tracking-wider mb-2">
                <span>PROGRAM & EVENT MANAGEMENT</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold font-serif text-[#0F2537]">Manajemen Event & Kegiatan Asesmen</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola program uji instansi, workshop, dan agenda Pretest & Posttest secara terstruktur.</p>
        </div>
        <div>
            <button @click="createModal = true" type="button" class="w-full sm:w-auto px-5 py-3 bg-[#0F2537] hover:bg-[#183650] text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-[#C5A059]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Event Baru</span>
            </button>
        </div>
    </div>

    <!-- EXECUTIVE QUICK STATS BAR -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-semibold uppercase text-slate-400 block tracking-wider font-mono">TOTAL EVENT</span>
                <strong class="text-lg font-bold text-[#0F2537]">{{ $stats['total_events'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-semibold uppercase text-slate-400 block tracking-wider font-mono">EVENT AKTIF</span>
                <strong class="text-lg font-bold text-emerald-700">{{ $stats['active_events'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#F4F1EA] text-[#8C703E] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-semibold uppercase text-slate-400 block tracking-wider font-mono">TOTAL PESERTA</span>
                <strong class="text-lg font-bold text-[#0F2537]">{{ number_format($stats['event_participants'] ?? 0) }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-semibold uppercase text-slate-400 block tracking-wider font-mono">SUBMISSIONS</span>
                <strong class="text-lg font-bold text-[#0F2537]">{{ number_format($stats['event_submissions'] ?? 0) }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3 col-span-2 md:col-span-1">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-semibold uppercase text-slate-400 block tracking-wider font-mono">INSTANSI MITRA</span>
                <strong class="text-lg font-bold text-[#0F2537]">{{ $stats['total_institutions'] ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <!-- SEARCH, STATUS TABS & VIEW TOGGLE BAR -->
    <div class="space-y-3">
        <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
            <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5 w-full md:w-auto">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama event, kode, instansi..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#0F2537] outline-none text-xs">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <select name="status" onchange="this.form.submit()" class="w-full sm:w-36 py-2 px-3 rounded-lg border border-slate-300 bg-white font-medium outline-none text-xs">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-[#0F2537] text-white font-semibold rounded-lg hover:bg-[#183650] transition cursor-pointer">Filter</button>
            </form>

            <div class="flex items-center justify-between md:justify-end gap-3 w-full md:w-auto">
                <div class="text-slate-500 font-medium text-xs">
                    Total: <strong class="text-[#0F2537] font-semibold">{{ $events->total() }}</strong>
                </div>

                <!-- View Switcher (Grid vs Table) -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                    <button type="button" @click="toggleViewMode('grid')" :class="viewMode === 'grid' ? 'bg-white text-[#0F2537] shadow-2xs font-semibold' : 'text-slate-500 hover:text-slate-800'" class="px-2.5 py-1.5 rounded-md text-xs flex items-center gap-1.5 transition cursor-pointer" title="Tampilan Kartu Grid">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span class="hidden sm:inline">Grid Kartu</span>
                    </button>
                    <button type="button" @click="toggleViewMode('table')" :class="viewMode === 'table' ? 'bg-white text-[#0F2537] shadow-2xs font-semibold' : 'text-slate-500 hover:text-slate-800'" class="px-2.5 py-1.5 rounded-md text-xs flex items-center gap-1.5 transition cursor-pointer" title="Tampilan Tabel Ringkas">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span class="hidden sm:inline">Tabel Ringkas</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- QUICK STATUS FILTER TABS (CLEAN SEGMENTED PILLS) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            <a href="{{ route('admin.events.index', array_merge(request()->except('status', 'page'), ['status' => ''])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition whitespace-nowrap {{ !request('status') ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                Semua ({{ $stats['total_events'] ?? 0 }})
            </a>
            <a href="{{ route('admin.events.index', array_merge(request()->except('status', 'page'), ['status' => 'active'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition whitespace-nowrap {{ request('status') === 'active' ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                Aktif ({{ $stats['active_events'] ?? 0 }})
            </a>
            <a href="{{ route('admin.events.index', array_merge(request()->except('status', 'page'), ['status' => 'draft'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition whitespace-nowrap {{ request('status') === 'draft' ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                Draft
            </a>
            <a href="{{ route('admin.events.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition whitespace-nowrap {{ request('status') === 'completed' ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                Selesai
            </a>
            <a href="{{ route('admin.events.index', array_merge(request()->except('status', 'page'), ['status' => 'archived'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition whitespace-nowrap {{ request('status') === 'archived' ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                Diarsipkan
            </a>
        </div>
    </div>

    <!-- VIEW MODE 1: UNIFORM GRID CARDS VIEW -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($events as $event)
            @php
                $isFinished = ($event->end_date && $event->end_date->isPast()) || $event->status === 'completed';
                $isUpcoming = ($event->start_date && $event->start_date->isFuture()) && $event->status !== 'draft';
                $quotaPct = $event->quota ? min(100, round(($event->participants_count / $event->quota) * 100)) : 0;
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden relative group h-full">
                
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="font-mono text-[10px] font-semibold text-[#8C703E] bg-[#F4F1EA] border border-[#DFD8C8] px-2 py-0.5 rounded-md uppercase">
                                {{ $event->event_code }}
                            </span>
                            @if($event->assessment_type === 'prepost')
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded-md text-[10px] font-medium">Pre & Post</span>
                            @endif
                        </div>

                        <!-- Single Unified Non-Contradictory Status Badge -->
                        @if($event->status === 'draft')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                Draft
                            </span>
                        @elseif($event->status === 'archived')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                Diarsipkan
                            </span>
                        @elseif($isFinished)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span>Selesai</span>
                            </span>
                        @elseif($isUpcoming)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <span>Akan Datang</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Berlangsung</span>
                            </span>
                        @endif
                    </div>

                    <h3 class="font-serif font-bold text-base text-[#0F2537] group-hover:text-[#B5945B] transition-colors leading-snug line-clamp-2">
                        <a href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a>
                    </h3>

                    <p class="text-[11px] text-slate-500 flex items-center gap-1.5 font-normal truncate">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="truncate">{{ $event->institution_name ?? 'Instansi Internal' }}</span>
                        @if($event->regency || $event->province)
                            <span class="text-slate-300">•</span>
                            <span class="truncate text-slate-400">{{ $event->regency ? $event->regency->formatted_name : '' }}</span>
                        @endif
                    </p>
                </div>

                <!-- Card Body -->
                <div class="p-5 bg-slate-50/40 space-y-3 text-xs flex-1 flex flex-col justify-between">
                    <div>
                        <!-- Participant & Submission Summary Strip -->
                        <div class="bg-white p-3 rounded-xl border border-slate-200/70 mb-3">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-[9.5px] font-mono text-slate-400 uppercase block">Peserta</span>
                                    <strong class="text-sm font-bold text-[#0F2537]">{{ number_format($event->participants_count) }}</strong>
                                </div>
                                <div class="text-right">
                                    <span class="text-[9.5px] font-mono text-slate-400 uppercase block">Submissions</span>
                                    <strong class="text-sm font-bold text-slate-700">{{ number_format($event->submissions_count) }}</strong>
                                </div>
                            </div>

                            @if($event->quota)
                                <div class="mt-2 pt-2 border-t border-slate-100 space-y-1">
                                    <div class="flex justify-between text-[10px] text-slate-500 font-medium">
                                        <span>Kuota:</span>
                                        <span class="{{ $quotaPct >= 90 ? 'text-rose-600 font-semibold' : 'text-slate-700' }}">{{ $event->participants_count }} / {{ $event->quota }} ({{ $quotaPct }}%)</span>
                                    </div>
                                    <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $quotaPct >= 90 ? 'bg-rose-500' : 'bg-[#0F2537]' }} rounded-full" style="width: {{ $quotaPct }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-[11px] text-slate-600 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Kategori:</span>
                                <span class="font-medium text-slate-800">{{ $event->target_category ?? 'Bebas / Fleksibel' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Jadwal:</span>
                                <span class="font-medium text-slate-800">
                                    {{ $event->start_date ? $event->start_date->format('d M Y') : 'Fleksibel' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Clean Subcategory Pills -->
                    @if(!empty($event->custom_subcategories) && is_array($event->custom_subcategories))
                        <div class="pt-2 border-t border-slate-200/60 space-y-1">
                            <span class="text-[10px] text-slate-400 block font-medium">{{ $event->group_label ?: 'Opsi Pilihan' }}:</span>
                            <div class="flex flex-wrap gap-1 items-center">
                                @foreach(array_slice($event->custom_subcategories, 0, 2) as $subCat)
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 rounded text-[10px] font-medium">{{ $subCat }}</span>
                                @endforeach
                                @if(count($event->custom_subcategories) > 2)
                                    <span title="{{ implode(', ', array_slice($event->custom_subcategories, 2)) }}" class="px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-medium cursor-help">+{{ count($event->custom_subcategories) - 2 }} opsi</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Footer -->
                <div class="p-3 bg-white border-t border-slate-100 flex items-center justify-between gap-1.5 text-xs">
                    <a href="{{ route('admin.events.show', $event) }}" class="flex-1 py-2 bg-[#0F2537] hover:bg-[#183650] text-white font-semibold text-center rounded-xl transition text-xs flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#C5A059]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Analitik</span>
                    </a>

                    <button @click="openEdit({{ json_encode($event) }}, '{{ route('admin.events.update', $event) }}')" type="button" title="Edit Event" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer text-xs flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit</span>
                    </button>

                    <button @click="activeQrUrl = '{{ $event->direct_access_url }}'; activeQrTitle = '{{ addslashes($event->title) }}'; activeInstitution = '{{ addslashes($event->institution_name ?? '') }}'; activeStartDate = '{{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}'; qrModal = true" type="button" title="QR Code & Broadcast Link" class="w-8 h-8 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200/80 rounded-xl transition cursor-pointer flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </button>

                    <a href="{{ route('admin.reports.export_pdf', ['event_id' => $event->id]) }}" target="_blank" title="Unduh Laporan PDF" class="w-8 h-8 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200/80 rounded-xl transition flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </a>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin menghapus event ini? Data peserta terkait tidak akan terhapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Event" class="w-8 h-8 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200/80 rounded-xl transition cursor-pointer flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-2xs space-y-3">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="font-serif font-bold text-base text-slate-800">Belum Ada Event Terdaftar</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Klik tombol "Buat Event Baru" di atas untuk menambahkan agenda uji ruhiologi resmi.</p>
            </div>
        @endforelse
    </div>

    <!-- VIEW MODE 2: HIGH DENSITY COMPACT TABLE VIEW -->
    <div x-show="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-[#FAF9F6] border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <th class="px-5 py-3.5">Kode & Nama Event</th>
                    <th class="px-5 py-3.5">Instansi & Lokasi</th>
                    <th class="px-5 py-3.5">Preset Kategori</th>
                    <th class="px-5 py-3.5">Status & Waktu</th>
                    <th class="px-5 py-3.5 text-center">Peserta / Submissions</th>
                    <th class="px-5 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($events as $event)
                    @php
                        $isFinished = ($event->end_date && $event->end_date->isPast()) || $event->status === 'completed';
                        $isUpcoming = ($event->start_date && $event->start_date->isFuture()) && $event->status !== 'draft';
                        $quotaPct = $event->quota ? min(100, round(($event->participants_count / $event->quota) * 100)) : 0;
                    @endphp
                    <tr class="hover:bg-[#FAF9F6] transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="font-mono text-[10px] font-semibold text-[#8C703E] bg-[#F4F1EA] border border-[#DFD8C8] px-2 py-0.5 rounded uppercase">
                                    {{ $event->event_code }}
                                </span>
                                @if($event->assessment_type === 'prepost')
                                    <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded text-[9px] font-medium">Pre & Post</span>
                                @endif
                            </div>
                            <a href="{{ route('admin.events.show', $event) }}" class="font-semibold text-[#0F2537] hover:text-[#B5945B] transition text-xs block">
                                {{ $event->title }}
                            </a>
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            <div class="font-medium text-slate-800 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>{{ $event->institution_name ?? 'Instansi Internal' }}</span>
                            </div>
                            @if($event->regency || $event->province)
                                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span>{{ $event->regency ? $event->regency->formatted_name : '' }}{{ $event->province ? ', ' . $event->province->name : '' }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 rounded text-[11px] font-medium inline-block mb-0.5">
                                {{ $event->target_category ?? 'Bebas / Fleksibel' }}
                            </span>
                            @if(!empty($event->custom_subcategories) && is_array($event->custom_subcategories))
                                <div class="text-[10px] text-slate-400">
                                    {{ count($event->custom_subcategories) }} Opsi: <span class="text-slate-600">{{ implode(', ', array_slice($event->custom_subcategories, 0, 2)) }}{{ count($event->custom_subcategories) > 2 ? '...' : '' }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 mb-1">
                                @if($event->status === 'draft')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Draft
                                    </span>
                                @elseif($event->status === 'archived')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        Diarsipkan
                                    </span>
                                @elseif($isFinished)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Selesai</span>
                                    </span>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Akan Datang</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Berlangsung</span>
                                    </span>
                                @endif
                            </div>
                            <div class="text-[10px] text-slate-400 flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $event->start_date ? $event->start_date->format('d/m/Y') : 'Fleksibel' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="font-bold text-[#0F2537] text-xs">
                                {{ number_format($event->participants_count) }} <span class="text-[10px] text-slate-400 font-normal">peserta</span>
                            </div>
                            <div class="text-[10px] text-slate-500">
                                {{ number_format($event->submissions_count) }} submissions
                            </div>
                            @if($event->quota)
                                <div class="w-20 mx-auto mt-1">
                                    <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $quotaPct >= 90 ? 'bg-rose-500' : 'bg-[#0F2537]' }} rounded-full" style="width: {{ $quotaPct }}%"></div>
                                    </div>
                                    <span class="text-[8.5px] text-slate-400 block mt-0.5">{{ $quotaPct }}%</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.events.show', $event) }}" title="Lihat Analitik Event" class="p-1.5 bg-[#0F2537] hover:bg-[#183650] text-white rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </a>
                                <button @click="openEdit({{ json_encode($event) }}, '{{ route('admin.events.update', $event) }}')" type="button" title="Edit Event" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="activeQrUrl = '{{ $event->direct_access_url }}'; activeQrTitle = '{{ addslashes($event->title) }}'; activeInstitution = '{{ addslashes($event->institution_name ?? '') }}'; activeStartDate = '{{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}'; qrModal = true" type="button" title="QR Code & Link Akses" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                </button>
                                <a href="{{ route('admin.reports.export_pdf', ['event_id' => $event->id]) }}" target="_blank" title="Unduh PDF" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin menghapus event ini? Data peserta terkait tidak akan terhapus.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Event" class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-lg transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-normal">
                            Belum Ada Event / Kegiatan Asesmen Ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="pt-2">
        {{ $events->links() }}
    </div>

    <!-- CREATE EVENT MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="createModal = false" class="bg-white rounded-2xl max-w-4xl w-full shadow-2xl relative border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0F2537] text-white flex items-center justify-between shrink-0 border-b border-[#1E3E5B]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#8C703E] text-white font-bold flex items-center justify-center text-base shadow">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D4AF37] uppercase tracking-widest block">ADMINISTRATION CONSOLE</span>
                        <h3 class="text-base font-serif font-bold text-white tracking-tight">Buat Event Asesmen Baru</h3>
                    </div>
                </div>
                <button @click="createModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Compact 2 Columns Grid) -->
            <form action="{{ route('admin.events.store') }}" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-5">
                @csrf
                <input type="hidden" name="access_type" value="EVENT_PROGRAM">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- LEFT COLUMN: Informasi Utama & Akses -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0F2537] flex items-center gap-2 text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>1. Identitas & Target Event</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Nama Event / Kegiatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required placeholder="Contoh: Asesmen Ruhiologi Pemda Jambi 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] outline-none font-medium text-xs transition">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Tempat / Instansi Kegiatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="institution_name" required placeholder="Contoh: SMAN Titian Teras Jambi / Aula Pemda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] outline-none font-medium text-xs transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Provinsi</label>
                                <select name="province_id" x-model="createProvinceId" @change="onCreateProvinceChange()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-medium text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs transition">
                                    <option value="">-- Pilih Provinsi --</option>
                                    <template x-for="prov in provincesList" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Kabupaten / Kota</label>
                                <select name="regency_id" x-model="createRegencyId" :disabled="!createProvinceId" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-medium text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs disabled:opacity-50 transition">
                                    <option value="">-- Pilih Kab/Kota --</option>
                                    <template x-for="reg in filteredCreateRegencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.formatted_name || ((reg.name.startsWith('Kota') || reg.name.startsWith('Kabupaten') || !reg.type) ? reg.name : (reg.type + ' ' + reg.name))"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Kode Event (Opsional)</label>
                                <input type="text" name="event_code" placeholder="Contoh: RQ-JAMBI-26" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-mono bg-slate-50/50 focus:bg-white uppercase font-bold text-xs outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] transition">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Status Event <span class="text-rose-500">*</span></label>
                                <select name="status" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] text-xs transition">
                                    <option value="active">Active (Aktif)</option>
                                    <option value="draft">Draft (Konsep)</option>
                                    <option value="completed">Completed (Selesai)</option>
                                    <option value="archived">Archived (Arsip)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Paket Soal / Instrumen <span class="text-rose-500">*</span></label>
                            <select name="instrument_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs transition">
                                <option value="">-- Pilih Paket Soal --</option>
                                @foreach($instruments as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->code }} - {{ $inst->name }} ({{ $inst->questions_count ?? $inst->questions->count() }} Soal)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-700 text-xs">Preset Target Kategori</label>
                                <button type="button" @click="autoLoadSubCategories('create')" class="text-[10px] font-bold text-[#0F2537] bg-slate-100 hover:bg-slate-200 px-2 py-0.5 rounded-md border border-slate-200 transition cursor-pointer flex items-center gap-1">
                                    <svg class="w-3 h-3 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Auto-isi Sub-Kategori
                                </button>
                            </div>
                            <select name="target_category" x-model="createTargetCategory" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs transition">
                                <option value="">Bebas / Fleksibel (Peserta memilih sendiri)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-xs outline-none focus:border-[#0F2537] transition">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-xs outline-none focus:border-[#0F2537] transition">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Target Kuota Peserta</label>
                            <input type="number" name="quota" placeholder="Contoh: 250" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white outline-none font-medium text-xs focus:border-[#0F2537] transition">
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Pengaturan Mode & Kelompok Custom -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0F2537] flex items-center gap-2 text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            <span>2. Mode Asesmen & Opsi Kelompok</span>
                        </div>

                        <!-- Mode Asesmen -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                            <label class="block font-bold text-[#0F2537] text-xs">Mode Asesmen Event <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="assessmentType = 'single'" :class="assessmentType === 'single' ? 'bg-[#0F2537] text-white font-bold border-[#0F2537] shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[11px] text-center transition flex items-center justify-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Sekali Tes (Single)</span>
                                </button>
                                <button type="button" @click="assessmentType = 'prepost'" :class="assessmentType === 'prepost' ? 'bg-[#0F2537] text-white font-bold border-[#0F2537] shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[11px] text-center transition flex items-center justify-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span>Pre & Posttest</span>
                                </button>
                            </div>
                            <input type="hidden" name="assessment_type" :value="assessmentType">

                            <template x-if="assessmentType === 'prepost'">
                                <div class="pt-2 space-y-2 border-t border-slate-200 mt-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Pretest</label>
                                            <input type="datetime-local" name="pretest_start" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Pretest</label>
                                            <input type="datetime-local" name="pretest_end" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Posttest</label>
                                            <input type="datetime-local" name="posttest_start" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Posttest</label>
                                            <input type="datetime-local" name="posttest_end" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Custom Group / Subcategories -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                            <label class="block font-bold text-[#0F2537] text-xs">Opsi Kelompok / Kelas / Divisi (Kustom)</label>

                            <div>
                                <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Judul Label Form Peserta</label>
                                <input type="text" name="group_label" placeholder="Contoh: Pilih Kelas / Pilih Divisi" class="w-full p-2 rounded-xl border border-slate-200 bg-white text-xs">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Opsi Pilihan (Pisahkan Koma)</label>
                                <input type="text" name="custom_subcategories" x-model="createCustomSubcategories" placeholder="Contoh: Kelas X-A, Kelas X-B, Kelas XI IPA 1" class="w-full p-2 rounded-xl border border-slate-200 bg-white text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Catatan / Deskripsi Event</label>
                            <textarea name="description" rows="2" placeholder="Catatan peruntukan kegiatan..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white outline-none text-xs font-medium focus:border-[#0F2537] transition"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                    <button @click="createModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold rounded-xl shadow-sm transition cursor-pointer text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan & Terbitkan Event</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT EVENT MODAL WITH FIELD LOCKING & INTERACTIVE TAG MANAGER -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="editModal = false" class="bg-white rounded-2xl max-w-4xl w-full shadow-2xl relative border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Edit Modal Header -->
            <div class="px-6 py-4 bg-[#0F2537] text-white flex items-center justify-between shrink-0 border-b border-[#1E3E5B]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#8C703E] text-white font-bold flex items-center justify-center text-base shadow">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D4AF37] uppercase tracking-widest block">ADMINISTRATION CONSOLE</span>
                        <h3 class="text-base font-serif font-bold text-white tracking-tight">Edit Event & Kelola Opsi Kelas</h3>
                    </div>
                </div>
                <button @click="editModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Edit Modal Body -->
            <form :action="editUrl" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="access_type" :value="editData.access_type">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- LEFT COLUMN: Informasi Utama & Akses (Locked Vital Fields) -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0F2537] flex items-center gap-2 text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>1. Identitas & Target Event</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Nama Event / Kegiatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" x-model="editData.title" required placeholder="Contoh: Asesmen Ruhiologi Pemda Jambi 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] outline-none font-medium text-xs transition">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Tempat / Instansi Kegiatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="institution_name" x-model="editData.institution_name" required placeholder="Contoh: SMAN Titian Teras Jambi / Aula Pemda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] outline-none font-medium text-xs transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Provinsi</label>
                                <select name="province_id" x-model="editProvinceId" @change="onEditProvinceChange(true)" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-medium text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs transition">
                                    <option value="">-- Pilih Provinsi --</option>
                                    <template x-for="prov in provincesList" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Kabupaten / Kota</label>
                                <select name="regency_id" x-model="editRegencyId" :disabled="!editProvinceId" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-medium text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs disabled:opacity-50 transition">
                                    <option value="">-- Pilih Kab/Kota --</option>
                                    <template x-for="reg in filteredEditRegencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.formatted_name || ((reg.name.startsWith('Kota') || reg.name.startsWith('Kabupaten') || !reg.type) ? reg.name : (reg.type + ' ' + reg.name))"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1 flex items-center justify-between">
                                    <span>Kode Event</span>
                                    <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Terkunci
                                    </span>
                                </label>
                                <input type="text" name="event_code" :value="editData.event_code" readonly class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-mono bg-slate-100 text-slate-600 uppercase font-bold text-xs outline-none cursor-not-allowed">
                                <span class="text-[10px] text-slate-400 mt-1 block">Kode event dikunci agar tautan & QR code tetap valid.</span>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Status Event <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="editData.status" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] text-xs transition">
                                    <option value="active">Active (Aktif)</option>
                                    <option value="draft">Draft (Konsep)</option>
                                    <option value="completed">Completed (Selesai)</option>
                                    <option value="archived">Archived (Arsip)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Paket Soal / Instrumen <span class="text-rose-500">*</span></label>
                            <select name="instrument_id" x-model="editData.instrument_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs transition">
                                <option value="">-- Pilih Paket Soal --</option>
                                @foreach($instruments as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->code }} - {{ $inst->name }} ({{ $inst->questions_count ?? $inst->questions->count() }} Soal)</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] text-slate-500 mt-1 block">Pilih paket soal instrumen yang digunakan pada event ini.</span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-700 text-xs">Preset Target Kategori</label>
                                <button type="button" @click="autoLoadSubCategories('edit')" class="text-[10px] font-bold text-[#0F2537] bg-slate-100 hover:bg-slate-200 px-2 py-0.5 rounded-md border border-slate-200 transition cursor-pointer flex items-center gap-1">
                                    <svg class="w-3 h-3 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Impor Sub-Kategori
                                </button>
                            </div>
                            <select name="target_category" x-model="editData.target_category" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] focus:ring-1 focus:ring-[#0F2537] text-xs transition">
                                <option value="">Bebas / Fleksibel (Peserta memilih sendiri)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" x-model="editData.start_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-xs outline-none focus:border-[#0F2537] transition">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" x-model="editData.end_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-xs outline-none focus:border-[#0F2537] transition">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Target Kuota Peserta</label>
                            <input type="number" name="quota" x-model="editData.quota" placeholder="Contoh: 250" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white outline-none font-medium text-xs focus:border-[#0F2537] transition">
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Interactive Subcategory Tag Manager & Dates -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0F2537] flex items-center gap-2 text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            <span>2. Mode Asesmen & Kelola Opsi Kelompok</span>
                        </div>

                        <!-- Mode Asesmen (Locked Indicator) -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-[#0F2537] text-xs">Mode Asesmen Event</label>
                                <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Terkunci
                                </span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-[#0F2537] flex items-center gap-2">
                                <span x-text="editData.assessment_type === 'prepost' ? 'Pretest & Posttest' : 'Sekali Tes (Single Test)'"></span>
                            </div>
                            <input type="hidden" name="assessment_type" :value="editData.assessment_type">

                            <template x-if="editData.assessment_type === 'prepost'">
                                <div class="pt-2 space-y-2 border-t border-slate-200 mt-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Pretest</label>
                                            <input type="datetime-local" name="pretest_start" x-model="editData.pretest_start" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Pretest</label>
                                            <input type="datetime-local" name="pretest_end" x-model="editData.pretest_end" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Posttest</label>
                                            <input type="datetime-local" name="posttest_start" x-model="editData.posttest_start" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Posttest</label>
                                            <input type="datetime-local" name="posttest_end" x-model="editData.posttest_end" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Custom Group / Interactive Subcategory Tag Manager -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-[#0F2537] text-xs">Opsi Kelompok / Kelas / Divisi (Kustom)</label>
                                <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Dinamis</span>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-[10px] mb-1">Judul Label Form Peserta</label>
                                <input type="text" name="group_label" x-model="editData.group_label" placeholder="Contoh: Pilih Kelas / Pilih Divisi" class="w-full p-2.5 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:border-[#0F2537] outline-none transition">
                            </div>

                            <!-- Interactive Tags Display -->
                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 text-[10px]">Daftar Opsi Pilihan Aktif Saat Ini:</label>
                                <div class="flex flex-wrap gap-1.5 p-3 bg-white rounded-xl border border-slate-200 min-h-[48px] items-center">
                                    <template x-for="(opt, idx) in editSubcategoriesList" :key="idx">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 font-semibold rounded-lg text-xs">
                                            <span x-text="opt"></span>
                                            <button type="button" @click="removeOption(idx)" class="text-slate-400 hover:text-rose-600 rounded-full w-3.5 h-3.5 inline-flex items-center justify-center font-bold text-[10px] transition cursor-pointer" title="Hapus opsi ini">✕</button>
                                        </span>
                                    </template>
                                    <template x-if="editSubcategoriesList.length === 0">
                                        <span class="text-xs text-slate-400 italic">Belum ada opsi khusus yang ditambahkan.</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Add New Option Input Field -->
                            <div class="space-y-1 pt-1">
                                <label class="block font-bold text-slate-700 text-[10px]">+ Tambah Opsi Pilihan Baru:</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="newOptionText" @keydown.enter.prevent="addOption()" placeholder="Ketik opsi lalu tekan Enter..." class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium outline-none focus:border-[#0F2537] transition">
                                    <button type="button" @click="addOption()" class="px-4 py-2 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-semibold rounded-xl text-xs transition cursor-pointer shrink-0">
                                        Tambah
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-500">Opsi baru akan langsung muncul pada pilihan form pendaftaran peserta.</p>
                            </div>

                            <input type="hidden" name="custom_subcategories" :value="customSubcategoriesSubmittedString">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Catatan / Deskripsi Event</label>
                            <textarea name="description" x-model="editData.description" rows="2" placeholder="Catatan peruntukan kegiatan..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white outline-none text-xs font-medium focus:border-[#0F2537] transition"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                    <button @click="editModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold rounded-xl shadow-sm transition cursor-pointer text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan Event</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- QR CODE & SHARE MODAL -->
    <div x-show="qrModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
        <div @click.away="qrModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 text-center space-y-4 border border-slate-200 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button @click="qrModal = false" type="button" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <span class="text-[10px] font-mono font-bold text-[#8C703E] uppercase tracking-wider block">QR CODE & DISTRIBUSI AKSES</span>
            <h3 class="font-serif font-bold text-lg text-[#0F2537]" x-text="activeQrTitle"></h3>

            <!-- QR Image via API -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block mx-auto shadow-inner">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(activeQrUrl)" alt="QR Code Event" class="w-44 h-44 mx-auto rounded-lg">
            </div>

            <div class="space-y-2 text-left">
                <span class="text-[11px] font-bold text-slate-700 block">Tautan Akses Langsung Peserta:</span>
                <input type="text" :value="activeQrUrl" readonly class="w-full p-2.5 text-xs text-center font-mono bg-slate-50 rounded-xl border border-slate-200 select-all font-semibold text-[#0F2537]">
                <button type="button" @click="navigator.clipboard.writeText(activeQrUrl); alert('Tautan pendaftaran berhasil disalin!')" class="w-full py-2.5 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold text-xs rounded-xl transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Salin Tautan Pendaftaran</span>
                </button>
            </div>

            <div class="pt-3 border-t border-slate-200 text-left space-y-2">
                <span class="text-[11px] font-bold text-[#0F2537] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>Format Siaran WhatsApp:</span>
                </span>
                <textarea readonly :value="waBroadcastText" rows="5" class="w-full p-3 text-xs font-mono bg-slate-50 border border-slate-200 rounded-xl text-slate-700 leading-relaxed outline-none"></textarea>
                <button type="button" @click="navigator.clipboard.writeText(waBroadcastText); alert('Format siaran WhatsApp berhasil disalin!')" class="w-full py-2.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold text-xs rounded-xl transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Salin Format Siaran WhatsApp</span>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminEventsManager() {
    return {
        viewMode: localStorage.getItem('admin_events_view') || 'grid',
        createModal: false,
        editModal: false,
        qrModal: false,
        activeQrUrl: '',
        activeQrTitle: '',
        activeInstitution: '',
        activeStartDate: '',
        assessmentType: 'single',
        createTargetCategory: '',
        createCustomSubcategories: '',

        toggleViewMode(mode) {
            this.viewMode = mode;
            localStorage.setItem('admin_events_view', mode);
        },

        provincesList: @json($provinces ?? []),
        regenciesMap: @json($regenciesMap ?? []),
        categoriesList: @json($categories ?? []),

        createProvinceId: '',
        createRegencyId: '',
        filteredCreateRegencies: [],

        editProvinceId: '',
        editRegencyId: '',
        filteredEditRegencies: [],

        autoLoadSubCategories(modalType) {
            const targetCat = modalType === 'create' ? this.createTargetCategory : this.editData.target_category;
            if (!targetCat) {
                alert('Pilih target kategori terlebih dahulu!');
                return;
            }

            const cat = this.categoriesList.find(c => c.name === targetCat);
            if (!cat || !cat.sub_categories || cat.sub_categories.length === 0) {
                alert('Tidak ada sub-kategori yang ditemukan untuk kategori ' + targetCat);
                return;
            }

            const subCatNames = cat.sub_categories.map(s => s.name);
            if (modalType === 'create') {
                this.createCustomSubcategories = subCatNames.join(', ');
                alert(`Berhasil memuat ${subCatNames.length} sub-kategori ke Opsi Kelompok!`);
            } else {
                let addedCount = 0;
                subCatNames.forEach(name => {
                    if (!this.editSubcategoriesList.includes(name)) {
                        this.editSubcategoriesList.push(name);
                        addedCount++;
                    }
                });
                alert(`Berhasil menambahkan ${addedCount} sub-kategori baru ke Opsi Kelompok!`);
            }
        },

        get waBroadcastText() {
            const instText = this.activeInstitution ? `\n🏛️ Instansi: *${this.activeInstitution}*` : '';
            const dateText = this.activeStartDate ? `\n📅 Jadwal: *${this.activeStartDate}*` : '';
            return `Assalamu'alaikum Wr. Wb.\n\nYth. Bapak/Ibu/Peserta Uji Ruhiologi,\n\nBerikut adalah Link & QR Code pendaftaran resmi untuk kegiatan:\n📌 *${this.activeQrTitle}*${instText}${dateText}\n\nSilakan melakukan registrasi & pengisian asesmen mandiri melalui link di bawah ini:\n🔗 ${this.activeQrUrl}\n\nTerima kasih.\n*Ruhiology Institute*`;
        },

        onCreateProvinceChange() {
            this.createRegencyId = '';
            if (!this.createProvinceId) {
                this.filteredCreateRegencies = [];
                return;
            }
            const pid = String(this.createProvinceId);
            this.filteredCreateRegencies = this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
        },

        onEditProvinceChange(resetRegency = true) {
            if (resetRegency) {
                this.editRegencyId = '';
            }
            if (!this.editProvinceId) {
                this.filteredEditRegencies = [];
                return;
            }
            const pid = String(this.editProvinceId);
            this.filteredEditRegencies = this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
        },

        editUrl: '',
        newOptionText: '',
        editSubcategoriesList: [],
        editData: {
            title: '',
            institution_name: '',
            event_code: '',
            status: 'active',
            instrument_id: '',
            target_category: '',
            start_date: '',
            end_date: '',
            quota: '',
            assessment_type: 'single',
            pretest_start: '',
            pretest_end: '',
            posttest_start: '',
            posttest_end: '',
            group_label: '',
            description: '',
            access_type: 'EVENT_PROGRAM',
            province_id: '',
            regency_id: '',
        },

        openEdit(eventData, updateUrl) {
            this.editUrl = updateUrl;
            
            if (Array.isArray(eventData.custom_subcategories)) {
                this.editSubcategoriesList = [...eventData.custom_subcategories];
            } else if (typeof eventData.custom_subcategories === 'string' && eventData.custom_subcategories.trim() !== '') {
                this.editSubcategoriesList = eventData.custom_subcategories.split(',').map(s => s.trim()).filter(Boolean);
            } else {
                this.editSubcategoriesList = [];
            }

            this.newOptionText = '';

            this.editProvinceId = eventData.province_id || '';
            this.onEditProvinceChange(false);
            this.editRegencyId = eventData.regency_id || '';

            this.editData = {
                title: eventData.title || '',
                institution_name: eventData.institution_name || '',
                event_code: eventData.event_code || '',
                status: eventData.status || 'active',
                instrument_id: eventData.instrument_id || '',
                target_category: eventData.target_category || '',
                start_date: eventData.start_date ? eventData.start_date.substring(0, 10) : '',
                end_date: eventData.end_date ? eventData.end_date.substring(0, 10) : '',
                quota: eventData.quota || '',
                assessment_type: eventData.assessment_type || 'single',
                pretest_start: eventData.pretest_start ? eventData.pretest_start.replace(' ', 'T').substring(0, 16) : '',
                pretest_end: eventData.pretest_end ? eventData.pretest_end.replace(' ', 'T').substring(0, 16) : '',
                posttest_start: eventData.posttest_start ? eventData.posttest_start.replace(' ', 'T').substring(0, 16) : '',
                posttest_end: eventData.posttest_end ? eventData.posttest_end.replace(' ', 'T').substring(0, 16) : '',
                group_label: eventData.group_label || '',
                description: eventData.description || '',
                access_type: eventData.access_type || 'EVENT_PROGRAM',
                province_id: eventData.province_id || '',
                regency_id: eventData.regency_id || '',
            };
            this.editModal = true;
        },

        addOption() {
            const val = this.newOptionText.trim();
            if (val && !this.editSubcategoriesList.includes(val)) {
                this.editSubcategoriesList.push(val);
                this.newOptionText = '';
            }
        },

        removeOption(index) {
            this.editSubcategoriesList.splice(index, 1);
        },

        get customSubcategoriesSubmittedString() {
            return this.editSubcategoriesList.join(', ');
        }
    }
}
</script>
@endpush
@endsection
